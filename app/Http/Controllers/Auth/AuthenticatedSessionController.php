<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $email = $request->email;
        $user = User::where('email', $email)->first();
        if ($user) {
            $user_status = $user->is_active;
            if (!$user_status) {
                Auth::guard('web')->logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                throw ValidationException::withMessages([
                    'email' => [trans('Your account is disabled so contact admin for this.')],
                ])->redirectTo(route('login'));
            }
        }
        $request->authenticate();

        $request->session()->regenerate();
        $role=DB::table('model_has_roles')->where('model_id',Auth::user()->id)->select('role_id')->first()->role_id;
        if($role==1){
            return redirect('/admin/dashboard');
        }
        else{
            return redirect('/user');
        }
        return redirect('/dashboard');
        return redirect()->intended(RouteServiceProvider::HOME);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();
        
        $request->session()->invalidate();
        
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
