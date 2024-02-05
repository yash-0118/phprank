<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Setting;
use Illuminate\Support\Facades\Auth;

class SettingController extends Controller
{
    public function general()
    {
        $settings = Setting::all();
        $groupedSettings = $settings->groupBy('group');

        // dd($groupedSettings);
        return view('admin.setting', compact('groupedSettings'));
    }
    public function general1(Setting $settings)
    {
        $settingsRecord = Setting::where('group', 'general')->get();

        dd($settingsRecord);
        // $settingValue = $settings->get('setting');
        // dd("hello", $settingValue);
        // return view('your_view', ['settingValue' => $settingValue]);
    }
    public function setting($group)
    {
        
        $settingsRecords = Setting::where('group', $group)->orderBy('id')->get();
        // dd($settingsRecords);
        foreach ($settingsRecords as $settingsRecord) {
            $settingsRecord->payload = trim(str_replace(["\n", "\r", "\t", '"', "\\"], '', $settingsRecord->payload));
        }
        // dd($settingsRecord->payload);
        $view = 'settings.' . $group;

        return view($view, ['settingsRecords' => $settingsRecords, 'group' => $group]);
    }

    // public function dynamicSetting()
    // {
    //     $generalRecords = Setting::where('group', 'general')->orderBy('id')->get();
    //     // dd($generalRecords);
    //     $settings = [];
    //     // dd($settings);
    //     foreach ($generalRecords as $setting) {
    //         // dd($setting);
    //         $settings[$setting->name] = str_replace(['"'], '', $setting->payload);
    //     }
    //     dd($settings['title']);
    //     return view('layouts.admin', ['settings' => $settings]);
    // }
    public function saveSettings(Request $request, $group)
    {
        $inputData = $request->except('_token');
        // dd($inputData);
        $rules = [
            // "custom_index" => "nullable",
            // "logo" => "nullable",
            // "favicon" => "nullable",
            // "bad_words" => "nullable",
            "*" => 'required',
            // "facebook" => ["nullable", "regex:/^(https?:\/\/)?(www\.)?facebook\.com\/?.*/"],
            "facebook" => ["nullable", "regex:/^(?:https?:\/\/)?(?:www\.)?facebook\.com\/[a-zA-Z0-9_.-]+\/?$/"],
            // "youtube" => ["nullable", "regex:/^(https?:\/\/)?(www\.)?youtube\.com\/?.*/"],
            "youtube" => ["nullable", "regex:/^(http:|https:)?(\/\/)?(www\.)?(youtube\.com|youtu\.be)\/(watch|embed)?(\?v=|\/)?(\S+)?/"],
            // "twitter" => ["nullable", "regex:/^(https?:\/\/)?(www\.)?twitter\.com\/?.*/"],
            "twitter" => ["nullable", "regex:/^http(?:s)?:\/\/(?:www\.)?twitter\.com\/([a-zA-Z0-9_]+)/"],
            // "instagram" => ["nullable", "regex:/^(https?:\/\/)?(www\.)?instagram\.com\/?.*/"],
            "instagram" => ["nullable", "regex:/^(?:(?:http|https):\/\/)?(?:www\.)?(?:instagram\.com|instagr\.am|instagr\.com)\/(\w+)/"],

            "demo_url" => ["nullable", "regex:/^(https?:\/\/www\.|https?:\/\/|www\.)?[a-zA-Z]{2,}(\.[a-zA-Z]{2,})(\.[a-zA-Z]{2,})?\/[a-zA-Z0-9]{2,}|(https?:\/\/www\.|https?:\/\/|www\.)?[a-zA-Z]{2,}(\.[a-zA-Z]{2,})(\.[a-zA-Z]{2,})?|(https?:\/\/www\.|https?:\/\/|www\.)?[a-zA-Z0-9]{2,}\.[a-zA-Z0-9]{2,}\.[a-zA-Z0-9]{2,}(\.[a-zA-Z0-9]{2,})?$/"]
        ];

        $message = [
            "*.required" => "The :attribute field is required.",
            "facebook" => "Invalid format for Facebook.",
            "youtube" => "Invalid format for YouTube.",
            "twitter" => "Invalid format for Twitter.",
            "instagram" => "Invalid format for Instagram.",
            "demo_url" => "Invalid Url Format"
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
                // dd(session('logo_path'));    

            } else {
                if ($name == "theme") {
                    session(['theme' => $value]);
                }
                // dd($value);
                Setting::where('group', $group)->where('name', $name)->update(['payload' => json_encode($value)]);
            }
        }
        return redirect()->back()->with('success', 'Settings updated successfully');
    }
}
