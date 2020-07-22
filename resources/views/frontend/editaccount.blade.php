@extends('frontend.layout')

@section('title', 'Havtech Events Hub - Account Info')
@section('header_image')
  <div class="page-section page-account">
@endsection
@section('header_title', 'Account Info')

@section('content')
<div class="main-container form-container">
    <div class="triangle-deco">
        <img src="/eventshub/images/Triangle.svg" alt="" class="orange-triangle">
    </div>
    <div class="form-section">
        <div class="form-block w-form">
            <form id="email-form" name="email-form" data-name="Email Form" method="POST" action="{{route('save_edit_user_account')}}">
                {{ csrf_field() }}
                <div class="form-row">
                    <div class="field-container half">
                        <label for="First-Name" class="field-label">First Name</label>
                        <input type="text" name="first_name" class="field w-input"  placeholder="First Name" value="{{$user->first_name}}" required>
                    </div>
                    <div class="field-container half">
                        <label for="Last-Name" class="field-label">Last Name</label>
                        <input type="text" name="last_name" class="field w-input"  placeholder="Last Name" value="{{$user->last_name}}" required>
                    </div>
                </div>
                <div class="form-row">
                    <div class="field-container half">
                        <label for="Company" class="field-label">Company</label>
                        <input type="text" name="company" class="field w-input"  placeholder="Company" value="{{$user->company}}" required>
                    </div>
                    <div class="field-container half">
                        <label for="Title" class="field-label">Title</label>
                        <input type="text" name="title" class="field w-input" placeholder="Title" value="{{$user->title}}" required>
                    </div>
                </div>
                <div class="form-row">
                    <div class="field-container half">
                        <label for="Customer-Type" class="field-label">Customer Type</label>
                        <select id="customer_type" name="customer_type" data-name="Customer Type" required="" class="form-select w-select">
                            @foreach($customer_types as $customer_type)
                                <option value="{{$customer_type->id}}" @if($customer_type->id == $user->customer_type) selected @endif>{{$customer_type->type}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="field-container half">
                        <label for="Email" class="field-label">Email Address</label>
                        <input type="email" name="email" class="field w-input"  placeholder="email" value="{{$user->email}}" required>
                    </div>
                </div>
                <div class="form-row">
                    <div class="field-container half">
                        <label for="Phone-Number" class="field-label">Phone Number</label>
                        <input type="text" name="phone" class="field w-input" placeholder="Phone" value="{{$user->phone}}" required>
                    </div>
                    <div class="field-container half">
                        <label for="Password" class="field-label">Password</label>
                        <input type="password" class="field w-input" maxlength="256" name="password" data-name="password" id="password">
                    </div>
                </div>
                <div class="button-row">
                    <input type="submit" value="Save" data-wait="Please wait..." class="submit-button w-button" data-ix="button-hover">
                    
                    @if(session('status') == 'saved')
                        <div class="save-settings" id="saved">
                            <div class="save-check"></div>
                            <div class="save-message">Your settings have been saved.</div>
                        </div>
                    @endif
                </div>
            </form>
        </div>
    </div>
</div>
@stop
