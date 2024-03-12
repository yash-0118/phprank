<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Models\Site;
use App\Models\SiteMaster;
use App\Models\User;
use DOMXPath;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Validator;

class DataController extends Controller
{
    public function index(Request $request)
    {
        $setting = Setting::where('name', 'result_per_page')->first();
        $paginate=(int)json_decode($setting->payload);
        $user_id = Auth::user()->id;
        $user = User::find($user_id);
        $searchTerm = $request->session()->get('searchTerm', '');

        if ($request->has('search')) {
            $searchTerm = $request->input('search');
            $request->session()->put('searchTerm', $searchTerm);
        }
        $sites = $user->sitemasters()->where('domain', 'like', '%' . $searchTerm . '%')
            ->orderBy('created_at', 'desc')
            ->paginate($paginate);
        return view('user.reports.index', ['sites' => $sites, 'searchTerm' => $searchTerm]);
    }


    public function report($id)
    {
        $reportDetail = SiteMaster::findorFail($id);
        return view('user.reports.show', ['reportDetail' => $reportDetail]);
    }
    public function edit($id)
    {
        $user_id = Auth::id();
        $site = SiteMaster::where('id', $id)
            ->where('user_id', $user_id)
            ->first();
        if (!$site) {
            abort(403);
        }
        if ($site->visibility === 'password') {
            $site->password = Crypt::decrypt($site->password);
        } else {
            $site->password = null;
        }

        return view('user.reports.edit', ['report' => $site]);
    }
    public function update(Request $request, $id)
    {
        $report = SiteMaster::findOrFail($id);
        $request->validate([
            'visibility' => ['required', Rule::in(['public', 'private', 'password'])],
            'password' => ['required_if:visibility,password'],
        ]);
        $report->visibility = $request->input('visibility');

        if ($report->visibility === 'password') {
            $report->password = Crypt::encrypt($request->input('password'));
        } else {
            $report->password = null;
        }
        $report->save();

        return redirect()->route('user.reports')->with('success', 'Report visibility updated successfully');
    }
    public function destroy($id)
    {
        $report = SiteMaster::findOrFail($id);
        if (!$report) {
            return redirect('/reports')->with('error', 'Report not found.');
        }
        $report->delete();
        return redirect('/reports')->with('success', 'Report deleted successfully.');
    }
    public function projects()
    {
        $user_id = Auth::user()->id;
        $setting = Setting::where('name', 'result_per_page')->first();
        $paginate = (int)json_decode($setting->payload);
        $user = User::find($user_id);
        $result = [];
        $projects = $user->sites()->paginate($paginate);
        $reportsCounts = SiteMaster::join('sites', 'site_masters.site_id', '=', 'sites.id')
            ->where('sites.user_id', $user->id)
            ->groupBy('sites.domain')
            ->selectRaw('sites.domain, COUNT(site_masters.id) as count')
            ->pluck('count', 'domain');

        foreach ($projects as $project) {
            $domain = $project->domain;
            $reports_count = $reportsCounts[$domain] ?? 0;
            $result[$domain] = $reports_count;
        }
        return view('user.projects.index', ['result' => $result, 'projects' => $projects]);
    }
    public function reports($domain, Request $req)
    {
        $user_id = Auth::user()->id;
        $setting = Setting::where('name', 'result_per_page')->first();
        $paginate = (int)json_decode($setting->payload);


        $reports = SiteMaster::join('sites', 'site_masters.site_id', '=', 'sites.id')
            ->where('sites.domain', $domain)
            ->where('site_masters.user_id', $user_id)
            ->orderBy('created_at', 'desc')
            ->paginate($paginate, ['site_masters.*']);

        return view('user.projects.show', ["sites" => $reports]);
    }
    public function search(Request $request)
    {
        $url = $request->input('search');
        $visibility = $request->input('visibility');
        $password = null;
        if ($visibility == "password") {
            $validator = Validator::make($request->all(), [
                'password' => 'required',
            ]);
            if ($validator->fails()) {
                return redirect('/reports')->withErrors($validator)->withInput();
            }
            $password = Crypt::encrypt($request->input('password'));
        }

        $type = $request->input('search_option');
        if (filter_var($url, FILTER_VALIDATE_URL)) {
            if ($type == "report") {
                if (!preg_match('/\.xml$/i', $url)) {

                    if ($this->getdata($url, $password, $visibility)) {
                        return redirect('/reports');
                    }
                } else {
                    return redirect('/reports')->with('error', "This link is sitemap link so select sitemap and then analyze");
                }
            } else {
                if ($this->sitemaps($url, $password, $visibility)) {
                    return redirect('/reports');
                } else {
                    return redirect('/reports');
                }
            }
        } else {
            return redirect('/reports')->with('error', 'Invalid website URL format. Please enter a valid URL.');
        }
    }
    public function getdata($url, $password, $visibility)
    {

        $user = Auth::user()->id;
        $domain = $this->getDomain($url);
        $site = SiteMaster::where('user_id', $user)->where('domain', $domain)->first();
        try {
            $client = new Client();
            $response = $client->request('GET', $url);
            $htmlContent = $response->getBody()->getContents();
            // $htmlContent = @file_get_contents($url);
            $parsedUrl = parse_url($url);

            // if ($htmlContent === false) {
            //     return redirect('/reports')->with('error', "Failed to fetch data for this website");
            // }
            libxml_use_internal_errors(true);

            $doc = new \DOMDocument();
            if ($htmlContent) {

                $doc->loadHTML($htmlContent);
            } else {
                return redirect('/reports')->with('error', "Failed to Get Data For this Website");
            }

            libxml_use_internal_errors(false);

            //HEADING
            $headings = $this->heading($doc);

            //TITLE
            $title = $this->title($doc);

            //Domain
            $domain = $this->getDomain($url);

            //RootDomain
            $rootDomain = $this->getRootDomain($parsedUrl['host']);

            $api_data = $this->score($url);
            sleep(10);
            if (!is_array($api_data)) {
                return redirect('/reports')->withErrors(['error' => 'Invalid response from the PageSpeed API.']);
            }

            //SCORE
            $score = $api_data['score'];

            //LOAD TIME
            $load_time = $api_data['load_time'];

            //Category
            if ($score > 0 && $score < 50) {
                $category = "Bad";
            } elseif ($score >= 50 && $score < 90) {
                $category = "Decent";
            } elseif ($score >= 90 and $score <= 100) {
                $category = "Good";
            }

            //META
            $meta_description = $this->metaDescription($doc);

            //Image
            if (strlen($url) > 100) {
                // Parse the URL to extract the domain
                $parsedUrl = parse_url($url);
                $domain = $parsedUrl['host'] ?? '';

                // Use the domain in the image link
                $image = "https://image.thum.io/get/width/600/crop/600/{$domain}";
            } else {
                // Use the full URL in the image link
                $image = "https://image.thum.io/get/width/600/crop/600/{$url}";
            }

            //pageSize
            $pagesize =  round(strlen($htmlContent) / 1024, 2);

            //Http REQ
            $httpResult = $this->http($doc, $url);
            $http_count = $httpResult['total_http_requests'];
            //headings
            $headings = $this->heading($doc);

            //Keywords
            $keywords = $this->keyword($doc);

            //Image Keywords
            $imagesWithoutAlt = $this->ImagesWithoutAlt($doc, $url);

            //SEO Friendly
            $seo = $this->isSEOFriendly($url);

            //404 page
            $notFound = $this->not_found($url);

            //Robot.txt
            $robot_txt = $this->robot_txt($url);

            //no-index
            $no_index = $this->no_index($doc);

            //InPage Links
            $page_links = $this->pagelinks($doc, $url);
            //Language
            $language = $this->language($doc);


            //Favicon
            $favicon = $this->favicon($doc, $url);
            $doctype = $doc->doctype;
            if ($doctype) {
                $doctype = $doctype->name;
            } else {
                $doctype = "doc type is not available";
            }

            //Text Compression
            $text_compression = $pagesize;

            //PLAINTEXT EMAIL
            $email = $this->plaintext_email($doc);

            //STRUCTURED DATA
            $structure_data = $this->structure_data($doc);

            //Meta Viewport
            $meta_viewport = $this->metaViewport($doc);

            //HTTP ENC
            $http_enc = $this->http_enc($url);

            //HTST
            $htst = $this->htst($url);

            //Mixed_Content
            $mix_content = $this->mixed($url);

            //Server
            $server = $this->getServerSignature($url);

            //CHARSET
            $charset = $this->charset($doc);

            //SITEMAP
            $sitemap = $this->sitemap($url);

            //SOCIAL
            $social = $this->social($doc);

            //RATIO
            $ratio = $this->ratio($doc, $htmlContent);

            //Content Length
            $content_length = $this->contentLength($doc);

            //Image
            $image_format = $this->image_format($doc, $url);

            //LOAD TIME
            $load_time = $api_data['load_time'];

            //JS DEFER
            $js_defer = $this->js_defer($doc, $url);

            //DOM
            $allElements = $doc->getElementsByTagName('*');
            $dom = $allElements->length;

            //Inline CSS
            $css = $this->inline_css($doc);

            $http_req = $httpResult['categories_links'];

            $user_id = Auth::id();
            $site = Site::where('domain', $rootDomain)
                ->where('user_id', $user_id)
                ->first();
            if (!$site) {
                $site = new Site([
                    'user_id' => $user_id,
                    'domain' => $rootDomain
                ]);
                $site->save();
            }

            $siteId = $site->id;
            $data = [
                'user_id' => $user_id,
                'site_id' => $siteId,
                'visibility' => $visibility,
                'password' => $password,
                'title' => $title,
                "domain" => $domain,
                "category" => $category,
                "meta_description" => $meta_description,
                "score" => $score,
                "image" => $image,
                "load_time" => $load_time,
                "page_size" => $pagesize,
                "http_requests_count" => $http_count,
                "url" => $url,
                "headings" => json_encode($headings),
                "keywords" => json_encode($keywords),
                "image_keywords" => json_encode($imagesWithoutAlt),
                "seo_friendly" => $seo,
                "notfound" => $notFound,
                "robot_txt" => $robot_txt,
                "no_index" => $no_index,
                "page_links" => json_encode($page_links),
                "language" => $language,
                "favicon" => $favicon,
                "text_compression" => $text_compression,
                "http_requests" => json_encode($http_req),
                "image_format" => json_encode($image_format),
                "js_defer" => json_encode($js_defer),
                "dom_size" => $dom,
                "doctype" => $doctype,
                "http_enc" => $http_enc,
                "mix_content" => $mix_content,
                "server" => $server,
                "htst" => $htst,
                "plaintext_email" => json_encode($email),
                "structured_data" => json_encode($structure_data),
                "meta_viewport" => $meta_viewport,
                "char_set" => $charset,
                "sitemap" => json_encode($sitemap),
                "social" => json_encode($social),
                "content_length" => $content_length,
                "ratio" => $ratio,
                "inline_css" => json_encode($css),
            ];
            $sitemaster = new SiteMaster($data);
            $sitemaster->save();
            return redirect('/reports')->with('success', 'Website is valid and operations are performed.');
        } catch (\Exception $e) {
            return redirect('/reports')->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function sitemaps($url, $password, $visibility)
    {
        $setting = Setting::where('name', 'links_per_sitemaps')->first();
        try {

            if (preg_match('/\.xml$/i', $url)) {
                $htmlContent = @file_get_contents($url);
                libxml_use_internal_errors(true);

                $doc = new \DOMDocument();
                if ($htmlContent !== false) {
                    $doc->loadHTML($htmlContent);

                    libxml_use_internal_errors(false);
                    $locElements = $doc->getElementsByTagName('loc');

                    if ($locElements->length > 0) {
                        $urls = [];
                        foreach ($locElements as $locElement) {
                            if (preg_match('/\.xml$/i', $locElement->nodeValue)) {
                                return redirect('/reports')->with('error', 'This Sitemap Contains Sitemap links not  Webpage Links   ');
                            }
                            $urls[] = $locElement->nodeValue;
                        }
                        if (count($urls) < (int)json_decode($setting->payload)) {
                            $count = count($urls);
                        } else {
                            $count = (int)json_decode($setting->payload);
                        }
                        $firstUrls = array_slice($urls, 0, $count);

                        foreach ($firstUrls as $url) {
                            $this->getdata($url, $password, $visibility);
                        }
                        return view('user.reports.index')->with('success', 'Website Sitemap data Added successfully.');
                    }
                } else {
                    return redirect('/reports')->with('error', "Sitemap link is  not available or not in proper format");
                }
            } else {
                return redirect('/reports')->with('error', 'Website Sitemap Url Is not Valid.');
            }
        } catch (\Exception $e) {
            return redirect('/reports')->with('error', $e->getMessage());
        }
    }
    public function score($url)
    {
        $result = '';
        ini_set('max_execution_time', 120);
        $key = 'AIzaSyBND5AcgxwylBXRReKsNjeUAXTDPiuGiRU';
        set_time_limit(120);
        $client = new \GuzzleHttp\Client();
        $apiUrl = "https://www.googleapis.com/pagespeedonline/v5/runPagespeed?key={$key}&strategy=DESKTOP&url={$url}";

        try {
            $res = $client->request('GET', $apiUrl);

            if ($res->getStatusCode() == 200) {
                $result = json_decode($res->getBody()->getContents());
                $score = $result->lighthouseResult->categories->performance->score;
                $load_time = $result->lighthouseResult->audits->{'first-contentful-paint'}->numericValue / 1000;
                $performanceData = [
                    'score' => $score * 100,
                    'load_time' => $load_time,
                ];

                return $performanceData;
            } else {
                return redirect()->back();
            }
        } catch (\GuzzleHttp\Exception\RequestException $e) {
            if ($e->getCode() === 28) {
                // Timeout occurred
                return redirect()->back()->withErrors(['error' => 'Request to the PageSpeed API timed out.']);
            } else {
                // Handle other Guzzle request exceptions
                return redirect()->back()->withErrors(['error' => 'Failed to fetch data from the PageSpeed API.']);
            }
        }
    }
    public function title($doc)
    {
        $title = '';

        $titleElements = $doc->getElementsByTagName('title');
        if ($titleElements->length > 0) {
            $title = $titleElements->item(0)->textContent;
        }
        if ($title) {

            return $title;
        } else {
            return "Title is not available";
        }
    }
    public function heading($doc)
    {
        $headings = [];

        for ($i = 1; $i <= 6; $i++) {
            $headingElements = $doc->getElementsByTagName('h' . $i);
            foreach ($headingElements as $element) {
                $textContent = trim(preg_replace('/\s+/', ' ', $element->textContent));
                $headings['h' . $i][] = $textContent;
            }
        }
        if ($headings) {
            return $headings;
        } else {
            return "There is no Heading Tags";
        }
    }

    public function keyword($doc)
    {
        $keywords = [];
        $metaKeywords = $doc->getElementsByTagName('meta');
        foreach ($metaKeywords as $metaKeyword) {
            $name = $metaKeyword->getAttribute('name');
            if (strcasecmp($name, 'keywords') === 0) {
                $content = $metaKeyword->getAttribute('content');
                $keywords = explode(',', $content);
                break;
            }
        }
        if (empty($keywords)) {
            return "Content Keywords are not present";
        } else {
            return $keywords;
        }
    }
    public function getDomain($url)
    {
        $parsedUrl = parse_url($url);
        if (isset($parsedUrl['host'])) {
            $domain = $parsedUrl['host'];
            if (isset($parsedUrl['path'])) {
                $domain .= $parsedUrl['path'];
            }
            return rtrim(str_replace(["www."], "", $domain), "/");
        }
        return null;
    }
    function getRootDomain($host)
    {
        $hostParts = explode('.', $host);
        $numParts = count($hostParts);
        if ($numParts >= 3 && $hostParts[0] === 'www') {
            array_shift($hostParts);
            $rootdomain = implode('.', $hostParts);
        } else {
            $rootdomain = $host;
        }

        return $rootdomain;
    }

    public function metaDescription($doc)
    {
        $metaDescription = '';
        $metaElements = $doc->getElementsByTagName('meta');

        foreach ($metaElements as $element) {
            $name = $element->getAttribute('name');
            $property = $element->getAttribute('property');
            if (strcasecmp($name, 'description') === 0 || strcasecmp($property, 'description') === 0 || strcasecmp($property, 'og:description') === 0) {
                $metaDescription = $element->getAttribute('content');
                break;
            }
        }
        if ($metaDescription) {

            return $metaDescription;
        } else {
            return "The meta description tag is missing or empty.";
        }
    }
    public function http($doc, $url)
    {
        $result = [];
        $httpRequestsCount = 0;
        $baseUrl = parse_url($url, PHP_URL_HOST);

        $mapFunction = function ($url) use ($baseUrl, &$httpRequestsCount, &$url1) {
            $httpRequestsCount++;
            if (filter_var($url, FILTER_VALIDATE_URL)) {
                if (strpos($url, 'data:') === 0 || (strpos($url, 'www') === 0)|| strpos($url, '//www') ===0 || strpos($url, 'https') == 0) {
                    return $url;
                }
                return "https://" . $url;
            } else {
                if (strpos($url, 'data:') === 0 || (strpos($url, 'www') === 0)|| strpos($url, '//www') === 0 || strpos($url, 'https') == 0) {
                    return $url;
                }
                return "https://" . $baseUrl . $url;
            }
        };

        $elementsToExtract = [
            'script' => 'src',
            'img' => 'src',
            'iframe' => 'src',
        ];

        foreach ($elementsToExtract as $tagName => $attributeName) {
            $urls = [];

            $elements = $doc->getElementsByTagName($tagName);
            foreach ($elements as $element) {
                $url = $element->getAttribute($attributeName);
                if (!empty($url)) {
                    $urls[] = $mapFunction($url);
                }
            }
            if (!empty($urls)) {
                $result[strtolower($tagName)] = $urls;
            }
        }
        $cssUrls = $this->extractCssUrls($doc, $mapFunction);
        if (!empty($cssUrls)) {
            $result['css'] = $cssUrls;
        }
        return [
            'total_http_requests' => $httpRequestsCount,
            'categories_links' => $result,
        ];
    }



    private function extractCssUrls($doc, $mapFunction)
    {
        $cssUrls = [];
        $linkElements = $doc->getElementsByTagName('link');
        foreach ($linkElements as $element) {
            $rel = $element->getAttribute('rel');
            $href = $element->getAttribute('href');

            if (strcasecmp($rel, 'stylesheet') === 0 && !empty($href)) {
                $cssUrls[] = $mapFunction($href);
            }
        }

        return $cssUrls;
    }
    public function ImagesWithoutAlt($doc, $url)
    {
        $imagesWithoutAlt = [];
        $parsedBaseUrl = parse_url($url);
        $baseUrl = $parsedBaseUrl['host'];

        $imageElements = $doc->getElementsByTagName('img');
        foreach ($imageElements as $imageElement) {
            $altAttribute = $imageElement->getAttribute('alt');
            $srcAttribute = $imageElement->getAttribute('src');

            if (empty($altAttribute) && !empty($srcAttribute)) {
                if (strpos($srcAttribute, 'data:') === 0) {
                    $imagesWithoutAlt[] = $srcAttribute;
                } else {
                    if (!preg_match('/^https?:\/\//i', $srcAttribute)) {
                        $srcAttribute = "https://" . $baseUrl . '/' . ltrim($srcAttribute, '/');
                    }
                    $imagesWithoutAlt[] = $srcAttribute;
                }
            }
        }

        if ($imagesWithoutAlt) {
            return $imagesWithoutAlt;
        } else {
            return "All images have alt attributes set.";
        }
    }

    public function isSEOFriendly($url)
    {
        $website = $this->getDomain($url);
        $structure = preg_match('/^[a-zA-Z0-9\-\.\:\/]+$/', $website);
        if ($structure) {
            return "URL is SEO Friendly";
        } else {
            return "URL is not SEO Friendly";
        }
    }
    public function not_found($url)
    {
        $notFound = " ";
        $nonExistentPage = $url . '/nonexistent-page';
        $file = @file_get_contents($nonExistentPage);
        if ($file == false) {
            return $nonExistentPage;
        } else {
            return "There is no 404 page";
        }
    }
    public function robot_txt($url)
    {
        try {
            $parsedUrl = parse_url($url);
            $robot = $this->getRootDomain($parsedUrl['host']);
            $roboturl = "https://www." . $robot;
            $robotsTxtUrl = rtrim($roboturl, '/') . '/robots.txt';
            $robotsTxt = @file_get_contents($robotsTxtUrl);
            if ($robotsTxt !== false) {
                return
                    "The webpage can be accessed by search engines.";
            } else {
                return 'The webpage can not be accessed by search engines.';
            }

        } catch (\Exception $e) {
            return redirect('/reports')->with('error', "Can't Able to get this website data");
        }
    }

    public function no_index($doc)
    {
        $metaTags = $doc->getElementsByTagName('meta');
        $hasNoIndexTag = false;
        foreach ($metaTags as $metaTag) {
            $name = $metaTag->getAttribute('name');
            $content = $metaTag->getAttribute('content');

            if ($name === 'robots' && $content === 'noindex') {
                $hasNoIndexTag = true;
                break;
            }
        }

        if ($hasNoIndexTag) {
            return "The webpage does not have a noindex tag set.";
        } else {
            return "The webpage have a noindex tag set.";
        }
    }
    public function pageLinks($doc, $url)
    {
        $baseUrl = rtrim($url, '/');
        $links = $doc->getElementsByTagName('a');

        $internalLinks = [];
        $externalLinks = [];

        $baseUrlInfo = parse_url($url);
        $baseUrlHost = $baseUrlInfo['host'] ?? '';
        foreach ($links as $link) {
            $href = $link->getAttribute('href');
            $linkName = $link->textContent;
            if (!empty($href)) {
                $absoluteUrl = $baseUrl . $href;
                $parsedUrl = parse_url($absoluteUrl);
                $linkHost = $parsedUrl['host'] ?? '';
                $isExternal = $linkHost !== $baseUrlHost;
                $cleanedLinkName = trim(preg_replace('/\s+/', ' ', $linkName));


                if (!empty($cleanedLinkName)) {
                    if ($isExternal) {
                        $externalLinks[] = $cleanedLinkName;
                    } else {
                        $internalLinks[] = $cleanedLinkName;
                    }
                }
            }
        }
        $result = [
            "Internal" => $internalLinks,
            "External" => $externalLinks
        ];
        return $result;
    }
    public function language($doc)
    {
        $language = "";
        $htmlElement = $doc->getElementsByTagName('html')->item(0);

        if ($htmlElement) {
            $langAttribute = $htmlElement->attributes->getNamedItem('lang');

            if ($langAttribute) {
                $language = $langAttribute->nodeValue;
            }
        }
        if (!empty($language)) {
            $language = $language;
        } else {
            $language = "Language is Not Declared";
        }
        return $language;
    }
    public function favicon($doc, $url)
    {
        $faviconUrl = '';

        $linkElements = $doc->getElementsByTagName('link');
        foreach ($linkElements as $element) {
            $rel = $element->getAttribute('rel');
            if (in_array($rel, ['icon', 'shortcut icon'])) {
                $faviconUrl = $element->getAttribute('href');
                break;
            }
        }
        if ($faviconUrl && !filter_var($faviconUrl, FILTER_VALIDATE_URL)) {
            $faviconUrl = $url . '/' . ltrim($faviconUrl, '/');
        }
        if ($faviconUrl) {

            return $faviconUrl;
        } else {
            return "The webpage does not have a favicon.";
        }
    }
    public function image_format($doc, $url)
    {
        $images = $doc->getElementsByTagName('img');
        $filteredImages = [];
        $parsedBaseUrl = parse_url($url);
        $baseUrl = $parsedBaseUrl['host'];

        foreach ($images as $image) {
            $src = $image->getAttribute('src');

            if (stripos($src, '.avif') === false && stripos($src, '.webp') === false) {
                if (!preg_match('/^https?:\/\//i', $src)) {
                    if (strpos($src, 'data:') === 0 || strpos($src,'//www.')==0 || strpos($src,'https')==0 ) {
                        $src =$src;
                    } else {
                        $src = "https://" . $baseUrl . '/' . ltrim($src, '/');
                    }
                }
                $filteredImages[] = $src;
            }
        }

        if ($filteredImages) {
            return $filteredImages;
        } else {
            return "The images are served in the AVIF, WebP format.";
        }
    }


    public function js_defer($doc, $url)
    {
        $xpath = new \DOMXPath($doc);
        $query = '//script[not(@defer)]';
        $parsedBaseUrl = parse_url($url);
        $baseUrl = $parsedBaseUrl['host'];

        $scriptNodes = $xpath->query($query);
        $filteredScripts = [];

        foreach ($scriptNodes as $scriptNode) {
            $src = $scriptNode->hasAttribute('src') ? $scriptNode->getAttribute('src') : null;
            if ($src !== null) {

                if (filter_var($src, FILTER_VALIDATE_URL)) {
                    $filteredScripts[] = $src;
                } else {
                    $filteredScripts[] = "https://" . $baseUrl . $src;
                }
            }
        }
        if ($filteredScripts) {

            return $filteredScripts;
        } else {
            return "There is All Js Has Defer Attribute available";
        }
    }
    public function plaintext_email($doc)
    {
        $plaintextEmailLinks = [];
        $xpath = new DOMXPath($doc);
        $query = '//text()[contains(., "@")]';

        $textNodes = $xpath->query($query);

        foreach ($textNodes as $textNode) {
            $text = trim($textNode->nodeValue);
            preg_match_all('/[a-zA-Z0-9]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}/', $text, $matches);
            $plaintextEmailLinks = array_merge($plaintextEmailLinks, $matches[0]);
        }

        if ($plaintextEmailLinks) {
            return $plaintextEmailLinks;
        } else {
            return "No Plaintext emails are available";
        }
    }
    public function http_enc($url)
    {
        $headers = @get_headers($url, 1);
        // dd($headers);
        if ($headers !== false) {
            $httpenc = false;

            if (isset($headers['location']) && is_array($headers['location'])) {

                foreach ($headers['location'] as $location) {
                    if (strpos($location, 'https://') === 0) {
                        $httpenc = true;
                    }
                }
            } elseif (isset($headers['location'])) {
                if (strpos($headers['location'], 'https://') === 0) {
                    $httpenc = true;
                }
            }
            if (strpos($url, 'https://') === 0) {
                $httpenc = true;
            }
            if ($httpenc) {
                return "The webpage uses HTTPS encryption.";
            } else {
                return "The webpage Not uses HTTPS encryption.";
            }
        } else {
            return "The webpage Not uses HTTPS encryption.";
        }
    }
    public function mixed($url)

    {
        $headers = @get_headers($url, 1);
        $httpSupported = false;
        $httpsSupported = false;

        if (isset($headers['location']) && is_array($headers['location'])) {
            foreach ($headers['location'] as $location) {
                if (strpos($location, 'https://') === 0) {
                    $httpsSupported = true;
                } elseif (strpos($location, 'http://') === 0) {
                    $httpSupported = true;
                }
            }
        } elseif (isset($headers['location'])) {
            if (strpos($headers['location'], 'https://') === 0) {
                $httpsSupported = true;
            } elseif (strpos($headers['location'], 'http://') === 0) {
                $httpSupported = true;
            }
        }
        if ($httpsSupported && $httpSupported) {
            return "There are mixed content resources on the webpage.";
        } else {
            return "There are no mixed content resources on the webpage.";
        }
    }
    public function htst($url)
    {
        $headers = @get_headers($url, 1);
        if (isset($headers['Strict-Transport-Security'])) {
            return "The webpage has the HTTP Strict-Transport-Security header set.";
        } else {
            return "The webpage has not the HTTP Strict-Transport-Security header set.";
        }
    }
    public function structure_data($doc)
    {
        $xpath = new DOMXPath($doc);
        $structuredData = [];

        // Open Graph meta tags
        $ogNodes = $xpath->query('//meta[starts-with(@property, "og:")]');
        foreach ($ogNodes as $ogNode) {
            $property = $ogNode->getAttribute('property');
            $content = $ogNode->getAttribute('content');
            $structuredData['openGraph'][str_replace('og:', '', $property)] = $content;
        }

        // Schema.org meta tags
        $schemaNodes = $xpath->query('//script[@type="application/ld+json"]');
        foreach ($schemaNodes as $schemaNode) {
            $json = json_decode($schemaNode->textContent, true);
            if (is_array($json) && isset($json['@context']) && $json['@context'] === 'https://schema.org') {
                $structuredData['schemaOrg'] = $json;
            }
        }
        $twitterNodes = $xpath->query('//meta[starts-with(@name, "twitter:")]');
        foreach ($twitterNodes as $twitterNode) {
            $name = $twitterNode->getAttribute('name');
            $content = $twitterNode->getAttribute('content');
            $structuredData['twitter'][str_replace('twitter:', '', $name)] = $content;
        }
        if ($structuredData) {
            return $structuredData;
        } else {
            return "Structuted Data is not available On This Page";
        }
    }
    public function metaViewport($doc)
    {
        $viewport = null;
        foreach ($doc->getElementsByTagName('meta') as $meta) {
            if ($meta->getAttribute('name') === 'viewport') {
                $viewport = $meta->getAttribute('content');
                break;
            }
        }
        if ($viewport !== null) {
            return $viewport;
        } else {
            return "Meta viewport not available";
        }
    }
    public function getServerSignature($url)
    {

        $headers = @get_headers($url, 1);
        if (isset($headers['Server'])) {
            if (is_array($headers['Server'])) {
                return ($headers['Server'][0]);
            } elseif (empty($headers['Server'])) {
                return "Server header not found.";
            } else {
                return $headers['Server'];
            }
        } else {
            return "Server header not found.";
        }
    }

    public function charset($doc)
    {

        $xpath = new DOMXPath($doc);
        $charsetQuery = '//meta[@charset] | //meta[@http-equiv="Content-Type"]';
        $charsetNodes = $xpath->query($charsetQuery);
        $charset = '';

        foreach ($charsetNodes as $node) {
            $charset = $node->getAttribute('charset');
            if (empty($charset)) {
                $contentAttr = $node->getAttribute('content');
                preg_match('/charset=(.*)/', $contentAttr, $matches);
                $charset = isset($matches[1]) ? trim($matches[1]) : '';
            }
            break;
        }
        if ($charset) {

            return $charset;
        } else {
            return "There is no character Set is Defined";
        }
    }
    public function sitemap($url)
    {

        $parsedUrl = parse_url($url);
        $robot = $this->getRootDomain($parsedUrl['host']);
        $roboturl = "https://www." . $robot;
        $robotsTxtUrl = rtrim($roboturl, '/') . '/robots.txt';
        try {
            $robotsTxt = @file_get_contents($robotsTxtUrl);
            if ($robotsTxt !== false) {

                $matches = [];
                preg_match_all('/^sitemap:\s*(.*?)$/im', $robotsTxt, $matches);
                $sitemapLinks = $matches[1] ?? [];
                if ($sitemapLinks) {
                    return $sitemapLinks;
                } else {
                    return "There is no sitemaps Available";
                }
            } else {
                return "There is no sitemaps Available";
            }
        } catch (Exception) {
            return "There is no sitemaps Available";
        }
    }

    public function social($doc)
    {
        $socialMediaPatterns = [
            'YouTube' => '/youtube\.com/i',
            'Facebook' => '/facebook\.com/i',
            'Twitter' => '/twitter\.com/i',
            'Instagram' => '/instagram\.com/i',
            'LinkedIn' => '/linkedin\.com/i',
        ];
        $socialMediaLinks = [];
        foreach ($doc->getElementsByTagName('a') as $link) {
            $href = $link->getAttribute('href');
            foreach ($socialMediaPatterns as $platform => $pattern) {
                if (preg_match($pattern, $href)) {
                    $socialMediaLinks[$platform][] = $href;
                }
            }
        }
        if ($socialMediaLinks) {
            return $socialMediaLinks;
        } else {
            return "There is no Social Media Link is Available";
        }
    }
    public function ratio($doc, $html)
    {
        $bodyText = $doc->getElementsByTagName('body')->item(0)->textContent;
        $textNodesCount = str_word_count(strip_tags($bodyText));

        $totalPageSize = strlen($html) / 1024;

        if ($totalPageSize > 0) {
            return round($textNodesCount / $totalPageSize);
        } else {
            return 0;
        }
    }
    public function contentLength($doc)
    {
        $bodyText = $doc->getElementsByTagName('body')->item(0)->textContent;
        return str_word_count(strip_tags($bodyText));
    }
    public function inline_css($doc)
    {
        $inlineStyles = [];

        foreach ($doc->getElementsByTagName('*') as $element) {
            $styleAttribute = $element->getAttribute('style');

            if (!empty($styleAttribute)) {
                $cleanedStyle = preg_replace('/\s+/', ' ', $styleAttribute);
                $inlineStyles[] = $cleanedStyle;
            }
        }

        if ($inlineStyles) {
            return $inlineStyles;
        } else {
            return "There is no Inline CSS is Used";
        }
    }
}
