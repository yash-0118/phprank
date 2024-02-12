<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $searchTerm=$request->input('search');
        $users = User::where('name', '!=', 'admin')->select('id', 'name', 'email')->get();
        if($searchTerm){
            $users=User::where('name','like','%'.$searchTerm.'%')->where('name', '!=', 'admin')->get();
        }
        return view('admin.users.index', ["users" => $users]);
    }
    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('admin.users.edit', compact('user'));
    }
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255'],
            'role' => ['required', Rule::in(['user', 'admin'])],
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);
        $user->roles()->detach();
        $user->assignRole($request->role);

        return redirect()->route('admin.user.edit', $user->id)->with('success', 'User details updated successfully');
    }
    public function destroy(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'User deleted successfully');
    }
}
