@extends('frontend.layout')

@section('title', 'Havtech Events Hub - Event')

@section('header_image')
  <div class="page-section events">
@endsection
@section('header_title', 'Events')

@section('content')
    <div class="events-nav-container">
        <div class="event-nav-row">
        <div class="sticky-nav-container">
            <div class="events-filter-nav">
            @foreach($tabs as $tab)
            @if($tab!="photos" && $tab!="attendee-list")
                <a href="/events/{{$event_id}}#{{ucfirst($tab)}}" class="nav-event w-inline-block">
                     <div>@if($tab=="travel-host") TRAVEL HOST & INFORMATION @else {{$tab}} @endif</div>
                </a>
            @endif
            @endforeach
            </div>
            <!--<div><a href="/events/registration/{{$event_id}}" class="base-button orange-button event-button w-button" data-ix="button-hover">+ Register</a></div>!-->
        </div>
        </div>
    </div>
  
    <div id="Contact-Information" class="havtech-blue-section payment-information" data-ix="fade-in">
        <div class="triangle-deco"><img src="/eventshub/images/Triangle.svg" alt="" class="orange-triangle"></div>
        <div class="main-container" data-ix="fade-in">
            @if($amount > 0)
                <h2 class="heading-2 center">Confirmed</h2>
            @else
                <h2 class="heading-2 center">Submitted</h2>
            @endif
            <p class="paragraph centered blue">You will receive a confirmation email shortly.</p>
            <div class="button-row center">
                <!--<a href="#" class="base-button orange-button first w-button" data-ix="button-hover">Download PDF</a>!-->
                <a href="/account/edit" class="base-button white-button event-button last w-button" data-ix="button-hover">View Account</a>
            </div>
            <div class="button-row center">
                <a href="https://play.google.com/store/apps/details?id=com.mojo.havtechevents&hl=en_US" class="android-button w-inline-block" data-ix="button-hover" target="_blank">
                  <img src="/eventshub/images/Android-Icon.svg" height="25" alt="" class="android-icon">
                  <div class="text-block">Download For Android</div>
                </a>
                <a href="https://apps.apple.com/us/app/havtech-events/id1484532326?ls=1" class="iphone-button w-inline-block" data-ix="button-hover" target="_blank">
                    <img src="/eventshub/images/Apple-Icon.svg" height="25" alt="" class="apple-icon">
                    <div class="text-block">Download For Iphone</div>
                </a>
              </div>
        </div>
    </div>
@stop

@section('js')
<script src="{{ asset('/js/frontend/events.js') }}"></script>
@stop