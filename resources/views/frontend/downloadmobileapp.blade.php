@extends('frontend.layout')

@section('title', 'Havtech Events Hub - Download App')
@section('header_image')
  <div class="page-section download-app">
@endsection
@section('header_title', 'Havtech Events Hub App')

@section('content')
  <div class="content-section center app-features">
    <h1 class="heading-2 centered">Events Hub App Features</h1><img src="/eventshub/images/Triangle.svg" alt="" class="orange-triangle center">
    <div class="app-screen-container">
      <div class="callout-left-container">
        <div class="callout-1">
          <div class="callout-dot before"></div>
          <p class="callout-text right-align">Receive up-to-date event notifications</p>
          <div class="callout-line"></div>
          <div class="callout-dot"></div>
        </div>
      </div>
      <div class="app-screen"></div>
      <div class="callout-right-container">
        <div class="callout-2">
          <div class="callout-dot"></div>
          <div class="callout-line"></div>
          <div class="callout-dot before"></div>
          <p class="callout-text left">View all of Havtech's upcoming events and Learning Institute trainings</p>
        </div>
        <div class="callout-2">
          <div class="callout-dot"></div>
          <div class="callout-line"></div>
          <div class="callout-dot before"></div>
          <p class="callout-text left"> Register for an event, access event information such as schedules, accommodations, dining, and maps, then personalize your itinerary.</p>
        </div>
      </div>
    </div>
  </div>
  <div class="eventshub-app-section"></div>
  <div class="app-download-section" data-ix="fade-in">
    <div class="triangle-deco app-triangle"><img src="/eventshub/images/Triangle.svg" alt="" class="orange-triangle"></div>
    <div class="main-container flex" data-ix="fade-in">
      <div class="home-app">
        <div class="home-app-image"></div>
      </div>
      <div class="home-app-content top-margin">
        <h3 class="heading-3">Get the Havtech Events Hub App now!</h3>
        <p class="paragraph">Havtech recognizes the need for professionals like you to stay informed about the latest products and industry trends. Havtech Events Hub is a tool we created to make it easier for you to attend continuing education courses, learn about new products from manufacturers, and receive invites to special events exclusive to our customers.</p>
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
  </div>
  <div class="havtech-blue-section" data-ix="fade-in">
    <div class="triangle-deco"><img src="/eventshub/images/Triangle.svg" alt="" class="orange-triangle"></div>
    <div class="main-container">
      <div class="heading-row border-bottom">
        <h2 class="heading-3 white">OTHER FEATURES</h2>
      </div>
      <ul class="app-features-list">
        <li class="first-list-item">
          <p class="paragraph white">View upcoming events from Havtech and our manufacturing partners.</p>
        </li>
        <li>
          <p class="paragraph white">Receive direct on screen notifications for event changes and other helpful reminders from Havtech.</p>
        </li>
        <li>
          <p class="paragraph white">Register for our free events straight from your phone.</p>
        </li>
        <li>
          <p class="paragraph white">Select and customize your schedule for upcoming educational events.</p>
        </li>
        <li>
          <p class="white">View details about the venue such as accommodations, dinning, and even transportation for the event.</p>
        </li>
        <li>
          <p class="paragraph white">Upload photos of the event in real-time and share your “Havtech Moments” with the other event guests!</p>
        </li>
        <li>
          <p class="paragraph white">Get quick access to the event host if you ever need help!</p>
        </li>
        <!--<li>
          <p class="paragraph white">Real-time synchronization with our HAVTECH Events website!</p>
        </li>!-->
      </ul>
    </div>
  </div>
@stop
