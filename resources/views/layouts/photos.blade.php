@extends('layouts.dashboard')
@section('page_heading','Event Photos')
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
        <!-- /. photos table --> 
        @endif
        <div class="row" style="margin-top:20px">
            <div class="col-md-12">   
                <div class="row">
                    <div class="col-md-12">
                        <div class="col-md-2">
                            <select id="event_id" name="event_id" class="form-control">
                                <option value="0">Select Event</option>
                                @foreach ($events as $e)
                                    <option {{ $e->id == $event_id ? ' selected' : ''}} value="{{$e->id}}">{{$e->event_name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <!--<div class="col-md-2">
                            <button type="button" id="select-photos"  class="btn btn-default btn-block btn-blue ">Select Photos</button>
                        </div>!-->
                        <div class="pull-right col-md-2"><!-- data-toggle="modal" data-target="#uploadPhotoModal"!-->
                                <button type="button" id="uploadbtn"  class="btn btn-default btn-block btn-blue "  ><i class="fa fa-upload"></i> Upload Photos</button>
                        </div>
                        <div class="pull-right col-md-2">
                                <button type="button" onclick="DownloadAll()"  class="btn btn-default btn-block btn-blue " ><i class="fa fa-download"></i> Download All</button>
                        </div>
                        <div class="pull-right col-md-2">
                            <button type="button" onclick="DownloadPhotos()" class="btn btn-default btn-block btn-blue "><i class="fa fa-download"></i> Download Photos</button>
                        </div>
                    </div>
                </div>
                <!--<div class="row select-p" style="margin-top:10px;display:none">
                    <div class="col-md-12">
                        <div class="pull-right col-md-2">
                            <button type="button" onclick="DownloadPhotos()" class="btn btn-default btn-block btn-blue "><i class="fa fa-download"></i> Download Photos</button>
                        </div>
                        <div class="pull-right col-md-2">
                            <button type="button" onclick="DeletePhotos()"  class="btn btn-danger btn-block  " > Delete Photos</button>
                        </div>
                    </div>
                </div>  !-->
                <div class="row"> 
                    <div class="col-md-12" style="margin-top:20px">  
                        <h2 class="event_photos_title">Pending Photos</h2>
                        <div class="row ">
                            <div class="col-md-12">
                                <div class="col-md-2">
                                    <button type="button" onclick="ApprovePhotos()" class="btn btn-default btn-block btn-orange "></i> Approve Selected</button>
                                </div>
                                <div class="col-md-2">
                                    <button type="button" onclick="ApproveAllPendingPhotos({{$event_id}})"  class="btn  btn-orange btn-block" > Approve All</button>
                                </div>
                                <div class="col-md-2">
                                    <button type="button" onclick="DeletePendingPhotos()"  class="btn  btn-orange btn-block" > Delete Selected</button>
                                </div>
                                <div class="col-md-2">
                                    <button type="button" onclick="DeleteAllPhotos({{$event_id}},1)"  class="btn  btn-orange btn-block" > Delete All</button>
                                </div>
                                <div class="col-md-2">
                                    <button type="button" onclick="selectAllPhotos('pending')"  class="btn  btn-orange btn-block pending-all-btn" >Select All</button>
                                </div>
                            </div>
                        </div>  
                        <div class="row" id="PendingPhotos_ctnr">
                            @if(!$pending_photos->isEmpty())
                                <form id="PendingPhotoForm">
                                    @foreach($pending_photos as $key=>$p)
                                        <div class="col-md-2">                                                       
                                            <div class="form-group">                                                        
                                                <div class="radio-inline">
                                                    <label class="col-md-12" style="padding-bottom: 1px">
                                                        <input type="checkbox" name="photoselect[]" value="{{$p->id}}" class="checkbox-select select-p pending" style="fo"  >
                                                    </label>
                                                    <div class="photo-preview" alt="{{$p->event_name}}" id="photo{{$key}}" style="margin-top:0px">
                                                        <div class="photo-image" alt="{{$p->event_id}}">
                                                            <a class="group" data-fancybox="gallery" href="/uploads/photos/{{$p->filename}}"> <img data-dz-thumbnail="" alt="{{$p['original_name']}}" src="/uploads/photos/{{$p['resized_name']}}" style="width:120px"></a>
                                                        </div>
                                                    </div>
                                                    <p class="col-md-12" style="margin: 0px; padding-bottom: 1px">
                                                        User:
                                                    </p>
                                                    <p  class="col-md-12" style="margin: 0px; padding-bottom: 1px ">
                                                        {{$p->first_name}} {{$p->last_name}}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                    @csrf
                                </form>
                            @else
                                <center><p>No pending photos uploaded for this event.</p></center>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="row"> 
                    <div class="col-md-12" style="margin-top:20px">  
                        <h2 class="event_photos_title">Approved Photos</h2>
                        <div class="row ">
                            <div class="col-md-12">
                                <div class="col-md-2">
                                    <button type="button" onclick="DeletePhotos()"  class="btn  btn-orange btn-block  " > Delete Selected</button>
                                </div>
                                <div class="col-md-2">
                                    <button type="button" onclick="DeleteAllPhotos({{$event_id}},0)"  class="btn  btn-orange btn-block  " > Delete All</button>
                                </div>
                                <div class="col-md-2">
                                    <button type="button" onclick="selectAllPhotos('approved')"  class="btn  btn-orange btn-block approve-all-btn" >Select All</button>
                                </div>
                            </div>
                        </div>  
                        <div class="row" id="ApprovedPhotos_ctnr">
                            @if(!$approved_photos->isEmpty())
                                <form id="PhotoForm">
                                    @foreach($approved_photos as $key=>$p)
                                        <div class="col-md-2">                                                       
                                            <div class="form-group">                                                        
                                                <div class="radio-inline">
                                                    <label class="col-md-12" style="padding-bottom: 1px">
                                                        <input type="checkbox" name="photoselect[]" value="{{$p->id}}" class="checkbox-select select-p approved"  >
                                                    </label>
                                                        <div class="photo-preview" alt="{{$p->event_name}}" id="photo{{$key}}" style="margin-top:0px">
                                                            <div class="photo-image" alt="{{$p->event_id}}">
                                                                <a class="group" data-fancybox="gallery" href="/uploads/photos/{{$p->filename}}"> <img data-dz-thumbnail="" alt="{{$p['original_name']}}" src="/uploads/photos/{{$p['resized_name']}}" style="width:120px"></a>
                                                            </div>
                                                        </div>
                                                        <p class="col-md-12" style="margin: 0px; padding-bottom: 1px">
                                                            User: {{$p->first_name}} {{$p->last_name}}
                                                        </p>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                    @csrf
                                </form>
                            @else
                                <center><p>No approved photos uploaded for this event.</p></center>
                            @endif
                        </div>
                        
                    </div>
                </div>
            </div>
        </div>
                
        
            <!-- /.photos table -->
    </div>

    
<!--Add User Modal -->
<div class="modal fade" id="uploadPhotoModal" tabindex="-1" role="dialog" aria-labelledby="uploadPhotosModallLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="uploadPhotosModalLabel">Upload Photos for <span id="event_title"></span></h4>
            </div>
            
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-12 offset-sm-1">
                            @section ('pane2_panel_title', 'Upload Photos')
                            @section ('pane2_panel_body')
                                <form method="post" action="{{ url('events/photos/images-save') }}"
                                    enctype="multipart/form-data" class="dropzone" id="my-dropzone">
                                    {{ csrf_field() }}
                                    <input type="hidden" name="event_id" id="event_id">
                                    <div class="dz-message">
                                        <div class="col-xs-12">
                                            <div class="message">
                                                <p>Drop files here or Click to Upload Photos</p>
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
                <div class="modal-footer">
                <button type="button" class="btn btn-flat close-btn" data-dismiss="modal">Close</button>
                </div>
        </div>
    </div>
</div>
  

@stop
@section('css')
    <link rel="stylesheet" href="{{ asset('/css/events.css') }}">
@stop
@section('js')
<script src="{{ asset('/js/jquery/photos_dropzone_config.js') }}"></script>
<script src="{{ asset('/js/jquery/photos.js') }}"></script>
@stop