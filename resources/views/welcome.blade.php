<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>AdminLTE 3</title>

    <link href="/css/app.css" rel="stylesheet">
</head>
<body class="hold-transition sidebar-mini">
    <div class="wrapper" id="app">
        <router-view></router-view>
    </div>


        @auth
            <script>
                window.user = @json(auth()->user())
            </script>
        @endauth
        <script src="/js/app.js"></script>
</body>
</html>
