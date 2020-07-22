@extends('frontend.layout')

@section('title', 'Havtech Events Hub - Event')

@section('header_title', 'Events')
@section('header_image')
  <div class="page-section events">
@endsection

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
    <div id="Overview" class="content-section summary">
        <div class="registration-title">Registration Summary</div>
        <h2 class="heading-2">{{$event->event_name}}</h2>
        <div class="event-list-title-row registration">
        <div class="event-list-description gray">Description</div>
        <div class="event-list-cost gray">Cost</div>
        </div>
        @if($custom_schedule->grouped_schedules)
            @foreach ($custom_schedule->grouped_schedules as $date=>$schedules)
                <div class="event-summary">
                <div class="event-list-description gray">{{date("l, M. j, Y",strtotime($date))}}</div>
                    @foreach ($schedules as $schedule)
                        <div class="event-summary-row">
                           @if($schedule->googlemap)
                              <div class="google-map w-embed w-iframe">
                                  <iframe src="https://www.google.com/maps/embed/v1/place?key={{env('GOOGLE_MAP_API')}}&q={{$schedule->googlemap}}&zoom=18" width="100%" height="150" frameborder="0" style="border:0;" allowfullscreen=""></iframe>
                              </div>
                            @endif
                            <p class="paragraph" style="width:70%"">
                              <span class="text-span">{{$schedule->title}}: </span>
                                <br>{{$schedule->location_address}}@if($schedule->location_address), @endif
                                {{ucfirst($schedule->city)}}@if($schedule->city), @endif{{ucfirst($schedule->state)}} {{$schedule->zip}} •
                                Begins {{date("g:i A",strtotime($schedule->start_date))}} to {{date("g:i A",strtotime($schedule->end_date))}}</span>
                              </p>
                            <div class="price">
                                @if($schedule->price && $schedule->price!=0)
                                    ${{number_format($schedule->price, 2)}}
                                @else
                                  @if($event->cost_per_person > 0)
                                      INCLUDED
                                  @else
                                      FREE
                                  @endif
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @endforeach
        @endif
        @if($event->cost_per_person && $event->cost_per_person!=0)
        <div class="event-list-title-row total-row event-summary">
            <div class="event-list-description gray">Registration Fee</div>
            <div class="event-list-cost total-cost blue">${{number_format($event->cost_per_person, 2)}}</div>
        </div>        
        @endif
        <div class="event-summary gray">
            <div class="event-summary-row">
                <div class="event-list-description gray">Number of Persons</div>
            <div class="price blue">{{$payment->number_persons}}</div>
            </div>
        </div>
        <div class="event-list-title-row total-row event-summary">
            <div class="event-list-description gray">Total Amount</div>
            <div class="event-list-cost total-cost blue">${{$payment->amount}}</div>
        </div>
    </div>
    <!-- Payment Information Part !-->
    <div id="Contact-Information" class="havtech-blue-section payment-information" data-ix="fade-in">
        <div class="triangle-deco"><img src="/eventshub/images/Triangle.svg" alt="" class="orange-triangle"></div>
        <div class="main-container" data-ix="fade-in">
            <div class="heading-row border-bottom">
              <h2 class="heading-2 schedule">Payment Information</h2>
            </div>
            @if($payment->amount > 0)
              <div class="form-block w-form">
                <form id="email-form" name="email-form" method="POST" action="/events/register-complete" data-name="Email Form" >
                  @csrf
                  <input type="hidden" name="payment_id" id="payment_id" value="{{$payment->id}}">
                  <h3 class="heading-3 white">Select A Payment Method:</h3>
                  <div class="form-row">
                    <div class="field-container half">
                      <div class="field-container one-fourth" style="display:inline-block;color:#FFF"> <input type="radio" name="payment_method" checked="" id="payment_method" value="check" required><span style="margin-left:5px">Check</span></div>
                      <div class="field-container one-fourth" style="display:inline-block;color:#FFF"><input type="radio" name="payment_method"  id="payment_method" value="cc" required><span style="margin-left:5px">Credit Card</span></div>
                    </div>
                  </div>
                  <!-- Check Part !-->
                  <div id="payment_check">
                    <h3 class="heading-3 white">Pay With Check:</h3>
                    <div class="form-row">
                      <div class="field-container half one-column"><label for="Business-Check-Number-2" class="field-label">Business Check Number</label>
                        <input type="text" class="field w-input check-input" maxlength="256" name="Business-Check-Number" data-name="Business Check Number" id="Business-Check-Number" required=""></div>
                    </div>
                    <div class="form-row">
                      <div class="field-container half one-column">
                            <label for="Purchase-Order-Number" class="field-label"><b>Purchase Order Number:</b></label> 
                            <input type="text" class="field w-input" maxlength="256" name="Purchase-Order-Number" data-name="Purchase Order Number" id="Purchase-Order-Number" >
                        </div>
                    </div>
                  </div>

                  <!-- Credit card Part !-->
                  <div id="payment_cc" style="display:none">
                    <h3 class="heading-3 white">Pay With Credit Card:</h3>
                    <div class="credit-card-container"><img src="/eventshub/images/Visa.png" alt="Visa" class="credit-cards"><img src="/eventshub/images/Mastercard.png" alt="Visa" class="credit-cards"><img src="/eventshub/images/AMEX.png" alt="Visa" class="credit-cards"></div>
                    <div class="form-row">
                      <div class="field-container half"><label for="Card-Number" class="field-label">Card Number</label><input type="text" class="field w-input cc-input" maxlength="256" name="Card-Number" data-name="Card Number" id="Card-Number" required=""></div>
                      <div class="field-container half"><label for="Name-On-Card" class="field-label">Name On Card</label><input type="text" class="field w-input cc-input" maxlength="256" name="Name-On-Card" data-name="Name On Card" id="Name-On-Card" required=""></div>
                    </div>
                    <div class="form-row">
                      <div class="field-container half">
                        <label for="Expiration-Date" class="field-label">Expiration Date</label>
                        <!--<input type="text" class="field w-input cc-input" name="Expiration-Date" data-name="Expiration Date" id="Expiration-Date" required=""  maxlength="256">!-->
                        <div class="field-container half" style="display:inline-block">
                          <select  class=" w-input cc-input" name="Expiration-Date-Month" data-name="Expiration Date Month" id="Expiration-Date-Month" required="" style="font-weight:200"> 
                            <option value=''>--Select Month--</option>
                            <option value='01' >Janaury</option>
                            <option value='02' selected>February</option>
                            <option value='03'>March</option>
                            <option value='04'>April</option>
                            <option value='05'>May</option>
                            <option value='06'>June</option>
                            <option value='07'>July</option>
                            <option value='08'>August</option>
                            <option value='09'>September</option>
                            <option value='10'>October</option>
                            <option value='11'>November</option>
                            <option value='12'>December</option>
                          </select> 
                        </div>
                        <div class="field-container half" style="display:inline-block">
                          <select class=" w-input cc-input" name="Expiration-Date-Year" data-name="Expiration Date Year" id="Expiration-Date-Year" required=""  style="font-weight:200">
                            @foreach ($date_range as $year)
                            <option value="{{$year}}" @if($year==date('Y')) selected @endif>{{$year}}</option>
                            @endforeach 
                          </select>
                        </div>
                      </div>
                      <div class="field-container half"><label for="CVV-CVC" class="field-label">CVV/CVC</label><input type="text" class="field w-input cc-input" maxlength="256" name="CVV-CVC" data-name="CVV/CVC" id="CVV-CVC" required=""></div>
                    </div>
                    <div class="form-row">
                      <div class="field-container three-forths"><label for="Billing-Address" class="field-label">Billing Address</label><input type="text" class="field w-input cc-input" maxlength="256" name="Billing-Address" data-name="Billing Address" id="Billing-Address" required=""></div>
                      <div class="field-container one-fourth"><label for="Apt-Suite" class="field-label">Apt/Suite</label><input type="text" class="field w-input" maxlength="256" name="Apt-Suite" data-name="Apt/Suite" id="Apt-Suite" ></div>
                    </div>
                    <div class="form-row">
                      <div class="field-container third"><label for="Billing-City" class="field-label">City</label><input type="text" class="field w-input cc-input" maxlength="256" name="Billing-City" data-name="Expiration Date 2" id="Billing-City" required=""></div>
                      <div class="field-container third"><label for="Billing-State" class="field-label">State</label>
                        <!--<input type="text" class="field w-input cc-input" maxlength="256" name="Billing-State" data-name="Expiration Date 2" id="Billing-State" required="">!-->
                        <select class=" w-input cc-input"  name="Billing-State" data-name="Expiration Date 2" id="Billing-State" required=""  style="font-weight:200">
                          <option value="AL">Alabama</option>
                          <option value="AK">Alaska</option>
                          <option value="AZ">Arizona</option>
                          <option value="AR">Arkansas</option>
                          <option value="CA">California</option>
                          <option value="CO">Colorado</option>
                          <option value="CT">Connecticut</option>
                          <option value="DE">Delaware</option>
                          <option value="DC">District Of Columbia</option>
                          <option value="FL">Florida</option>
                          <option value="GA">Georgia</option>
                          <option value="HI">Hawaii</option>
                          <option value="ID">Idaho</option>
                          <option value="IL">Illinois</option>
                          <option value="IN">Indiana</option>
                          <option value="IA">Iowa</option>
                          <option value="KS">Kansas</option>
                          <option value="KY">Kentucky</option>
                          <option value="LA">Louisiana</option>
                          <option value="ME">Maine</option>
                          <option value="MD">Maryland</option>
                          <option value="MA">Massachusetts</option>
                          <option value="MI">Michigan</option>
                          <option value="MN">Minnesota</option>
                          <option value="MS">Mississippi</option>
                          <option value="MO">Missouri</option>
                          <option value="MT">Montana</option>
                          <option value="NE">Nebraska</option>
                          <option value="NV">Nevada</option>
                          <option value="NH">New Hampshire</option>
                          <option value="NJ">New Jersey</option>
                          <option value="NM">New Mexico</option>
                          <option value="NY">New York</option>
                          <option value="NC">North Carolina</option>
                          <option value="ND">North Dakota</option>
                          <option value="OH">Ohio</option>
                          <option value="OK">Oklahoma</option>
                          <option value="OR">Oregon</option>
                          <option value="PA">Pennsylvania</option>
                          <option value="RI">Rhode Island</option>
                          <option value="SC">South Carolina</option>
                          <option value="SD">South Dakota</option>
                          <option value="TN">Tennessee</option>
                          <option value="TX">Texas</option>
                          <option value="UT">Utah</option>
                          <option value="VT">Vermont</option>
                          <option value="VA">Virginia</option>
                          <option value="WA">Washington</option>
                          <option value="WV">West Virginia</option>
                          <option value="WI">Wisconsin</option>
                          <option value="WY">Wyoming</option>
                        </select>
                      </div>
                      <div class="field-container third"><label for="Billing-Zip" class="field-label">Zip</label><input type="text" class="field w-input cc-input" maxlength="256" name="Billing-Zip" data-name="Expiration Date 2" id="Billing-Zip" required=""></div>
                    </div>
                  </div>
                  <!-- end CC part !-->
                  <div class="event-list-title-row total-row">
                    <div class="event-list-description">Total Amount</div>
                    <div class="event-list-cost total-cost">${{$payment->amount}}</div>
                  </div>
                  <div class="button-row right">
                    <!--<a href="{{ redirect()->back()->getTargetUrl() }}/18" class="base-button white-button event-button w-button" data-ix="button-hover">Back</a>!-->
                    <input type="hidden" value="{{ collect(request()->segments())->last() }}" name="payment_registration_id">
                    <input type="submit" value="Register Now" data-wait="Please wait..." class="submit-button w-button" data-ix="button-hover">
                  </div>
                </form>
                <div class="w-form-done">
                  <div>Thank you! Your submission has been received!</div>
                </div>
                <div class="w-form-fail">
                  <div>Oops! Something went wrong while submitting the form.</div>
                </div>
              </div>
            @else
              <div>
                <p class="paragraph"><span class="text-span-1" style="color:#FFF">The sessions you  have selected are <b>free of charge</b>. Proceed Below to register.</p>
                <div class="form-block w-form">
                  <form id="email-form" name="email-form" method="POST" action="/events/register-complete" data-name="Email Form" >
                    @csrf
                  <div class="button-row left">
                    <a href="{{ URL::previous() }}" class="base-button white-button event-button last w-button" data-ix="button-hover">Back</a>
                    <!--<a href="{{ redirect()->back()->getTargetUrl() }}/18" class="base-button white-button event-button w-button" data-ix="button-hover">Back</a>!-->
                    <input type="hidden" value="{{ collect(request()->segments())->last() }}" name="payment_registration_id">
                    <input type="submit" value="Register Now" data-wait="Please wait..." class="submit-button w-button" data-ix="button-hover" style="margin-left:20px;">
                </div>
              </form>
              </div>
            @endif
          </div>
    </div>
@stop

@section('js')
<script src="{{ asset('/js/frontend/events.js') }}"></script>
@stop