<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Incident;
use App\Models\IncidentType;
use Illuminate\Http\Request;
use Psy\Readline\Hoa\Console;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\RedirectResponse;
use App\Providers\RouteServiceProvider;

class IncidentController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
    }

    public function index(Request $request)
    {
        if (!Gate::allows('access-commune')) {
            return redirect(RouteServiceProvider::HOME);
        }
        $incidents = Incident::with('incidentType')
            ->where('commune_id', auth()->user()->id)
            ->where('is_resolved', false)
            ->get();

        $heroes = User::with('incidentTypes')
            ->where('role_id', User::ROLE_HERO)
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->get();

        return view('incidents.index', [
            'incidents' => $incidents,
            'heroes' => $heroes,
        ]);
    }

    public function map()
    {
        $incidents = Incident::all();
        $incidentTypes = IncidentType::all();
        return view('incidents.map', [
            'incidents' => $incidents,
            'incidentTypes' => $incidentTypes,
        ]);
    }

    public function create()
    {
        if (!Gate::allows('access-commune')) {
            return redirect(RouteServiceProvider::HOME);
        }
        $incidentTypes = IncidentType::all();
        return view('incidents.create', [
            'incidentTypes' => $incidentTypes,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validatedData = $request->validate([
            'commune_id' => 'required',
            'incident_type_id' => 'required',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        $incident = Incident::create($validatedData);

        return redirect()->route('incidents.index')->with('status', 'incident-ajouté');
    }

    public function resolve(Incident $incident)
    {

        try {
            $incident->is_resolved = true;
            $incident->save();

            return response()->json([
                'status' => 'ok',
            ]);
        } catch (\Exception $e) {

            return response()->json([
                'status' => 'error',
            ]);
        }
    }

    public function destroy(Incident $incident)
    {
        try {
            $incident->delete();

            return response()->json([
                'status' => 'ok',
            ]);
        } catch (\Exception $e) {

            return response()->json([
                'status' => 'error',
            ]);
        }
    }
}
