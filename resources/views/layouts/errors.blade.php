<!DOCTYPE html>
<html lang="en">

<head>
    <title>Horizom Framework</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1">
    <link href="https://fonts.googleapis.com/css2?family=Aclonica&family=Open+Sans+Condensed:wght@300&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">

    @yield('style')
</head>

<body>
    <div class="container">
        @yield('content')
    </div>

    @yield('script')
</body>

</html>