@extends('layouts.dashboard')
@section('page_heading','Edit User Profile')
@section('section')   

@if(session('status') == 'saved')
    <div class="alert alert-info alert-dismissible alert-saved">
        <button type="button" class="close alert-close-btn" data-dismiss="alert" aria-hidden="true">×</button>
        <h4><i class="icon fa fa-check"></i>Saved!</h4>
    </div>
@elseif(session('status') == 'error_saving')
    <div class="alert alert-info alert-dismissible alert-error">
        <button type="button" class="close alert-close-btn" data-dismiss="alert" aria-hidden="true">×</button>
        <h4><i class="icon fa fa-warning"></i>Error Saving Data!</h4>
    </div>
@endif

<div class="container-fluid account-settings-container">
  <div class="row">
  
     <div class="col-md-4">
        <form method="POST" action="{{ route('update_user_settings') }}">
            @csrf
            <div class="box box-solid box-primary account-settings-box">
                <div class="box-header account-settings-box-header">
                    <h3 class="box-title"></h3>
                </div><!-- /.box-header -->
                <div class="box-body">
                    <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>First Name:</label>
                                    <input type="text" class="form-control" id="first_name" name="first_name" placeholder="First Name" value="{{ $data['user']['first_name'] }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Last Name:</label>
                                    <input type="text" class="form-control" id="last_name" name="last_name" placeholder="Last Name" value="{{ $data['user']['last_name'] }}" required>
                                </div>
                            </div>
                    </div>
                
                <div class="form-group">
                    <label>Email:</label>
                    <input type="email" class="form-control" id="email" name="email" placeholder="Email"  value="{{ $data['user']['email'] }}" required>
                </div>
                    
                <div class="form-group">
                    <label>Password:</label>
                    <input type="password" class="form-control" id="password" name="password" placeholder="Password">
                </div>
               
                <div class="form-group ">
                    <label class="checkbox-inline"> 
                        
                    <input type="checkbox" @if ($data['user']['receive_notifications']==1) checked @endif name="receive_notifications"   value="1"> 
                    <b>Receive Notifications</b>
                    </label> 
                </div>
               
                <div class="form-group settings-btn-container">
                    <button type="submit" class="btn btn-flat so-btn btn-blue" data-dismiss="modal">submit</button>
                </div>
                </div><!-- /.box-body -->
            </div><!-- /.box -->
        </form>
    </div>

    
    
  </div>
</div>
@stop
