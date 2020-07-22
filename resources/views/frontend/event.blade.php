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
              <a href="#{{ucfirst($tab)}}" class="nav-event w-inline-block" >
                <div>@if($tab=="travel-host") TRAVEL HOST & INFORMATION @else {{$tab}} @endif</div>
              </a>
            @endif
          @endforeach
        </div>
        <div>
          @if($event->register_button)
            <a href="{{$event->register_button}}" download class="base-button orange-button event-button2 download w-button" data-ix="button-hover" target="_blank">+ Register</a>
          @else
            @if($event->is_registered==false)
              <a href="/events/registration/{{$event->id}}" class="base-button orange-button event-button w-button" data-ix="button-hover">+ Register</a>
            @endif
          @endif
        </div>
      </div>
    </div>
  </div>
  
  <div id="Overview" class="content-section">
    <div id="Special-Event" class="event-row no-border" data-ix="fade-in">
      <div class="image-container overview-image" @if($event->image) style="background-image: url({{asset($event->image)}})"@endif @if($event->event_type->description=="Special Events") orange-overlay @elseif($event->event_type->description=="Learning Institute") gray-overlay @else blue-overlay @endif >
          <div class="@if($event->event_type->description=="Special Events") orange-overlay @elseif($event->event_type->description=="Learning Institute") gray-overlay @else blue-overlay @endif">
            <div class="event-label">
              @if($event->event_type->description=="Special Events")<img src="/eventshub/images/Star.svg" alt="Special Event" class="special-star">@endif
              <div class="event-category events">{{$event->event_type->description}}</div>
            </div>
          </div>
      </div>
      <div class="event-content overview-content">
        @if($event->event_type->id==2)
        <div class="event-label"><img src="/eventshub/images/Orange-Star.svg" alt="Special Event" class="special-star">
            <div class="event-category events orange">Special Event</div>
        </div>
        @endif
        <h2 class="heading-2">{{$event->event_name}}</h2>
        <p class="event-subtitle">
          @if($event->start_date==$event->end_date)
              {{date('D, M d, Y', strtotime($event->end_date))}}
            @else
            @if(date("Y-m",strtotime($event->start_date)) == date("Y-m",strtotime($event->end_date)))
                  {{date('D, M d', strtotime($event->start_date)) . ' - ' . date('d, Y', strtotime($event->end_date))}}
            @else
                  {{date('D, M d', strtotime($event->start_date)) . ' - ' . date('M d, Y', strtotime($event->end_date))}}
            @endif
          @endif
        </p>
        <!--<p class="event-subtitle">
          @if($event->schedules)
            @if($event->schedules[0]->location=="Specify Address")
              {{trim($event->schedules[0]->location_address)}}@if($event->schedules[0]->location_address), @endif
              @if($event->schedules[0]->cname){{$event->schedules[0]->cname}}, @endif
              @if($event->schedules[0]->city){{$event->schedules[0]->city}},@endif
              @if($event->schedules[0]->state){{ $event->schedules[0]->state}}@endif
              @if($event->schedules[0]->zip){{ $event->schedules[0]->zip}}@endif
              @else
                {{$event->schedules[0]->room_number}}
              @endif
          @endif
        </p>!-->
        <p class="paragraph">{!!$event->description!!}</p>
        <p class="paragraph">
        @if($event->event_host_title) <span style="color:black;text-transform: uppercase;font-weight:bold">{{$event->event_host_title}}</span> @if($event->event_host_description): {!!$event->event_host_description!!} @endif @endif </p>
        @if($event->event_host_email)
            <a href="mailto:{{$event->event_host_email}}" class="base-button blue-button first-button w-button" data-ix="button-hover"><i class="fa fa-envelope fa-fw"></i></i> Email</a>
        @endif <br>
        @if($event->overview_file)
          <a href="{{$event->overview_file}}" download class="base-button orange-button event-button download w-button" data-ix="button-hover">Download PDF</a>
        @endif
        
         @if($event->register_button)
          <a href="{{$event->register_button}}" download class="base-button orange-button event-button2 download w-button" data-ix="button-hover" target="_blank">Register</a>
        @endif
        @if($event->directions_url)
         <a href="{{$event->directions_url}}" download class="base-button orange-button event-button2 download w-button" data-ix="button-hover">Directions</a>
       @endif
       @if($event->website_url)
        <a href="{{$event->website_url}}" download class="base-button orange-button event-button2 download w-button" data-ix="button-hover">Website</a>
      @endif
      </div>
    </div>
  </div>
  @if($event->grouped_schedules)
  <div id="Schedule" class="havtech-blue-section" data-ix="fade-in">
    <div class="triangle-deco"><img src="/eventshub/images/Triangle.svg" alt="" class="orange-triangle"></div>
    <div class="main-container" data-ix="fade-in">
      <div class="heading-row schedule">
        <h2 class="heading-2 schedule">Schedule</h2>
        <div>
          @if($event->schedules[0]->itinerary_file)
          <a href="{{$event->schedules[0]->itinerary_file}}" download class="base-button orange-button event-button download w-button" data-ix="button-hover">Download</a>
          @endif
        </div>
      </div>
      <div class="schedule-container">
        @foreach ($event->grouped_schedules as $date=>$schedules)
          <div class="event-container @if(($loop->iteration-1)%2 == 0)  @else even @endif" data-ix="fade-in">
            <p class="event-date schedule">{{date("D, M. j, Y",strtotime($date))}}</p>
            @foreach ($schedules as $schedule)
              <div class="event-row single-event">
                <div class="event-hours"> 
                  <p class="paragraph description">
                    {{date("g:i A",strtotime($schedule->start_date))}} - {{date("g:i A",strtotime($schedule->end_date))}}
                    <!--<br>
                    {{$schedule->city}}@if($schedule->city), @endif{{$schedule->state}}!-->
                  </p>
                </div>
                <div class="event-description">
                  <div class="event-title">{{$schedule->title}}</div>
                  {{$schedule->location_address}}@if($schedule->location_address), @endif
                                                @if($schedule->city){{ucfirst($schedule->city)}}, @endif{{ucfirst($schedule->state)}} {{$schedule->zip}}
                  <!--<p class="paragraph description">{!!$schedule->description!!}</p>!-->
                  <br><br>
                </div>
              </div> 
            @endforeach
          </div>
        @endforeach
      </div>
    </div>
  </div>
  @endif
  @if($event->accomodations_image && $event->accomodations_image!=0)
  <div id="Accomodations" class="content-section event-section" data-ix="fade-in">
    <h3 class="heading-3 event-section">Accomodations</h3>
    @foreach ($event->accomodations as $accomodations )
      <div id="Special-Event" class="event-row no-border top-align" data-ix="fade-in">
        @if($accomodations->image) <div class="image-container accomodations"  style="background-image: url({{asset($accomodations->image)}})"  ></div> @endif
          <div class="event-content">
            <div class="event-title">{{$accomodations->name}}</div>
            <p class="paragraph description">{!!$accomodations->description!!} </p>
            <div class="event-title">{{$accomodations->hotel}}</div>
            <p class="paragraph description">
              {{$accomodations->address1}} {{$accomodations->address2}} <br>
              {{$accomodations->city}}@if($accomodations->state), @endif{{$accomodations->state}}<br>
            <!-- {{$accomodations->zip}} {{$accomodations->country}}<br></p>!-->
            </p>
            @if($accomodations->phone)
            <a href="#" class="body-link event-link w-inline-block">
              <div>+{{$accomodations->phone}}</div>
            </a>
            @endif
            <div class="button-row event-buttons">
              @if($accomodations->directions_button)
              <a href="{{$accomodations->directions_button}}" target="_blank" class="base-button blue-button first-button w-button" data-ix="button-hover">Directions</a>
              @endif
              @if($accomodations->website_url)
              <a href="{{$accomodations->website_url}}" target="_blank" class="base-button orange-button w-button" data-ix="button-hover">Website</a>
              @endif
            </div>
          </div>
        </div>
      @endforeach
  </div>
  @endif
  
  @if($event->dining_image && $event->dining_image!=0)
  @if(count($event->dining) > 0 && $event->dining[0]->location)
  <div id="Dining" class="content-section event-section" data-ix="fade-in">
    <h3 class="heading-3 event-section">Dining</h3>
    @foreach ($event->dining as $dining )
      <div id="Special-Event" class="event-row no-border top-align" data-ix="fade-in">
        <div class="event-content full-width">
          <div class="event-title">{{$dining->location}}</div>
            <p class="paragraph description">{!!$dining->description!!} </p>
            <br>
            <p class="paragraph description">
              {{$dining->address1}} {{$dining->address2}} <br>
              {{$dining->city}}@if($dining->state), @endif{{$dining->state}}<br>
            <!-- {{$dining->zip}} {{$dining->country}}<br></p>!-->
            </p>
            @if($dining->phone)
            <a href="#" class="body-link event-link w-inline-block">
              <div>+{{$dining->phone}}</div>
            </a>
            @endif
            <div class="button-row event-buttons">
              @if($dining->directions_button)
              <a href="{{$dining->directions_button}}" target="_blank" class="base-button blue-button first-button w-button" data-ix="button-hover">Directions</a>
              @endif
              @if($dining->website_url)
              <a href="{{$dining->website_url}}" target="_blank" class="base-button orange-button w-button" data-ix="button-hover">Website</a>
              @endif
            </div>
        </div>
      </div>
    @endforeach
  </div>
  @endif
  @endif
  @if($event->transportation_image && $event->transportation_image!=0)
  <div id="Transportation" class="content-section event-section" data-ix="fade-in">
    <h3 class="heading-3 event-section">Transportation</h3>
    <div id="Special-Event" class="event-row no-border top-align" data-ix="fade-in">
      <div class="event-content full-width">
        <div class="event-title">{{$event->transportations->company_name}}</div>
          <p class="paragraph description">{!!$event->transportations->description!!} </p>
          <!--<div class="event-title">{{$event->transportations->company_name}}</div>!-->
          <br>
          <p class="paragraph description">
            {{$event->transportations->address1}} {{$event->transportations->address2}} <br>
            {{$event->transportations->city}}@if($event->transportations->state), @endif{{$event->transportations->state}}<br>
          <!-- {{$event->transportations->zip}} {{$event->transportations->country}}<br></p>!-->
          @if($event->transportations->phone)
          <a href="#" class="body-link event-link w-inline-block">
            <div>+{{$event->transportations->phone}}</div>
          </a>
          @endif
          <div class="button-row event-buttons">
            @if($event->transportations->directions_button)
            <a href="{{$event->transportations->directions_button}}" target="_blank" class="base-button blue-button first-button w-button" data-ix="button-hover">Directions</a>
            @endif
            @if($event->transportations->website_url)
            <a href="{{$event->transportations->website_url}}" target="_blank" class="base-button orange-button w-button" data-ix="button-hover">Website</a>
            @endif
          </div>
      </div>
    </div>
  </div>
  @endif
  @if($event->map && $event->map!=0)
  <div id="Map" class="content-section event-section" data-ix="fade-in">
    <h3 class="heading-3 event-section">Map</h3>
    @foreach ($event->maps as  $map)
      <div id="Special-Event" class="event-row no-border top-align" data-ix="fade-in">
        <div class="event-content full-width">
          <div class="event-map" @if($map->filename) style="background-image: url({{$map->filename}})"@endif>
            <img src="{{$map->filename}}" style="width:100%">
            <!--<div class="zoom-box">
              <a href="#" class="zoom-link w-inline-block">
                <div class="zoom">+</div>
              </a>
              <a href="#" class="zoom-link w-inline-block">
                <div class="zoom">-</div>
              </a>
            </div>!-->
          </div>
        </div>
      </div> 
      <script>
        $(document).ready(function(){
          $('.event-map img')
          .wrap('<span style="display:inline-block"></span>')
          .css('display', 'block')
          .parent()
          .zoom();
      });

      </script>     
    @endforeach
  </div>
  @endif
  @if($event->travelhost  && $event->travelhost!=0)
  <div id="Travel-host" class="content-section event-section" data-ix="fade-in">
    <h3 class="heading-3 event-section">Travel Host</h3>
    @foreach ($event->hosts as  $host)
      <div id="Special-Event" class="event-row no-border top-align" data-ix="fade-in">
        <div class="event-content full-width">
          <p class="event-subtitle">Travel Host: <b> {{$host->host_name}}</b></p>
          <p class="paragraph description"> {!!$host->description!!} </p>
          @if($host->email)
          <div class="button-row event-buttons">
            <a href="mailto:{{$host->email}}" target="_blank" class="base-button blue-button first-button w-button" data-ix="button-hover">Email</a>
          </div>
          @endif
        </div>
      </div>
    @endforeach
  </div>
  @endif
  @if($event->faqs && $event->travelhost!=0 && ($event->faqs[0]->faq_title!=""))
  <div class="content-section"  id="Travel-host" data-ix="fade-in">
    <h3 class="heading-3 event-section">Event Information</h3>
    <div id="Special-Event" class="event-row no-border top-align" data-ix="fade-in">
      <div class="event-content full-width">
        @foreach ($event->faqs as $faq)
          <div class="q-holder" data-ix="move-down">
            <a href="#" class="link-block-3 w-inline-block" data-ix="question-appear">
              <div class="faq-icon">+</div>
              <p class="question-title">{{$faq->faq_title}}</p>
            </a>
            <div class="answer">
              <p class="paragraph description">{!!$faq->faq_answer!!}</p>
            </div>
          </div>
        @endforeach
      </div>
    </div>
  </div>
  @endif
@stop

@section('css')
<style>
  /* these styles are for the demo, but are not required for the plugin */
  .zoom {
    display:inline-block;
    position: relative;
  }
  
  /* magnifying glass icon */
  .zoom:after {
    content:'';
    display:block; 
    width:33px; 
    height:33px; 
    position:absolute; 
    top:0;
    right:0;
    background:url({{asset("/eventshub/js/zoom-master/icon.png")}});
  }

  .zoom img {
    display: block;
  }

  .zoom img::selection { background-color: transparent; }

</style>
@endsection
@section('js')
<script src='//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js'></script>
<script src="/eventshub/js/zoom-master/jquery.zoom.js" type="text/javascript"></script>
<script src="/js/frontend/eventScroll.js" type="text/javascript"></script>
@endsection