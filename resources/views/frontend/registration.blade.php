@extends('frontend.layout')

@section('title', 'Havtech Events Hub - Event')
@section('header_image')
  <div class="page-section events">
@endsection

@section('header_title', 'Events')

@section('content')
    <!--
    <div class="events-nav-container">
        <div class="event-nav-row">
        <div class="sticky-nav-container">
            <div class="events-filter-nav">
            @foreach($tabs as $tab)
            @if($tab!="photos" && $tab!="attendee-list")
                <a href="/events/{{$event->id}}#{{ucfirst($tab)}}" class="nav-event w-inline-block">
                     <div>@if($tab=="travel-host") EVENT HOST & INFORMATION @else {{$tab}} @endif</div>
                </a>
            @endif
            @endforeach
            </div>
            <div><a href="/events/registration/{{$event->id}}" class="base-button orange-button event-button w-button" data-ix="button-hover">+ Register</a></div>
        </div>
        </div>
    </div>
    !-->
    <div id="Overview" class="content-section">
        <div class="registration-title">Registration</div>
        <!-- removed by client
             @if($event->googlemap)
                <div class="google-map w-embed w-iframe">
                    <iframe src="https://www.google.com/maps/embed/v1/place?key={{env('GOOGLE_MAP_API')}}&q={{$event->googlemap}}&zoom=18" width="100%" height="350" frameborder="0" style="border:0;" allowfullscreen=""></iframe>
                </div>
            @endif
            <div id="Special-Event" class="event-row no-border" data-ix="fade-in">
       
            <div class="event-content overview-content">
                @if($event->event_type->id==2)
                <div class="event-label"><img src="/eventshub/images/Orange-Star.svg" alt="Special Event" class="special-star">
                    <div class="event-category events orange">Special Event</div>
                </div>
                @endif
                <h2 class="heading-2">{{$event->event_name}}</h2>
                <p class="event-subtitle">{{date("D, M. j, Y",strtotime($event->start_date))}}</p>
                <p class="event-subtitle">
                    
                @if($event->schedules)
                        @if($event->schedules[0]->location=="Specify Address")
                            {{$event->schedules[0]->location_address}}
                            @if($event->schedules[0]->location_address),  @endif             
                            {{$event->schedules[0]->cname}}
                            @if($event->schedules[0]->cname),  @endif
                            {{$event->schedules[0]->city}} {{$event->schedules[0]->state}}  {{$event->schedules[0]->zip}} 
                        @else
                            {{$event->schedules[0]->room_number}}
                        @endif
                @endif
                </p>
                <p class="paragraph">{!!$event->description!!}</p>
                <p class="event-subtitle">@if($event->event_host_title || $event->hosts) Event Hosted By:  @endif
                    @if($event->event_host_title) {{$event->event_host_title}} @else @if($event->hosts){{ $event->hosts[0]->host_name}}@endif @endif</p>
            </div>
        </div>
        !-->
    </div>
    <div id="Contact-Information" class="havtech-blue-section" data-ix="fade-in">
        <div class="triangle-deco"><img src="/eventshub/images/Triangle.svg" alt="" class="orange-triangle"></div>
        <div class="main-container" data-ix="fade-in">
        <div class="heading-row border-bottom">
            <h2 class="heading-2 schedule">Contact Information</h2>
        </div>
        <div class="form-block w-form">
            <form id="email-form" name="email-form" data-name="Email Form" method="post" action="/register">
                @csrf
                <input type="hidden" name="event_id" id="event_id" value="{{$event->id}}">
                <div id="attendees0" class="attendees_hldr">
                    <div class="form-row" id="prepender">
                        <div class="field-container half"><label for="First-Name-2" class="field-label">First Name</label>
                            <input type="text" class="field w-input" maxlength="256" name="firstname[]" value="{{$user->first_name}}" data-name="First Name 2" id="First-Name-2" required="">
                        </div>
                        <div class="field-container half"><label for="Last-Name-2" class="field-label">Last Name</label>
                            <input type="text" class="field w-input" maxlength="256" name="lastname[]]" value="{{$user->last_name}}" data-name="Last Name 2" id="Last-Name-2" required="">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="field-container half"><label for="Company-2" class="field-label">Company</label>
                            <input type="text" class="field w-input" maxlength="256" name="company[]" value="{{$user->company}}" data-name="Company 2" id="Company-2" required="">
                        </div>
                        <div class="field-container half"><label for="Title-2" class="field-label">Title</label>
                            <input type="text" class="field w-input" maxlength="256" name="title[]" value="{{$user->title}}" data-name="Title 2" id="Title-2" required="">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="field-container half"><label for="Email-2" class="field-label">Email Address</label>
                            <input type="email" class="field w-input" maxlength="256" name="email[]" value="{{$user->email}}" data-name="Email 2" id="Email-2" required="">
                        </div>
                        <div class="field-container half"><label for="Phone-Number-3" class="field-label">Phone Number</label>
                            <input type="tel" class="field w-input" maxlength="256" name="phone[]" value="{{$user->phone}}" data-name="Phone Number 2" id="Phone-Number-2" required="">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="field-container ful-width"><label for="Dietary-Restrictions" class="field-label">Dietary Restrictions/Special Requirements</label>
                            <textarea data-name="Dietary Restrictions" maxlength="5000" id="Dietary-Restrictions" name="dietaryrestrictions[]" class="textarea w-input"></textarea>
                        </div>
                    </div>
                </div>
                <div class="appender"></div>
                <!-- add more attendees only show if the event is special !-->
                @if($event->event_type->id==3)
                <a onclick="cloneAttendee()"class="add-icon w-inline-block">
                    <div class="add">+</div>
                    <div class="orange-text">Additional Attendees</div>
                </a>
                @endif
                <h3 class="heading-3 white">Select which sessions you are interested in attending:</h3>
                <div class="event-list-title-row">
                    <div class="event-list-description">Description</div>
                    <div class="event-list-cost">Cost</div>
                </div>
                @if($event->grouped_schedules)
                    @foreach ($event->grouped_schedules as $date=>$schedules)
                        <div class="q-holder schedules_options">   
                            <a href="#" class="link-block-3 form-acc w-inline-block" data-ix="question-appear">
                            <p class="question-title date-acc">{{date("l, M. j, Y",strtotime($date))}}</p><img src="/eventshub/images/arrow-nav.svg" alt="" class="arrow-icon"></a>
                            <div class="answer form-event-acc">
                                @foreach ($schedules as $schedule)
                                    <div class="checkbox-row">
                                       <!-- @if($schedule->googlemap)
                                            <div class="google-map w-embed w-iframe">
                                                <iframe src="https://www.google.com/maps/embed/v1/place?key={{env('GOOGLE_MAP_API')}}&q={{$schedule->googlemap}}&zoom=18" width="100%" height="150" frameborder="0" style="border:0;" allowfullscreen=""></iframe>
                                            </div>
                                        @endif    !-->
                                        <label class="w-checkbox checkbox-field register-checkboxes">
                                            <input type="checkbox" name="schedule[]" value="{{$schedule->id}}" onclick="calculateTotal({{$schedule->price}})" data-name="Checkbox 3" class="w-checkbox-input checkbox schedules " required style="height:20px;width:20px;"><span class="checkbox-label w-form-label">
                                                <!--<span class="text-span">{{ucfirst($schedule->city)}}
                                                @if($schedule->city),  @endif {{$schedule->state}} @if($schedule->city && $schedule->state) :  @endif</span>!-->
                                                <span class="text-span">{{$schedule->title}}: </span>
                                                <br>{{$schedule->location_address}}@if($schedule->location_address), @endif
                                                @if($schedule->city){{ucfirst($schedule->city)}}, @endif{{ucfirst($schedule->state)}} {{$schedule->zip}} •
                                                {{date("g:i A",strtotime($schedule->start_date))}} to {{date("g:i A",strtotime($schedule->end_date))}}</span>
                                        </label>
                                        @if($schedule->price)
                                            <input type="hidden" class="price" id="price{{$schedule->id}}" value="{{$schedule->price}}">
                                            <div class="cost">${{$schedule->price}}</div>
                                        @else
                                            <input type="hidden" class="price" id="price{{$schedule->id}}" value="0">
                                            @if($event->cost_per_person > 0)
                                                <div class="cost">INCLUDED</div>
                                            @else
                                                <div class="cost">FREE</div>
                                            @endif
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                @endif
            
                @if($event->cost_per_person && $event->cost_per_person!=0)
                <div class="event-list-title-row total-row">
                    <div class="event-list-description">Event Registration Fee(Per Person)</div>
                    <div class="event-list-cost total-cost">${{number_format($event->cost_per_person, 2)}} </div>
                </div>
                @endif
                <div class="event-list-title-row total-row" id="num_attendees" style="display:none">
                    <div class="event-list-description">Number of Attendees</div>
                    <div class="event-list-cost total-cost total-attendee"></div>
                </div>
                <input name="registration_fee" id="registration_fee" type="hidden" value="{{$event->cost_per_person}}">
                <div class="event-list-title-row total-row">
                    <div class="event-list-description">Total Amount</div>
                    <div class="event-list-cost total-cost" id="overallTotalhtml">${{$event->totalcost}}</div>
                    <input type="hidden" class="price" id="overalltotal" name="overalltotal"  value="0">
                </div>
                <div class="button-row right">
                    <!--<a href="../events/event-1-registration-payment.html" class="base-button orange-button event-button w-button" data-ix="button-hover">Continue</a>!-->
                    <button type="submit" class="base-button orange-button event-button w-button" data-ix="button-hover">Continue</button>
                </div>
                <input type="hidden" name="attendees_num"  id="attendees_num" value="1">
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
@stop

@section('js')
<script src="{{ asset('/js/frontend/events.js') }}"></script>
@stop