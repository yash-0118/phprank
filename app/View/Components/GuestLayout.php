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
        $announcement_guest = Setting::where('group', 'announcement_guest')->orderBy('id')->get()[0];
        // $announcement_guest = str_replace(['"'], '', $announcement_guest->payload);

        return view('layouts.guest', ['announcemet_guest' => $announcement_guest]);
    }
}
