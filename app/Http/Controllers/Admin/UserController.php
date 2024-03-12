<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $searchTerm = $request->input('search');
        $currentUser = Auth::user();
        $usersQuery = User::where('id', '!=', 1)->where('id', '!=', $currentUser->id);

        if ($searchTerm) {
            $usersQuery = $usersQuery->where('name', 'like', '%' . $searchTerm . '%')->where('name', '!=', 'admin');
        }

        $users = $usersQuery->get();
        $userRoles = DB::table('model_has_roles')->pluck('role_id', 'model_id');
        $usersWithRoles = [];
        foreach ($users as $user) {
            $userId = $user->id;
            $role = isset($userRoles[$userId]) ? $userRoles[$userId] : null;
            $usersWithRoles[] = [
                'user' => $user,
                'role' => $role,
            ];
        }

        return view('admin.users.index', ['usersWithRoles' => $usersWithRoles]);
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        if($id==1){
            abort(403,"You have don't access for this operation");
        }
        return view('admin.users.edit', compact('user'));
    }
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255'],
            'role' => ['required', Rule::in(['user', 'admin'])],
            'is_active' => ['required', Rule::in([0, 1])]
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'is_active' => $request->is_active
        ]);
        $user->roles()->detach();
        $user->assignRole($request->role);

        return redirect()->route('admin.users.index', $user->id)->with('success', 'User details updated successfully');
    }
    public function destroy(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'User deleted successfully');
    }
}
