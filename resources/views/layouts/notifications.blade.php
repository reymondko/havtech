@extends('layouts.dashboard')
@section('page_heading','Notifications')
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
        <!-- /. notifications table --> 
                
        @endif
        <div class="row" style="margin-top:20px">
            <div class="col-md-12">   
                    <div class="row">
                            <div class="col-md-12">
                                <div class=" col-md-2 pull-right">
                                    <a href="/notifications/new" class="btn btn-default btn-block btn-blue " >New Notification</a>
                                </div>
                                <div class="pull-right col-md-2">
                                </div>
                            </div>
                        </div>     
                <table id="notifications_all" class="table table-condensed table-bordered table-striped " style="margin-top: 20px ">
                    <thead>
                        <tr>
                            <th>Notifications</th>
                            <th>Date</th>
                            <th>Time</th>
                            <th>Status</th>
                            <th>Edit</th>
                            <th>Delete</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($notifications as $n)
                        <tr>
                            <td>{{$n->title}}</td>
                            <td data-sort="{{date("Y-m-d",strtotime($n->notif_date))}}">{{date("F j,Y",strtotime($n->notif_date))}}</td>
                            <td data-sort="{{date("H:i:s",strtotime($n->notif_date))}}">{{date("h:i a",strtotime($n->notif_date))}}</td>
                            <td></td>
                            <td><span class="col-md-1 text-muted small"><em>
                                <a href="/notifications/edit/{{$n->id}}">
                                <i class="fa fa-pencil"></i></a> </em></span></td>
                            <td><span class="col-md-1 text-muted small"><em><a href="#" onclick="deleteEvent({{$n->id}})"><i class="fa fa-trash"></i></a> </em></span></td>
                        </tr>
                        @endforeach
                    </tbody>

                </table>
            </div>
        </div>
                
            
            <!-- /.notifications table -->
    </div>

              
    <script>
        $(document).ready(function() {
            var table = $('#notifications_all').DataTable({
                "order": [[ 1, "desc" ]],
            responsive: true,
            dom: 'lr<"table-filter-container">tip',
            initComplete: function(settings){
                var api = new $.fn.dataTable.Api( settings );
                $('.table-filter-container', api.table().container()).append(
                    $('#table-filter').detach().show()
                );
                
                $('#filterby').on('change', function(){
                    table.search(this.value).draw();   
                });       
            }
            });
            $('[name="notifications_all_length"]').addClass("form-control");
            $('[name="notifications_all_length"]').css("width", "70px");
            $('[name="notifications_all_length"]').css("display", "inline-block");
            $('.dataTables_length').css("position","absolute");
            $('.dataTables_length').css("margin-top","-50px");
            $('.dataTables_length > label').css("font-weight", "normal");

            $('#add_user_form').submit(function(){
                let pass1 = $('#password').val();
                let pass2 = $('#password_confirm').val();
                if(pass1 != pass2){
                    $('.pass_error').show();
                    return false;
                }
            })
            
        });

    function editUser(id,first_name,last_name,email,company,phone){
        $('#edit_user_form #id_edit').val(id);
        $('#edit_user_form #first_name').val(first_name);
        $('#edit_user_form #last_name').val(last_name);
        $('#edit_user_form #email').val(email);
        $('#edit_user_form #company').val(company);
        $('#edit_user_form #phone').val(phone);
    }
    function deleteEvent(id){
        var prompt = window.confirm("Are you sure you want to delete this notification?");
        if(prompt){
            window.location.href = "/notifications/delete?notif_id="+id;
        }
    }
</script>

@stop
