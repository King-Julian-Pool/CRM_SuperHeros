<?php

namespace App\Http\Controllers;

use App\Models\Incident;
use App\Models\IncidentType;
use Illuminate\Http\Request;
use Psy\Readline\Hoa\Console;
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
        // $incidents = Incident::all();

        // return view('incidents.index', [
        //     'incidents' => $incidents,
        // ]);
        return redirect(RouteServiceProvider::HOME);
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
        $incidentTypes = IncidentType::all();
        return view('incidents.create', [
            'incidentTypes' => $incidentTypes,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        dd($request->all());
        $validatedData = $request->validate([
            'commune_id' => 'required',
            'incident_type_id' => 'required',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        $incident = Incident::create($validatedData);

        return redirect(RouteServiceProvider::HOME);
    }
}
