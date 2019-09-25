<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Bios</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">

    <!-- Styles -->
    <style>
        html, body {
            background-color: #fff;
            color: #636b6f;
            font-family: 'Nunito', sans-serif;
            font-weight: 200;
            height: 100vh;
            margin: 0;
        }

        .full-height {
            height: 100vh;
        }

        .flex-center {
            align-items: center;
            display: flex;
            justify-content: center;
        }

        .position-ref {
            position: relative;
        }

        .top-right {
            position: absolute;
            right: 10px;
            top: 18px;
        }

        .content {
            text-align: center;
        }

        .title {
            font-size: 84px;
        }

        .links > a {
            color: #636b6f;
            padding: 0 25px;
            font-size: 13px;
            font-weight: 600;
            letter-spacing: .1rem;
            text-decoration: none;
            text-transform: uppercase;
        }

        .m-b-md {
            margin-bottom: 30px;
        }
    </style>
</head>
<body>
<div class="flex-center position-ref full-height">
    @if (Route::has('login'))
        <div class="top-right links">
            @auth
                <a href="{{ routeTenant('home') }}">Home</a>
            @else
                <a href="{{ routeTenant('login') }}">Login</a>

                @if (Route::has('register'))
                    <a href="{{ routeTenant('register') }}">Register</a>
                @endif
            @endauth
        </div>
    @endif

    <div class="content">
        <div class="title m-b-md">
            Bios
        </div>
        <div class="form-group">
            <div class="form-group">
                <label for="bios_code">Insert the code: </label>
                <input class="form-control" type="text" id="bios_code" name="bios_code">
            </div>
            <button type="button" id="btn_bios_code">Transcode</button>
            <div class="form-group">
                <span>Transcode Code: <span id="label_bios_code"></span></span>
            </div>
        </div>

    </div>
</div>
<script type="text/javascript" src="{{ asset('plugin/jquery/js/jquery.min.js') }}"></script>
<script src="{{ asset('js/bios_calc/scripts.js') }}"></script>
</body>
</html>
