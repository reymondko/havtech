@extends('layouts.dashboard')
@section('page_heading',"$event->event_name")
@section('section')     
           
<meta name="_token" content="{{ csrf_token() }}" /> 

<div class="col-md-12">
    <div class="row">
        <div class="col-md-9">
            <h1 class="form-title">Create Attendee List (Step {{$step}})</h1>
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
    </div>
    <div class="row">
        <div class="sidebar-search  col-md-4 pull-left" >
            <div class="input-group custom-search-form col-md-6 pull-left"  style="padding: 0.5px">
                <input type="text" id="search_attendee" class="form-control" placeholder="Search...">
                <span class="input-group-btn">
                <button class="btn btn-default" type="button">
                    <i class="fa fa-search"></i>
                </button>
            </span>
            </div>
            <button class="btn btn-default col-md-6 btn-orange pull-right" onclick="sendAttendees();" id="checkAll">Send to Attendees</button>
        </div>
        <div class="col-md-1 pull-right" style="padding: 0.5px">
            <button class="btn btn-default btn-block btn-blue" onclick="checkallAttendees();" id="checkAll" >Check All</button>
        </div>
        
        <div class="col-md-1 pull-right" style="padding: 0.5px">
            <button class="btn btn-default btn-block btn-blue" onclick="uncheckallAttendees();" id="uncheckAll" >Uncheck All</button>
        </div>
        <div class="col-md-3 pull-right">
            <div class="form-group pull-right">
                <label class="col-md-3" style="padding: 0px;">Filter By:</label>
                <label class=" col-md-6" style="padding: 0px;">
                    <select id="filterby" name="filterby" class="form-control"  autocomplete="off" >
                        <option value="ASC">Alphabetical (A-Z)</option>
                        <option value="DESC">Alphabetical (Z-A)</option>
                    </select>
                </label>
            </div>
        </div>
        <div class="col-md-3 pull-right">
            <div class="form-group pull-right col-md-12">
                <label class="col-md-5">Customer Type:</label>
                <label class="checkbox-inline col-md-7" style="padding: 0;">
                    <select id="customer_type" name="customer_type" class="form-control"  autocomplete="off" >
                        <option value=""></option>
                        @foreach($customertype as $i)
                            <option value="{{ $i->id }}">{{ $i->type }}</option>
                        @endforeach
                    </select>
                </label>
            </div>
        </div>
    </div>
    <div class="row" style="margin-top:20px">
        <div class="col-md-12">           
            <div class="tab">
                @csrf 
                <div id="event_attendeelist1" class="event_attendeelist">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">Attendee List</h3>
                            <div class="col-md-4 pull-right attendee-list-importer">
                                <form action="{{ route('import') }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="col-md-2 radio-inline pull-right">
                                        <button class="btn btn-success">Import</button>
                                    </div>
                                    <div class="col-md-6 pull-right">
                                        <input type="file" name="file" class="form-control col-md-2 radio-inline" accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel" >
                                    </div>
                                    <div class="col-md-3 pull-right text-center" style="font-size: 12px;">
                                        <a href="/download/Sample Import Attendees.xlsx">Download Sample file</a>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="panel-body">
                        <form id="eventattendeelistform" enctype="multipart/form-data" method="post" >                               
                            @csrf 
                            <input type="hidden" name="eventid" id="eventid" value="{{$data['event_id']}}" >
                            <input type="hidden" name="todo" id="todo" value="0" >
                            <div id="attendeelist">
                                @foreach ($attendees as $attendee)
                                    <div class="col-md-2">
                                        <div class="form-group ">
                                            <label class="checkbox-inline" style="
                                            @if($attendee['email_sent_approved']==1)
                                                background-color:#f69031;
                                            @endif padding:0px 5px;" > 
                                            <input type="checkbox" @if ($attendee['inv_id']!=null) checked @endif name="attendee[]" id="attendee{{$attendee['id']}}" onclick="inviteUser('{{$attendee['id']}}')" value="{{$attendee['id']}}">{{ucfirst($attendee['first_name'])}} {{ucfirst($attendee['last_name'])}}
                                            </label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </form> 
                    </div>
                </div>
                <div class="appender"></div>
                <input name="form_cnt" id="form_cnt" value="1" type="hidden">
            </div>
                    
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
                    <button onclick="$('#attendeeForm').submit()" id="nextBtn" class="btn btn-block btn-blue">Next</button>
                </div>
            </div> 
        </div>
        <form name="attendeeForm" id="attendeeForm">
            <input type="hidden" name="event_id" id="event_id" value="{{$event->id}}" > 
            <input type="hidden" name="step" id="step" value="{{$step}}"> 
            <input type="hidden" name="total" id="total" value="{{$total}}">
        </form>
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
