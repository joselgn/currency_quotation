<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Cotações de Moedas</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@200;600&display=swap" rel="stylesheet">

    <!-- Styles / CSS -->
    <link href="{{ asset('assets/style/layout.css') }}" rel="stylesheet">

    <!-- Scripts JS -->
    <script src="https://www.gstatic.com/charts/loader.js"></script>
    <script
            src="https://code.jquery.com/jquery-3.5.1.min.js"
            integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0="
            crossorigin="anonymous"></script>
    <script src="{{ asset('assets/js/coinsInit.js') }}"></script>

    <script type="text/javascript">
        google.charts.load('current', {'packages':['corechart']});
    </script>
</head>
<body>
    <header>
        <h3 class="title">
            Cotação de Moedas
        </h3>
    </header>

    <div class="messages">
        <div id="alert_msg" class="alert"></div>
    </div>

    <input type="hidden" id="token" value="{{ csrf_token() }}" />

   <div class="container">
       @yield('content')
   </div>

    @yield('scripts-bottom')
</body>
</html>