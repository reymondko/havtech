@extends('layouts.events.edit.editEvent_template')
@section('content')  
<meta name="_token" content="{{ csrf_token() }}" /> 
<div id="event_dining0" class="event_dining"  style="display:none">
    <input type="hidden" id="e_id" name="e_ids[]"  value="0" >
    <div class="panel panel-default">
        <div class="panel-heading event-heading">
            <h3 class="panel-title">Dining #<span class="itinum"></span> 
                :<span id="iti_title" style="font-weight:bold"></span>
                <span id="delbtnhldr"></span>
            </h3>
        </div>
        <div class="panel-body">
            <div class="row"> 
                <div class="col-md-4">
                    <div class="form-group"> 
                        <label>Dining Type:</label>                                        
                        <select id="dining_type" name="dining_type[]" class="form-control"  autocomplete="off" >
                            @foreach ($data['event_dining'] as $ed )
                                <option value="{{$ed->id}}">{{$ed->name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>                               
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Location:</label>
                        <input id="location" name="location[]" required class="form-control "  autocomplete="off" >
                    </div>
                </div> 
                <div class="col-md-4">
                    <!--<div class="form-group">
                        <label>Date:</label>
                        <div class='input-group add-on date datepicker' >
                            <input id="meal_date" name='meal_date[]' value="" type="text" class="form-control date-picker" autocomplete="off"/>
                            <div class="input-group-btn">
                                <button class="btn btn-default">
                                    <i class="fa fa-calendar"></i>
                                </button>
                            </div>
                        </div>        
                    </div>!-->
                    
                    <div class="form-group"> 
                        <label>Date:</label>                                        
                        <select id="meal_date" name="meal_date[]" required class="form-control"  autocomplete="off" >
                            @foreach ($data['dates'] as $d )
                                <option  value="{{$d}}">{{date("F j,Y", strtotime($d))}}</option>
                            @endforeach
                        </select>
                    </div>                                     
                </div>
            </div>
            <div class="row">
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
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Location Address:</label>
                        <input id="address1" name="address1[]" class="form-control "  autocomplete="off" >
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Location Address #2:</label>
                        <input id="address2" name="address2[]" class="form-control "  autocomplete="off" >
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
                        <label>Zip Code:</label>
                        <input id="zip" name="zip[]" class="form-control "  autocomplete="off" >
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
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Telephone:</label>
                        <input id="phone" name="phone[]" class="form-control "  autocomplete="off" >
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
        </div>
    </div>
</div>
<form id="eventdiningform" enctype="multipart/form-data" method="post" >                               
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header" id="event-title">{{$data['event_name']}}</h1>
        </div>   
        <div class="col-md-1 pull-right update-btn-top">
            <button class="btn btn-default btn-block btn-blue" onclick="$('#eventdiningform').submit();" id="update_event" >Update</button>
        </div>         
    </div>
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
                        <button type="button" id="deleteBanner" class="deleteBanner btn " onclick="removeBanner('event_dining')"><i class="fa fa-close"></i> Remove</button>
                    </div>
                </div>
            @endif
        </div>  
    </div>
    @csrf
   
    @foreach ($data['eventdata'] as $key => $edata)  
        <div id="event_dining{{$key +1}}" class="event_dining">
            <input type="hidden" id="e_id" name="e_ids[]"  value="{{$edata['id']}}" >
            <div class="panel panel-default">
                <div class="panel-heading event-heading" data-toggle="collapse" data-target="#panel{{$key +1}}" style="cursor: pointer">
                    <h3 class="panel-title">Dining #{{$key +1}}  
                        :<span id="iti_title" style="font-weight:bold">{{$edata['location']}}</span>
                        <span id="delbtnhldr">
                            <button type="button" onclick="deleteEventDining({{$edata['id']}},'event_dining{{$key +1}}')" id="event_dining" class="tn btn-default btn-outline pull-right deleteEventItem"><i class="fa fa-minus-circle"></i> Delete </button>
                        </span>
                    </h3>
                </div>
                <div class="panel-body collapse" id="panel{{$key +1}}">
                    <div class="row"> 
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Dining Type:</label>                                        
                                <select id="dining_type" name="dining_type[]" class="form-control"  autocomplete="off" >
                                    @foreach ($data['event_dining'] as $ed )
                                        <option value="{{$ed->id}}" {{ $edata['dining_type'] == $ed->id ? ' selected' : '' }}>{{$ed->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>                               
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Location:</label>
                                <input id="location" value="{{$edata['location']}}" name="location[]" required class="form-control "  autocomplete="off" >
                            </div>
                        </div> 
                        <div class="col-md-4">
                            <!--<div class="form-group">
                                <label>Date:</label>
                                <div class='input-group add-on date datepicker' >
                                    <input  value="{{date('m/d/Y',strtotime($edata['meal_date']))}}" id="meal_date" name='meal_date[]' type="text" class="form-control date-picker" autocomplete="off"/>
                                    <div class="input-group-btn">
                                        <button class="btn btn-default">
                                            <i class="fa fa-calendar"></i>
                                        </button>
                                    </div>
                                </div>        
                            </div> !-->
                            <div class="form-group"> 
                                <label>Date:</label>                                        
                                <select id="meal_date" name="meal_date[]" required class="form-control"  autocomplete="off" >
                                    @foreach ($data['dates'] as $d )
                                        <option {{ date("F j,Y",strtotime($edata['meal_date'])) == $d ? ' selected' : '' }} value="{{$d}}">{{date("F j,Y", strtotime($d))}}</option>
                                    @endforeach
                                </select>
                            </div>                                    
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Start Time:</label>
                                <input id="start_time" value="{{date('h:i A',strtotime($edata['start_time']))}}" name="start_time[]" required class="form-control timepicker"  autocomplete="off" >
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>End Time:</label>
                                <input id="end_time" value="{{date('h:i A',strtotime($edata['end_time']))}}" name="end_time[]" required class="form-control timepicker"  autocomplete="off" >
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Location Address:</label>
                                <input id="address1" value="{{$edata['address1']}}" name="address1[]" class="form-control "  autocomplete="off" >
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Location Address #2:</label>
                                <input id="address2" value="{{$edata['address2']}}" name="address2[]" class="form-control "  autocomplete="off" >
                            </div>
                        </div> 
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>City:</label>
                                <input id="city" value="{{$edata['city']}}" name="city[]" class="form-control "  autocomplete="off" >
                            </div>
                        </div> 
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>State:</label>
                                <input id="state" value="{{$edata['state']}}" name="state[]" class="form-control "  autocomplete="off" >
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Zip Code:</label>
                                <input id="zip" value="{{$edata['zip']}}" name="zip[]" class="form-control "  autocomplete="off" >
                            </div>
                        </div> 
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Country:</label>
                                <select id="country" name="country[]" class="form-control "  autocomplete="off" >
                                    <option value="">Select a country</option>
                                    @foreach($countries as $country)
                                        <option value="{{ $country->id }}" {{ $edata['country'] == $country->id ? ' selected' : '' }}>{{ $country->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>  
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Telephone:</label>
                                <input id="phone" value="{{$edata['phone']}}" name="phone[]" class="form-control "  autocomplete="off" >
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Description:</label>
                                <div class="description_cntr">
                                    <textarea id="description" name="description[]" class="form-control description"  autocomplete="off" >{{$edata['description']}}</textarea>
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
                                        <input type="radio"  {{ $edata['directions_button'] == 1 ? ' checked' : '' }} name="directions_button{{$key +1}}" id="directions_button1" value="1" >Yes
                                    </label>
                                </div>
                                <div class="radio-inline">
                                    <label>
                                        <input type="radio" {{ $edata['directions_button'] == 0 ? ' checked' : '' }} name="directions_button{{$key +1}}" id="directions_button2" value="0">No
                                    </label>
                                </div>
                            </div>!-->
                            <input id="directions_button" name="directions_button[]" value="{{$edata['directions_button']}}" class="form-control " placeholder="http://"  autocomplete="off" >
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Website Button(Optional):</label>
                                <input id="website_url" value="{{$edata['website_url']}}" name="website_url[]" class="form-control " placeholder="http://"  autocomplete="off" >
                            </div>
                        </div>  
                    </div>
                </div>
            </div>
        </div>      
        <script>
            $("#event_dining{{$key +1}} #location").keyup(function(){
                $("#event_dining{{$key +1}} #iti_title").html($(this).val());
            });
        </script>      
    @endforeach
    <div class="appender"></div>
    <input name="form_cnt" id="form_cnt" value="{{count($data['eventdata'])}}" type="hidden">


</form>                     
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">	  
            <div class="panel-heading">
                <h3 class="panel-title"><span  class="pull-left">DINING</span> &nbsp; 
                <button onclick="cloneDining()" type="button" id="add_moreDining" class="btn btn-default btn-outline pull-right"><i class="fa fa-plus-circle"></i> ADD </button></a>                                                            
            </div>     
        </div>                        
    </div>
</div>
            
<div class="row" style="margin-bottom: 20px">                     
    <div class="col-md-1 pull-right">
        <button class="btn btn-default btn-block btn-blue" onclick="$('#eventdiningform').submit();" id="update_event" >Update</button>
    </div>
</div>
@section('css')
<link rel="stylesheet" href="{{ asset('/css/events.css') }}">
@stop

@section('js')
<script src="{{ asset('/js/jquery/edit_events.jquery.js') }}"></script>
@stop        
@stop