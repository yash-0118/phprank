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
        $announcement_guest = Setting::where('group', 'announcement_guest')->orderBy('id')->get();
        // $announcement_guest = str_replace(['"'], '', $announcement_guest->payload);
        $ann_guests = [];
        // dd($settings);
        foreach ($announcement_guest as $user) {
            $ann_guests[$user->name] = str_replace(['"'], '', $user->payload);
        }

        return view('layouts.guest', ['announcemet_guest' => $ann_guests]);
    }
}
