@extends('frontend.layout')
@section('title', 'Havtech Events Hub')
@section('content')
  <section id="vue-app"> 
    <upcoming-event-slider :events="{{json_encode($latestUpcomingEvents)}}" :user="{{json_encode($user)}}"></upcoming-event-slider>
  <div class="eventshub-app-section">
    <div class="home-app">
      <div class="home-app-image"></div>
    </div>
    <div class="home-app-content">
      <div class="home-app-heading-container">
          <h3 class="heading-3">Havtech Events Hub App</h3>
          <a href="/download-mobile-app" class="more-info-container w-inline-block">
              <div class="more">Learn About Our App</div>
              <img src="/eventshub/images/Arrow.png" alt="">
          </a>
      </div>
      <p class="paragraph">
        <!--Continuing your education is a big part of being a professional in the HVAC industry.  HAVTECH recognizes the need for professionals like you to stay informed about the latest products and industry trends.!-->
        </p>
      <div class="button-row">
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
  <event-calendar :eventsddata="{{json_encode($events)}}"></event-calendar>
  </section>
  <script src="{{ asset('js/app.js') }}" defer></script>
@stop
