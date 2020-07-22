@extends('layouts.dashboard')
@section('page_heading',"$event->event_name")
@section('section')     
           
<meta name="_token" content="{{ csrf_token() }}" /> 
<div class="col-md-12">
    <div class="row">
        <div class="col-md-9">
            <h1 class="form-title">Transportation  (Step {{$step}})</h1>
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
    <!-- /. create events -->        
    <div class="row" style="margin-top:20px">
        <div class="col-md-12">           
            <!-- start step 4 Event Trasportation!-->
            <div class="tab">
                <form id="eventtransportationform" enctype="multipart/form-data" method="post" > 
                    <input type="hidden" name="from" value="create">                            
                    @csrf
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Banner Image(Max File Size - 2MB):</label>
                                <input type="file" id="banner_image" name="sbanner_image[]" class="form-control banner_image" accept="image/x-png,image/gif,image/jpeg"   autocomplete="off" >
                            </div>  
                        </div>
                        <div class="col-md-4" id="banner-preview">
                            @if(!empty($banner_image))
                                <div class="photo-preview" >
                                    <div class="photo-image">
                                        <img data-dz-thumbnail=""  src="{{$banner_image}}" style="width:120px">
                                        <button type="button" id="deleteBanner" class="deleteBanner btn " onclick="removeBanner('event_transportation')"><i class="fa fa-close"></i> Remove</button>
                                    </div>
                                </div>
                            @endif
                        </div> 
                    </div>
                    @if(count($data['eventdata'])<1)
                        <div id="event_transportation1" class="event_transportation">
                            @section ('pane4_panel_title', 'TRANSPORTATION: GROUND ')
                            @section ('pane4_panel_body')
                                <div class="row"> 
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Transportation Service:</label>
                                            <input id="company_name" name="company_name" class="form-control "  autocomplete="off" >
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Service Address:</label>
                                            <input id="service_address1" name="service_address1" class="form-control "  autocomplete="off" >
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Service Address #2:</label>
                                            <input id="service_address2" name="service_address2" class="form-control "  autocomplete="off" >
                                        </div>
                                    </div>
                                </div>
                                <div class="row">                                 
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Location Address:</label>
                                            <input id="address1" name="address1" class="form-control "  autocomplete="off" >
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Location Address #2:</label>
                                            <input id="address2" name="address2" class="form-control "  autocomplete="off" >
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>City:</label>
                                            <input id="city" name="city" class="form-control "  autocomplete="off" >
                                        </div>
                                    </div>   
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>State:</label>
                                            <input id="state" name="state" class="form-control "  autocomplete="off" >
                                        </div>
                                    </div>                            
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Zip Code:</label>
                                            <input id="zip" name="zip" class="form-control "  autocomplete="off" >
                                        </div>
                                    </div> 
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Country:</label>
                                            <select id="country" name="country" class="form-control "  autocomplete="off" >
                                                <option value="">Select a country</option>
                                                @foreach($countries as $country)
                                                    <option value="{{ $country->id }}">{{ $country->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row"> 
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Telephone:</label>
                                            <input id="phone" name="phone" class="form-control "  autocomplete="off" >
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Description:</label>
                                            <textarea id="description" name="description" class="form-control description"  autocomplete="off" ></textarea>
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
                                            <input id="website_url" name="website_url" class="form-control " placeholder="http://"  autocomplete="off" >
                                        </div>
                                    </div>  
                                </div>
                                
                            @endsection
                            @include('widgets.panel', array('header'=>true, 'as'=>'pane4'))
                            @section ('pane5_panel_title', 'TRANSPORTATION: AIR')
                            @section ('pane5_panel_body')
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Title/Description:</label>
                                        <input id="flight_description" name="flight_description" class="form-control " placeholder="Please check your confirmation email for flight details."  autocomplete="off" >
                                    </div>
                                </div>  
                            @endsection
                            @include('widgets.panel', array('header'=>true, 'as'=>'pane5'))
                        </div>
                    @endif
                    @foreach ($data['eventdata'] as $key => $edata)  
                        <div id="event_transportation1" class="event_transportation">
                            @section ('pane4_panel_title', 'TRANSPORTATION: GROUND ')
                            @section ('pane4_panel_body')
                                <div class="row"> 
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Transportation Service:</label>
                                            <input id="company_name" value="{{$edata['company_name']}}" name="company_name" class="form-control "  autocomplete="off" >
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Service Address:</label>
                                            <input id="service_address1" value="{{$edata['service_address1']}}" name="service_address1" class="form-control "  autocomplete="off" >
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Service Address #2:</label>
                                            <input id="service_address2" value="{{$edata['service_address2']}}" name="service_address2" class="form-control "  autocomplete="off" >
                                        </div>
                                    </div>
                                </div>
                                <div class="row">                                 
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Location Address:</label>
                                            <input id="address1" name="address1" value="{{$edata['address1']}}" class="form-control "  autocomplete="off" >
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Location Address #2:</label>
                                            <input id="address2" name="address2" value="{{$edata['address2']}}" class="form-control "  autocomplete="off" >
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>City:</label>
                                            <input id="city" name="city" value="{{$edata['city']}}" class="form-control "  autocomplete="off" >
                                        </div>
                                    </div>   
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>State:</label>
                                            <input id="state" name="state" value="{{$edata['state']}}" class="form-control "  autocomplete="off" >
                                        </div>
                                    </div>                            
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Zip Code:</label>
                                            <input id="zip" name="zip" value="{{$edata['zip']}}" class="form-control "  autocomplete="off" >
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Country:</label>
                                            <select id="country" name="country" class="form-control "  autocomplete="off" >
                                                <option value="">Select a country</option>
                                                @foreach($countries as $country)
                                                    <option value="{{ $country->id }}" {{ $edata['country'] == $country->id ? ' selected' : '' }}>{{ $country->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>  
                                    
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Telephone:</label>
                                            <input id="phone" name="phone" value="{{$edata['phone']}}" class="form-control "  autocomplete="off" >
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Description:</label>
                                            <textarea id="description" name="description" class="form-control description"  autocomplete="off" >{{$edata['description']}}</textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <label>Directions Button:</label>                                                        
                                        <!--<div class="form-group">                                                        
                                            <div class="radio-inline">
                                                <label>
                                                    <input type="radio" {{ $edata['directions_button'] == 1 ? ' checked' : '' }}  name="directions_button1" id="directions_button1" value="1" >Yes
                                                </label>
                                            </div>
                                            <div class="radio-inline">
                                                <label>
                                                    <input type="radio" {{ $edata['directions_button'] == 0 ? ' checked' : '' }}  name="directions_button1" id="directions_button2" value="0">No
                                                </label>
                                            </div>
                                        </div>!-->
                                        <input id="directions_button" name="directions_button" value="{{$edata['directions_button']}}" class="form-control " placeholder="http://"  autocomplete="off" >
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Website Button(Optional):</label>
                                            <input id="website_url" value="{{$edata['website_url']}}" name="website_url" class="form-control " placeholder="http://"  autocomplete="off" >
                                        </div>
                                    </div>  
                                </div>
                                
                            @endsection
                            @include('widgets.panel', array('header'=>true, 'as'=>'pane4'))
                            @section ('pane5_panel_title', 'TRANSPORTATION: AIR')
                            @section ('pane5_panel_body')
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Title/Description:</label>
                                        <input id="flight_description" value="{{$edata['flight_description']}}" name="flight_description" class="form-control " placeholder="Please check your confirmation email for flight details."  autocomplete="off" >
                                    </div>
                                </div>  
                            @endsection
                            @include('widgets.panel', array('header'=>true, 'as'=>'pane5'))
                        </div>
                        <input name="id" id="id" value="{{$edata['id']}}" type="hidden">
                    @endforeach
                    <div class="appender"></div>
                    <input name="form_cnt" id="form_cnt" value="1" type="hidden">
                </form>                        
               
            </div>
            <!-- end step 5 Event Transportation!-->   
                    
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
                    <button onclick="$('#eventtransportationform').submit()" id="nextBtn" class="btn btn-block btn-blue">Next</button>
                </div>
            </div> 
        </div>
            <input type="hidden" name="event_id" id="event_id" value="{{$event->id}}" > 
            <input type="hidden" name="step" id="step" value="{{$step}}">   
               
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
