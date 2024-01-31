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
        $announcement_user = Setting::where('group', 'announcement_user')->orderBy('id')->get()[0];
        // $announcement_user = str_replace(['"'], '', $announcement_user->payload);
        // dd($generalRecords);
        // dd($announcement_user['payload']);
        $settings = [];
        // dd($settings);
        foreach ($generalRecords as $setting) {
            // dd($setting);
            $settings[$setting->name] = str_replace(['"'], '', $setting->payload);
        }
        // dd($settings['title']);
        return view('layouts.admin', ['settings' => $settings, 'announcemet_user' => $announcement_user]);
        // return view('layouts.admin');
    }
}
