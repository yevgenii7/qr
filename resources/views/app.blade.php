<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{$title??env('APP_NAME')}}</title>
    <link rel="stylesheet" href="{{asset('/css/app.css')}}">
    <link rel="stylesheet" href="{{asset('/css/project.css')}}">
    <script src="https://use.fontawesome.com/ad2a8ea6e6.js"></script>
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col-md-12 col-sm-12">
                @yield('content')
            </div>
        </div>
    </div>
@yield('scripts')
</body>
</html>
