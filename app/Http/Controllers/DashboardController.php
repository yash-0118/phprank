<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Models\SiteMaster;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class DashboardController extends Controller
{
    public function index()
    {
        $user_id = Auth::user()->id;
        $user = User::find($user_id);

        $reports = $user->sitemasters()->latest()->take(5)->get();
        $projects = $user->sites()->latest()->take(5)->get();
        $categoryCounts = $user->sitemasters()->select('category', DB::raw('count(*) as count'))
        ->groupBy('category')
        ->pluck('count', 'category');

        $totalReports = $categoryCounts->sum();
        $categoryPercentages = $categoryCounts->map(function ($count) use ($totalReports) {
            return ($count / $totalReports) * 100;
        });

        return view('user.index', ['reports' => $reports, 'projects' => $projects, "user" => $user,  'categoryPercentages' => $categoryPercentages,]);
    }
    public function admin()
    {
        $currentUser = Auth::user();
        $users = User::where('id', '!=', 1)->where('id', '!=', $currentUser->id)->select('id', 'name', 'email')->latest()->take(5)->get();
        return view('admin.dashboard.index', ['users' => $users]);
    }
    public function reports()
    {
        $setting = Setting::where('name', 'result_per_page')->first();
        $paginate = (int)json_decode($setting->payload);
        $reports = SiteMaster::with('user')
            ->orderBy('created_at', 'desc')
            ->paginate($paginate);
        return view('admin.reports.index', ['reports' => $reports]);
    }
    public function destroy($id)
    {
        $report = SiteMaster::find($id);
        if (!$report) {
            return redirect('/admin/reports')->with('error', 'Report not found.');
        }
        $report->delete();
        return redirect('/admin/reports')->with('success', 'Report deleted successfully.');
    }
    public function publicReport($id)
    {
        $site = SiteMaster::find($id);
        return view('user.reports.password_report', ['reportDetail' => $site]);
    }
    public function password($id)
    {
        return view('user.reports.password', ['id' => $id]);
    }

    public function verifyPassword(Request $request, $id)
    {
        $site = SiteMaster::find($id);
        $request->validate([
            'password' => 'required|string',
        ]);

        if ($request->password=== Crypt::decrypt($site->password)) {
            session(['password_verified' => true]);
            return redirect()->route('password.report', ['id' => $id]);
        }


        return redirect()->back()->withErrors(['password' => 'Incorrect password']);
    }
    public function passwordReport($id)
    {
        if (session('password_verified')) {
            $site = SiteMaster::find($id);
            return view('user.reports.password_report', ['reportDetail' => $site]);
        }
        return redirect()->route('password.entry.form', ['id' => $id]);
    }
    public function edit($id)
    {
        $report = SiteMaster::findOrFail($id);
        return view('admin.reports.edit', compact('report'));
    }
    public function update(Request $request, $id)
    {
        $report = SiteMaster::findOrFail($id);
        $request->validate([
            'visibility' => ['required', Rule::in(['public', 'private', 'password'])],
            'password' => ['required_if:visibility,password'],
        ]);
        $report->visibility = $request->input('visibility');

        if ($report->visibility === 'password') {
            $report->password = Hash::make($request->input('password'));
        } else {
            $report->password = null;
        }
        $report->save();

        return redirect()->route('admin.reports.index')->with('success', 'Report visibility updated successfully');
    }
}
