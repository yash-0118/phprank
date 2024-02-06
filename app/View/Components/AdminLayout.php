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
        $announcement_user = Setting::where('group', 'announcementUser')->orderBy('id')->get();
        $settings = [];
        $ann_users = [];
        foreach ($announcement_user as $user) {
            $ann_users[$user->name] = str_replace(['"'], '', $user->payload);
        }
        foreach ($generalRecords as $setting) {
            $settings[$setting->name] = str_replace(['"'], '', $setting->payload);
        }
        return view('layouts.admin', ['settings' => $settings, 'announcemet_user' => $ann_users]);
    }
}
