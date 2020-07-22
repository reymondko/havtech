@extends('layouts.events.edit.editEvent_template')
@section('content')   

    <form id="eventoverviewform" enctype="multipart/form-data" method="post" >
        @foreach ($data['eventoverview'] as $eo)  
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header" id="event-title">{{$eo->event_name}}</h1>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Upload File::</label>
                        <label class="checkbox-inline">
                            <input type="file" id="overview_file" name="overview_file"  class="form-control "   autocomplete="off" >
                        </div>  
                        </label>
                    </div>
                    <div class="col-md-3" id="itinerary_file_div">
                        @if(!empty($eo['overview_file']))
                            <a href="{{$eo['overview_file']}}" target="_blank"><i class="fa fa-file"></i> Download Itinerary File</a> &nbsp;&nbsp;
                            <button type="button" id="deleteBanner" class="deleteBanner btn " onclick="removeOverviewFile({{$eo->id}})"><i class="fa fa-close"></i> Remove File</button>
                        @endif
                    </div> 
                </div>
            </div>
            <div class="row">
                <!--<div class="col-md-3">
                    <div class="form-group">
                        <label>Status:</label>
                        <label class="checkbox-inline">
                            <select id="event_status" name="visibility" class="form-control"  autocomplete="off" >
                                <option {{ $eo->event_status == 'Published' ? ' selected' : '' }}>Published</option>
                                <option {{ $eo->event_status == 'Unpublished' ? ' selected' : '' }}>Unpublished</option>
                            </select>
                        </label>
                    </div>
                </div>!-->
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Event Type:</label>
                        <label class="checkbox-inline">
                            <select  id="event_type" name="event_type" class="form-control"  >
                                @foreach ($data['eventtypes'] as $et)
                                    <option value="{{$et->id}}" {{ $et->id == $eo->event_types ? ' selected' : ''}} >{{$et->description}}</option>  
                                @endforeach                                        
                            </select>
                        </label>
                    </div>
                </div>
                <div class="col-md-1 pull-right">
                    <button class="btn btn-default btn-blue" type="submit" id="update_event" >Update Event</button>
                </div>
                <div class="col-md-1 pull-right">
                    <button class="btn btn-default btn-danger" onclick="deleteEvent({{$eo->id}})" id="delete_event" type="button">Delete Event</button>
                </div>
            </div>  
            <div class="row">
                <div class="col-md-12" style="margin: 0 0 20px 0;"> 
                    @csrf
                    <div class="row">
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <label>Event Title:</label>
                                    <input  id="event_name" value="{{$eo->event_name}}" name="event_name" class="form-control"  autocomplete="off" required>
                                    </div>
                                </div> 
                                <div class="col-md-2">
                                    <div class="form-group"> 
                                        <label>Publish To Website:</label>
                                        <select id="visibility_web" name="visibility_web" class="form-control"  autocomplete="off" >
                                            <option {{ $eo->visibility_web == 'Published' ? ' selected' : '' }}>Published</option>
                                            <option {{ $eo->visibility_web == 'Unpublished' ? ' selected' : '' }}>Unpublished</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group"> 
                                        <label>Publish To App:</label>
                                        <select id="visibility_app" name="visibility_app" class="form-control"  autocomplete="off" >
                                            <option {{ $eo->visibility_app == 'Published' ? ' selected' : '' }}>Published</option>
                                            <option {{ $eo->visibility_app == 'Unpublished' ? ' selected' : '' }}>Unpublished</option>
                                        </select>
                                    </div>
                                </div> 
                            </div>
                            <div class="row"> 
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>Start Date:</label>
                                        <div class='input-group add-on date datepicker' >
                                        <input id="start_date" name='start_dateonly' value="{{date('m/d/Y',strtotime($eo->start_date))}}" type="text" class="form-control date-picker" autocomplete="off" data-date-format="yyyy-mm-dd" required/>
                                            <div class="input-group-btn">
                                                <button class="btn btn-default">
                                                    <i class="fa fa-calendar"></i>
                                                </button>
                                            </div>
                                        </div>        
                                    </div>                                   
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>End Date:</label>
                                        <div class='input-group add-on date datepicker' >
                                            <input id="end_date" name='end_dateonly' value="{{date('m/d/Y',strtotime($eo->end_date))}}" type="text" class="form-control date-picker" autocomplete="off" data-date-format="yyyy-mm-dd" required/>
                                            <div class="input-group-btn">
                                                <button class="btn btn-default">
                                                    <i class="fa fa-calendar"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                        <div class="form-group">
                                            <label>Event Date Offset (in hours):</label>
                                            <input type="number"  min="-6" max="17" id="timezone_offset" name="timezone_offset" class="form-control" value="{{$eo->timezone_offset}}" autocomplete="off" required>
                                        </div>
                                    </div> 
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Banner Image(Max File Size - 2MB):</label>
                                        <input type="file" id="banner_image" accept="image/x-png,image/gif,image/jpeg"  id="thumb_image"  name="banner_image" class="form-control"  autocomplete="off" >
                                    </div>  
                                </div>
                                <div class="col-md-3" id="banner-preview">
                                    @if(!empty($eo->image))
                                        <div class="photo-preview" >
                                            <div class="photo-image">
                                                <img data-dz-thumbnail=""  src="{{$eo->image}}" style="width:120px">
                                                <button type="button" id="deleteBanner" class="deleteBanner btn " onclick="removeBanner('events')"><i class="fa fa-close"></i> Remove</button>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <!--<div class="row">
                                <div class="col-md-8">
                                    <div class="event_dates_appender " >
                                        @foreach ($datez as $date)
                                            <div class='row' id='datez{{$date->id}}'><div class='col-md-2'><div class='form-group'><label>{{date("F j,Y",strtotime($date->event_date))}}</label></div></div><div class='col-md-2'><div class='form-group'><input type='hidden' name='dates[]' value='{{date("F j,Y",strtotime($date->event_date))}}'><input id='start_time' name='start_time[]' value="{{date("H:i a",strtotime($date->start_time))}}" class='form-control timepicker'  autocomplete='off' required></div></div><div class='col-md-2'><div class='form-group'> <input  id='end_time' name='end_time[]'  value="{{date("H:i a",strtotime($date->end_time))}}" class='form-control timepicker'  autocomplete='off' required>        </div>    </div>    <div class='col-md-2'>    <button type='button' id='deleteSchedule' class='btn btn-default btn-outline' onclick='removeDate({{$date->id}})'><i class='fa fa-times-circle-o' aria-hidden='true' title='click to Remove This Date'></i>  Remove</button>    </div>    </div>
                                        @endforeach
                                    </div>
                                </div>
                                <div class="col-md-4" id="banner-preview">
                                    @if(!empty($eo->image))
                                        <div class="photo-preview" >
                                            <div class="photo-image">
                                                <img data-dz-thumbnail=""  src="{{$eo->image}}" style="width:120px">
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div> !-->                                      
                        </div>                            
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Description:</label>
                                <textarea id="description" name="description" class="form-control description"  autocomplete="off" >{{$eo->description}}</textarea>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Custom Calendar Message (Email):</label>
                                <textarea id="custom_calendar_message" name="custom_calendar_message" class="form-control description"  autocomplete="off" >{{$eo->custom_calendar_message}}</textarea>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="col-md-4">
                                <label>Register Button:</label>                                                    
                                <!--<div class="form-group">                                                        
                                    <div class="radio-inline">
                                        <label>
                                            <input type="radio" name="register_button" id="register_button1" value="1" {{ $eo->register_button == 1 ? ' checked' : '' }} >Yes
                                        </label>
                                    </div>
                                    <div class="radio-inline">
                                        <label>
                                            <input type="radio" name="register_button" id="register_button2"  {{ $eo->register_button == 0 ? ' checked' : '' }} value="0">No
                                        </label>
                                    </div>
                                </div>!-->
                                <div class="form-group">
                                    <input type="text" name="register_button" id="register_button" class="form-control" value="{{ $eo->register_button}}" autocomplete="off"  placeholder="http://">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label>Directions Button:</label>                                                        
                                <!--<div class="form-group">                                                        
                                    <div class="radio-inline">
                                        <label>
                                            <input type="radio" name="directions_button" {{ $eo->directions_button == 1 ? ' checked' : '' }}  id="directions_button1" value="1" >Yes
                                        </label>
                                    </div>
                                    <div class="radio-inline">
                                        <label>
                                            <input type="radio" name="directions_button" {{ $eo->directions_button == 0 ? ' checked' : '' }} id="directions_button2" value="0">No
                                        </label>
                                    </div>
                                </div>!-->
                                <div class="form-group">
                                    <input type="text" name="directions_url" id="directions_url" class="form-control" value="{{ $eo->directions_url}}" autocomplete="off"  placeholder="http://">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label>Website Button:</label>                                                        
                                <!--<div class="form-group">                                                        
                                    <div class="radio-inline">
                                        <label>
                                            <input type="radio" name="is_register_url" id="register_url1" value="1" {{ $eo->is_register_url == 1 ? ' checked' : '' }} >Yes
                                        </label>
                                    </div>
                                    <div class="radio-inline">
                                        <label>
                                            <input type="radio" name="is_register_url" id="register_url2" value="0" {{ $eo->is_register_url == 0 ? ' checked' : '' }}>No
                                        </label>
                                    </div>
                                </div>!-->
                                <div class="form-group">
                                    <input type="text" name="website_url" id="website_url" class="form-control" value="{{ $eo->website_url}}" autocomplete="off"  placeholder="http://">
                                </div>
                            </div>
                        </div>                                                
                    </div>
                    <div class="row">
                            <div class="col-md-12">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label >Event Registration Fee: <i class="fa fa-info-circle" title="Leave empty if FREE event"></i></label>
                                        <label class="checkbox-inline">
                                            <input id="cost_per_person" name="cost_per_person" value="{{$eo->cost_per_person}}" class="form-control" autocomplete="off" >
                                        </label>
                                    </div>
                                </div>
                                <!--<div class="col-md-4">
                                    <div class="form-group">
                                        <label >Number Of Person/s:</label>
                                        <label class="checkbox-inline">
                                            <select id="number_of_person" name="number_of_person" class="form-control"  >
                                                @for($x=1;$x<10;$x++)
                                                    <option {{ $eo->number_of_person == $x ? ' selected' : '' }}>{{$x}}</option>
                                                @endfor
                                            </select>
                                        </label>
                                    </div>
                                </div>!-->
                            </div>
                        </div>
                    </div>
             </div>   

            @section ('pane2_panel_title', 'EVENT CONTENT')
            @section ('pane2_panel_body')
                <div class="row">
                    <div class="col-md-2" style="text-align:center">
                        <div class="form-group">
                            <label>Schedule</label>
                            <label class="checkbox-inline event_content_cb">
                                <input type="checkbox"  required {{ $eo->schedule_image == 1 ? ' checked' : '' }} name="schedule_image" id="schedule" value="1">
                            </label>                                                      
                        </div>
                    </div>
                    <div class="col-md-2" style="text-align:center">
                        <div class="form-group">
                            <label>Accommodations</label>
                            <label class="checkbox-inline event_content_cb">
                                <input type="checkbox" {{ $eo->accomodations_image == 1 ? ' checked' : '' }} name="accommodations_image" id="accommodations" value="1">
                            </label>                                                      
                        </div>
                    </div>
                    <div class="col-md-2" style="text-align:center">
                        <div class="form-group">
                            <label>Dining</label>
                            <label class="checkbox-inline event_content_cb">
                                <input type="checkbox" {{ $eo->dining_image == 1 ? ' checked' : '' }} name="dining_image" id="dining" value="1">
                            </label>                                                      
                        </div>
                    </div>
                    <div class="col-md-2" style="text-align:center">
                        <div class="form-group">
                            <label>Transportation</label>
                            <label class="checkbox-inline event_content_cb">
                                <input type="checkbox" {{ $eo->transportation_image == 1 ? ' checked' : '' }} name="transportation_image" id="transportation" value="1">
                            </label>                                                      
                        </div>
                    </div>
                    <div class="col-md-1" style="text-align:center">
                        <div class="form-group">
                            <label>Map</label>
                            <label class="checkbox-inline event_content_cb">
                                <input type="checkbox" {{ $eo->map == 1 ? ' checked' : '' }} name="map" id="map" value="1">
                            </label>                                                      
                        </div>
                    </div>
                    <div class="col-md-3" style="text-align:center">
                        <div class="form-group">
                            <label>Travel Host & Information</label>
                            <label class="checkbox-inline event_content_cb">
                                <input type="checkbox" {{ $eo->travelhost == 1 ? ' checked' : '' }} name="travelhost" id="travelhost" value="1">
                            </label>                                                      
                        </div>
                    </div>
                </div>
            @endsection
            @include('widgets.panel', array('header'=>true, 'as'=>'pane2'))

            @section ('pane3_panel_title', 'EVENT HOST')
            @section ('pane3_panel_body')
            <div class="row">
                    <div class="col-md-4">
                        <label>Event Host Section?</label>                                                        
                        <div class="form-group">                                                        
                            <div class="radio-inline">
                                <label>
                                    <input type="radio" name="event_host" {{ $eo->event_host == 1 ? ' checked' : '' }} id="event_host" value="1" >Yes
                                </label>
                            </div>
                            <div class="radio-inline">
                                <label>
                                    <input type="radio" name="event_host" {{ $eo->event_host == 0 ? ' checked' : '' }} id="event_host" value="0">No
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label>Event Host Title:</label> 
                        <div class="form-group">
                            <input type="text" name="event_host_title" value="{{$eo->event_host_title}}" id="event_host_title" class="form-control" autocomplete="off"  placeholder="Have Questions?">
                        </div>
                    </div>
                </div> 
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <textarea id="event_host_description" name="event_host_description" class="form-control description"  autocomplete="off" >{{$eo->event_host_description}}</textarea>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Email Address:</label>
                            <input id="event_host_email" name="event_host_email" value="{{$eo->event_host_email}}" class="form-control" autocomplete="off" >
                        </div>
                    </div>
                </div>
            @endsection
            @include('widgets.panel', array('header'=>true, 'as'=>'pane3'))
            <div class="row">
                <!--<div class="col-md-2">
                    <div class="form-group">
                        <label>Visibility:</label>
                        <label class="checkbox-inline">
                            <select id="visibility" name="visibility" class="form-control"  autocomplete="off" >
                                <option {{ $eo->visibility == 'Not Published' ? ' selected' : '' }}>Not Published</option>
                                <option {{ $eo->visibility == 'Published' ? ' selected' : '' }}>Published</option>
                                <option {{ $eo->visibility == 'Publish To App' ? ' selected' : '' }}>Publish To App</option>
                                
                            </select>
                        </label>
                    </div>
                </div>!-->
                <div class="col-md-1 pull-right">
                    <button class="btn btn-default btn-blue" onclick="$('#eventoverviewform').submit();" id="update_event" >Update Event</button>
                </div>
                <div class="col-md-1 pull-right">
                    <button class="btn btn-default btn-danger" onclick="deleteEvent({{$eo->id}})" id="delete_event" >Delete Event</button>
                </div>
            </div>  
        @endforeach 
              
                                     
    </form>  
@stop
@section('css')
<link rel="stylesheet" href="{{ asset('/css/events.css') }}">
@stop

@section('js')
<script src="{{ asset('/js/jquery/edit_events.jquery.js') }}"></script>
@stop  
                

