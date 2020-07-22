@extends('layouts.dashboard')
@section('page_heading','Users')
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
        <!-- /. users table -->

        @endif
        <div class="row" >
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-12" style=" z-index: 20;">
                        <div class=" col-md-8 pull-right">
                            <div class="col-md-7  ">
                                <div class="col-md-2 pull-left text-center" style="font-size: 12px;">
                                    <a href="/download/Sample Import Attendees.xlsx">Download Sample file</a>
                                </div>
                                <div class="col-md-10  ">
                                    <form action="{{ route('import') }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <div class="col-md-1 radio-inline pull-right">
                                            <button class="btn btn-success">Import</button>
                                        </div>
                                        <div class="col-md-10 pull-right">
                                            <input type="file" name="file" class="form-control radio-inline " accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel" >
                                        </div>
                                    </form>
                                </div>
                            </div>
                        <button type="button" class="btn btn-default btn-blue col-md-3 pull-right " data-toggle="modal" data-target="#addUserModal" >Add User</a>
                    </div>

                    <div class="pull-right col-md-2">
                        <input type="text" placeholder="Search User" id="user_search" class="form-control">
                    </div>
                </div>
            </div>
                <table id="users_all" class="table table-condensed table-bordered table-striped " style="margin-top: 40px ">
                    <thead>
                        <tr>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Email</th>
                            <th>Company Name</th>
                            <th>Phone</th>
                            <th>Customer Type</th>
                            <th>Date Added</th>
                            <th>Last Login</th>
                            <th style="width:60px;">Admin</th>
                            <th style="width:60px;">Edit</th>
                            <th style="width:60px;">Delete</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $u)
                        <tr>
                            <td>{{$u->first_name}}</td>
                            <td>{{$u->last_name}}</td>
                            <td>{{$u->email}}</td>
                            <td>{{$u->company}}</td>
                            <td>{{$u->phone}}</td>
                            <td>{{$u->customer_type}}</td>
                            <td>{{date("F j,Y",strtotime($u->created_at))}}</td>
                            <td>@if ($u->last_login) {{date("F j,Y",strtotime($u->last_login))}} @endif</td>
                            
                            <td style="text-align:center;"><input type="checkbox" name="makeAdmin" id="makeAdmin"  onclick="makeAdmin({{$u->id}})" value="1" @if($u->role==1) checked @endif @if($u->id==Auth::user()->id) disabled @endif></td>
                            <td><span class="col-md-1 text-muted small"><em>
                                <a href="#"
                                data-toggle="modal"
                                data-target="#editUserModal"
                                onClick="editUser('{{ $u->id }}','{{ $u->first_name }}','{{ $u->last_name }}','{{ $u->email }}','{{ $u->company }}','{{ $u->phone }}','{{ $u->customer_type_id }}','{{ $u->title }}','{{ $u->role }}')">
                                <i class="fa fa-pencil"></i></a> </em></span></td>
                            <td><span class="col-md-1 text-muted small"><em><a href="#" onclick="deleteUser({{$u->id}})"><i class="fa fa-trash"></i></a> </em></span></td>
                        </tr>
                        @endforeach
                    </tbody>

                </table>
            </div>
        </div>

            <!-- /.users table -->
    </div>

<!--Add User Modal -->
<div class="modal fade" id="addUserModal" tabindex="-1" role="dialog" aria-labelledby="addUserModallLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="addUserModalLabel">Add User</h4>
            </div>
            <form id="add_user_form" class="form-horizontal" method="POST" action="{{ route('add_user') }}" >
                @csrf
                <div class="modal-body">
                <fieldset>
                    <!-- Form Name -->
                    <!-- Text input-->
                    <div class="form-group">
                        <label class="col-md-3 control-label" for="first_name"></label>
                        <div class="col-md-6">
                            <input id="first_name" name="first_name" type="text" autocomplete="off" placeholder="First Name" class="form-control input-md" required="">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label" for="last_name"></label>
                        <div class="col-md-6">
                            <input id="last_name" name="last_name" type="text" autocomplete="off" placeholder="Last Name" class="form-control input-md" required="">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label" for="company"></label>
                        <div class="col-md-6">
                            <input id="company" name="company" type="text" autocomplete="off" placeholder="Company" class="form-control input-md">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label" for="title"></label>
                        <div class="col-md-6">
                            <input id="title" name="title" type="text" autocomplete="off" placeholder="Title" class="form-control input-md">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label" for="title"></label>
                        <div class="col-md-6">
                            <select id="customer_type" name="customer_type" class="form-control"  autocomplete="off" >
                                <option value="">Select Customer Type</option>
                                @foreach($customertype as $i)
                                    <option value="{{ $i->id }}">{{ $i->type }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <!-- Text input-->
                    <div class="form-group">
                        <label class="col-md-3 control-label" for="email"></label>
                        <div class="col-md-6">
                            <input id="email" name="email" type="email" autocomplete="off" placeholder="User Email" class="form-control input-md" required="">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-3 control-label" for="phone"></label>
                        <div class="col-md-6">
                            <input id="phone" name="phone" type="text" autocomplete="off" placeholder="Phone Number" class="form-control input-md">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-3 control-label" for="title"></label>
                        <div class="col-md-6">
                            <select id="role" name="role" class="form-control"  autocomplete="off" >
                                <option value="">Select User Type</option>
                                @foreach($roles as $r)
                                    <option value="{{ $r->id }}">{{ $r->description }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Password input-->
                    <div class="form-group">
                        <label class="col-md-3 control-label" for="password"></label>
                        <div class="col-md-6">
                            <input id="password" name="password" autocomplete="off" type="password" placeholder="Password" class="form-control input-md" required="">
                        </div>
                    </div>
                    <!-- Password input-->
                    <div class="form-group">
                        <label class="col-md-3 control-label" for="password_confirm"></label>
                        <div class="col-md-6">
                            <input id="password_confirm" name="password_confirm" autocomplete="off" type="password" placeholder="Confirm Password" class="form-control input-md" required="">
                            <span class="pass_error">passwords do not match</span>
                        </div>
                    </div>
                    <!-- Button -->
                    <div class="form-group">
                        <label class="col-md-4 control-label" for="submit"></label>
                    </div>
                </fieldset>
                </div>
                <div class="modal-footer">
                <button type="button" class="btn btn-flat close-btn" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-flat add-user-btn">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!--edit User Modal -->
<div class="modal fade" id="editUserModal" tabindex="-1" role="dialog" aria-labelledby="editUserModallLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="editUserModalLabel">Edit User</h4>
            </div>
            <form id="edit_user_form" class="form-horizontal" method="POST" action="{{ route('edit_user') }}" >
                @csrf
                <div class="modal-body">
                    <fieldset>
                        <!-- Form Name -->
                        <!-- Text input-->
                        <div class="form-group">
                            <label class="col-md-3 control-label" for="email"></label>
                            <div class="col-md-6">
                                <input id="first_name" name="first_name" type="text" autocomplete="off" placeholder="First Name" class="form-control input-md" required="">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label" for="email"></label>
                            <div class="col-md-6">
                                <input id="last_name" name="last_name" type="text" autocomplete="off" placeholder="Last Name" class="form-control input-md" required="">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label" for="company"></label>
                            <div class="col-md-6">
                                <input id="company" name="company" type="text" autocomplete="off" placeholder="Company" class="form-control input-md">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label" for="title"></label>
                            <div class="col-md-6">
                                <input id="title" name="title" type="text" autocomplete="off" placeholder="Title" class="form-control input-md">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label" for="title"></label>
                            <div class="col-md-6">
                                <select id="customer_type" name="customer_type" class="form-control"  autocomplete="off" >
                                    <option value="">Select Customer Type</option>
                                    @foreach($customertype as $i)
                                        <option value="{{ $i->id }}">{{ $i->type }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <!-- Text input-->
                        <div class="form-group">
                            <label class="col-md-3 control-label" for="email"></label>
                            <div class="col-md-6">
                                <input id="email" name="email" type="email" autocomplete="off" placeholder="User Email" class="form-control input-md" required="">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-3 control-label" for="phone"></label>
                            <div class="col-md-6">
                                <input id="phone" name="phone" type="text" autocomplete="off" placeholder="Phone Number" class="form-control input-md">
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="col-md-3 control-label" for="title"></label>
                            <div class="col-md-6">
                                <select id="role" name="role" class="form-control"  autocomplete="off" >
                                    <option value="">Select User Type</option>
                                    @foreach($roles as $r)
                                        <option value="{{ $r->id }}">{{ $r->description }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>


                        <!-- Password input-->
                        <div class="form-group">
                            <label class="col-md-3 control-label" for="password"></label>
                            <div class="col-md-6">
                                <input id="password" name="password" autocomplete="off" type="password" placeholder="Password" class="form-control input-md">
                            </div>
                        </div>
                        <!-- Password input-->
                        <div class="form-group">
                            <label class="col-md-3 control-label" for="password_confirm"></label>
                            <div class="col-md-6">
                                <input id="password_confirm" name="password_confirm" autocomplete="off" type="password" placeholder="Confirm Password" class="form-control input-md">
                                <span class="pass_error">passwords do not match</span>
                            </div>
                        </div>
                        <!-- Button -->
                        <div class="form-group">
                            <label class="col-md-4 control-label" for="submit"></label>
                        </div>
                    </fieldset>
                </div>
                <div class="modal-footer">
                <button type="button" class="btn btn-flat close-btn" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-flat edit-user-btn">Submit</button>
                </div>
                <input type="hidden" name="id_edit" id="id_edit" >
            </form>
        </div>
    </div>
</div>
    <script>
        $(document).ready(function() {
            var table = $('#users_all').DataTable({
            responsive: true,
            dom: 'lrB<"table-filter-container">tip',
            responsive: true,
            "bFilter": true,
            "columnDefs": [ { orderable: false, targets: [8,9,10] }, { visible: false, targets: [3,4] } ],
            buttons: [
                {
                extend: 'excelHtml5',
                title: 'Havtech EventsHub Users',
                exportOptions: {
                    columns: [ 0, 1, 2, 3, 4, 5 ]
                }
            },
            {
                extend: 'pdfHtml5',
                title: 'Havtech EventsHub Users',
                exportOptions: {
                    columns: [ 0, 1, 2, 3, 4, 5 ]
                }
            }
            ],
            initComplete: function(settings){
                var api = new $.fn.dataTable.Api( settings );
                $('.table-filter-container', api.table().container()).append(
                    $('#table-filter').detach().show()
                );

                $('#user_search').keyup('change', function(){
                    table.search(this.value).draw();
                });
            },
            
            "pageLength": 10,
            "aLengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
            });
            $('[name="users_all_length"]').addClass("form-control");
            $('[name="users_all_length"]').css("width", "70px");
            
            $('[name="users_all_length"]').css("z-index", "21");
            $('.dt-buttons').css("z-index", "21");
            $('#users_all_length').css("z-index", "21");
            
            
            $('#users_all_length').css("padding-right", "20px");
            $('[name="users_all_length"]').css("display", "inline-block");

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

    function editUser(id,first_name,last_name,email,company,phone,customer_type,title,role){
        $('#edit_user_form #id_edit').val(id);
        $('#edit_user_form #first_name').val(first_name);
        $('#edit_user_form #last_name').val(last_name);
        $('#edit_user_form #email').val(email);
        $('#edit_user_form #company').val(company);
        $('#edit_user_form #phone').val(phone);
        $('#edit_user_form #customer_type').val(customer_type);
        $('#edit_user_form #title').val(title);
        $('#edit_user_form #role').val(role);
    }
    function deleteUser(id){
        var prompt = window.confirm("Are you sure you want to delete this user?");
        if(prompt){
            window.location.href = "/users/delete?user_id="+id;
        }
    }
    function makeAdmin(id){
        
            window.location.href = "/users/makeadmin?user_id="+id;
    }
    
</script>
<style>
    .dataTables_wrapper{
        margin-top: 20px;
    }
</style>
@stop
@section('js')
    
<script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.2/js/buttons.flash.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.2/js/buttons.print.min.js"></script>

@stop
