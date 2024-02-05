<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Setting;
use Illuminate\Support\Facades\Auth;

class SettingController extends Controller
{
    public function setting($group)
    {

        $settingsRecords = Setting::where('group', $group)->orderBy('id')->get();
        foreach ($settingsRecords as $settingsRecord) {
            $settingsRecord->payload = trim(str_replace(["\n", "\r", "\t", '"', "\\"], '', $settingsRecord->payload));
        }
        $view = 'settings.' . $group;

        return view($view, ['settingsRecords' => $settingsRecords, 'group' => $group]);
    }


    public function saveSettings(Request $request, $group)
    {
        $inputData = $request->except('_token');
        $rules = [

            "*" => 'required',

            "facebook" => ["nullable", "regex:/^(?:https?:\/\/)?(?:www\.)?facebook\.com\/[a-zA-Z0-9_.-]+\/?$/"],

            "youtube" => ["nullable", "regex:/^(http:|https:)?(\/\/)?(www\.)?(youtube\.com)\/(watch|embed)?(\?v=|\/)?(\S+)?/"],

            "twitter" => ["nullable", "regex:/^http(?:s)?:\/\/(?:www\.)?twitter\.com\/([a-zA-Z0-9_]+)/"],

            "instagram" => ["nullable", "regex:/^(?:(?:http|https):\/\/)?(?:www\.)?(?:instagram\.com|instagr\.am|instagr\.com)\/(\w+)/"],

            "demo_url" => ["nullable", "regex:/^(https?:\/\/www\.|https?:\/\/|www\.)?[a-zA-Z]{2,}(\.[a-zA-Z]{2,})(\.[a-zA-Z]{2,})?\/[a-zA-Z0-9]{2,}|(https?:\/\/www\.|https?:\/\/|www\.)?[a-zA-Z]{2,}(\.[a-zA-Z]{2,})(\.[a-zA-Z]{2,})?|(https?:\/\/www\.|https?:\/\/|www\.)?[a-zA-Z0-9]{2,}\.[a-zA-Z0-9]{2,}\.[a-zA-Z0-9]{2,}(\.[a-zA-Z0-9]{2,})?$/"]
        ];

        $message = [
            "*.required" => "The :attribute field is required.",
            "facebook" => "Invalid format for Facebook. valid format:https://www.facebook.com/example_user",
            "youtube" => "Invalid format for YouTube. valid format: https://www.youtube.com/watch?v=abcdef12345",
            "twitter" => "Invalid format for Twitter. valid format: https://twitter.com/example_user",
            "instagram" => "Invalid format for Instagram. valid format :https://www.instagram.com/example_user",
            "demo_url" => "Invalid Url Format valid url: https://www.example.com/path/to/page"
        ];
        $validator = Validator::make($inputData, $rules, $message);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        foreach ($inputData as $name => $value) {

            if ($request->hasFile($name)) {
                $file = $request->file($name);


                if ($file->isValid() && str_starts_with($file->getMimeType(), 'image/')) {
                    $path = $file->store('uploads', 'public');
                    Setting::where('group', $group)->where('name', $name)->update(['payload' => json_encode($path)]);
                } else {
                    return redirect()->back()->with('error', 'Only image files are allowed for ' . $name);
                }

            } else {
                if ($name == "theme") {
                    session(['theme' => $value]);
                }

                Setting::where('group', $group)->where('name', $name)->update(['payload' => json_encode($value)]);
            }
        }
        return redirect()->back()->with('success', 'Settings updated successfully');
    }
}
