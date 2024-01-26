<?php

namespace App\Http\Controllers;

use App\Settings\generalSetting;
use Illuminate\Http\Request;
use App\Models\Setting;

class SettingController extends Controller
{
    public function general()
    {
        // $general=new generalSetting();
        $settings = Setting::all();
        $groupedSettings = $settings->groupBy('group');
        return view('admin.setting',compact('groupedSettings'));
       


        
    }
}
