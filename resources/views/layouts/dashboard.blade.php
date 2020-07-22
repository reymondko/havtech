@extends('layouts.plane')

@section('body')
 <div id="wrapper" class="nav-wrapper">

        <!-- Navigation -->
        <nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
            <a class="navbar-brand" href="{{ url ('') }}"><img class="logo" src="{{asset('images/Havtech_logo_final.svg')}}" /></a>
            </div>
            <!-- /.navbar-header -->

            <ul class="nav navbar-top-links navbar-left">
                <li><a href="/settings"   ><i class="fa fa-user fa-fw"></i> User Profile</a></li>
                <li><a href="{{ url(config('adminlte.logout_url', 'auth/logout')) }}"><i class="fa fa-sign-out fa-fw"></i> Logout</a>
            </ul>
            <!-- /.navbar-top-links -->

            <div class="navbar-default sidebar" role="navigation">
                <div class="sidebar-nav navbar-collapse">
                    <ul class="nav" id="side-menu">
                        <li {{ (Request::is('/dashboard') ? 'class="active"' : '') }}>
                            <a href="{{ url ('dashboard') }}"></i> Dashboard</a>
                        </li>
                        <li id="event_link" class=" {{ (Request::is('*Events') ? 'active' : '') }}">
                            <a href="{{route('events')}}" onclick="showevents()"> Events <span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                                <li {{ (Request::is('*specialEvents') ? 'class="active"' : '') }}>
                                    <a href="{{ url ('/events/specialevents') }}">Special Events</a>
                                </li>
                                <li {{ (Request::is('*generalEvents') ? 'class="active"' : '') }}>
                                    <a href="{{ url ('/events/generalevents' ) }}">General Events</a>
                                </li>
                                <li {{ (Request::is('*upcomingEvents') ? 'class="active"' : '') }}>
                                    <a href="{{ url('/events/upcomingevents') }}">All Upcoming Events</a>
                                </li>
                                <li {{ (Request::is('*archiveEvents') ? 'class="active"' : '') }}>
                                    <a href="{{ url ('/events/archiveevents') }}">Archive Events</a>
                                </li>                                
                                <li {{ (Request::is('*LIEvents') ? 'class="active"' : '') }}>
                                    <a href="{{ url ('/events/lievents') }}">LI Events</a>
                                </li>
                                <li {{ (Request::is('*create-event') ? 'class="active"' : '') }}>
                                    <a href="{{ url ('events/step/1of9/create-event-overview/0') }}"> + Add An Event</a>
                                </li>
                            </ul>
                            <!-- /.nav-second-level -->
                        </li>
                        <li {{ (Request::is('*Notifications') ? 'class="active"' : '') }}>
                            <a href="{{ url ('notifications') }}">Notifications</a>
                            <!-- /.nav-second-level -->
                        </li>
                        <li {{ (Request::is('*Users') ? 'class="active"' : '') }}>
                            <a href="/users">Users</a>
                            <!-- /.nav-second-level -->
                        </li>
                        <li {{ (Request::is('*Photos') ? 'class="active"' : '') }}>
                            <a href="/photos/0">Photos</a>
                            <!-- /.nav-second-level -->
                        </li>
                        <li {{ (Request::is('*registration') ? 'class="active"' : '') }}>
                            <a href="/registration">Event Registration</a>
                            <!-- /.nav-second-level -->
                        </li>
                        <li {{ (Request::is('*pending') ? 'class="active"' : '') }}>
                            <a href="/pending">Pending Accounts</a>
                            <!-- /.nav-second-level -->
                        </li>
                        
                    </ul>
                </div>
                <!-- /.sidebar-collapse -->
            </div>
            <!-- /.navbar-static-side -->
        </nav>

        <div id="page-wrapper">
			 <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header main-header">@yield('page_heading')</h1>
                </div>
                <!-- /.col-lg-12 -->
           </div>
			<div class="row">  
				@yield('section')

            </div>
            <!-- /#page-wrapper -->
        </div>
    </div>
    <script>
        function showevents(){
            window.location="/events/admin"
        }
    </script>
    
<link rel="stylesheet" href="{{ asset('/css/loader.css') }}">
@stop