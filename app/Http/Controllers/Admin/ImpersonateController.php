<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class ImpersonateController extends Controller
{
    public function start(Request $request, User $user)
    {
        // Safety check: only admin can start impersonation
        if (!in_array(auth()->user()->role, ['admin', 'superadmin'])) {
            abort(403);
        }

        // Safety check: cannot impersonate self or another admin (usually)
        if ($user->id === auth()->id() || in_array($user->role, ['admin', 'superadmin'])) {
            return back()->withErrors(['error' => 'Nu poți impersona acest utilizator.']);
        }

        // Store original admin ID and the target ID
        $request->session()->put('impersonate_id', $user->id);
        $request->session()->put('original_user_id', auth()->id());

        return redirect()->route('employee.dashboard')->with('success', "Acum acționezi în numele lui {$user->name}.");
    }

    public function stop(Request $request)
    {
        $request->session()->forget('impersonate_id');
        $request->session()->forget('original_user_id');

        return redirect()->route('admin.employees.index')->with('success', 'Te-ai întors la contul tău de Administrator.');
    }
}
