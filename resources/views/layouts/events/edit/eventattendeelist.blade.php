@extends('layouts.events.edit.editEvent_template')
@section('content')
<div class="row">
        <div class="col-lg-12">
            <h1 class="page-header" id="event-title">{{$data['event_name']}}
            </h1>
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
            <label>Filter By:</label>
            <label class="checkbox-inline">
                <select id="filterby" name="filterby" class="form-control"  autocomplete="off" >
                    <option value="ASC">Alphabetical (A-Z)</option>
                    <option value="DESC">Alphabetical (Z-A)</option>
                </select>
            </label>
        </div>
    </div>
    <div class="col-md-3 pull-right">
        <div class="form-group pull-right col-md-12">
            <label class="col-md-6" style="padding: 0px;">Filter By:</label>
                <label class=" col-md-6" style="padding: 0px;">
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
<!-- /. create events -->        
<div class="row" style="margin-top:20px">
    <div class="col-md-12">       
        <div class="tab">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Attendee List</h3>
                    <div class="col-md-5 pull-right attendee-list-importer">
                        <div class="col-md-3 pull-left text-center" style="font-size: 12px;">
                            <a href="/download/Sample Import Attendees.xlsx">Download Sample file</a>
                        </div>
                        <div class="col-md-2 radio-inline pull-right">
                            <!--<button class="btn btn-orange"  onclick="exportAttendees()">Export</button>!-->
                           <a href=" attendee-export/{{$data['event_id']}}" class="btn btn-orange" download>Export</a>
                        </div>
                        <form action="{{ route('import') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            
                            <div class="col-md-5 pull-left">
                                <input type="file" name="file" class="form-control col-md-2 radio-inline" accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel" >
                            </div>
                            <div class="col-md-2 radio-inline pull-right">
                                <button class="btn btn-success">Import</button>
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
                    <div id="event_attendeelist1" class="event_attendeelist">
                   
                        <div id="attendeelist">
                            @foreach ($attendees as $attendee)
                                <div class="col-md-2">
                                    <div class="form-group " >
                                        <label class="checkbox-inline" style="
                                        @if($attendee['email_sent_approved']==1)
                                            background-color:#f69031;
                                        @endif padding:0px 5px;" >
                                        <input type="checkbox" class="attendees" @if ($attendee['inv_id']!=null) checked @endif name="attendee[]" id="attendee{{$attendee['id']}}" onclick="inviteUser('{{$attendee['id']}}')" value="{{$attendee['id']}}">{{ucfirst($attendee['first_name'])}} {{ucfirst($attendee['last_name'])}}
                                        </label>
                                    </div>
                                </div>
                            @endforeach
                        </div>                   
                    </div>
                    <div class="appender"></div>
                    <input name="form_cnt" id="form_cnt" value="1" type="hidden">
                </form> 
            </div>
        </div> 
    </div>
</div>
@section('css')
<link rel="stylesheet" href="{{ asset('/css/events.css') }}">
@stop

@section('js')
<script src="{{ asset('/js/jquery/edit_events.jquery.js') }}"></script>
@stop        
@stop
                
