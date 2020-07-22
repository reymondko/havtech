@extends('layouts.dashboard')
@section('page_heading',"$event->event_name")
@section('section')     
           
<meta name="_token" content="{{ csrf_token() }}" /> 
<div class="col-md-12">
    <div class="row">
        <div class="col-md-9">
            <h1 class="form-title">Event Schedule (Step {{$step}})</h1>
        </div>
            <!-- Circles which indicates the steps of the form: -->
        <div class="col-md-3 pull-right"> 
            <div class="form-group" style="margin-top:40px">        
                <label>Step {{$step}} of {{$total}}  </label>                                                
                <div class="radio-inline uppercounter ">
                    @for($x=1;$x<$total+1;$x++)                    
                        <span class="step @if ($step == $x) active @endif" ></span>
                    @endfor
                </div>
            </div>
        </div>
    </div>
    <div id="event_schedule0" class="event_schedule"  style="display: none">
        <div class="panel panel-default">
            <div class="panel-heading event-heading">
                <h3 class="panel-title">ITINERARY <span class="itinum"></span> 
                    :<span id="iti_title" style="font-weight:bold"></span>
                    <span id="delbtnhldr">
                        <button type="button" onclick="$('#event_schedule1').remove()" id="deleteSchedule" class="tn btn-default btn-outline pull-right"><i class="fa fa-minus-circle"></i> Delete </button>
                    </span>
                </h3>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Download Link?</label>
                            <label class="radio-inline">
                                <input type="radio" name="download_link1" id="download_link1" value="1">Yes
                            </label>
                            <label class="radio-inline">
                                <input type="radio" name="download_link1" id="download_link2" value="0">No
                            </label>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="col-md-3">
                            <label>Upload File:</label>
                        </div> 
                        <div class="col-md-6">
                            <input type="file" id="itinerary_file" name="itinerary_file[]" class="form-control "    autocomplete="off" >
                        </div>  
                    </div>
                </div>
                <br/>
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Mandatory?</label>
                            <label class="radio-inline">
                                <input type="radio" name="mandatory1" id="download_link1" value="1">Yes
                            </label>
                            <label class="radio-inline">
                                <input type="radio" name="mandatory1" id="download_link2" value="0">No
                            </label>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Allow Overlapping Schedule?</label>
                            <label class="radio-inline">
                                <input type="radio" name="allow_overlapping_schedule1" id="allow_overlapping_schedule1" value="1">Yes
                            </label>
                            <label class="radio-inline">
                                <input type="radio" name="allow_overlapping_schedule1" id="allow_overlapping_schedule2" value="0">No
                            </label>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-8">
                        <div class="form-group">
                            <label>Title:</label>
                            <input id="title" name="title[]" class="form-control" autocomplete="off" required>
                        </div>  
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Presenter Name:</label>
                        <input type="text" id="presenter_name" name="presenter_name[]" class="form-control" autocomplete="off" >
                        </div>  
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Presenter Company:</label>
                        <input type="text" id="presenter_company" name="presenter_company[]" class="form-control" autocomplete="off" >
                        </div>  
                    </div>
                    <div class="col-md-4">
                        <div class="form-group"> 
                            <label>Start Date:</label>                                        
                            <select id="start_dateonly" name="start_dateonly[]" class="form-control" required  autocomplete="off" >
                                @foreach ($datez as $d )
                                    <option value="{{$d}}" >{{date("F j,Y", strtotime($d))}}</option>
                                @endforeach
                            </select>
                        </div>                                                
                    </div>
                </div>
                <div class="row"> 
                    <div class="col-md-4">
                        <!--<div class="form-group">
                            <label>Date:</label>
                            <div class='input-group add-on date' >
                                <input id="sched_start_date" name='start_dateonly[]' value="" type="text" class="form-control daterangepicker" autocomplete="off"/>
                                <div class="input-group-btn">
                                    <button class="btn btn-default">
                                        <i class="fa fa-calendar"></i>
                                    </button>
                                </div>
                            </div>        
                        </div>  !-->
                        <div class="form-group"> 
                            <label>End Date:</label>                                        
                            <select id="end_dateonly" name="end_dateonly[]" class="form-control" required  autocomplete="off" >
                                @foreach ($datez as $d )
                                    <option value="{{$d}}" >{{date("F j,Y", strtotime($d))}}</option>
                                @endforeach
                            </select>
                        </div>                                                
                    </div>
                    
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Start Time:</label>
                            <input id="start_time" name="start_time[]" required class="form-control timepicker"  autocomplete="off" >
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>End Time:</label>
                            <input id="end_time" name="end_time[]" required class="form-control timepicker"  autocomplete="off" >
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Location Type:</label>
                            <select id="location" name="location[]" required class="form-control "   >
                                <option>Specify Address</option>
                                <option>Room Number</option>
                            </select>
                        </div>
                    </div> 
                    <div class="col-md-4 room-num"  style="display:none" >
                            <div class="form-group">
                                <label>Room Number:</label>
                                <input type="text" id="room_number" name="room_number[] " class="form-control " autocomplete="off" >
                            </div>
                        </div>
                    <div class="col-md-4 specific-address" style="display:none">
                        <div class="form-group">
                            <label>Location Address:</label>
                            <input id="location_address" name="location_address[]" class="form-control location"  autocomplete="off" >
                        </div>
                    </div>
                    <div class="col-md-4 specific-address"  style="display:none">
                        <div class="form-group">
                            <label>Location Address #2:</label>
                            <input id="location_address2" name="location_address2[]" class="form-control "  autocomplete="off" >
                        </div>
                    </div> 
                </div>
                <div class="row specific-address" style="display:none">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>City:</label>
                            <input id="city" name="city[]" class="form-control "  autocomplete="off" >
                        </div>
                    </div> 
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>State:</label>
                            <input id="state" name="state[]" class="form-control "  autocomplete="off" >
                        </div>
                    </div> 
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>Zip Code:</label>
                            <input type="text" id="zip" name="zip[]" class="form-control "  autocomplete="off" >
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Country:</label>
                            <select id="country" name="country[]" class="form-control "  autocomplete="off" >
                                <option value="">Select a country</option>
                                @foreach($countries as $country)
                                    <option value="{{ $country->id }}">{{ $country->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div> 
                </div>
                <div class="row specific-address" style="display:none">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Telephone:</label>
                            <input id="phone" name="phone[]" class="form-control "  autocomplete="off" >
                        </div>
                    </div> 
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Thumbnail Image:</label>
                            <input type="file" accept="image/x-png,image/gif,image/jpeg"  id="thumb_image"  name="sthumb_image[]" class="form-control thumb_image"  autocomplete="off" >
                            <input type="hidden" id="thumb_image"  name="thumb_image_id[]" value="" >
                        </div>  
                    </div>
                    <div class="col-md-4" id="thumb-preview">
                        <div class="photo-preview" >
                            <div class="photo-image">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Description:</label>
                            <div class="description_cntr">
                                <textarea id="description" name="description[]" class="form-control description"  autocomplete="off" ></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <label>Directions Button:</label>                                                        
                        <!--<div class="form-group">                                                        
                            <div class="radio-inline">
                                <label>
                                    <input type="radio" name="directions_button1" id="directions_button1" value="1" >Yes
                                </label>
                            </div>
                            <div class="radio-inline">
                                <label>
                                    <input type="radio" name="directions_button1" id="directions_button2" value="0">No
                                </label>
                            </div>
                        </div>!-->
                        <input id="directions_button" name="directions_button[]" class="form-control " placeholder="http://"  autocomplete="off" >
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Website Button(Optional):</label>
                            <input id="website_url" name="website_url[]" class="form-control " placeholder="http://"  autocomplete="off" >
                        </div>
                    </div>  
                </div>  
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label >Event Registration Fee: <i class="fa fa-info-circle" title="Leave empty if FREE event"></i></label>
                            <input id="price" name="price[]"  class="form-control "  autocomplete="off" >
                        </div>
                    </div>  
                </div>
                    <input type="hidden" id="schedule_ids" name="schedule_ids[]"  value="0" >
            </div>
        </div>
    </div>
    <!-- /. create events -->  
    <form id="eventscheduleform" enctype="multipart/form-data" method="post" >      
        <div class="row" style="margin-top:20px">
            <div class="col-md-12">      
                <!-- start step 2 Event Schedule!-->
                <div class="tab">
                    <input type="hidden" name="from" value="create">
                    @csrf 
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Banner Image(Max File Size - 2MB):</label>
                                <input type="file" id="banner_image" name="banner_image" class="form-control banner_image" accept="image/x-png,image/gif,image/jpeg"   autocomplete="off" >
                            </div>  
                        </div>
                        <div class="col-md-4" id="banner-preview">
                            @if(!empty($banner_image))
                                <div class="photo-preview" >
                                    <div class="photo-image">
                                        <img data-dz-thumbnail=""  src="{{$banner_image}}" style="width:120px">
                                        <button type="button" id="deleteBanner" class="deleteBanner btn " onclick="removeBanner('event_schedule')"><i class="fa fa-close"></i> Remove</button>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div> 
                    @foreach ($data['eventscheds'] as $key => $es)
                        <div id="event_schedule{{$key +1}}" class="event_schedule">
                            <div class="panel panel-default">
                                <div class="panel-heading event-heading" data-toggle="collapse" data-target="#panel{{$key +1}}" style="cursor: pointer">
                                    <h3 class="panel-title" >Itinerary #{{$key +1}}  
                                        :<span id="iti_title" style="font-weight:bold">{{$es['title']}}</span>
                                        <span id="delbtnhldr">
                                            <button type="button" onclick="deleteEventSchedule({{$es['id']}},'event_schedule{{$key +1}}')" id="deleteSchedule" class="tn btn-default btn-outline pull-right"><i class="fa fa-minus-circle"></i> Delete </button>
                                        </span>
                                    </h3>
                                </div>
                                <div class="panel-body" collapse" id="panel{{$key +1}}">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Download Link?</label>
                                                <label class="radio-inline">
                                                    <input type="radio" {{ $es['download_link'] == 1 ? ' checked' : '' }} name="download_link{{$key +1}}" id="download_link1" value="1">Yes
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" {{ $es['download_link'] == 0 ? ' checked' : '' }} name="download_link{{$key +1}}" id="download_link2" value="0">No
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="col-md-3">
                                                <label>Upload File:</label>
                                            </div> 
                                            <div class="col-md-6">
                                                <input type="file" id="itinerary_file" name="itinerary_file[]" class="form-control "   autocomplete="off" >
                                            </div>  
                                        </div>
                                        <div class="col-md-2">
                                            @if(!empty($es['itinerary_file']))
                                               <a href="{{$es['itinerary_file']}}" target="_blank"><i class="fa fa-file"></i> Download Itinerary File</a>
                                            @endif
                                        </div>
                                    </div>
                                    <br/>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Mandatory? </label>
                                                <label class="radio-inline">
                                                    <input type="radio" {{ $es['mandatory'] == 1 ? ' checked' : '' }} name="mandatory{{$key +1}}" id="mandatory1" value="1">Yes
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" {{ $es['mandatory'] == 0 ? ' checked' : '' }} name="mandatory{{$key +1}}" id="mandatory2" value="0">No
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Allow Overlapping Schedule? </label>
                                                <label class="radio-inline">
                                                    <input type="radio" {{ $es['allow_overlapping_schedule'] == 1 ? ' checked' : '' }} name="allow_overlapping_schedule{{$key +1}}" id="allow_overlapping_schedule1" value="1">Yes
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" {{ $es['allow_overlapping_schedule'] == 0 ? ' checked' : '' }} name="allow_overlapping_schedule{{$key +1}}" id="allow_overlapping_schedule2" value="0">No
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-8">
                                            <div class="form-group">
                                                <label>Title:</label>
                                            <input type="text" id="title" name="title[]" value="{{$es['title']}}" class="form-control" autocomplete="off" >
                                            </div>  
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Presenter Name:</label>
                                            <input type="text" id="presenter_name" name="presenter_name[]" value="{{$es['presenter_name']}}" class="form-control" autocomplete="off" >
                                            </div>  
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Presenter Company:</label>
                                            <input type="text" id="presenter_company" name="presenter_company[]" value="{{$es['presenter_company']}}" class="form-control" autocomplete="off" >
                                            </div>  
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group"> 
                                                <label>Start Date:</label>                                        
                                                <select id="start_dateonly" name="start_dateonly[]" class="form-control"  autocomplete="off" >
                                                    @foreach ($datez as $d )
                                                        <option value="{{$d}}" {{ date('Y-m-d',strtotime($d)) == date('Y-m-d',strtotime($es['start_date'])) ? ' selected' : '' }} >{{date("F j,Y", strtotime($d))}}</option>
                                                    @endforeach
                                                </select>
                                            </div> 
                                        </div>  
                                    </div>
                                    <div class="row"> 
                                        <div class="col-md-4">
                                            <!--<div class="form-group">
                                                <label>Date:</label>
                                                <div class='input-group add-on date ' >
                                                    <input  type="text" id="sched_start_date" name='start_dateonly[]' value="{{date('m/d/Y',strtotime($es['start_date']))}} - {{date('m/d/Y',strtotime($es['end_date']))}}" type="text" class="form-control daterangepicker" autocomplete="off"/>
                                                    <div class="input-group-btn">
                                                        <button class="btn btn-default">
                                                            <i class="fa fa-calendar"></i>
                                                        </button>
                                                    </div>
                                                </div>        
                                            </div>!-->
                                            <div class="form-group"> 
                                                <label>End Date:</label>                                        
                                                <select id="end_dateonly" name="end_dateonly[]" class="form-control"  autocomplete="off" >
                                                    @foreach ($datez as $d )
                                                        <option value="{{$d}}" {{ date('Y-m-d',strtotime($d)) == date('Y-m-d',strtotime($es['end_date'])) ? ' selected' : '' }} >{{date("F j,Y", strtotime($d))}}</option>
                                                    @endforeach
                                                </select>
                                            </div>                                                     
                                        </div>
                                        <!--<div class="col-md-4">
                                            <div class="form-group">
                                                <label>End Date:</label>
                                                <div class='input-group add-on date datepicker' >
                                                    <input  type="text" id="sched_end_date" name='end_dateonly[]' value="{{date('m/d/Y',strtotime($es['end_date']))}}" type="text" class="form-control date-picker" autocomplete="off" />
                                                    <div class="input-group-btn">
                                                        <button class="btn btn-default">
                                                            <i class="fa fa-calendar"></i>
                                                        </button> 
                                                    </div>
                                                </div>
                                            </div>
                                        </div>!-->
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Start Time:</label>
                                                <input  type="text" id="start_time" name="start_time[]" value="{{date('h:i A',strtotime($es['start_date']))}}" class="form-control timepicker"  autocomplete="off" >
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>End Time:</label>
                                                <input  type="text" id="end_time" name="end_time[]" value="{{date('h:i A',strtotime($es['end_date']))}}" class="form-control timepicker"  autocomplete="off" >
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Location:</label>
                                                <select id="location" name="location[]" required class="form-control " >
                                                    <option {{ $es['location'] == "Specify Address" ? ' selected' : '' }}>Specify Address</option>
                                                    <option {{ $es['location'] == "Room Number" ? ' selected' : '' }}>Room Number</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-4 room-num"  {{ $es['location'] == "Room Number" ? '' : 'style="display:none"' }} >
                                            <div class="form-group">
                                                <label>Room Number:</label>
                                                <input type="text" id="room_num" name="room_number[] " value="{{$es['room_number']}}" class="form-control " autocomplete="off" >
                                            </div>
                                        </div>
                                        <div class="col-md-4 specific-address"  {{ $es['location'] == "Specify Address" ? ' ' : 'style="display:none"' }} >
                                            <div class="form-group">
                                                <label>Location Address:</label>
                                                <input type="text" id="location_address" name="location_address[] " value="{{$es['location_address']}}" class="form-control " autocomplete="off" >
                                            </div>
                                        </div>
                                        <div class="col-md-4 specific-address"  {{ $es['location'] == "Specify Address" ? ' ' : 'style="display:none"' }}>
                                            <div class="form-group">
                                                <label>Location Address #2:</label>
                                                <input type="text" id="location_address2" name="location_address2[] " value="{{$es['location_address2']}}" class="form-control "  autocomplete="off" >
                                            </div>
                                        </div> 
                                    </div>
                                    <div class="row specific-address"  {{ $es['location'] == "Specify Address" ? ' ' : 'style="display:none"' }}>
                                        <div class="col-md-4 ">
                                            <div class="form-group">
                                                <label>City:</label>
                                                <input type="text" id="city" name="city[]" class="form-control " value="{{$es['city']}}"  autocomplete="off" >
                                            </div>
                                        </div> 
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label>State:</label>
                                                <input type="text" id="state" name="state[]" class="form-control " value="{{$es['state']}}"  autocomplete="off" >
                                            </div>
                                        </div> 
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label>Zip Code:</label>
                                                <input type="text" id="zip" name="zip[]" class="form-control " value="{{$es['zip']}}"  autocomplete="off" >
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Country:</label>
                                                <select id="country" name="country[]" class="form-control "  autocomplete="off" >
                                                    <option value="">Select a country</option>
                                                    @foreach($countries as $country)
                                                        <option value="{{ $country->id }}" {{ $es['country'] == $country->id ? ' selected' : '' }}>{{ $country->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div> 
                                    </div>
                                    <div class="row specific-address" style="display:none">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Telephone:</label>
                                                <input id="phone" name="phone[]" value="{{$es['phone']}}" class="form-control "  autocomplete="off" >
                                            </div>
                                        </div> 
                                    </div>
                                    <script>
                                        var nextnum={{$key +1}};
                                        if($("#event_schedule{{$key +1}} #location").val()=="Specify Address"){
                                            $("#event_schedule{{$key +1}} .specific-address").show();
                                            $("#event_schedule{{$key +1}} .room-num").hide();
                                        }
                                        else{
                                            $("#event_schedule{{$key +1}} .room-num").show();
                                            $("#event_schedule{{$key +1}} .specific-address").hide();
                                        }
                                        $("#event_schedule{{$key +1}} #location").change(function(){
                                            console.log("herezz"+$(this).val());
                                            if($(this).val()=="Specify Address"){
                                                $("#event_schedule{{$key +1}} .specific-address").show();
                                                $("#event_schedule{{$key +1}} .room-num").hide();
                                            }
                                            else{
                                                $("#event_schedule{{$key +1}} .room-num").show();
                                                $("#event_schedule{{$key +1}} .specific-address").hide();
                                            }
                                        });
                                        $("#event_schedule{{$key +1}} #title").keyup(function(){
                                            $("#event_schedule{{$key +1}} #iti_title").html($(this).val());
                                        });
                                    </script>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Thumbnail Image:</label>
                                                <input type="file" accept="image/x-png,image/gif,image/jpeg"  id="thumb_image"  name="sthumb_image[]" class="form-control thumb_image"  autocomplete="off" >
                                                <input type="hidden" id="thumb_image"  name="thumb_image_id[]" value="{{$es['thumb_image']}}" >
                                            </div>  
                                        </div>
                                        <div class="col-md-4" id="thumb-preview">
                                            <div class="photo-preview" >
                                                <div class="photo-image">
                                                    @if(!empty($es['thumb_image']))
                                                        <img data-dz-thumbnail=""  src="{{$es['thumb_image']}}" style="width:120px">
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <script>
                                        $("#event_schedule{{$key +1}} #thumb_image").change(function() {
                                            readURL(this,"#event_schedule{{$key +1}} #thumb-preview");
                                        });
                                    </script>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label>Description:</label>
                                                <div class="description_cntr">
                                                    <textarea id="description" name="description[]" class="form-control description"  autocomplete="off" >{{$es['description']}}</textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <label>Directions Button:</label>                                                        
                                            <!--<div class="form-group">                                                        
                                                <div class="radio-inline">
                                                    <label>
                                                        <input type="radio"  {{ $es['directions_button'] == 1 ? ' checked' : '' }} name="directions_button{{$key +1}}" id="directions_button1" value="1" >Yes
                                                    </label>
                                                </div>
                                                <div class="radio-inline">
                                                    <label>
                                                        <input type="radio" {{ $es['directions_button'] == 0 ? ' checked' : '' }} name="directions_button{{$key +1}}" id="directions_button2" value="0">No
                                                    </label>
                                                </div>
                                            </div>!-->
                                             <input id="directions_button" name="directions_button[]" value="{{$es['directions_button']}}" class="form-control " placeholder="http://"  autocomplete="off" >
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Website Button(Optional):</label>
                                                <input id="website_url" name="website_url[]" value="{{$es['website_url']}}" class="form-control " placeholder="http://"  autocomplete="off" >
                                            </div>
                                        </div>  
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label >Event Registration Fee: <i class="fa fa-info-circle" title="Leave empty if FREE event"></i></label>
                                                <input id="price" name="price[]" value="{{$es['price']}}" class="form-control "   autocomplete="off" >
                                            </div>
                                        </div>  
                                    </div>
                                    <input type="hidden" id="schedule_ids" name="schedule_ids[]"  value="{{$es['id']}}" >
                                </div>
                            </div>
                        </div>
                        <input type="hidden" name="orig_schedule_ids[]"  value="{{$es['id']}}" >        
                    @endforeach
                    <div class="appender"></div>
                    <input name="eventsched_cnt" id="eventsched_cnt" value="{{count($data['eventscheds'])}}" type="hidden">
                                 
                    <div class="row">
                        <div class="col-md-12">
                            <div class="panel panel-default">	  
                                <div class="panel-heading">
                                    <h3 class="panel-title"><span  class="pull-left">ITINERARY ITEM</span> &nbsp; <button onclick="cloneSchedule()" id="add_moreSchedule" class="tn btn-default btn-outline pull-right"><i class="fa fa-plus-circle"></i> ADD </button></a>                                
                                </div>     
                            </div>                        
                        </div>
                    </div>
                </div>
                <!-- end step 2 Event Schedule!-->  
                        
                <div class="row">
                    <div class="col-md-2">
                        <button class="btn " type="button" onclick="cancelEvent({{$event->id}})">Cancel</button>
                    </div>
                    <div class="col-md-6" style="text-align:center;"> 
                        <div class="form-group">        
                            <label>Step {{$step}} of {{$total}}   </label>                                                
                            <div class="radio-inline bottomcounter">
                                @for($x=1;$x<$total+1;$x++)                    
                                    <span class="step @if ($step == $x) active @endif" ></span>
                                @endfor
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <button class="btn btn-block btn-blue" id="prevBtn" type="button" onclick="goBack()" >Back</button>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" id="nextBtn" class="btn btn-block btn-blue">Next</button>
                    </div>
                </div> 
            </div>
                <input type="hidden" name="event_id" id="event_id" value="{{$event->id}}" > 
                <input type="hidden" name="step" id="step" value="{{$step}}"> 
        </div>
        <!-- /.create events  -->
    </form>      
</div>

@section('css')
    <link rel="stylesheet" href="{{ asset('/css/events.css') }}">
@stop

@section('js')
<script src="{{ asset('/js/jquery/events.jquery.js') }}"></script>
@stop
@stop
