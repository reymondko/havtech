@extends('layouts.dashboard')
@section('page_heading',"$event->event_name")
@section('section')     
           
<meta name="_token" content="{{ csrf_token() }}" /> 
<div class="col-md-12">
    <div class="row">
        <div class="col-md-9">
            <h1 class="form-title">Travel Host & Information  (Step {{$step}})</h1>
        </div>
            <!-- Circles which indicates the steps of the form: -->
        <div class="col-md-3 pull-right"> 
            <div class="form-group" style="margin-top:40px">        
                <label>Step {{$step}} of {{$total}}   </label>                                                
                <div class="radio-inline bottomcounter">
                    @for($x=1;$x<$total+1;$x++)                    
                        <span class="step @if ($step == $x) active @endif" ></span>
                    @endfor
                </div>
            </div>
        </div>
    </div>
    <div id="event_travelhost0" class="event_travelhost"  style="display:none">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">Travel Host #<span class="itinum"></span> 
                    :<span id="iti_title" style="font-weight:bold"></span>
                    <span id="delbtnhldr">
                    </span>
                </h3>
            </div>
            <div class="panel-body">                         
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Travel Host Name:</label>
                            <input id="host_name" name="host_name[]" required class="form-control "  autocomplete="off" >
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
                        <div class="form-group">
                            <label>Email Address:</label>
                            <input id="email" name="email[]" class="form-control " autocomplete="off" >
                        </div>
                    </div>  
                </div>
            </div>
            <input type="hidden" id="travelhost_id" name="travelhost_id[]"  value="0" >
        </div>
    </div>
    <div id="event_information0" class="event_information" style="display:none">
        <div class="panel panel-default">
            <div class="panel-heading event-heading">
                <h3 class="panel-title">Event Information #<span class="itinum"></span> 
                    :<span id="iti_title" style="font-weight:bold"></span>
                    <span id="delbtnhldr"></span>
                </h3>
            </div>
            <div class="panel-body">
                <!--<div class="row">
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
                            <input type="file" id="event_info_file" name="event_info_file[]" class="form-control "    autocomplete="off" >
                        </div>  
                    </div>
                </div>!-->
                <div class="row">                 
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>FAQ Question:</label>
                            <input id="faq_title" name="faq_title[]" class="form-control "  autocomplete="off" >
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>FAQ Description:</label>
                            <div class="description_cntr">
                                <textarea id="faq_answer" name="faq_answer[]" class="form-control description"  autocomplete="off" ></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <input type="hidden" id="faq_id" name="faq_id[]"  value="0" >
            </div>
        </div>
    </div>  
    <form id="travelhostinformationform" enctype="multipart/form-data" method="post" > 
        <!-- /. create events -->        
        <div class="row" style="margin-top:20px">
            <div class="col-md-12">           
                <!-- start Event Travel host!-->
                <div class="tab">
                    <input type="hidden" name="from" value="create">                           
                    @csrf 
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Banner Image(Max File Size - 2MB):</label>
                                <input type="file" id="banner_image" name="banner_image[]" class="form-control banner_image" accept="image/x-png,image/gif,image/jpeg"   autocomplete="off" >
                            </div>
                        </div>
                        <div class="col-md-4" id="banner-preview">
                            @if(!empty($banner_image))
                                <div class="photo-preview" >
                                    <div class="photo-image">
                                        <img data-dz-thumbnail=""  src="{{$banner_image}}" style="width:120px">
                                        <button type="button" id="deleteBanner" class="deleteBanner btn " onclick="removeBanner('event_travel_hosts')"><i class="fa fa-close"></i> Remove</button>
                                    </div>
                                </div>
                            @endif
                        </div> 
                    </div>
                    @foreach ($data['eventdata'] as $key => $es) 
                        <div id="event_travelhost{{$key +1}}" class="event_travelhost">
                            <div class="panel panel-default">
                                <div class="panel-heading event-heading" data-toggle="collapse" data-target="#panel{{$key +1}}" style="cursor: pointer">
                                    <h3 class="panel-title">Travel Host #{{$key +1}}  
                                        : <span id="iti_title" >{{$es['host_name']}}</span>
                                        <span id="delbtnhldr">
                                            <button type="button" onclick="deleteTravelHost({{$es['id']}},'event_travelhost{{$key +1}}')"  class="tn btn-default btn-outline pull-right" id="deleteSchedule"><i class="fa fa-minus-circle"></i> Delete </button>
                                        </span>
                                    </h3>
                                </div>
                                <div class="panel-body " id="panel{{$key +1}}">                  
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Travel Host Name:</label>
                                                <input id="host_name" value="{{$es['host_name']}}" name="host_name[]" required class="form-control "  autocomplete="off" >
                                            </div>
                                        </div> 
                                    </div>
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
                                        $("#event_travelhost{{$key +1}} #thumb_image").change(function() {
                                            console.log("dri");
                                            readURL(this,"#event_travelhost{{$key +1}} #thumb-preview");
                                        });
                                    </script>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label>Description:</label>
                                                <div class="description_cntr">
                                                    <textarea id="description1" name="description[]" class="form-control description"  autocomplete="off" >{{$es['description']}}</textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <label>Email Button?</label>                                                
                                            <div class="form-group">                                                        
                                                <div class="radio-inline">
                                                    <label>
                                                        <input type="radio" {{ $es['email_button'] == 1 ? ' checked' : '' }} name="email_button{{$key +1}}" id="email_button" value="1" >Yes
                                                    </label>
                                                </div>
                                                <div class="radio-inline">
                                                    <label>
                                                        <input type="radio" {{ $es['email_button'] == 0 ? ' checked' : '' }} name="email_button{{$key +1}}" id="email_button" value="0">No
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Email Address:</label>
                                                <input id="email" value="{{$es['email']}}" name="email[]" class="form-control " autocomplete="off" >
                                            </div>
                                        </div>  
                                    </div>
                                    <input type="hidden" id="travelhost_id" name="travelhost_id[]"  value="{{$es['id']}}" >
                                </div>
                            </div>
                        </div>    
                        <script>
                            $("#event_travelhost{{$key +1}} #host_name").keyup(function(){
                                $("#event_travelhost{{$key +1}} #iti_title").html($(this).val());
                            });
                        </script> 
                    @endforeach
                    <div class="travelhostappender"></div>
                    <input name="travelhost_cnt" id="travelhost_cnt" value="{{count($data['eventdata'])}}" type="hidden">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="panel panel-default">	  
                                <div class="panel-heading">
                                    <h3 class="panel-title"><span  class="pull-left">TRAVEL HOST</span> &nbsp; 
                                        <button type="button" onclick="cloneTravelHost()" id="cloneTravelhost" class="btn btn-default btn-outline pull-right clone_btn"><i class="fa fa-plus-circle"></i> ADD </span>
                                </div>     
                            </div>                        
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Download Link?</label>
                                <label class="radio-inline">
                                    <input type="radio" {{ $download_link == 1 ? ' checked' : '' }} name="download_link" id="download_link1" value="1">Yes
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" {{ $download_link == 0 ? ' checked' : '' }} name="download_link" id="download_link2" value="0">No
                                </label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="col-md-2">
                                <label>Upload File:</label>
                            </div> 
                            <div class="col-md-5">
                                <input type="file" id="event_info_file" name="event_info_file" class="form-control"  autocomplete="off" >
                            </div>  
                            <div class="col-md-4">
                                @if(!empty($event_info_file))
                                   <a href="{{$event_info_file}}" target="_blank" download="{{$event_info_file}}"><i class="fa fa-file"></i> Download Information File</a>
                                @endif
                            </div>
                        </div>
                    </div>
                    @foreach ($data['eventdata2'] as $key2 => $es2) 
                        <div id="event_information{{$key2 +1}}" class="event_information">
                            <div class="panel panel-default">
                                <div class="panel-heading event-heading" data-toggle="collapse" data-target="#panel2{{$key2 +1}}" style="cursor: pointer">
                                    <h3 class="panel-title">Event Information #{{$key2 +1}}  
                                        : <span id="iti_title" >{{$es2['faq_title']}}</span>
                                        <span id="delbtnhldr">
                                            <button type="button" onclick="deleteEventInfo({{$es2['id']}},'event_information{{$key2 +1}}')" id="deleteSchedule" class="tn btn-default btn-outline pull-right"><i class="fa fa-minus-circle"></i> Delete </button>
                                        </span>
                                    </h3>
                                </div>
                                <div class="panel-body collapse" id="panel2{{$key2 +1}}">
                                    <!--<div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Download Link?</label>
                                                <label class="radio-inline">
                                                    <input type="radio" {{ $es2['download_link'] == 1 ? ' checked' : '' }} name="download_link{{$key2 +1}}" id="download_link1" value="1">Yes
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" {{ $es2['download_link'] == 0 ? ' checked' : '' }} name="download_link{{$key2 +1}}" id="download_link2" value="0">No
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="col-md-3">
                                                <label>Upload File:</label>
                                            </div> 
                                            <div class="col-md-6">
                                                <input type="file" id="event_info_file" name="event_info_file[]" class="form-control"  autocomplete="off" >
                                            </div> 
                                            <div class="col-md-3">
                                                @if(!empty($es2['event_info_file']))
                                                    <a href="{{$es2['event_info_file']}}" target="_blank" download="{{$es2['event_info_file']}}"><i class="fa fa-file"></i> Download Information File</a>
                                                @endif
                                            </div> 
                                            
                                        </div>
                                        
                                    </div>
                                    !-->
                                    <div class="row">                 
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label>FAQ Question:</label>
                                                <input id="faq_title" value="{{$es2['faq_title']}}" name="faq_title[]" class="form-control "  autocomplete="off" >
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label>FAQ Description:</label>
                                                <div class="description_cntr">
                                                    <textarea id="faq_answer" name="faq_answer[]" class="form-control description"  autocomplete="off" >{{$es2['faq_answer']}}</textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>                    
                                    <input type="hidden" id="faq_id" name="faq_id[]"  value="{{$es2['id']}}" >
                                </div>
                            </div>
                        </div>    
                        <script>
                            $("#event_information{{$key2 +1}} #faq_title").keyup(function(){
                                $("#event_information{{$key2 +1}} #iti_title").html($(this).val());
                            });
                        </script> 
                    @endforeach
                    <div class="infoappender"></div>
                    <input name="info_cnt" id="info_cnt" value="{{count($data['eventdata2'])}}" type="hidden">
                    <div class="informationappender"></div>
                    <input name="faq_cnt" id="faq_cnt" value="{{count($data['eventdata2'])}}" type="hidden">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="panel panel-default">	  
                                <div class="panel-heading">
                                    <h3 class="panel-title"><span  class="pull-left">EVENT INFORMATION</span> &nbsp; 
                                    <button type ="button" onclick="cloneEventInfo()" id class="clone_btn btn btn-default btn-outline pull-right"><i class="fa fa-plus-circle"></i> ADD </span>                                                            
                                </div>     
                            </div>                        
                        </div>
                    </div>                     
                        
                </div>
                <!-- end Event travel host!--> 
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
                        <button type="button" class="btn btn-block btn-blue" id="prevBtn" type="button" onclick="goBack()" >Back</button>
                    </div>
                    <div class="col-md-2">
                        <button  type="submit"  id="nextBtn" class="btn btn-block btn-blue">Next</button>
                    </div>
                </div> 
            </div>
            <input type="hidden" name="event_id" id="event_id" value="{{$event->id}}" > 
            <input type="hidden" name="step" id="step" value="{{$step}}">
        </div>
        <!-- /.create events  -->
    </form>
</div>
<div class="loading" id="loader">Loading&#8230;</div>
@section('css')
    <link rel="stylesheet" href="{{ asset('/css/events.css') }}">
    <link rel="stylesheet" href="{{ asset('/css/loader.css') }}">
@stop

@section('js')
<script src="{{ asset('/js/jquery/events.jquery.js') }}"></script>
@stop
@stop
