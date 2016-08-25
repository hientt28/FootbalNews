@extends('layouts.app')

@section('content')

    <div class="login-form">
        <div class="card">
        </div>
        <div class="card">
            <h1 class="title">{{ trans('login.login') }}</h1>
            {{ Form::open(['url' => '/login', 'method' => 'POST', 'role' => 'form']) }}
            <div class="input-container">
                <input type="text" id="email" name="email" required="required">
                <label for="email"> {{ trans('login.email') }} </label>
                <div class="bar"></div>
            </div>
            <div class="input-container">
                <input type="password" id="password" name="password" required="required">
                <label for="password">{{ trans('login.password') }}</label>
                <div class="bar"></div>
            </div>
            <div class="checkbox rememberme">
                <input type="checkbox" name="remember"/> &nbsp; {{ trans('login.remember') }}
            </div>
            <div class="button-container">
                <button type="submit"><span>{{ trans('login.go') }}</span></button>
            </div>
            <div class="social-container">
                <a class="ui circular facebook icon button" href="login/facebook/redirect">
                    <i class="facebook icon"></i>
                </a>
                <a class="ui circular twitter icon button" href="login/twitter/redirect">
                    <i class="twitter icon"></i>
                </a>
                <a class="ui circular google plus icon button" href="login/google/redirect">
                    <i class="google plus icon"></i>
                </a>
            </div>
            <div class="footer"><a href="#">{{ trans('login.forgot_password') }}</a></div>
            {{ Form::close() }}
        </div>
        <div class="card alt">
            <div class="toggle"></div>
            <h1 class="title">{{ trans('login.register') }}
                <div class="close"></div>
            </h1>
            {{ Form::open(['url' => '/register', 'method' => 'POST', 'role' => 'form']) }}
                <div class="input-container">
                    <input type="text" name="name" id="name" required="required">
                    <label for="name">{{ trans('login.name') }}</label>
                    <div class="bar"></div>
                </div>
                <div class="input-container">
                    <input type="text" id="email" name="email" required="required">
                    <label for="email">{{ trans('login.email') }}</label>
                    <div class="bar"></div>
                </div>
                <div class="input-container">
                    <input type="password" id="password" name="password" required="required">
                    <label for="password">{{ trans('login.password') }}</label>
                    <div class="bar"></div>
                </div>
                <div class="input-container">
                    <input id="password-confirm" type="password" name="password_confirmation">
                    <label for="Repeat Password">{{ trans('login.repeat_password') }}</label>
                    <div class="bar"></div>
                </div>
                <div class="button-container">
                    <button type="submit"><span>{{ trans('login.next') }}</span></button>
                </div>
            {{ Form::close() }}
        </div>
    </div>
@endsection
