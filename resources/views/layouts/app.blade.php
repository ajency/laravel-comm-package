<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-default navbar-static-top">
            <div class="container">
                <div class="navbar-header">

                    <!-- Collapsed Hamburger -->
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse">
                        <span class="sr-only">Toggle Navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>

                    <!-- Branding Image -->
                    <a class="navbar-brand" href="{{ url('/') }}">
                        {{ config('app.name', 'Laravel') }}
                    </a>
                </div>

                <div class="collapse navbar-collapse" id="app-navbar-collapse">
                    <!-- Left Side Of Navbar -->
                    <ul class="nav navbar-nav">
                        &nbsp;
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="nav navbar-nav navbar-right">
                        <!-- Authentication Links -->
                        @if (Auth::guest())
                            <li><a href="{{ route('login') }}">Login</a></li>
                            <li><a href="{{ route('register') }}">Register</a></li>
                        @else
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                    {{ Auth::user()->name }} <span class="caret"></span>
                                </a>

                                <ul class="dropdown-menu" role="menu">
                                    <li>
                                        <a href="{{ route('logout') }}"
                                            onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                            Logout
                                        </a>

                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                            {{ csrf_field() }}
                                        </form>
                                    </li>
                                </ul>
                            </li>
                        @endif
                    </ul>
                </div>
            </div>
        </nav>

        @yield('content')
    </div>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}"></script>



    @if(Auth::check())

        <script type="text/javascript">
            (function(p,u,s,h){
                p._pcq=p._pcq||[];
                p._pcq.push(['_currentTime',Date.now()]);
                s=u.createElement('script');
                s.type='text/javascript';
                s.async=true;
                s.src='https://cdn.pushcrew.com/js/854aefa3e69908e557df4e39f0228ef9.js';
                h=u.getElementsByTagName('script')[0];
                h.parentNode.insertBefore(s,h);
            })(window,document);


            window._pcq = window._pcq || [];
            _pcq.push(['subscriptionSuccessCallback',callbackFunctionOnSuccessfulSubscription]); //registers callback function to be called when user gets successfully subscribed

            function callbackFunctionOnSuccessfulSubscription(subscriberId, values) {
                console.log('User got successfully subscribed.');

                console.log(subscriberId); //will output the user's subscriberId

                console.log(values.status); // SUBSCRIBED or ALREADYSUBSCRIBED

                console.log(values.message) // 'User has subscribed to push notifications.' or 'User is already subscribed to push notifications.'

                console.log('Now you may run code which should be executed once user gets successfully subscribed.');

                if(values.status == 'SUBSCRIBED') {
                    var subscriber_id = subscriberId;
                    var provider = 'pushcrew';
                    $.ajax({
                        type: "POST",
                        url: 'http://127.0.0.1:8000' + '/subscription/web-push',
                        data: { subscriber_id: subscriber_id, provider: provider,  "_token": "{{ csrf_token() }}", },
                        success: function( msg ) {
                            $("#ajaxResponse").append("<div>"+msg+"</div>");
                        }
                    });
                }
            }

            window._pcq = window._pcq || [];
            _pcq.push(['subscriptionFailureCallback',callbackFunctionOnFailedSubscription]); //registers callback function to be called when user gets successfully subscribed

            function callbackFunctionOnFailedSubscription(values) {
                console.log('User could not get subscribed to push notifications');

                console.log(values.status); // BLOCKED , UNSUBSCRIBED or CANCELLED

                console.log(values.message) // 'User has blocked push notifications.', 'User has unsubscribed from push notifications', 'No change in subscription. Child window was closed.' or 'User has closed the notifications opt-in.'

                console.log('Now you may run code which should be executed once user subscription fails');
            }

            _pcq.push(['APIReady', callbackFunction]);

            function callbackFunction() {

                console.log(pushcrew.subscriberId); // will return something like this if current user is a subscriber '1c5d546172cfb4be65a8d51b047c4804'

                console.log(pushcrew.subscriberId); // will return false(boolean) if user is not subscribed to push notifications

                console.log(pushcrew.subscriberId); // will return -1(integer) if user has blocked push notifications from your website
                if(pushcrew.subscriberId) {

                    //TODO call API here
                }
            }

        </script>
    @endif


</body>
</html>
