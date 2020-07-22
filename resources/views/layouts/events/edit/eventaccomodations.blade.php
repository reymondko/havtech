@extends('layouts.events.edit.editEvent_template')
@section('content')  
<meta name="_token" content="{{ csrf_token() }}" /> 
<div id="accomodations0" class="accomodations" style="display:none">
    <div class="panel panel-default">
        <div class="panel-heading event-heading">
            <h3 class="panel-title ">Accommodations #<span class="itinum"></span>
                :<span id="iti_title" style="font-weight:bold"></span>
                <span id="delbtnhldr">
                </span>
            </h3>
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Hotel:</label>
                        <input id="hotel" name="hotel[]" class="form-control "  autocomplete="off" required>
                    </div>
                </div> 
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Name:</label>
                        <input id="name" name="name[]" class="form-control "  autocomplete="off" >
                    </div>
                </div> 
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Confirmation #:</label>
                        <input id="confirmation_number" name="confirmation_number[]" class="form-control "  autocomplete="off" >
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Room #:</label>
                        <input id="room_number" name="room_number[]" class="form-control "  autocomplete="off" >
                    </div>
                </div>
                <!--<div class="col-md-4">
                    <div class="form-group">
                        <label>Location:</label>
                        <input id="location" name="location[]" class="form-control "  autocomplete="off" >
                    </div>
                </div> !-->
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Location Address:</label>
                        <input id="location_address" name="location_address[]" class="form-control "  autocomplete="off" >
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Location Address 2:</label>
                        <input id="location_address2" name="location_address2[]" class="form-control "  autocomplete="off" >
                    </div>
                </div>
            </div>
            <div class="row">
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
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Zip Code:</label>
                        <input id="zip" name="zip[]" class="form-control "  autocomplete="off" >
                    </div>
                </div>                                
            </div>
            <div class="row">
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
            <input type="hidden" id="acc_ids" name="acc_ids[]"  value="0" >
        </div>
    </div>
</div>
<form id="eventaccomodationsform" enctype="multipart/form-data" method="post" >                               
    @csrf
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header" id="event-title">{{$data['event_name']}}</h1>
        </div>
        <div class="col-md-1 pull-right update-btn-top">
                <button class="btn btn-default btn-block btn-blue" onclick="$('#eventaccomodationsform').submit();" id="update_event" >Update</button>
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
                        <button type="button" id="deleteBanner" class="deleteBanner btn " onclick="removeBanner('event_accomodations')"><i class="fa fa-close"></i> Remove</button>
                    </div>
                </div>
            @endif
        </div>  
    </div>
    
    @foreach ($data['eventaccs'] as $key => $es)                  
        <div id="accomodations{{$key +1}}" class="accomodations">
            <div class="panel panel-default">
                <div class="panel-heading event-heading" data-toggle="collapse" data-target="#panel{{$key +1}}" aria-expanded="false">
                    <h3 class="panel-title">Accommodations #{{$key +1}}
                        :<span id="iti_title" style="font-weight:bold">{{$es['hotel']}}</span>
                        <span id="delbtnhldr">
                            <button type="button" onclick="deleteEventAccomodation({{$es['id']}},'accomodations{{$key +1}}')" id="deleteEventData" class="tn btn-default btn-outline pull-right deleteEventItem"><i class="fa fa-minus-circle"></i> Delete </button>
                        </span>
                    </h3>
                </div>
                <div class="panel-body collapse" id="panel{{$key +1}}">                
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Hotel:</label>
                                <input id="hotel" value="{{$es['hotel']}}" name="hotel[]" class="form-control "  autocomplete="off" required>
                            </div>
                        </div> 
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Name:</label>
                                <input id="name" value="{{$es['name']}}" name="name[]" class="form-control "  autocomplete="off" >
                            </div>
                        </div> 
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Confirmation #:</label>
                                <input id="confirmation_number" value="{{$es['confirmation_number']}}" name="confirmation_number[]" class="form-control "  autocomplete="off" >
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Room #:</label>
                                <input id="room_number" value="{{$es['room_number']}}" name="room_number[]" class="form-control "  autocomplete="off" >
                            </div>
                        </div>
                        <!--<div class="col-md-4">
                            <div class="form-group">
                                <label>Location:</label>
                                <input id="location" name="location[]" value="{{$es['location']}}" class="form-control "  autocomplete="off" >
                            </div>
                        </div>  !-->
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Location Address:</label>
                                <input id="location_address" value="{{$es['address1']}}" name="location_address[]" class="form-control "  autocomplete="off" >
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Location Address 2:</label>
                                <input id="location_address2" value="{{$es['address2']}}" name="location_address2[]" class="form-control "  autocomplete="off" >
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>City:</label>
                                <input id="city" name="city[]" value="{{$es['city']}}" class="form-control "  autocomplete="off" >
                            </div>
                        </div> 
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>State:</label>
                                <input id="state" name="state[]" value="{{$es['state']}}" class="form-control "  autocomplete="off" >
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Zip Code:</label>
                                <input id="zip" name="zip[]" value="{{$es['zip']}}" class="form-control "  autocomplete="off" >
                            </div>
                        </div> 
                    </div> 
                    <div class="row">
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
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Telephone:</label>
                                <input id="phone" value="{{$es['phone']}}" name="phone[]" class="form-control "  autocomplete="off" >
                            </div>
                        </div>                                
                    </div>
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
                                <input id="website_url" value="{{$es['website_url']}}" name="website_url[]" class="form-control " placeholder="http://"  autocomplete="off" >
                            </div>
                        </div>  
                    </div>            
                </div>
                <input type="hidden" id="acc_ids" name="acc_ids[]"  value="{{$es['id']}}" >
            </div>
        </div>
        
        <script>
            $("#accomodations{{$key +1}} #hotel").keyup(function(){
                $("#accomodations{{$key +1}} #iti_title").html($(this).val());
            });
        </script>
    @endforeach
    <div class="appender"></div>
    <input name="eventsched_cnt" id="eventsched_cnt" value="{{count($data['eventaccs'])}}" type="hidden">
</form>                     
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">	  
            <div class="panel-heading">
                <h3 class="panel-title"><span  class="pull-left">Accomodations</span> &nbsp; <button onclick="cloneAccomodations()" id="add_more" class="tn btn-default btn-outline pull-right"><i class="fa fa-plus-circle"></i> ADD </button></a>                                
            </div>     
        </div>                        
    </div>
</div>
</div>   
        
<div class="row" style="margin-bottom: 20px">                     
    <div class="col-md-1 pull-right">
        <button class="btn btn-default btn-block btn-blue" onclick="$('#eventaccomodationsform').submit();" id="update_event" >Update</button>
    </div>
</div>   
                
@section('css')
<link rel="stylesheet" href="{{ asset('/css/events.css') }}">
@stop

@section('js')
<script src="{{ asset('/js/jquery/edit_events.jquery.js') }}"></script>
@stop        
@stop
            
