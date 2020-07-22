@extends('layouts.dashboard')
@section('page_heading','Pending Accounts')
@section('section')
    <div class="col-md-12">
        @if(session('status') == 'saved')
            <div class="alert alert-info alert-dismissible alert-saved">
                <button type="button" class="close alert-close-btn" data-dismiss="alert" aria-hidden="true">×</button>
                <h4><i class="icon fa fa-check"></i> Saved!</h4>
            </div>
        @elseif(session('status') == 'deleted')
            <div class="alert alert-info alert-dismissible alert-error">
                <button type="button" class="close alert-close-btn" data-dismiss="alert" aria-hidden="true">×</button>
                <h4><i class="icon fa fa-warning"></i> Deleted Successfully!</h4>
            </div>
        @elseif(session('status') == 'email_error')
            <div class="alert alert-info alert-dismissible alert-error">
                <button type="button" class="close alert-close-btn" data-dismiss="alert" aria-hidden="true">×</button>
                <h4><i class="icon fa fa-warning"></i> Email Already Exists!</h4>
            </div>
        <!-- /. users table -->
        @endif
        <div class="row" style="margin-top:20px">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-12">
                        <div class=" col-md-2 pull-right" style="padding:unset">
                            <input type="text" placeholder="Search User" id="user_search" class="form-control input-n">
                        </div>
                        <div class=" col-md-4 pull-right">
                            <button type="button" class="btn btn-default sec-btn col-md-4 pull-right " data-toggle="modal" data-target="#approveModal" onClick="setApprove('all')">Approve All</a></button> &nbsp;
                            <button type="button" class="btn btn-default pri-btn col-md-4 pull-right " data-toggle="modal" data-target="#deleteModal"  onClick="setDelete('all')" >Delete All</a></button>
                        </div>
                    </div>
            </div>
                <table id="users_all" class="table table-condensed table-bordered table-striped " style="margin-top: 20px ">
                    <thead>
                        <tr>
                            <th>Profile Name</th>
                            <th>Company</th>
                            <th>Title</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $u)
                        <tr>
                            <td>{{$u->first_name}} {{$u->last_name}}</td>
                            <td>{{$u->company}}</td>
                            <td>{{$u->title}}</td>
                            <td>{{$u->email}}</td>
                            <td>{{$u->phone}}</td>
                            <td>
                                <button type="button" class="btn btn-default sec-btn w-40" data-toggle="modal" data-target="#approveModal" onClick="setApprove({{$u->id}})">Approve</button>
                                <button type="button" class="btn btn-default pri-btn w-40" data-toggle="modal" data-target="#deleteModal" onClick="setDelete({{$u->id}})" >Delete</button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>

                </table>
            </div>
        </div>
    </div>

    <!-- APPROVE MODAL -->
    <div class="modal fade" id="approveModal" tabindex="-1" role="dialog" aria-labelledby="approveAllModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <p>
                        <h4 class="modal-title" id="addUserModalLabel">Approve Account(s)?</h4>
                    </p>
                    <p>
                        Are you sure you want to approve these accounts?</br>This action cannot be undone
                    </p>
                    <div>
                        <button type="button" class="btn btn-default pri-btn w-40" class="close" data-dismiss="modal">Cancel</button>
                        <a id="approve_link" href="#">
                            <button type="button" class="btn btn-default sec-btn w-40" data-toggle="modal">Approve</button>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- DELETE MODAL -->
    <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="approveAllModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <p>
                        <h4 class="modal-title" id="addUserModalLabel">Delete Account(s)?</h4>
                    </p>
                    <p>
                        Are you sure you want to delete these accounts?</br>This action cannot be undone
                    </p>
                    <div>
                        <button type="button" class="btn btn-default pri-btn w-40" class="close" data-dismiss="modal">Cancel</button>
                        <a id="delete_link" href="#">
                            <button type="button" class="btn btn-default sec-btn w-40" data-toggle="modal">Delete</button>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            var table = $('#users_all').DataTable({
            responsive: true,
            dom: 'lr<"table-filter-container">tip',
            initComplete: function(settings){
                var api = new $.fn.dataTable.Api( settings );
                $('.table-filter-container', api.table().container()).append(
                    $('#table-filter').detach().show()
                );

                $('#user_search').keyup('change', function(){
                    table.search(this.value).draw();
                });
            }
            });
            $('[name="users_all_length"]').addClass("form-control input-n");
            $('[name="users_all_length"]').css("width", "75px");
            $('[name="users_all_length"]').css("display", "inline-block");
            $('.dataTables_length').css("position","absolute");
            $('.dataTables_length').css("margin-top","-70px");
            $('.dataTables_length').css("font-size","21px");
            $('.dataTables_length > label').css("font-weight", "normal");

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

        function setApprove(p){
            console.log('approving' + p);
            $('#approve_link').attr("href", "/pending/approve/" + p);
        }

        function setDelete(p){
            console.log('deleting' + p);
            $('#delete_link').attr("href", "/pending/delete/" + p);
        }

</script>
<link href="/css/pending_accounts.css" rel="stylesheet" type="text/css">
@stop
