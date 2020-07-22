@extends('layouts.dashboard')
@section('page_heading',"$event->event_name")
@section('section')     
          
<meta name="_token" content="{{ csrf_token() }}" /> 
<div class="col-md-12">
    <div class="alert alert-success  alert-dismissable " role="alert" style="display: none;">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>  <i class="fa fa-check"></i>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <span id="success_msg">You have an alert.</span>
    </div>
    <div class="row">
        <div class="col-md-12">
            <h1 class="form-title">Map (Step {{$step}})</h1>
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
    @if(count($data['eventdata'])>0)
        <div class="row">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Uploaded Maps
                        <div class="col-md-2 pull-right">
                            <button class="btn btn-default btn-block btn-blue updatemapnames" onclick="$('#updateMapNames').submit();" id="update_event">Update Map Names</button>
                        </div>
                    </h3>
                </div>
                <div class="panel-body"> 
                    <div class="col-md-12">
                        <form id="updateMapNames"  method="post" >  
                                {{ csrf_field() }}
                            @foreach ($data['eventdata'] as $key => $es)
                                <div class="map-preview text-center"  id="map{{$key}}">
                                    <fieldset>
                                            <input value="{{$es['id']}}" type="hidden" name="map_ids[]" id="map_ids" placeholder="Map Name" class="form-control" >
                                    <input value="{{$es['map_name']}}" type="text" name="map_name[]" id="map_name" placeholder="Map Name" class="form-control" >
                                    </fieldset>
                                    <div class="map-image">
                                        <img data-dz-thumbnail="" alt="{{$es['original_name']}}" src="/uploads/maps/{{$es['resized_name']}}" style="width:120px">
                                    </div>
                                    <button type="button"  onclick="deleteMap('{{$es['original_name']}}','map{{$key}}')" class="tn btn-default btn-outline deleteEventItem map-remove"><i class="fa fa-minus-circle"></i> Delete Map</a></button>
                                </div>
                            @endforeach
                            
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif
    <!-- /. create events -->        
    <div class="row" style="margin-top:20px">
        <div class="col-md-12">  
                     
            <!-- start step 4 Event Trasportation!-->
            <div class="tab">
                    <div class="row">
                            <div class="col-sm-12 offset-sm-1">
                                @section ('pane2_panel_title', 'Upload Maps')
                                @section ('pane2_panel_body')
                                    <form method="post" action="{{ url('events/maps/images-save') }}"
                                        enctype="multipart/form-data" class="dropzone" id="my-dropzone">
                                        {{ csrf_field() }}
                                        <input type="hidden" name="event_id" id="event_id" value="{{$event->id}}">
                                        <div class="dz-message">
                                            <div class="col-xs-12">
                                                <div class="message">
                                                    <p>Drop files here or Click to Upload Maps</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="fallback">
                                            <input type="file" name="file" multiple>
                                        </div>
                                    </form>
                                @endsection
                                @include('widgets.panel', array('header'=>true, 'as'=>'pane2'))  
                            </div>
                        </div>
                        
                        {{--Dropzone Preview Template--}}
                        <div id="preview" style="display: none;">
                        
                            <div class="dz-preview dz-file-preview">
                                <div class="dz-image"><img data-dz-thumbnail /></div>
                        
                                <div class="dz-details">
                                    <div class="dz-size"><span data-dz-size></span></div>
                                    <div class="dz-filename"><span data-dz-name></span></div>
                                </div>
                                <div class="dz-progress"><span class="dz-upload" data-dz-uploadprogress></span></div>
                                <div class="dz-error-message"><span data-dz-errormessage></span></div>
                        
                        
                        
                                <div class="dz-success-mark">
                        
                                    <svg width="54px" height="54px" viewBox="0 0 54 54" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:sketch="http://www.bohemiancoding.com/sketch/ns">
                                        <!-- Generator: Sketch 3.2.1 (9971) - http://www.bohemiancoding.com/sketch -->
                                        <title>Check</title>
                                        <desc>Created with Sketch.</desc>
                                        <defs></defs>
                                        <g id="Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd" sketch:type="MSPage">
                                            <path d="M23.5,31.8431458 L17.5852419,25.9283877 C16.0248253,24.3679711 13.4910294,24.366835 11.9289322,25.9289322 C10.3700136,27.4878508 10.3665912,30.0234455 11.9283877,31.5852419 L20.4147581,40.0716123 C20.5133999,40.1702541 20.6159315,40.2626649 20.7218615,40.3488435 C22.2835669,41.8725651 24.794234,41.8626202 26.3461564,40.3106978 L43.3106978,23.3461564 C44.8771021,21.7797521 44.8758057,19.2483887 43.3137085,17.6862915 C41.7547899,16.1273729 39.2176035,16.1255422 37.6538436,17.6893022 L23.5,31.8431458 Z M27,53 C41.3594035,53 53,41.3594035 53,27 C53,12.6405965 41.3594035,1 27,1 C12.6405965,1 1,12.6405965 1,27 C1,41.3594035 12.6405965,53 27,53 Z" id="Oval-2" stroke-opacity="0.198794158" stroke="#747474" fill-opacity="0.816519475" fill="#FFFFFF" sketch:type="MSShapeGroup"></path>
                                        </g>
                                    </svg>
                        
                                </div>
                                <div class="dz-error-mark">
                        
                                    <svg width="54px" height="54px" viewBox="0 0 54 54" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:sketch="http://www.bohemiancoding.com/sketch/ns">
                                        <!-- Generator: Sketch 3.2.1 (9971) - http://www.bohemiancoding.com/sketch -->
                                        <title>error</title>
                                        <desc>Created with Sketch.</desc>
                                        <defs></defs>
                                        <g id="Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd" sketch:type="MSPage">
                                            <g id="Check-+-Oval-2" sketch:type="MSLayerGroup" stroke="#747474" stroke-opacity="0.198794158" fill="#FFFFFF" fill-opacity="0.816519475">
                                                <path d="M32.6568542,29 L38.3106978,23.3461564 C39.8771021,21.7797521 39.8758057,19.2483887 38.3137085,17.6862915 C36.7547899,16.1273729 34.2176035,16.1255422 32.6538436,17.6893022 L27,23.3431458 L21.3461564,17.6893022 C19.7823965,16.1255422 17.2452101,16.1273729 15.6862915,17.6862915 C14.1241943,19.2483887 14.1228979,21.7797521 15.6893022,23.3461564 L21.3431458,29 L15.6893022,34.6538436 C14.1228979,36.2202479 14.1241943,38.7516113 15.6862915,40.3137085 C17.2452101,41.8726271 19.7823965,41.8744578 21.3461564,40.3106978 L27,34.6568542 L32.6538436,40.3106978 C34.2176035,41.8744578 36.7547899,41.8726271 38.3137085,40.3137085 C39.8758057,38.7516113 39.8771021,36.2202479 38.3106978,34.6538436 L32.6568542,29 Z M27,53 C41.3594035,53 53,41.3594035 53,27 C53,12.6405965 41.3594035,1 27,1 C12.6405965,1 1,12.6405965 1,27 C1,41.3594035 12.6405965,53 27,53 Z" id="Oval-2" sketch:type="MSShapeGroup"></path>
                                            </g>
                                        </g>
                                    </svg>
                                </div>
                            </div>
                        </div>
                        {{--End of Dropzone Preview Template--}}  
               
            </div>
            <!-- end step 6 Map!-->   
            
                    
            <div class="row">
                <div class="col-md-2">
                    <button class="btn " onclick="cancelEvent({{$event->id}})">Cancel</button>
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
                    <button onclick="$('#eventMapform').submit()" id="nextBtn" class="btn btn-block btn-blue">Next</button>
                </div>
            </div> 
        </div>
        
        <form name="eventMapform" id="eventMapform">
            <input type="hidden" name="event_id" id="event_id" value="{{$event->id}}" > 
            <input type="hidden" name="step" id="step" value="{{$step}}"> 
        </form>
            <!-- /.create events  -->
    </div>
       
</div>

@section('css')
    <link rel="stylesheet" href="{{ asset('/css/events.css') }}">
@stop

@section('js')
<script src="{{ asset('/js/jquery/dropzone_config.js') }}"></script>
<script src="{{ asset('/js/jquery/events.jquery.js') }}"></script>
@stop
@stop
