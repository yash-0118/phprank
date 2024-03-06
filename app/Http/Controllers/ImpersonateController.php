<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Lab404\Impersonate\Services\ImpersonateManager;

class ImpersonateController extends Controller
{
    protected $impersonate;
    public function __construct(ImpersonateManager $impersonate)
    {
        $this->impersonate = $impersonate;
    }

    public function startImpersonate()
    {
        $this->middleware('admin');

        $users = User::all();
        return view('start-impersonate', compact('users'));
    }

    public function impersonateUser(User $user)
    {
        $role = DB::table('model_has_roles')->where('model_id', $user->id)->select('role_id')->first()->role_id;
        if ($role == 1) {
            $this->impersonate->take(Auth::user(), $user);
            session()->put('impersonate', $user);
            return redirect()->route('admin.dashboard.index');
        }
        elseif($role==2){
            $this->impersonate->take(Auth::user(), $user);
            session()->put('impersonate', $user);
            return redirect()->route('user.index');
        }
        
    }

    public function stopImpersonating()
    {
        if (Auth::check()) {
            $this->impersonate->leave();
            session()->forget('impersonate');
            return redirect()->route('admin.dashboard.index');
        } else {
            return redirect()->route('login')->with('status', 'Please log in as admin.');
        }
    }

}
