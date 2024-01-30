<?php

namespace App\View\Components;

use App\Models\Setting;
use Illuminate\View\Component;
use Illuminate\View\View;

class AdminLayout extends Component
{
    /**
     * Get the view / contents that represents the component.
     */
    public function render(): View
    {
        $generalRecords = Setting::orderBy('id')->get();
        // dd($generalRecords);
        $settings = [];
        // dd($settings);
        foreach ($generalRecords as $setting) {
            // dd($setting);
            $settings[$setting->name] = str_replace(['"'], '', $setting->payload);
        }
        // dd($settings['title']);
        return view('layouts.admin', ['settings' => $settings]);
        // return view('layouts.admin');
    }
}
