<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\View\View;
use App\Models\IncidentType;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\RedirectResponse;
use Illuminate\Auth\Events\Registered;
use App\Providers\RouteServiceProvider;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        $incidentTypes = IncidentType::all();
        return view('auth.register', compact('incidentTypes'));
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {

        dd($request->all());

        $role = null;
        if ($request->role === 'commune') {
            $role = User::ROLE_COMMUNE;
        } elseif ($request->role === 'hero') {
            $role = User::ROLE_HERO;
        }

        if ($role == null) {
            return redirect()->back()->withErrors(['role' => 'Veuillez choisir un rôle']);
        }

        if ($role == User::ROLE_HERO) {
            $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:' . User::class],
                'password' => ['required', 'confirmed', Rules\Password::defaults()],
                'role' => ['required', 'string', 'max:255', 'in:commune,hero'],
                'incident_types' => ['required', 'array', 'min:1', 'max:2'], // add validation for incident types array
            ]);
        } else {
            $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:' . User::class],
                'password' => ['required', 'confirmed', Rules\Password::defaults()],
                'role' => ['required', 'string', 'max:255', 'in:commune,hero'],
            ]);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role_id' => $role,
        ]);

        if ($request->has('incident_types')) {
            $incidentTypes = IncidentType::whereIn('id', $request->incident_types)->get();
            $user->incidentTypes()->sync($incidentTypes);
        }

        event(new Registered($user));

        Auth::login($user);

        return redirect(RouteServiceProvider::HOME);
    }
}
