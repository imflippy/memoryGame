<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Laravel</title>
        <meta name="csrf-token" content="{{ csrf_token() }}">


        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">

        <link href="{{ asset('css/app.css') }}" />
        <!-- Styles -->
    </head>
    <body>
        <div id="app">

        </div>
        <script src="{{ asset('js/app.js') }}"></script>
        <script src="https://js.pusher.com/7.0/pusher.min.js"></script>
        <!-- <script>
            Pusher.logToConsole = true;

            var pusher = new Pusher('fbaa4e2714086ca20608', {
            cluster: 'eu'
            });

            var channel = pusher.subscribe('my-channel');
            channel.bind('my-event', function(data) { 
            messages.push(JSON.stringify(data));
            });
        </script> -->
    </body>
</html>
