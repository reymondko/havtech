@extends('layouts.dashboard')
@section('section')     
           
<meta name="_token" content="{{ csrf_token() }}" /> 
<div class="col-md-12">
    <div class="row">
        <div class="col-md-9">
            <h1 class="form-title">Create An Event (Step 1)</h1>
        </div>
            <!-- Circles which indicates the steps of the form: -->
        <div class="col-md-3 pull-right"> 
            <div class="form-group" style="margin-top:40px">        
                <label>Step <span class="counter">1</span> of 9  </label>                                                
                <div class="radio-inline uppercounter ">
                    <span class="step active"></span>
                    <span class="step"></span>
                    <span class="step"></span>
                    <span class="step"></span>
                    <span class="step"></span>
                    <span class="step"></span>
                    <span class="step"></span>
                    <span class="step"></span>
                    <span class="step"></span>
                </div>
            </div>
        </div>
    </div>
    <!-- /. create events -->        
    <div class="row" style="margin-top:20px">
        <div class="col-md-12">           
            <!-- start of step 1 event overview !-->
            <div class="tab">               
                @section ('pane1_panel_title', 'EVENT OVERVIEW')
                @section ('pane1_panel_body')
                <form id="eventoverviewform" enctype="multipart/form-data" method="post" >                               
                    @csrf
                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label>Event Title:</label>
                                <input  id="event_name" name="event_name" class="form-control"  autocomplete="off" >
                            </div> 
                            <div class="row"> 
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Start Date:</label>
                                        <div class='input-group add-on date datepicker' >
                                            <input id="start_date" name='start_dateonly' value="" type="text" class="form-control date-picker" autocomplete="off" data-date-format="yyyy-mm-dd"/>
                                            <div class="input-group-btn">
                                                <button class="btn btn-default">
                                                    <i class="fa fa-calendar"></i>
                                                </button>
                                            </div>
                                        </div>        
                                    </div>                                   
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>End Date:</label>
                                        <div class='input-group add-on date datepicker' >
                                            <input id="end_date" name='end_dateonly' value="" type="text" class="form-control date-picker" autocomplete="off" data-date-format="yyyy-mm-dd"/>
                                            <div class="input-group-btn">
                                                <button class="btn btn-default">
                                                    <i class="fa fa-calendar"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>                                        
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Banner Image(Max File Size - 2MB):</label>
                                <input type="file" id="banner_image" name="banner_image" class="form-control banner_image" accept="image/x-png,image/gif,image/jpeg"   autocomplete="off" >
                            </div>  
                        </div>
                    </div>
                    <div class="row"> 
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Start Time:</label>
                                    <input id="start_time" name="start_time" class="form-control timepicker"  autocomplete="off" >
                                </div>                                                   
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>End Time:</label>
                                    <input  id="end_time" name="end_time" class="form-control timepicker"  autocomplete="off" >
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Status:</label>
                                    <select id="status" name="status" class="form-control"  autocomplete="off" >
                                        <option>Invites Only</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Description:</label>
                                    <textarea id="description" name="description" class="form-control description"  autocomplete="off" >
                                    </textarea>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="col-md-4">
                                    <label>Register Button?</label>                                                    
                                    <div class="form-group">                                                        
                                        <div class="radio-inline">
                                            <label>
                                                <input type="radio" name="register_button" id="register_button1" value="1" checked="">Yes
                                            </label>
                                        </div>
                                        <div class="radio-inline">
                                            <label>
                                                <input type="radio" name="register_button" id="register_button2" value="0">No
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <label>Register URL:</label>                                                        
                                    <div class="form-group">                                                        
                                        <div class="radio-inline">
                                            <label>
                                                <input type="radio" name="is_register_url" id="register_url1" value="1" checked="">Yes
                                            </label>
                                        </div>
                                        <div class="radio-inline">
                                            <label>
                                                <input type="radio" name="is_register_url" id="register_url2" value="0">No
                                            </label>
                                        </div>
                                    </div>
                                    <input type="text" name="register_url" id="register_url" class="form-control"  autocomplete="off"  placeholder="http://">
                                </div>
                                <div class="col-md-4">
                                    <label>Directions Button?</label>                                                        
                                    <div class="form-group">                                                        
                                        <div class="radio-inline">
                                            <label>
                                                <input type="radio" name="directions_button" id="directions_button1" value="1" >Yes
                                            </label>
                                        </div>
                                        <div class="radio-inline">
                                            <label>
                                                <input type="radio" name="directions_button" id="directions_button2" value="0">No
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>                                                
                        </div>
                
                    @endsection
                    @include('widgets.panel', array('header'=>true, 'as'=>'pane1'))

                    @section ('pane2_panel_title', 'EVENT CONTENT')
                    @section ('pane2_panel_body')
                    <div class="row">
                        <div class="col-md-1">
                            <div class="form-group">
                                <label>Schedule</label>
                                <label class="checkbox-inline">
                                    <input type="checkbox" name="schedule_image" id="schedule" value="1" checked readonly>
                                </label>                                                      
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Accommodations</label>
                                <label class="checkbox-inline">
                                    <input type="checkbox" name="accommodations_image" id="accommodations" value="1">
                                </label>                                                      
                            </div>
                        </div>
                        <div class="col-md-1">
                            <div class="form-group">
                                <label>Dining</label>
                                <label class="checkbox-inline">
                                    <input type="checkbox" name="dining_image" id="dining" value="1">
                                </label>                                                      
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Transportation</label>
                                <label class="checkbox-inline">
                                    <input type="checkbox" name="transportation_image" id="transportation" value="1">
                                </label>                                                      
                            </div>
                        </div>
                        <div class="col-md-1">
                            <div class="form-group">
                                <label>Map</label>
                                <label class="checkbox-inline">
                                    <input type="checkbox" name="map" id="map" value="1">
                                </label>                                                      
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Travel Host & Information</label>
                                <label class="checkbox-inline">
                                    <input type="checkbox" name="travelhost" id="travelhost" value="1">
                                </label>                                                      
                            </div>
                        </div>
                    </div>
                    @endsection
                    @include('widgets.panel', array('header'=>true, 'as'=>'pane2'))

                    @section ('pane3_panel_title', 'EVENT HOST')
                    @section ('pane3_panel_body')
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Email Address:</label>
                                    <input id="event_host" name="event_host" class="form-control" autocomplete="off" >
                                </div>
                            </div>
                        </div> 
                    @endsection
                    @include('widgets.panel', array('header'=>true, 'as'=>'pane3'))                            
                </form>
                
            </div>
            
            <!-- end of step 1 event overview !-->
                    
            <!-- start step 2 Event Schedule!-->
            <div class="tab">
                <form id="eventscheduleform" enctype="multipart/form-data" method="post" >                               
                    @csrf 
                    <div id="event_schedule1" class="event_schedule">
                        @section ('pane4_panel_title', 'SCHEDULE')
                        @section ('pane4_panel_body')
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Banner Image(Max File Size - 2MB):</label>
                                        <input type="file" id="banner_image" name="sbanner_image[]" class="form-control banner_image" accept="image/x-png,image/gif,image/jpeg"   autocomplete="off" >
                                    </div>  
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <label>Title:</label>
                                        <input id="title" name="title[]" class="form-control" autocomplete="off" >
                                    </div>  
                                </div>
                            </div>
                            <div class="row"> 
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Start Date:</label>
                                        <div class='input-group add-on date datepicker' >
                                            <input id="sched_start_date" name='start_dateonly[]' value="" type="text" class="form-control date-picker" autocomplete="off"/>
                                            <div class="input-group-btn">
                                                <button class="btn btn-default">
                                                    <i class="fa fa-calendar"></i>
                                                </button>
                                            </div>
                                        </div>        
                                    </div>                                   
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>End Date:</label>
                                        <div class='input-group add-on date datepicker' >
                                            <input id="sched_end_date" name='end_dateonly[]' value="" type="text" class="form-control date-picker" autocomplete="off" />
                                            <div class="input-group-btn">
                                                <button class="btn btn-default">
                                                    <i class="fa fa-calendar"></i>
                                                </button> 
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Start Time:</label>
                                        <input id="start_time" name="start_time[]" class="form-control timepicker"  autocomplete="off" >
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>End Time:</label>
                                        <input id="end_time" name="end_time[]" class="form-control timepicker"  autocomplete="off" >
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Location:</label>
                                        <input id="location" name="location[]" class="form-control "  autocomplete="off" >
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Location Address:</label>
                                        <input id="location_address" name="location_address[]" class="form-control "  autocomplete="off" >
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Location Address #2:</label>
                                        <input id="location_address2" name="location_address2[]" class="form-control "  autocomplete="off" >
                                    </div>
                                </div> 
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>City:</label>
                                        <input id="city" name="city[]" class="form-control "  autocomplete="off" >
                                    </div>
                                </div> 
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>State:</label>
                                        <input id="state" name="state[]" class="form-control "  autocomplete="off" >
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Thumbnail Image:</label>
                                        <input type="file" accept="image/x-png,image/gif,image/jpeg"  id="thumb_image"  name="sthumb_image[]" class="form-control thumb_image"  autocomplete="off" >
                                    </div>  
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Description:</label>
                                        <textarea id="description" name="description[]" class="form-control description"  autocomplete="off" >
                                        </textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <label>Directions Button?</label>                                                        
                                    <div class="form-group">                                                        
                                        <div class="radio-inline">
                                            <label>
                                                <input type="radio" name="directions_button" id="directions_button1" value="1" >Yes
                                            </label>
                                        </div>
                                        <div class="radio-inline">
                                            <label>
                                                <input type="radio" name="directions_button" id="directions_button2" value="0">No
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Website Button(Optional):</label>
                                        <input id="website_url" name="website_url[]" class="form-control " placeholder="http://"  autocomplete="off" >
                                    </div>
                                </div>  
                            </div>
                            <input type="hidden" name="schedule_ids[]" >
                        @endsection
                        @include('widgets.panel', array('header'=>true, 'as'=>'pane4'))
                    </div>
                    <div class="appender"></div>
                    <input name="eventsched_cnt" id="eventsched_cnt" type="hidden">
                </form>                     
                <div class="row">
                    <div class="col-md-12">
                        <div class="panel panel-default">	  
                            <div class="panel-heading">
                                <h3 class="panel-title"><span  class="pull-left">SCHEDULE</span> &nbsp; <button onclick="cloneSchedule()" id="add_moreSchedule" class="tn btn-default btn-outline pull-right"><i class="fa fa-plus-circle"></i> ADD </button></a>                                
                            </div>     
                        </div>                        
                    </div>
                </div>
            </div>
            <!-- end step 2 Event Schedule!-->   
            
            <!-- start step 3 Accomodations!-->
            <div class="tab">
                <form id="eventaccomodationsform" enctype="multipart/form-data" method="post" >                               
                    @csrf 
                    <div id="event_schedulezzz" class="event_schedulezz">
                        @section ('pane4_panel_title', 'ACCOMODATIONS')
                        @section ('pane4_panel_body')
                        
                        @endsection
                        @include('widgets.panel', array('header'=>true, 'as'=>'pane4'))
                    </div>
                    <div class="acc_appender"></div>
                    <input name="accomodations_cnt" id="eventsched_cnt" type="hidden">
                </form>                     
                <div class="row">
                    <div class="col-md-12">
                        <div class="panel panel-default">	  
                            <div class="panel-heading">
                                <h3 class="panel-title"><span  class="pull-left">ACCOMODATIONS</span> &nbsp; <button onclick="cloneAccomodation()" id="add_moreAccomodation" class="tn btn-default btn-outline pull-right"><i class="fa fa-plus-circle"></i> ADD </button></a>                                
                            </div>     
                        </div>                        
                    </div>
                </div>
            </div>
            <!-- end step 3 Accomodations!-->

                    <div class="tab">Birthday:
                        <p><input placeholder="dd" oninput="this.className = ''"></p>
                        <p><input placeholder="mm" oninput="this.className = ''"></p>
                        <p><input placeholder="yyyy" oninput="this.className = ''"></p>
                    </div>
                        
                    <div class="tab">Login Info:
                        <p><input placeholder="Username..." oninput="this.className = ''"></p>
                        <p><input placeholder="Password..." oninput="this.className = ''"></p>
                    </div>

                    
                    
            <div class="row">
                <div class="col-md-2">
                    <button class="btn ">Cancel</button>
                </div>
                <div class="col-md-6" style="text-align:center;"> 
                    <div class="form-group">        
                        <label>Step <span class="counter2">1</span> of 9   </label>                                                
                        <div class="radio-inline bottomcounter">
                                <span class="step_b active"></span>
                                <span class="step_b"></span>
                                <span class="step_b"></span>
                                <span class="step_b"></span>
                                <span class="step_b"></span>
                                <span class="step_b"></span>
                                <span class="step_b"></span>
                                <span class="step_b"></span>
                                <span class="step_b"></span>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <button class="btn btn-block" id="prevBtn" onclick="nextPrev(-1)" >Back</button>
                </div>
                <div class="col-md-2">
                    <button onclick="nextPrev(1)" id="nextBtn" class="btn btn-block">Next</button>
                </div>
            </div> 
        </div>
            <input type="hidden" name="event_id" id="event_id" > 
            <input type="hidden" name="step1" id="step1" value="0">
            <input type="hidden" name="step1" id="step1" value="0">  
            <input type="hidden" name="step2" id="step2" value="0">  
            <input type="hidden" name="step3" id="step3" value="0">  
            <input type="hidden" name="step4" id="step4" value="0">  
            <input type="hidden" name="step5" id="step5" value="0">  
            <input type="hidden" name="step6" id="step6" value="0">  
            <input type="hidden" name="step7" id="step7" value="0">  
            <input type="hidden" name="step8" id="step8" value="0">   
            <input type="hidden" name="step9" id="step9" value="0">   
               
            <!-- /.create events  -->
    </div>
       
</div>

@section('css')
    <link rel="stylesheet" href="{{ asset('/css/events.css') }}">
@stop

@section('js')
<script src="{{ asset('/js/jquery/events.jquery.js') }}"></script>
@stop
@stop
