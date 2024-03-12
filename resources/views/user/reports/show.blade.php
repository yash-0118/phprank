<x-admin-layout>
    <h1 class="text-2xl font-bold mb-4">{{$reportDetail->domain}}</h1>
    <div class="mb-6">
        <h2 class="text-lg font-semibold">Score: {{$reportDetail->score}}</h2>
        <p class="mb-2"><span class="font-bold">Title:</span> {{$reportDetail->title}}</p>
        <p class="mb-2"><span class="font-bold">Meta Description:</span> {{$reportDetail->meta_description}}</p>
        <a href="{{$reportDetail->url}}" target="_blank" class="text-blue-500 no-underline">
            <p class="mb-2"><span class="font-bold">URL:</span> {{$reportDetail->url}}</p>
        </a>
        <img src="{{$reportDetail->image}}" alt="" class="max-w-full h-auto mb-4">
    </div>
    <div class="mb-6">
        <p class="mb-2"><span class="font-bold">Load Time:</span> {{$reportDetail->load_time}}s</p>
        <p class="mb-2"><span class="font-bold">Page Size:</span> {{$reportDetail->page_size}}kb</p>
        <p class="mb-2"><span class="font-bold">Http Request Count:</span> {{$reportDetail->http_requests_count}}</p>
    </div>
    <div class="mb-6">
        <h1 class="text-2xl font-bold mb-4">SEO</h1>
        <p class="mb-2"><span class="font-bold">Title:</span> {{$reportDetail->title}}</p>
        <p class="mb-2"><span class="font-bold">Meta Description:</span> {{$reportDetail->meta_description}}</p>
        <p class="mb-2"><span class="font-bold">Headings:</span></p>

        @if ($headings = json_decode($reportDetail->headings, true))
        @if (is_array($headings) && !empty($headings))
        <?php
        $headingCounts = [];

        foreach ($headings as $tag => $content) {
            $headingCounts[$tag] = count($content);
        }
        ?>
        @foreach ($headings as $tag => $content)
        <p class="mb-1">
            <span class="font-bold">{{ $tag }}:</span>
            {{ $headingCounts[$tag] }}
        </p>
        <ul class="list-disc ml-4">
            @foreach ($content as $item)
            <li>{{ $item }}</li>
            @endforeach
        </ul>
        @endforeach
        @else
        {{ json_decode($reportDetail->headings) }}
        @endif
        @endif


        <p class="mb-2">
            <span class="font-bold">Content Keywords:</span>
            @php
            $keywords = json_decode($reportDetail->keywords);
            @endphp

            @if(is_array($keywords))
        <p>{{count($keywords)}}</p>
        <ul>
            @foreach($keywords as $keyword)
            <li>{{ $keyword }}</li>
            @endforeach
        </ul>
        @else
        {{ $keywords }}
        @endif
        </p>

        <p class="mb-2"><span class="font-bold">Image Keywords:</span></p>
        @php
        $imageKeywords=json_decode($reportDetail->image_keywords)
        @endphp
        @if (is_array($imageKeywords) && count($imageKeywords) > 0)
        <p>{{count($imageKeywords)}}</p>
        <ul class="list-disc ml-4">
            @foreach ($imageKeywords as $keyword)
            <a href="{{$keyword}}" target="_blank">
                <li>{{ $keyword }}</li>
            </a>
            @endforeach
        </ul>
        @else
        <p>{{ $imageKeywords }}</p>
        @endif
        <p class="mb-2"><span class="font-bold">SEO Friendly URL:</span> {{$reportDetail->seo_friendly}}</p>
        <p class="mb-2"><span class="font-bold">404 Page:</span>
            @if (filter_var($reportDetail->notfound, FILTER_VALIDATE_URL))
            <a href="{{ $reportDetail->notfound }}" target="_blank" class="text-blue-500 no-underline">{{ $reportDetail->notfound }}</a>
            @else
        <p>{{ $reportDetail->notfound }}</p>
        @endif
        </p>
        <p class="mb-2"><span class="font-bold">Robots.txt:</span> {{$reportDetail->robot_txt}}</p>
        <p class="mb-2"><span class="font-bold">Noindex:</span> {{$reportDetail->no_index}}</p>
        <p class="mb-2"><span class="font-bold">Inpage-Links:</span></p>
        @php
        $links=json_decode($reportDetail->page_links);
        if(is_object($links)){
        $links=(array)json_decode($reportDetail->page_links);
        $external=(array)$links['External'];
        $internal=(array)$links['Internal'];
        }
        @endphp
        @if (count($external) > 0)
        <p class="mb-1"><span class="font-bold">External Links:</span></p>
        <p>{{count($external)}}</p>
        <ul class="list-disc ml-4">
            @foreach ($external as $link)
            <li>{{ $link }}</li>
            @endforeach
        </ul>
        @endif
        @if (count($internal) > 0)
        <p class="mb-1"><span class="font-bold">Internal Links:</span></p>
        <p>{{count($internal)}}</p>
        <ul class="list-disc ml-4">
            @foreach ($internal as $link)
            <li>{{ $link }}</li>
            @endforeach
        </ul>
        @endif
        <p class="mb-2"><span class="font-bold">Language:</span> {{$reportDetail->language}}</p>
        <p class="mb-2"><span class="font-bold">Favicon:</span>
            @if (filter_var($reportDetail->favicon, FILTER_VALIDATE_URL))
            <a href="{{ $reportDetail->favicon }}" target="_blank" class="text-blue-500 no-underline">{{ $reportDetail->favicon }}</a>
            @else
        <p>{{ $reportDetail->favicon }}</p>
        @endif
        </p>
    </div>
    <div class="mb-6">
        <h1 class="text-2xl font-bold mb-4">PERFORMANCE</h1>
        <p>Text compression : The HTML filesize is {{$reportDetail->page_size}} kB.</p>
        <p>Load Time : The webpage loaded in {{$reportDetail->load_time}} seconds.</p>
        <p>HTTP requests : @php
            $links=json_decode($reportDetail->http_requests);
            $css=$links->css ?? [];
            $script=$links->script ?? [];
            $images=$links->img ?? [];
            $iframes=$links->iframe ?? [];
            @endphp
            @if (count($css) > 0)
        <p class="mb-1"><span class="font-bold">CSS:</span></p>
        <p>{{count($css)}}</p>
        <ul class="list-disc ml-4">
            @foreach ($css as $link)
            <a href="{{$link}}" target="_blank" class="text-blue-500 no-underline">
                <li>{{ $link }}</li>
            </a>
            @endforeach
            @endif
        </ul>
        @if (count($script) > 0)
        <p class="mb-1"><span class="font-bold">Script:</span></p>
        <p>{{count($script)}}</p>
        <ul class="list-disc ml-4">
            @foreach ($script as $link)
            <a href="{{$link}}" target="_blank" class="text-blue-500 no-underline">
                <li>{{ $link }}</li>
            </a>
            @endforeach
            @endif
        </ul>
        @if (count($images) > 0)
        <p class="mb-1"><span class="font-bold">Images:</span></p>
        <p>{{count($images)}}</p>
        <ul class="list-disc ml-4">
            @foreach ($images as $link)
            <a href="{{$link}}" target="_blank" class="text-blue-500 no-underline">
                <li>{{ $link }}</li>
            </a>
            @endforeach
            @endif
        </ul>
        @if (count($iframes) > 0)
        <p class="mb-1"><span class="font-bold">Iframes:</span></p>
        <p>{{count($iframes)}}</p>
        <ul class="list-disc ml-4">
            @foreach ($iframes as $link)
            <a href="{{$link}}" target="_blank" class="text-blue-500 no-underline">
                <li>{{ $link }}</li>
            </a>
            @endforeach
            @endif
        </ul>


        </p>
        <p><span class="font-bold">Image format:</span>
            @php
            $image_format=json_decode($reportDetail->image_format);
            @endphp
            @if(is_array($image_format))
        <p>{{count($image_format)}}</p>
        <ul>
            @foreach($image_format as $link)
            <a href="{{$link}}" target="_blank" class="text-blue-500 no-underline">
                <li>{{$link}}</li>
            </a>
            @endforeach
        </ul>
        @else
        {{$image_format}}
        @endif
        </p>
        <p><span class="font-bold">JavaScript defer :</span>
            @php
            $js_defer=json_decode($reportDetail->js_defer);
            @endphp
            @if(is_array($js_defer))
        <p>{{count($js_defer)}}</p>
        <ul>
            @foreach($js_defer as $link)
            <a href="{{$link}}" target="_blank" class="text-blue-500 no-underline">
                <li>{{$link}}</li>
            </a>
            @endforeach
        </ul>
        @else
        {{$js_defer}}
        @endif
        </p>
        <p><span class="font-bold">DOM size:</span>
            The HTML file has {{$reportDetail->dom_size}} DOM nodes.
        </p>


    </div>
    <div class="mb-6">
        <h1 class="text-2xl font-bold mb-4">SECURITY</h1>
        <p><span class="font-bold">HTTPS encryption :</span> {{$reportDetail->http_enc}}</p>
        <p><span class="font-bold">Mixed content: </span>{{$reportDetail->mix_content}}</p>
        <p><span class="font-bold">Server signature : </span>{{$reportDetail->server}}</p>
        <p><span class="font-bold">HTST : </span>{{$reportDetail->htst}}</p>
        <p><span class="font-bold">Plaintext email :</span> @php
            $email=json_decode($reportDetail->plaintext_email);
            @endphp
            @if(is_array($email))
        <p>{{count($email)}}</p>
        <ul>
            @foreach($email as $link)
            <li>{{$link}}</li>
            @endforeach
        </ul>
        @else
        {{$email}}
        @endif
        </p>

    </div>
    <div class="mb-6">
        <h1 class="text-2xl font-bold mb-4">Miscellaneous</h1>
        <div class="ml-3">
            <p><span class="font-bold">Structured data :</span>
                @php
                $data=json_decode($reportDetail->structured_data);
                if(is_object($data)){
                $data=(array)$data;
                $opengraph = isset($data['openGraph']) ? (array) $data['openGraph'] : [];
                $twitter = isset($data['twitter']) ? (array) $data['twitter'] : [];
                $schemaorg = isset($data['schemaOrg']) ? (array) $data['schemaOrg'] : [];
                }
                else{
                $data=json_decode($reportDetail->structured_data);
                }
                @endphp
            <div class="space-y-4">
                @if(isset($data['openGraph']) || isset($data['twitter']) || isset($data['schemaOrg']))
                @if(count($opengraph)>0)
                <h2 class="text-xl text-blue-500">OpenGraph</h2>
                @foreach($opengraph as $links=>$value)
                <p class="font-bold">{{$links}}:</p>
                <p>{{$value}}</p>
                @endforeach
                @endif
            </div>
            <div class="space-y-4">
                @if(count($twitter)>0)
                <h2 class="text-xl text-blue-500">Twitter</h2>
                @foreach($twitter as $links=>$value)
                <p class="font-bold">{{$links}}:</p>
                <p>{{$value}}</p>
                @endforeach
                @endif
            </div>
            @else
            {{$data}}
            @endif
            </p>
        </div>
        <p><span class="font-bold">Meta viewport:</span>
            {{$reportDetail->meta_viewport}}
        </p>
        <p><span class="font-bold">Character set:</span>
            {{$reportDetail->char_set}}
        </p>
        <p><span class="font-bold">Sitemap:</span>
            @php
            $sitemap=json_decode($reportDetail->sitemap);
            @endphp
            @if(is_array($sitemap))
        <p>{{count($sitemap)}}</p>
        <ul>
            @foreach($sitemap as $link)
            <li>{{$link}}</li>
            @endforeach
        </ul>
        @else
        {{$sitemap}}
        @endif
        </p>
        <p><span class="font-bold">Social:</span>
            @php
            $youtube=$instagram=$linkedin=$facebook=$twitter=[];
            if (is_object(json_decode($reportDetail->social))) {
            $social = (array) json_decode($reportDetail->social);
            $youtube = isset($social['YouTube']) ? $social['YouTube'] : [];
            $facebook = isset($social['Facebook']) ? $social['Facebook'] : [];
            $twitter = isset($social['Twitter']) ? $social['Twitter'] : [];
            $instagram = isset($social['Instagram']) ? $social['Instagram'] : [];
            $linkedin = isset($social['LinkedIn']) ? $social['LinkedIn'] : [];
            } else {
            $social = json_decode($reportDetail->social);
            }

            @endphp

            @if(count($youtube)>0)
        <p>{{count($youtube)}}</p>
        <ul>
            <span class="text-orange-500 text-lg">YouTube:</span>
            @foreach($youtube as $link)
            <li><a href="{{ $link }}" target="_blank" class="text-blue-500 no-underline">{{ $link }}</a></li>
            @endforeach
        </ul>
        @endif

        @if(is_array($social))

        @if(count($facebook)>0)
        <p>{{count($facebook)}}</p>
        <ul><span class="text-orange-500 text-lg">Facebook:</span>
            @foreach($facebook as $link)
            <li><a href="{{ $link }}" target="_blank" class="text-blue-500 no-underline">{{ $link }}</a></li>
            @endforeach
        </ul>
        @endif
        @if(count($twitter)>0)
        <p>{{count($twitter)}}</p>
        <ul><span class="text-orange-500 text-lg">Twitter:</span>
            @foreach($twitter as $link)
            <li><a href="{{ $link }}" target="_blank" class="text-blue-500 no-underline">{{ $link }}</a></li>
            @endforeach
        </ul>
        @endif
        @if(count($instagram)>0)
        <p>{{count($instagram)}}</p>
        <ul><span class="text-orange-500 text-lg">Instagram:</span>
            @foreach($instagram as $link)
            <li><a href="{{ $link }}" target="_blank" class="text-blue-500 no-underline">{{ $link }}</a></li>
            @endforeach
        </ul>
        @endif
        @if(count($linkedin)>0)
        <p>{{count($linkedin)}}</p>
        <ul><span class="text-orange-500 text-lg">LinkedIn:</span>
            @foreach($linkedin as $link)
            <li><a href="{{ $link }}" target="_blank" class="text-blue-500 no-underline">{{ $link }}</a></li>
            @endforeach
        </ul>
        @endif
        @else
        {{ $social }}
        @endif
        </p>
        <p><span class="font-bold">Content Length:</span>
            The webpage has {{$reportDetail->content_length}} words.
        </p>
        <p>
            <span class="font-bold">Text to HTML ratio:</span> The text to HTML ratio is {{$reportDetail->ratio}}%.
        </p>
        <p><span class="font-bold">Inline CSS:</span>
            @php
            $css=json_decode($reportDetail->inline_css);
            @endphp

            @if(is_array($css))
        <ul>
            <p>{{count($css)}}</p>
            @foreach($css as $link)
            <li>{{$link}}</li>
            @endforeach
        </ul>
        @else
        {{$css}}
        @endif
        </p>


    </div>

</x-admin-layout>