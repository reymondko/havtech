@extends('layouts.dashboard')
@section('page_heading','Dashboard')
@section('section')     
           
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-6">

                @section ('pane2_panel_title', 'Upcoming Events')
                @section ('pane2_panel_body')
                   
                <!-- /.upcoming events pane -->   
                                 
                    <div class="list-group">
                        @foreach($data['upcomingevents'] as $e)
                            <div class="list-group-item" style="border: 0px;">
                                <div class="row">
                                    <div class="col-md-8">{{$e->event_name}}</div>
                                    <div class="col-md-3">{{$e->start_date}}</div>
                                    <span class="col-md-1 text-muted small"><em><a href="{{ route('editEventOverview',['step' => '0','event_id' => $e->id]) }} "><i class="fa fa-pencil"></i></a>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- /.upcoming events panepanel-body -->
                    <div class="row">
                        <div class="col-md-4"><a href="/events/admin" class="btn btn-default btn-block btn-blue">View All Events</a> </div> 
                        <div class="col-md-4"><a href="{{ url ('events/step/1of9/create-event-overview/0') }}" class="btn btn-default btn-block btn-blue">Add New Event</a> </div>
                    </div>
                    <!-- /.panel -->
                @endsection
                @include('widgets.panel', array('header'=>true, 'as'=>'pane2'))
                </div>
                <!-- /.col-lg-8 -->
                <div class="col-lg-6">
                   

                    @section ('pane1_panel_title', 'Notifications Panel')
                    @section ('pane1_panel_body')
                        <div class="list-group">
                            @foreach($data['notifs'] as $n)
                                <a href="#" class="list-group-item">
                                    <i class="fa fa-comment fa-fw"></i> {{$n->title}}
                                    <span class="pull-right text-muted small"><em>{{date("F j,Y",strtotime($n->notif_date))}} {{date("H:i a",strtotime($n->notif_date))}}</em>
                                    </span>
                                </a>
                            @endforeach
                               <!-- <a href="#" class="list-group-item">
                                    <i class="fa fa-twitter fa-fw"></i> 3 New Followers
                                    <span class="pull-right text-muted small"><em>12 minutes ago</em>
                                    </span>
                                </a>
                                <a href="#" class="list-group-item">
                                    <i class="fa fa-envelope fa-fw"></i> Message Sent
                                    <span class="pull-right text-muted small"><em>27 minutes ago</em>
                                    </span>
                                </a>
                                <a href="#" class="list-group-item">
                                    <i class="fa fa-tasks fa-fw"></i> New Task
                                    <span class="pull-right text-muted small"><em>43 minutes ago</em>
                                    </span>
                                </a>
                                <a href="#" class="list-group-item">
                                    <i class="fa fa-upload fa-fw"></i> Server Rebooted
                                    <span class="pull-right text-muted small"><em>11:32 AM</em>
                                    </span>
                                </a>
                                <a href="#" class="list-group-item">
                                    <i class="fa fa-bolt fa-fw"></i> Server Crashed!
                                    <span class="pull-right text-muted small"><em>11:13 AM</em>
                                    </span>
                                </a>
                                <a href="#" class="list-group-item">
                                    <i class="fa fa-warning fa-fw"></i> Server Not Responding
                                    <span class="pull-right text-muted small"><em>10:57 AM</em>
                                    </span>
                                </a>
                                <a href="#" class="list-group-item">
                                    <i class="fa fa-shopping-cart fa-fw"></i> New Order Placed
                                    <span class="pull-right text-muted small"><em>9:49 AM</em>
                                    </span>
                                </a>
                                <a href="#" class="list-group-item">
                                    <i class="fa fa-money fa-fw"></i> Payment Received
                                    <span class="pull-right text-muted small"><em>Yesterday</em>
                                    </span>
                                </a>!-->
                            </div>
                            <!-- /.list-group -->
                           
                            <div class="row">
                                <div class="col-md-4"> <a href="/notifications" class="btn btn-default btn-block btn-blue">View All Notifications</a></div> 
                                <div class="col-md-4"> <a href="/notifications/new" class="btn btn-default btn-block btn-blue">New Notification</a> </div>
                            </div>
                        <!-- /.panel-body -->
                        
                    @endsection
                    @include('widgets.panel', array('header'=>true, 'as'=>'pane1'))

                    @section ('pane3_panel_title', 'Recently Added Photos')
                    @section ('pane3_panel_body')
                        @foreach ($data['photos'] as $key => $es)
                            <div class="photo-preview"  id="photo{{$key}}">
                            <div class="photo-image">
                                <a class="group" data-fancybox="gallery" href="/uploads/photos/{{$es->filename}}"> <img data-dz-thumbnail="" alt="{{$es['original_name']}}" src="/uploads/photos/{{$es['resized_name']}}" style="width:120px"></a>
                                </div>
                            </div>
                        @endforeach
                    @endsection
                    @include('widgets.panel', array('header'=>true, 'as'=>'pane3'))
                    
                </div>

                <!-- /.col-lg-4 -->

<div class="loading" id="loader">Loading&#8230;</div>
@stop
@section('css')
<link rel="stylesheet" href="{{ asset('/css/events.css') }}">
<link rel="stylesheet" href="{{ asset('/css/loader.css') }}">
@stop