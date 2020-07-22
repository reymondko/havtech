@extends('layouts.dashboard')
@section('page_heading','')
@section('section')              
          
    <div class="col-md-12">
        @if(session('status') == 'saved')
            <div class="alert alert-info alert-dismissible alert-saved">
                <button type="button" class="close alert-close-btn" data-dismiss="alert" aria-hidden="true">×</button>
                <h4><i class="icon fa fa-check"></i>Saved!</h4>
            </div>
        @elseif(session('status') == 'email_error')
            <div class="alert alert-info alert-dismissible alert-error">
                <button type="button" class="close alert-close-btn" data-dismiss="alert" aria-hidden="true">×</button>
                <h4><i class="icon fa fa-warning"></i>Email Already Exists!</h4>
            </div>
        @endif
        <form id="editnotificationform" enctype="multipart/form-data" method="post" >
            <input value="{{$n->id}}" type="hidden" name="notif_id" id="notif_id" class="form-control"  autocomplete="off" >
            @csrf
                <div class="row">
                    <div class="col-lg-12">
                    <h1 class="page-header" id="notification-title">{{$n->title}}</h1>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Visibility:</label>
                                <label class="checkbox-inline">
                                    <select id="visibility2" name="visibility" class="form-control"  autocomplete="off" >
                                            <option {{ $n->visibility == 'Not Published' ? ' selected' : '' }}>Unpublished</option>
                                            <option {{ $n->visibility == 'Published' ? ' selected' : '' }}>Published</option>
                                    </select>
                                </label>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Send As:</label>
                                <label class="checkbox-inline">
                                    <select id="send_as" name="send_as" class="form-control"  autocomplete="off" >
                                        <option {{ $n->send_as == 'push_notification' ? ' selected' : '' }} value="push_notification" selected>Push Notification</option>
                                        <option {{ $n->send_as == 'text_notification' ? ' selected' : '' }} value="text_notification">Text Notification</option>
                                        <option {{ $n->send_as == 'email_notification' ? ' selected' : '' }} value="email_notification">Email</option>
                                        <option {{ $n->send_as == 'both' ? ' selected' : '' }} value="both">All</option>
                                    </select>
                                </label>
                            </div>
                        </div>
    
                    </div>
                    
                </div>  
                <div class="row">
                    <div class="col-md-12" style="margin: 0 0 20px 0;"> 
                        @csrf
                        <div class="row">
                            <div class="col-md-8">
                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="form-group">
                                            <label>Title:</label>
                                            <input required value="{{$n->title}}" id="notification_title"  name="notification_title" class="form-control"  autocomplete="off" required >
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Date:</label>
                                            <div class='input-group add-on date datepicker' >
                                            <input required id="notif_date" name='notif_date'  value="{{date('m/d/Y',strtotime($n->notif_date))}}"  type="text" class="form-control date-picker" autocomplete="off" data-date-format="yyyy-mm-dd"/>
                                                <div class="input-group-btn">
                                                    <button class="btn btn-default">
                                                        <i class="fa fa-calendar"></i>
                                                    </button>
                                                </div>
                                            </div>        
                                        </div>                                   
                                    </div>
                                </div>
                                <div class="row"> 
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Time:</label>
                                            <input required value="{{date('h:i A',strtotime($n->notif_date))}}" id="start_time" name="start_time"  class="form-control timepicker"  autocomplete="off" >
                                        </div>                                                   
                                    </div>
                                </div>                                        
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Description:</label>
                                    <textarea id="description" name="description" class="form-control description"  autocomplete="off" >{{$n->description}}</textarea>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="col-md-4">
                                    <label>Button Link? *</label>                                                    
                                    <div class="form-group">                                                        
                                        <div class="radio-inline">
                                            <label>
                                                <input required {{ $n->button_link == 1 ? ' checked' : '' }} type="radio" name="button_link" id="button_link" value="1" >Yes
                                            </label>
                                        </div>
                                        <div class="radio-inline">
                                            <label>
                                                <input {{ $n->button_link == 0 ? ' checked' : '' }} type="radio" name="button_link" id="button_link"  value="0">No
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <label>Button URL:</label>                                                        
                                    <!--<div class="form-group">                                                        
                                        <div class="radio-inline">
                                            <label>
                                                <input {{ $n->with_button_url == 0 ? ' checked' : '' }} type="radio" name="with_button_url" id="with_button_url" value="1" >Yes
                                            </label>
                                        </div>
                                        <div class="radio-inline">
                                            <label>
                                                <input {{ $n->with_button_url == 0 ? ' checked' : '' }} type="radio" name="with_button_url" id="with_button_url" value="0" >No
                                            </label>
                                        </div>
                                    </div>!-->
                                    <div class="form-group">  
                                        <input value="{{$n->button_url}}" type="text" name="button_url" id="button_url" class="form-control"  autocomplete="off"  placeholder="http://">
                                    </div>
                                </div>
                                
                            </div>                                                
                        </div>
                    
                    </div>
                </div>   

                <div class="panel panel-default">	  
                    <div class="panel-heading">
                        <h3 class="panel-title">
                            <div class="row">
                                <div class="col-md-1" style="margin-top: 10px;"><span  class="pull-left">Send To </span> &nbsp; </div> 
                                <div class="sidebar-search  col-md-2 pull-left">
                                    <div class="input-group custom-search-form">
                                        <input type="text" id="search_user" class="form-control" placeholder="Search...">
                                        <span class="input-group-btn">
                                        <button class="btn btn-default" type="button">
                                            <i class="fa fa-search"></i>
                                        </button>
                                    </span>
                                    </div>
                                </div> 
                                <div class="col-md-6">
                                    <select id="event_id" name="event_id" class="form-control">
                                        <option value="0">Select Event</option>
                                        @foreach ($events as $e)
                                            <option value="{{$e->id}}">{{$e->event_name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>  
                    </div> 
                    <div class="panel-body "> 
                        <div id="recipientlist" class="recipientlist">
                            @foreach ($users as $user)
                                <div class="col-md-2">
                                    <div class="form-group ">
                                        <label class="checkbox-inline"> 
                                        <!-- <input type="checkbox" onclick="AddRecipientUser('{{$user['id']}}')" @if ($user['inv_id']!=null) checked @endif name="recipient[]" id="recipient{{$user['id']}}"  value="{{$user['id']}}">{{ucfirst($user['first_name'])}} {{ucfirst($user['last_name'])}} -->
                                        <input type="checkbox" @if ($user['inv_id']!=null) checked @endif name="recipient[]" id="recipient{{$user['id']}}"  value="{{$user['id']}}"  onclick="AddRecipientUser('{{$user['id']}}')">{{ucfirst($user['first_name'])}} {{ucfirst($user['last_name'])}}
                                        </label> <!-- onclick="AddRecipientUser('{{$user['id']}}')"!-->
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>                  
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="col-md-4 pull-left">
                            <button class="btn btn-default btn-primary col-md-3 notif-btns pull-left" id="sendtoall" type="button">Check All</button>
                            <button class="btn btn-default btn-danger col-md-3 notif-btns pull-left"  id="removeall" type="button">Uncheck All</button>
                        </div>
                    </div>
                </div> <br>
                <div class="row">
                    <div class="col-md-12">
                        <!--<div class="col-md-2">
                            <div class="form-group">
                                <label>Visibility:</label>
                                <label class="checkbox-inline">
                                    <select id="visibility1" name="visibility" class="form-control"  autocomplete="off" >
                                            <option {{ $n->visibility == 'Not Published' ? ' selected' : '' }}>Not Published</option>
                                            <option {{ $n->visibility == 'Published' ? ' selected' : '' }}>Published</option>
                                    </select>
                                </label>
                            </div>
                        </div>!-->
                        <div class="col-md-4 pull-right">
                            <button class="btn btn-default btn-blue col-md-3 notif-btns pull-right" type="submit" id="update_notification" >Send</button>
                            <!--<button class="btn btn-default btn-blue col-md-3 notif-btns pull-right"  type="submit"  id="update_notification" >Save</button>!-->
                            <button class="btn btn-default btn-danger col-md-3 notif-btns pull-right"  type="button" onclick="deleteEvent({{$n->id}})" id="delete_notification" >Delete</button>
                        </div>
                    </div>
                </div> 
                </br></br> 
        </form>
         
    </div>
@stop
@section('css')
<link rel="stylesheet" href="{{ asset('/css/events.css') }}">
@stop

@section('js')
<script src="{{ asset('/js/jquery/newnotifs.js') }}"></script>
@stop       
