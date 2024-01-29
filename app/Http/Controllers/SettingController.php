<?php

namespace App\Http\Controllers;

use App\Settings\generalSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Validator;
use App\Models\Setting;
use Spatie\LaravelData\Attributes\Validation\Nullable;

class SettingController extends Controller
{
    public function general()
    {
        // $general=new generalSetting();
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
            $settingsRecord->payload = trim(str_replace(["\n", "\r", "\t", '"'], '', $settingsRecord->payload));
        }

        $view = 'settings.' . $group;

        return view($view, ['settingsRecords' => $settingsRecords, 'group' => $group]);
    }


    public function saveSettings(Request $request, $group)
    {
        $inputData = $request->except('_token');
        // dd($inputData);
        $rules = ["custom_index" => "nullable", "logo" => "nullable", "favicon" => "nullable", "bad_words" => "nullable", "*" => 'required',];
        $message = ["*.required" => "The :attribute field is required."];
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
                session(['logo_path' => $path]);
                // dd(session('logo_path'));    

            } else {
                Setting::where('group', $group)->where('name', $name)->update(['payload' => json_encode($value)]);
            }
        }
        // Config::set('settings.logo_path', $path);
        return redirect()->back()->with('success', 'Settings updated successfully');
    }
}
