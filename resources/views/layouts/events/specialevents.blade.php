@extends('layouts.dashboard')
@section('page_heading','Special Events')
@section('section')              
          
    <div class="col-md-12">
            <!-- /. events table --> 
        
        <div class="row" style="margin-top:20px">
            <div class="col-md-12">   
                    <div class="row">
                            <div class="col-md-12">
                                <div class=" col-md-2 pull-right">
                                    <a class="btn btn-default btn-block btn-blue  " href="{{ url ('events/step/1of9/create-event-overview/0') }}">Add Event</a>
                                </div>
                                <div class="pull-right col-md-2">
                                   <input type="text" name="search" id="search" class="form-control " placeholder="Search">
                                </div>
                                
                            </div>
                        </div>     
                <table id="events_all" class="table table-condensed table-bordered table-striped " style="margin-top: 20px ">
                    <thead>
                        <tr>
                            <th>Event Name</th>
                            <th>Event Type</th>
                            <th>Event Date</th>
                            <th>Visibility</th>
                            <th>Edit</th>
                            <th>Delete</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($data['events'] as $e)
                        <tr>
                            <td>{{$e->event_name}}</td>
                            <td>{{$e->event_type}}</td>
                            <td data-sort="{{$e->sd}}">{{$e->start_date}}</td>
                            <td>
                                @if($e->visibility_web=="Published")
                                    Web
                                @endif
                                @if($e->visibility_web=="Published" && $e->visibility_app=="Published")
                                /
                                @endif
                                @if($e->visibility_app=="Published")
                                    App
                                @endif
                                @if($e->visibility_web=="Unpublished" && $e->visibility_app=="Unpublished")
                                Unpublished
                                @endif
                            </td>
                            <td><span class="col-md-1 text-muted small"><em><a href="{{ route('editEventOverview',['step' => '0','event_id' => $e->id]) }} "><i class="fa fa-pencil"></i></a> </em></span></td>
                            <td><span class="col-md-1 text-muted small"><em><a href="#" onclick="deleteEvent({{$e->id}})"><i class="fa fa-trash"></i></a> </em></span></td>
                        </tr>
                        @endforeach
                    </tbody>

                </table>
            </div>
        </div>
                
            
            <!-- /.events table -->
    </div>
             
    <script>
        $(document).ready(function() {
            var table = $('#events_all').DataTable({
            responsive: true,
            dom: 'lr<"table-filter-container">tip',
                 /* Disable initial sort */
            "aaSorting": [],
            initComplete: function(settings){
                /*var api = new $.fn.dataTable.Api( settings );
                $('.table-filter-container', api.table().container()).append(
                    $('#table-filter').detach().show()
                );
                
                $('#filterby').on('change', function(){
                    table.search(this.value).draw();   
                }); */ 
                
                $('#search').keyup('change', function(){
                    table.search(this.value).draw();   
                });       
            }
            });
            $('[name="events_all_length"]').addClass("form-control");
            $('[name="events_all_length"]').css("width", "70px");
            $('[name="events_all_length"]').css("display", "inline-block");
            $('.dataTables_length').css("position","absolute");
            $('.dataTables_length').css("margin-top","-50px");
            $('.dataTables_length > label').css("font-weight", "normal");

        });
  //delete event
function deleteEvent(id){
    var prompt = window.confirm("Are you sure you want to delete this Event?");
    if(prompt){
        window.location.href = "/events/deleteevents?event_id="+id;
    }
}      
    </script>

@stop
