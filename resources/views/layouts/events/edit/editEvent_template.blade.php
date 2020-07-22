@extends('layouts.dashboard')
@section('section')     
    
<meta name="_token" content="{{ csrf_token() }}" />       
<div class="col-md-12">
    @if(session('status') == 'saved')
    <div class="alert alert-success2  alert-dismissable " role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>  <i class="fa fa-check"></i>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <span id="success_msg">Event {{session('type')}} Saved.</span>
    </div>
    @endif

    <div class="alert alert-success  alert-dismissable " role="alert" style="display: none;">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>  <i class="fa fa-check"></i>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <span id="success_msg">You have an alert.</span>
    </div>
       
    <div class="row" style="margin-top:20px">
        <div class="col-md-12">      
            <ul class="nav nav-tabs" id="myTab" role="tablist">
               
                @foreach($data['events'] as $i => $event )
                    <li class="nav-item {{ $i == $data['step'] ? ' active' : '' }}">
                        <a class="nav-link " id="tab_c{{ $i }}" 
                            {{ $i == $data['step'] ? ' data-toggle="tab"' : '' }}
                            href="{{ $i == $data['step'] ? ' #tab_e'.$i : route('editEvent'.ucfirst($event),['step' => $i,'event_id' => $data['event_id']]) }}"
                            role="tab" aria-controls="home"
                            aria-selected="true">
                            @if($event=='travel-host')
                                Travel Host and Information
                            @elseif($event=='attendee-list')
                                Attendee List
                            @else   
                                {{ucfirst($event)}}
                            @endif
                        </a>
                    </li>
                @endforeach
            </ul>
            <div class="tab-content" id="myTabContent">
            <div class="tab-pane fade show active in" id="tab_e{{ $data['step'] }}" role="tabpanel" aria-labelledby="home-tab">
                
                @yield("content")
            </div>
            
        </div>
            <input type="hidden" name="event_id" id="event_id" value="{{$data['event_id']}}">
            <input type="hidden" name="step" id="step"  value="{{$data['step']}}" > 
               
        
    </div>
       
</div>
<div class="loading" id="loader">Loading&#8230;</div>
@stop