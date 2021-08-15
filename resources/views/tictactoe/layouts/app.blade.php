<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Hello, world!</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KyZXEAg3QhqLMpG8r+8fhAXLRk2vvoC2f3B09zVXn8CA5QIVfZOJ3BCsw2P0p/We" crossorigin="anonymous">
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous"/>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>

<div id="app">
    <div class="container mt-5">
        <div class="row">
            @if (session('status'))
                <div class="col-md-12">
                    <div class="alert alert-info" role="alert">
                        {{ session('status') }}
                    </div>
                </div>
            @endif
            @if(request()->is('/'))
                <div class="col-md-4">
                    <rooms></rooms>
                    <div class="mt-3 text-center">
                        <form action="{{ route('auth.logout') }}" method="POST">
                            @csrf
                            <button class="btn btn-danger" onclick="return confirm('r u sure?')">
                                Logout - {{ auth()->user()->name }}
                            </button>
                        </form>
                    </div>
                </div>
            @endif
            <div class="@if (request()->is('/')) col-md-8 @else col-md-12 d-flex justify-content-center @endif">
                @yield('content')
            </div>
        </div>
    </div>
</div>

@yield('data')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-U1DAWAznBHeqEIlVSCgzq+c9gqGAJn5c/t99JyeKa9xxaYpSvHU5awsuZVVFIhvj" crossorigin="anonymous"></script>
<script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
