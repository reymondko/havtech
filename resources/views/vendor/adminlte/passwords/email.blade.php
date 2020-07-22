
<!-- @extends('adminlte::master') -->
@extends('frontend.layout')
@section('adminlte_css')
    <link rel="stylesheet" href="{{ asset('vendor/adminlte/vendor/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    @yield('css')
@stop
@section('title', 'Havtech Events Hub - Reset Password')
@section('header_image')
  <div class="page-section page-account">
@endsection
@section('header_title', 'Account Login')

@section('content')
    <div class="main-container">
        <div class="triangle-deco">
            <img src="/eventshub/images/Triangle.svg" alt="" class="orange-triangle">
        </div>
        <div class="form-section">
            <div class="form-block single-column w-form">
                <div class="form-text-block">
                    <h1 class="heading-2 centered white">Forgot Password?</h1>
                    <p class="paragraph centered white">Please enter the email address associated with this account below and check your email to receive your password.</p>
                </div>
                <div class="form-text-block">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif
                </div>
                <form id="email-form" name="email-form" data-name="Email Form" class="form" action="{{ url(config('adminlte.password_email_url', 'password/email')) }}" method="post">
                    {{ csrf_field() }}
                    <div class="field-container {{ $errors->has('email') ? 'has-error' : '' }}">
                        <label for="Email" class="field-label">Email</label>
                        <input type="email" name="email" class="field w-input" value="{{ isset($email) ? $email : old('email') }}"
                                placeholder="{{ trans('adminlte::adminlte.email') }}">
                        <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                        @if ($errors->has('email'))
                            <span class="help-block">
                                <strong>{{ $errors->first('email') }}</strong>
                            </span>
                        @endif
                    </div>
                    <div class="button-row">
                        <input type="submit" value="Submit" data-wait="Please wait..." class="submit-button w-button" data-ix="button-hover">
                    </div>
                </form>
                <div class="w-form-done">
                    <div>Thank you! Your submission has been received!</div>
                </div>
                <div class="w-form-fail">
                    <div>Oops! Something went wrong while submitting the form.</div>
                </div>
            </div>
        </div>
    </div>
<!-- <div class="login-box">
    <div class="login-logo">
        <a href="{{ url(config('adminlte.dashboard_url', 'home')) }}">{!! config('adminlte.logo', '<b>Admin</b>LTE') !!}</a>
    </div>
    <div class="login-box-body">
        <p class="login-box-msg">{{ trans('adminlte::adminlte.password_reset_message') }}</p>
        @if (session('status'))
            <div class="alert alert-success">
                {{ session('status') }}
            </div>
        @endif
        <form action="{{ url(config('adminlte.password_email_url', 'password/email')) }}" method="post">
            {{ csrf_field() }}

            <div class="form-group has-feedback {{ $errors->has('email') ? 'has-error' : '' }}">
                <input type="email" name="email" class="form-control" value="{{ isset($email) ? $email : old('email') }}"
                        placeholder="{{ trans('adminlte::adminlte.email') }}">
                <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                @if ($errors->has('email'))
                    <span class="help-block">
                        <strong>{{ $errors->first('email') }}</strong>
                    </span>
                @endif
            </div>
            <button type="submit" class="btn btn-primary btn-block btn-flat">
                {{ trans('adminlte::adminlte.send_password_reset_link') }}
            </button>
        </form>
    </div>
</div> -->
@stop
@section('adminlte_js')
    @yield('js')
@stop



