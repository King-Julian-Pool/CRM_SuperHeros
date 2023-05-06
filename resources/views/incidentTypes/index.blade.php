@extends('app')
@section('content')
    @foreach ($incidentTypes as $incidentType)
        {{ $incidentType->libelle }} <br />
    @endforeach
@endsection
