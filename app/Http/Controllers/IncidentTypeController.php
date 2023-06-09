<?php

namespace App\Http\Controllers;

use App\Models\IncidentType;
use Illuminate\Http\Request;

class IncidentTypeController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
    }

    public function index()
    {
        $incidents = IncidentType::all();

        return view('incidentTypes.index', [
            'incidentTypes' => $incidents,
        ]);
    }
}
