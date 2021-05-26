<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    @include('tribal.layouts._styles')

</head>
<body>
<div id="app">

    <main class="py-4">
        @yield('content')
    </main>
</div>
@include('tribal.layouts._scripts')
@if(Session::has('message'))
    <script>
        $(document).ready(function () {
            notify("{!! Session::get('message')['msg']!!}", "{!! Session::get('message')['type']!!}");
        });
    </script>
@endif

</body>

</html>
