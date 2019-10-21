<head>
    <meta charset="utf-8">
    <meta name="description" content="Demo partner app Mollie">
    <meta name="author" content="Mollie B.V.">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'AMAZING PLATFORM') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Styles -->
    <link rel="preload" as="font" href="https://fonts.googleapis.com/css?family=Roboto:400,900" crossorigin>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>