<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Client;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'phone' => ['required', 'string', 'max:20'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'role' => 'client',
        ]);

        // Link to existing client record if found, or create new one
        $client = Client::where('email', $request->email)->first();
        if ($client) {
            $client->update([
                'user_id' => $user->id,
                'phone' => $request->phone // Update phone if different
            ]);
        } else {
            Client::create([
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $request->phone,
                'user_id' => $user->id,
            ]);
        }

        event(new Registered($user));

        Auth::login($user);

        if ($request->filled('redirect')) {
            return redirect($request->input('redirect'));
        }

        return redirect(route('dashboard', absolute: false));
    }
}
