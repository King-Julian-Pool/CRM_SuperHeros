<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>CRM Super-Heros</title>
</head>

<body>
    <nav>
        <a href="{{ route('/') }}">Accueil</a>
        <a href="{{ route('incidentTypes.index') }}">Types d'incidents</a>
    </nav>

    @yield('content')
</body>

</html>
