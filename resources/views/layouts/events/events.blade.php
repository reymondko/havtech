@extends('layouts.dashboard')
@section('page_heading','Events')
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
                        <div class="pull-right">
                            Filter By  <select class="form-control " id="filterby"  style="width: 180px;display: inline-block;">
                                    <option></option>
                                    @foreach($data['eventtypes'] as $et)
                                    <option>{{$et->description}}</option>
                                    @endforeach
                                </select>
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
        $("#event_link").addClass('active');
        var table = $('#events_all').DataTable({
            responsive: true,
                 /* Disable initial sort */
            "aaSorting": [],
        dom: 'lr<"table-filter-container">tip',
        initComplete: function(settings){
            var api = new $.fn.dataTable.Api( settings );
            $('.table-filter-container', api.table().container()).append(
                $('#table-filter').detach().show()
            );
            
            var filterby="";
            var search="";
            /*$('#search').keyup('change', function(){
                table.search(this.value).draw();   
            });  
            $('#filterby').on('change', function(){
                table.search(this.value).draw();   
            });*/       

            $('#filterby').on('change', function(){
                filterby = this.value;
                console.log('filterby ='+filterby );
                console.log('search ='+search );
                table.columns(1).search(this.value).columns(0).search(search).draw();   
            }); 
            $('#search').keyup('change', function(){
                search=this.value;
                console.log('filterby ='+filterby );
                console.log('search ='+search );
                table.columns(0).search(this.value).columns(1).search(filterby).draw();   
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
