@extends('layouts.dashboard')
@section('page_heading','Event Registration')
@section('section')              
          
    <div class="col-md-12" style="font-size:12px;">
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
        <!-- /. users table --> 
                
        @endif
        <div class="row" style="margin-top:20px">
            <div class="row" style="z-index:99999">
                <div class="col-md-12">
                    <div class=" col-md-9 ">
                    
                        </div>
                    
                </div>
            </div> 
            <div class="col-md-12" style="z-index: 1">   
                        
                <table id="event_registrations" class="table table-condensed table-bordered table-striped " style="margin-top: 20px ; z-index: 1; position: relative;font-size:14px">
                    <thead>
                        <tr>
                            <th>Event</th>
                            <th>Profile Name</th>
                            <th>Company</th>
                            <!--<th>Industry</th>!-->
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Schedule Title</th>
                            <th>Schedule</th>
                            <th>Last Login</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($registered as $u)
                        <tr>
                            <td>{{$u->event_name}}</td>
                            <td>{{$u->first_name}} {{$u->last_name}}</td>
                            <td>{{$u->company}}</td>
                            <!--<td>{{$u->industry}}</td>!-->
                            <td>{{$u->email}}</td>
                            <td>{{$u->telephone}}</td>
                            <td>{{$u->sched_title}}</td>
                            <td>{{ $u->start_date  == "" ? "" : date("F j,Y h:i A", strtotime($u->start_date))}}</td> 
                            <td>{{ $u->last_login  == "" ? "" : date("F j,Y h:i A", strtotime($u->last_login))}}</td>
                        </tr>
                        @endforeach
                    </tbody>

                </table>
            </div>
        </div>
                
            <!-- /.users table -->
    </div>

              
    <script>
        $(document).ready(function() {
           /* var table = $('#event_registrations').DataTable({
                buttons: [
             'csv', 'excel'
            ],
            dom: 'lr<"table-filter-container">tip',
            
            initComplete: function(settings){
                var api = new $.fn.dataTable.Api( settings );
                $('.table-filter-container', api.table().container()).append(
                    $('#table-filter').detach().show()
                );
                
                $('#filterby').on('change', function(){
                    table.search(this.value).draw();   
                }); 
                $('#search').keyup('change', function(){
                    table.search(this.value).draw();   
                });             
                
            }
            });*/
           var table= $('#event_registrations').DataTable( {
            responsive: true,
                    dom: 'lBfrtip',"bPaginate": true,
                    "bFilter": true,
                    "bLengthChange": true,
                    buttons: [
                        { extend: 'csv', text: 'Export' }
                    ],
                initComplete: function(settings){
                    var api = new $.fn.dataTable.Api( settings );
                    $("#event_registrations_filter").addClass('col-md-7');
                    $("#event_registrations_filter").html('<div class="pull-right col-md-12" style="margin-bottom:0px;">'+
                        '<div class="col-md-4">'+
                            '<input  style="z-index:600;font-size:14px;height: 34px;padding: 6px 12px;font-size: 14px;line-height: 1.42857143;color: #555;background-color: #fff;background-image: none;border: 1px solid #ccc;border-radius: 4px;" type="text" id="search" class="form-control" placeholder="Search User">'+
                        '</div>'+
                            '<div class="col-md-8"><select  class="form-control " id="filterby"  style="display: inline-block; z-index:5002;font-size:14px;height: 34px;padding: 6px 12px;font-size: 14px;line-height: 1.42857143;color: #555;background-color: #fff;background-image: none;border: 1px solid #ccc;    border-radius: 4px;">'+
                                '<option value="">Filter by Event</option>'+
                                @foreach($events as $et)
                                '    <option>{{$et->event_name}}</option>'+
                                @endforeach
                            '</select></div>'+
                    '</div>')
                    var filterby="";
                    var search="";
                    $('#filterby').on('change', function(){
                        filterby = this.value;
                        //console.log('filterby ='+filterby );
                        //console.log('search ='+search );
                        table.columns(0).search(this.value).columns(1).search(search).draw();   
                    }); 
                    $('#search').keyup('change', function(){
                        search=this.value;
                        //console.log('filterby ='+filterby );
                        //console.log('search ='+search );
                        table.columns(1).search(this.value).columns(0).search(filterby).draw();   
                    });             
                    
                }
            } );
            $("#event_registrations_length").addClass("col-md-2 input-n");
            $("#search").addClass("input-n");
            $("#filterby").addClass("input-n");
            $("div.dt-buttons").addClass("col-md-1 btn-new");
            $('[name="event_registrations_length"]').addClass("form-control");
            $('[name="event_registrations_length"]').css("width", "75px");
            $('[name="event_registrations_length"]').css("height", "34px");
            $('[name="event_registrations_length"]').css("display", "inline-block");
            /*$('.dataTables_length').css("position","absolute");
            $('.dataTables_length').css("margin-top","-50px");*/
            $('.dataTables_length > label').css("font-weight", "normal");
            $('.dataTables_length > label').css("font-size", "14px");
            
            $('.dt-button.buttons-csv').css("padding", "inherit");
            $('.dt-button.buttons-csv').css("font-size", "14px");
            
            $('button').addClass("form-control");

            $('#add_user_form').submit(function(){
                let pass1 = $('#password').val();
                let pass2 = $('#password_confirm').val();
                if(pass1 != pass2){
                    $('.pass_error').show();
                    return false;
                }
                $.ajax({
                    url: '/users/validate-email',
                    type: 'POST',  
                    data: {email: $('#email'), _token: $('meta[name="_token"]').val(),event_id: $("#event_id").val()},
                    success: function (data) {  
                        if(data=="exist"){
                            $('.email_error').show();
                            return false;
                        }  
                    }
                });
            })
            
        });

</script>
<link href="/css/event_registrations.css" rel="stylesheet" type="text/css">
@stop
