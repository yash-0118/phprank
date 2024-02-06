<?php

namespace App\View\Components;

use App\Models\Setting;
use Illuminate\View\Component;
use Illuminate\View\View;

class GuestLayout extends Component
{
    /**
     * Get the view / contents that represents the component.
     */
    public function render(): View
    {
        $announcement_guest = Setting::where('group', 'announcementGuest')->orderBy('id')->get();
        $ann_guests = [];
        foreach ($announcement_guest as $user) {
            $ann_guests[$user->name] = str_replace(['"'], '', $user->payload);
        }

        return view('layouts.guest', ['announcemet_guest' => $ann_guests]);
    }
}
