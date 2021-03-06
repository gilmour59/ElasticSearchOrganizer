<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Organizer') }}</title>

        <!-- Scripts -->
        <script src="{{ asset('js/app.js') }}"></script>

        <!-- Fonts -->
        <link rel="dns-prefetch" href="https://fonts.gstatic.com">
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,300,600" rel="stylesheet" type="text/css">
        <link href="https://fonts.googleapis.com/css?family=Raleway:300,400" rel="stylesheet" type="text/css">

        <!-- Styles -->
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">
        @role('Super Admin')
        <link href="{{ asset('css/sidebar.css') }}" rel="stylesheet">
        @endrole
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">

        @yield('css')
        <style>
            body {
                font-family: 'Nunito', sans-serif !important;
                font-weight: 300 !important;
                background-color: #d8d8d8;
            }
        </style>
    </head>
    <body>
        @role('Super Admin')
        <div class="wrapper">
            <!-- Sidebar  -->
            <nav id="sidebar" class="active">
                <div class="sidebar-header">
                    <button id="closeSideBar" type="button" class="float-right pb-0 pt-0 btn btn-sm btn-outline-light">
                        <span style="color:#fff;" aria-hidden="true">&times;</span>
                    </button>
                    <h4>Super Admin</h4>
                </div>

                <ul class="list-unstyled components">
                    <li class="active">
                        <a href="{{ route('index') }}">Organizer</a>
                    </li>
                    <li>
                        <a href="{{ route('divisions.index') }}">Divisions</a>
                    </li>
                    <li>
                        <a href="{{ route('users.index') }}">Users</a>
                    </li>
                    <li>
                        <a href="{{ route('roles.index') }}">Roles</a>
                    </li>
                </ul>
            </nav>
        @endrole
            <div id="app" @role('Super Admin') class="active" @endrole>
                <nav class="navbar navbar-expand-md navbar-light navbar-laravel">
                    <div class=" @role('Super Admin') container-fluid @else container @endrole">
                        @role('Super Admin')
                        <button type="button" id="sidebarCollapse" class="btn btn-light mr-3">
                            <i class="fas fa-bars"></i>
                        </button>
                        @endrole
                        <a class="navbar-brand" href="{{ url('/') }}">
                            {{ config('app.name', 'Organizer') }}
                        </a>
                        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                            <span class="navbar-toggler-icon"></span>
                        </button>

                        <div class="collapse navbar-collapse" id="navbarSupportedContent">
                            <!-- Left Side Of Navbar -->
                            <ul class="navbar-nav mr-auto">

                            </ul>

                            <!-- Right Side Of Navbar -->
                            <ul class="navbar-nav ml-auto">
                                <!-- Authentication Links -->
                                @guest
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                                    </li>
                                @else
                                    <li class="nav-item dropdown">
                                        <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                            {{ Auth::user()->name }} <span class="caret"></span>
                                        </a> 
                                        
                                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                            <a class="dropdown-item" href="{{ route('logout') }}"
                                            onclick="event.preventDefault();
                                                            document.getElementById('logout-form').submit();">
                                                {{ __('Logout') }}
                                            </a>

                                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                                @csrf
                                            </form>
                                        </div>
                                    </li>
                                @endguest
                            </ul>
                        </div>
                    </div>
                </nav>

                <main class="py-4">

                    @if (count($errors) > 0)
                        @foreach ($errors->all() as $error) <!--all() because the object has arrays as values-->
                            <div id="addErrorMsg" class="alert alert-danger">
                                {{$error}} <!-- Errors from validations (not sessions) -->
                            </div>
                        @endforeach
                        <script>
                            setTimeout(function() {
                                $("#addErrorMsg").fadeTo(200, 0).slideUp(200, function(){
                                    $(this).remove(); 
                                });
                            }, 2000);
                        </script>
                    @endif

                    @if (session('success'))
                        <div id='successMsg' class='alert alert-success'>
                            {{session('success')}}
                        </div>
                        <script>
                            setTimeout(function() {
                                $("#successMsg").fadeTo(200, 0).slideUp(200, function(){
                                    $(this).remove(); 
                                });
                            }, 2000);
                        </script>
                    @endif
            
                    @if (session('error'))
                        <div id='errorMsg' class='alert alert-danger'>
                            {{session('error')}}
                        </div>
                        <script>
                            setTimeout(function() {
                                $("#errorMsg").fadeTo(200, 0).slideUp(200, function(){
                                    $(this).remove(); 
                                });
                            }, 2000);
                        </script>
                    @endif
                    <div id="mainContainer" class="container p-0">
                        @yield('content')
                    </div>
                    @yield('js')
                    @role('Super Admin')
                    <script>
                        $(document).ready(function () {    
                            $('#sidebarCollapse').on('click', function () {
                                $('#sidebar, #app').toggleClass('active');
                                $('.collapse.in').toggleClass('in');
                            });
                            $('#closeSideBar').on('click', function () {
                                $('#sidebar, #app').addClass('active');
                                $('.collapse.in').addClass('in');
                            });
                        });
                    </script>
                    @endrole
                </main>
            </div>
        @role('Super Admin')
        </div>
        @endrole
    </body>
</html>
