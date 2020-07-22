@extends('frontend.layout')

@section('title', 'Havtech Events Hub - Contact')
@section('header_image')
  <div class="page-section contact">
@endsection
@section('header_title', 'Contact')

@section('content')
    @if(session('status') == 'saved')
    <div class="content-section margin-bottom">
        <h1 class="heading-2 centered">Thank you for contacting us.</h1>
        <p class="paragraph centered">We will be in touch shortly.</p>
    </div>
    @else
        <div class="content-section intro-block">
            <h1 class="heading-2 centered">Questions or comments? Contact us below.</h1>
            <p class="paragraph centered">Please be sure to complete all fields below and a member of our team will get back to you within one (1) business day.</p>
        </div>
        <div class="main-container form-container">
            <div class="triangle-deco">
                <img src="/eventshub/images/Triangle.svg" alt="" class="orange-triangle">
            </div>
            <div class="form-section">
                <div class="form-block w-form">
                    <form id="email-form" name="email-form" data-name="Email Form" method="POST" action="{{route('contact_send')}}">
                        {{ csrf_field() }}
                        
                        @if ($errors->any())
                        <div class="form-row">
                            <div class="alert alert-danger" style="color:#ff0000c4">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                        @endif
                        <div class="form-row">
                            <div class="field-container half">
                                <label for="First-Name" class="field-label">First Name</label>
                                <input type="text" class="field w-input" maxlength="256" name="first_name" data-name="First Name" id="first_name" required="" @if(isset($user->first_name)) value="{{$user->first_name}}" @endif>
                            </div>
                            <div class="field-container half">
                                <label for="Last-Name" class="field-label">Last Name</label>
                                <input type="text" class="field w-input" maxlength="256" name="last_name" data-name="Last Name" id="last_name" required="" @if(isset($user->last_name)) value="{{$user->last_name}}" @endif>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="field-container half">
                                <label for="Company" class="field-label">Company</label>
                                <input type="text" class="field w-input" maxlength="256" name="company" data-name="Company" id="company" required=""  @if(isset($user->company)) value="{{$user->company}}" @endif>
                            </div>
                            <div class="field-container half">
                                <label for="Title" class="field-label">Title</label>
                                <input type="text" class="field w-input" maxlength="256" name="title" data-name="title" id="title" required=""  @if(isset($user->company)) value="{{$user->company}}" @endif>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="field-container half">
                                <label for="Email" class="field-label">Email Address</label>
                                <input type="email" class="field w-input" maxlength="256" name="email" data-name="email" id="Email" required="" @if(isset($user->email)) value="{{$user->email}}" @endif>
                            </div>
                            <div class="field-container half">
                                <label for="Phone-Number" class="field-label">Phone Number</label>
                                <input type="tel" class="field w-input" maxlength="256" name="phone" data-name="Phone Number" id="phone" required="" @if(isset($user->phone)) value="{{$user->phone}}" @endif>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="field-container ful-width">
                                <label for="Message" class="field-label">Message</label>
                                <textarea data-name="Message" maxlength="5000" id="message" name="message" required="" class="textarea w-input"></textarea>
                            </div>
                        </div>
                        <div class="button-row">
                            <input type="submit" value="Submit" data-wait="Please wait..." class="submit-button w-button" data-ix="button-hover">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
@stop
