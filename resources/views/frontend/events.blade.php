@extends('frontend.layout')

@section('title', 'Havtech Events Hub - Events')
@section('header_image')
  <div class="page-section events">
@endsection
@section('header_title', 'Events')

@section('content')
@csrf
  <div class="events-nav-container">
    <div class="event-nav-row">
      <div class="sticky-nav-container start-nav">
       <!-- @if (Auth::check())
        <a onclick="filterEventstype(99)"  class="e-nav my-events-button w-inline-block" data-ix="button-hover"><img src="/eventshub/images/havtech_swoosh_small.svg" alt="" class="swoosh-icon"><div>My Events</div></a>
        @endif
        !-->
        <div class="events-filter-nav">
          @if (Auth::check())
            <a onclick="filterEventstype(99)"  id="event99" class="e-nav nav-filter-special-events w-inline-block">
              <img src="/eventshub/images/havtech_swoosh_small.svg" alt="" class="swoosh-icon"><div  style="display:inline-block">My Events</div>
            </a>
          @endif
         
          <!-- @foreach($data['eventtypes'] as $et)
            <a onclick="filterEventstype({{$et->id}})" id="event{{$et->id}}" class="e-nav nav-filter-special-events w-inline-block">
              <div>{{$et->description}}</div>
            </a>
          @endforeach!-->
          <a onclick="filterEventstype(2)" id="event2" class="e-nav v nav-filter-special-events w-inline-block">
            <div>Special</div>
          </a>
          <a onclick="filterEventstype(1)" id="event1" class="e-nav v nav-filter-special-events w-inline-block">
            <div>General</div>
          </a>
          <a onclick="filterEventstype(3)" id="event3" class="e-nav v nav-filter-special-events w-inline-block">
            <div>Learning Institute</div>
          </a>
          <a onclick="filterEventstype(0)" id="event0" class="e-nav nav-filter-all-events active w-inline-block">
            <div>All</div>
          </a>
         
        <div class="events-select-nav">
          <!--
          <div data-delay="0" class="events-select-menu w-dropdown">
            <div class="date-dropdown w-dropdown-toggle">
              <div class="w-icon-dropdown-toggle"></div>
              <div id="date_filter_val">Select Date</div>
            </div>
            <nav class="dropdown-list w-dropdown-list" id="datedrop">
              <a onclick="filterEventsdate(null)" class="dropdown-select w-dropdown-link">Select Date</a>
              @foreach ($data['date_filter'] as $date_filter)
                <a onclick="filterEventsdate('{{$date_filter}}')" class="dropdown-select w-dropdown-link">{{$date_filter}}</a>
              @endforeach
            </nav>
          </div>
          <div data-delay="0" class="events-select-menu w-dropdown">
            <div class="date-dropdown w-dropdown-toggle">
              <div class="w-icon-dropdown-toggle"></div>
              <div id="location_filter_val">Select Location</div>
            </div>
            <nav class="dropdown-list w-dropdown-list">
                <a onclick="filterEventslocation(null)"  class="dropdown-select w-dropdown-link">Select Location</a>
              @foreach ($data['location_filter'] as $location)
                <a onclick="filterEventslocation('{{$location}}')"  class="dropdown-select w-dropdown-link">{{$location}}</a>
              @endforeach
            </nav>
          </div>
          !-->

        </div>
      </div>
    </div>
  </div>
  <div class="content-section" id="events-container">
    @foreach($data['events'] as $e)
      <div id="{{str_replace(" ","-",substr($e->event_type, 0, -1))}}" class="event-row" data-ix="fade-in">
        <div class="image-container event" @if($e->image) style="background-image: url({{asset($e->image)}})"@endif >
          <a href="/events/{{$e->id}}" class="events-image-link w-inline-block">
            <div class="@if($e->event_type=="Special Events") orange-overlay @elseif($e->event_type=="Learning Institute") gray-overlay @else blue-overlay @endif">
              <div class="event-label">
                @if($e->event_type=="Special Events")<img src="/eventshub/images/Star.svg" alt="Special Event" class="special-star">@endif
                <div class="event-category events">{{$e->event_type}}</div>
              </div>
            </div>
          </a>
        </div>
        <div class="event-content">
          <h3 class="heading-3 event-title">{{$e->event_name}}</h3>
          <p class="event-date">
            @if($e->start_date==$e->end_date)
              {{date('D, M d, Y', strtotime($e->end_date))}}
            @else
                @if(date("Y-m",strtotime($e->start_date)) == date("Y-m",strtotime($e->end_date)))
                    {{date('D, M d', strtotime($e->start_date)) . ' - ' . date('d, Y', strtotime($e->end_date))}}
                @else
                    {{date('D, M d', strtotime($e->start_date)) . ' - ' . date('M d, Y', strtotime($e->end_date))}}
                @endif
            @endif
          </p>
          <p class="event-subtitle">
            <!--@if($e->addressfull){{$e->addressfull}}@endif!-->
          </p>
          <p class="paragraph"><!--{!!$e->event_description!!}!--></p>
          <a href="/events/{{$e->id}}" class="base-button orange-button w-button" data-ix="button-hover">More Info</a></div>
      </div>  
    @endforeach
  </div>
@stop
<input type="hidden" name="event_type" id="event_type">
<input type="hidden" name="event_date" id="event_date">
<input type="hidden" name="event_location_city" id="event_location_city">
<input type="hidden" name="event_location_state" id="event_location_state">
@section('css')
    <link rel="stylesheet" href="{{ asset('/css/events-frontend.css') }}">
@stop

@section('js')
<script src="{{ asset('/js/frontend/events.js') }}"></script>
@stop