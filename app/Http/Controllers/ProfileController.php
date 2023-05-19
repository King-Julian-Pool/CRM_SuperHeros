<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\View\View;
use App\Models\IncidentType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests\ProfileUpdateRequest;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        $incidentTypes = IncidentType::all();
        $userIncidentTypes = $request->user()->incidentTypes()->pluck('incident_types.id')->toArray();

        return view('profile.edit', [
            'user' => $request->user(),
            'incidentTypes' => $incidentTypes,
            'userIncidentTypes' => $userIncidentTypes,
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {

        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->validate([
            'incident_types' => ['array', 'min:1', 'max:3'], // add validation for incident types array
        ]);

        if ($request->user()->role_id === User::ROLE_HERO) {
            $request->user()->phone = $request->phone;
            $request->user()->latitude = $request->latitude;
            $request->user()->longitude = $request->longitude;
            if ($request->has('incident_types')) {
                $incidentTypes = IncidentType::whereIn('id', $request->incident_types)->get();
                $request->user()->incidentTypes()->sync($incidentTypes);
            }
            $request->user()->save();
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
