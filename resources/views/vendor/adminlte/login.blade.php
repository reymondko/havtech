<!-- @extends('adminlte::master') -->
@extends('frontend.layout')
@section('adminlte_css')
    <link rel="stylesheet" href="{{ asset('vendor/adminlte/vendor/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    @yield('css')
@stop

@section('title', 'Havtech Events Hub - Account Login')
@section('header_image')
  <div class="page-section page-account">
@endsection
@section('header_title', 'Account Login')

@section('content')
<div class="main-container">
    <div class="triangle-deco"><img src="eventshub/images/Triangle.svg" alt="" class="orange-triangle"></div>
    <div class="form-section">
        <div class="form-block single-column w-form">
            <div class="form-text-block">
                <h1 class="heading-2 centered white">Login to your account</h1>
                <p class="paragraph centered white">To access your account, login below:</p>
            </div>
            <form action="{{ url(config('adminlte.login_url', 'login')) }}" method="post">
                {{ csrf_field() }}
                <div class="field-container has-feedback {{ $errors->has('email') ? 'has-error' : '' }}">
                        <label for="Email" class="field-label">Email</label>
                        <input type="email" name="email" class="field w-input" value="{{ old('email') }}"
                            placeholder="{{ trans('adminlte::adminlte.email') }}">
                        @if ($errors->has('email'))
                            <span class="help-block">
                                <strong>{{ $errors->first('email') }}</strong>
                            </span>
                        @endif
                </div>
                <div class="field-container">
                    <label for="Password" class="field-label">Password</label>
                    <input type="password" name="password" class="field w-input" value="{{ old('password') }}"
                            placeholder="{{ trans('adminlte::adminlte.password') }}">
                        @if ($errors->has('email'))
                            <span class="help-block">
                                <strong>{{ $errors->first('password') }}</strong>
                            </span>
                        @endif
                </div>
                <div class="button-row">
                    <input type="submit" value="Login" data-wait="Please wait..." class="submit-button w-button" data-ix="button-hover">
                </div>
            </form>
            <a href="{{ url(config('adminlte.password_reset_url', 'password/reset')) }}" class="body-link w-inline-block">
                <p class="paragraph white">Trouble Signing In?</p>
            </a>
        </div>
    </div>
</div>

    <!-- <div class="login-box">
        <div class="login-logo">
            <a href="{{ url(config('adminlte.dashboard_url', 'home')) }}"><img class="logo" src="{{asset('eventshub/images/Havtech_logo_final.svg')}}" /></a></a>
        </div>
        <div class="login-box-body">
            <p class="login-box-msg">{{ trans('adminlte::adminlte.login_message') }}</p>
            <form action="{{ url(config('adminlte.login_url', 'login')) }}" method="post">
                {{ csrf_field() }}
                <div class="form-group has-feedback {{ $errors->has('email') ? 'has-error' : '' }}">
                    <input type="email" name="email" class="form-control" value="{{ old('email') }}"
                           placeholder="{{ trans('adminlte::adminlte.email') }}">
                    <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                    @if ($errors->has('email'))
                        <span class="help-block">
                            <strong>{{ $errors->first('email') }}</strong>
                        </span>
                    @endif
                </div>
                <div class="form-group has-feedback {{ $errors->has('password') ? 'has-error' : '' }}">
                    <input type="password" name="password" class="form-control"
                           placeholder="{{ trans('adminlte::adminlte.password') }}">
                    <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                    @if ($errors->has('password'))
                        <span class="help-block">
                            <strong>{{ $errors->first('password') }}</strong>
                        </span>
                    @endif
                </div>
                <div class="row">
                    <div class="col-xs-8">
                        <div class="icheck-primary">
                            <input type="checkbox" name="remember" id="remember">
                            <label for="remember">{{ trans('adminlte::adminlte.remember_me') }}</label>
                        </div>
                    </div>
                    <div class="col-xs-4">
                        <button type="submit" class="btn btn-primary btn-block btn-flat">
                            {{ trans('adminlte::adminlte.sign_in') }}
                        </button>
                    </div>
                </div>
            </form>
            <br>
            <p>
                <a href="{{ url(config('adminlte.password_reset_url', 'password/reset')) }}" class="text-center">
                    {{ trans('adminlte::adminlte.i_forgot_my_password') }}
                </a>
            </p>
            @if (config('adminlte.register_url', 'register'))
                <p>
                    <a href="{{ url(config('adminlte.register_url', 'register')) }}" class="text-center">
                        {{ trans('adminlte::adminlte.register_a_new_membership') }}
                    </a>
                </p>
            @endif
        </div> -->
@stop
@section('adminlte_js')
    @yield('js')
@stop
