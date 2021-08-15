@extends('tictactoe.layouts.auth')

@section('content')

    <div class="container">
        <div class="row">
            <div class="col-md-6 offset-md-3">
                <div class="login-form bg-light mt-4 p-4">
                    <form action="{{ route('auth.login') }}" method="post" class="row g-3">
                        @csrf
                        @if($errors->any())
                            @foreach($errors->all() as $error)
                                <div class="alert alert-danger" role="alert">
                                    {{ $error }}
                                </div>
                            @endforeach
                        @endif
                        <h4>Login page</h4>
                        <div class="col-12">
                            <label>Nickname</label>
                            <input
                                type="text"
                                name="name"
                                class="form-control"
                                placeholder="Nickname"
                                value="{{ old('name') }}"
                            >
                        </div>
                        <div class="col-12">
                            <label>Password</label>
                            <input type="password" name="password" class="form-control" placeholder="Password">
                        </div>
                        <div class="col-12">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="rememberMe" name="remember_me">
                                <label class="form-check-label" for="rememberMe">Remember me</label>
                            </div>
                        </div>
                        <div class="col-12 text-center">
                            <button
                                type="submit"
                                class="btn btn-dark"
                            >
                                Login
                            </button>
                        </div>
                    </form>
                    <hr class="mt-4">
                    <div class="col-12">
                        <p class="text-center mb-0">
                            Have not account yet?
                            <a href="{{ route('auth.register') }}">
                                Signup
                            </a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>


@endsection
