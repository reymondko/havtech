@extends('frontend.layout')

@section('adminlte_css')
    @yield('css')
@stop

@section('title', 'Havtech Events Hub - Account Registration')
@section('header_image')
  <div class="page-section page-account">
@endsection
@section('header_title', 'Account Registration')

@section('content')
<div class="content-section intro-block">
    <h1 class="heading-2 centered">Request a Havtech Events Hub Account</h1>
    <p class="paragraph centered">Having an account has its benefits! By registering below, you can use our Havtech Events Hub app or website to register for upcoming events and trainings, receive real-time notifications for event changes or exclusive events, and view specific information including accommodations, dinning and even transportation. Request your account by filling out the form below.</p>  <p class="paragraph centered"><strong>Applications will be processed within one (1) to two (2) business days and you will be notified by email.</strong></p>
</div>

<div class="main-container form-container">
    <div class="triangle-deco"><img src="eventshub/images/Triangle.svg" alt="" class="orange-triangle"></div>
    <div class="form-section">
        <div class="form-block w-form">
            <form id="registration_account-form" name="registration_account-form" data-name="Register Account Form" action="{{ route('create_account') }}" method="post">
                {{ csrf_field() }}
                <div class="form-row">
                    <div class="field-container half">
                        <label for="First-Name" class="field-label">First Name</label>
                        <input type="text" class="field w-input" maxlength="256" name="first_name" data-name="First Name" id="first_name" required=""  value="{{ old('first_name') }}">
                        @if ($errors->has('first_name'))
                            <span class="help-block">
                                <strong class="w-form-fail" style="display: block;">{{ $errors->first('first_name') }}</strong>
                            </span>
                        @endif
                    </div>
                    <div class="field-container half">
                        <label for="Last-Name" class="field-label">Last Name</label>
                        <input type="text" class="field w-input" maxlength="256" name="last_name" data-name="Last Name" id="last_name" required="" value="{{ old('last_name') }}">
                        @if ($errors->has('last_name'))
                            <span class="help-block">
                                <strong class="w-form-fail" style="display: block;">{{ $errors->first('last_name') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>
                <div class="form-row">
                    <div class="field-container half">
                        <label for="Company" class="field-label">Company</label>
                        <input type="text" class="field w-input" maxlength="256" name="company" data-name="Company" id="company" required="" value="{{ old('company') }}">
                        @if ($errors->has('company'))
                            <span class="help-block">
                                <strong class="w-form-fail" style="display: block;">{{ $errors->first('company') }}</strong>
                            </span>
                        @endif
                    </div>
                    <div class="field-container half">
                        <label for="Title" class="field-label">Title</label>
                        <input type="text" class="field w-input" maxlength="256" name="title" data-name="Title" id="title" required=""  value="{{ old('title') }}">
                        @if ($errors->has('title'))
                            <span class="help-block">
                                <strong class="w-form-fail" style="display: block;">{{ $errors->first('title') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>
                <div class="form-row">
                    <!--<div class="field-container half">
                        <label for="Customer-Type" class="field-label">Customer Type</label>
                        <select id="Customer-Type" name="customer_type" data-name="customer_type" required="" class="form-select w-select">
                            <option value="">Select...</option>
                            @foreach(Session::get('customer_types') as $customer_type)
                                <option value="{{$customer_type->id}}">{{$customer_type->type}}</option>
                            @endforeach
                        </select>
                        @if ($errors->has('customer_type'))
                            <span class="help-block">
                                <strong class="w-form-fail" style="display: block;">{{ $errors->first('customer_type') }}</strong>
                            </span>
                        @endif
                    </div>!-->
                    <div class="field-container half">
                        <label for="Email" class="field-label">Email&nbsp;Address</label>
                        <input type="email" class="field w-input" maxlength="256" name="email" data-name="Email" id="email" required="" value="{{ old('email') }}">
                        @if ($errors->has('email'))
                            <span class="help-block">
                                <strong class="w-form-fail" style="display: block;">{{ $errors->first('email') }}</strong>
                            </span>
                        @endif
                    </div>
                    <div class="field-container half">
                        <label for="Phone-Number" class="field-label">Phone Number</label>
                        <input type="tel" class="field w-input" maxlength="256" name="phone" data-name="Phone Number" id="phone" required="" value="{{ old('phone') }}">
                        @if ($errors->has('phone'))
                            <span class="help-block">
                                <strong class="w-form-fail" style="display: block;">{{ $errors->first('phone') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>
                <!--<div class="form-row">
                    <div class="field-container half">
                        <label for="Password" class="field-label">Password</label>
                        <input type="password" class="field w-input" maxlength="256" name="password" data-name="Password" id="password" required="">
                        @if ($errors->has('password'))
                            <span class="help-block">
                                <strong class="w-form-fail" style="display: block;">{{ $errors->first('password') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>!-->
                <div class="button-row">
                    <input type="submit" value="Create Account" data-wait="Please wait..." class="submit-button w-button" data-ix="button-hover">
                </div>
            </form>
        </div>
    </div>
</div>
    <!-- <div class="register-box">
        <div class="register-logo">
            <a href="{{ url(config('adminlte.dashboard_url', 'dashboard')) }}">{!! config('adminlte.logo', '<b>Admin</b>LTE') !!}</a>
        </div>
        <div class="register-box-body">
            <p class="login-box-msg">{{ trans('adminlte::adminlte.register_message') }}</p>
            <form action="{{ url(config('adminlte.register_url', 'register')) }}" method="post">
                {{ csrf_field() }}
                <div class="form-group has-feedback {{ $errors->has('name') ? 'has-error' : '' }}">
                    <input type="text" name="first_name" class="form-control" value="{{ old('first_name') }}"
                           placeholder="First Name">
                    <span class="glyphicon glyphicon-user form-control-feedback"></span>
                    @if ($errors->has('first_name'))
                        <span class="help-block">
                            <strong class="w-form-fail" style="display: block;">{{ $errors->first('first_name') }}</strong>
                        </span>
                    @endif
                </div>
                <div class="form-group has-feedback {{ $errors->has('last_name') ? 'has-error' : '' }}">
                        <input type="text" name="last_name" class="form-control" value="{{ old('last_name') }}"
                               placeholder="Last Name">
                        <span class="glyphicon glyphicon-user form-control-feedback"></span>
                        @if ($errors->has('last_name'))
                            <span class="help-block">
                                <strong class="w-form-fail" style="display: block;">{{ $errors->first('last_name') }}</strong>
                            </span>
                        @endif
                    </div>
                <div class="form-group has-feedback {{ $errors->has('email') ? 'has-error' : '' }}">
                    <input type="email" name="email" class="form-control" value="{{ old('email') }}"
                           placeholder="{{ trans('adminlte::adminlte.email') }}">
                    <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                    @if ($errors->has('email'))
                        <span class="help-block">
                            <strong class="w-form-fail" style="display: block;">{{ $errors->first('email') }}</strong>
                        </span>
                    @endif
                </div>
                <div class="form-group has-feedback {{ $errors->has('password') ? 'has-error' : '' }}">
                    <input type="password" name="password" class="form-control"
                           placeholder="{{ trans('adminlte::adminlte.password') }}">
                    <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                    @if ($errors->has('password'))
                        <span class="help-block">
                            <strong class="w-form-fail" style="display: block;">{{ $errors->first('password') }}</strong>
                        </span>
                    @endif
                </div>
                <div class="form-group has-feedback {{ $errors->has('password_confirmation') ? 'has-error' : '' }}">
                    <input type="password" name="password_confirmation" class="form-control"
                           placeholder="{{ trans('adminlte::adminlte.retype_password') }}">
                    <span class="glyphicon glyphicon-log-in form-control-feedback"></span>
                    @if ($errors->has('password_confirmation'))
                        <span class="help-block">
                            <strong class="w-form-fail" style="display: block;">{{ $errors->first('password_confirmation') }}</strong>
                        </span>
                    @endif
                </div>
                <button type="submit" class="btn btn-primary btn-block btn-flat">
                    {{ trans('adminlte::adminlte.register') }}
                </button>
            </form>
            <br>
            <p>
                <a href="{{ url(config('adminlte.login_url', 'login')) }}" class="text-center">
                    {{ trans('adminlte::adminlte.i_already_have_a_membership') }}
                </a>
            </p>
        </div>
    </div> -->
@stop

@section('adminlte_js')
    @yield('js')
@stop
