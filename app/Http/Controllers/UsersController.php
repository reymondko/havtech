<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Models\Users;
use Illuminate\Support\Facades\Hash;
use App\Imports\UsersImport;
use App\Models\UserRoles;
use App\Models\CustomerType;
use App\Mail\SendInvites;
use Mail;
use Maatwebsite\Excel\Facades\Excel;

use DB;
use Session;
use Extension;

class UsersController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
       // $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $customertype=CustomerType::all();
        $userRoles=UserRoles::all();
        $users = DB::table('users')
        ->leftJoin('customer_type' , function($q)
        {
            $q->on('users.customer_type', '=', 'customer_type.id');
        })->leftJoin(
            DB::raw("(SELECT user_logs.users_id, max(date_add(user_logs.created_at, interval -5 hour)) as created_at from user_logs group by user_logs.users_id) as logs"), 
            'logs.users_id', '=', 'users.id'
            )
        ->get(['users.*','customer_type.type as customer_type','customer_type.id as customer_type_id','logs.created_at as last_login']);
        return view('layouts/users')->with('users', $users)->with('customertype',$customertype)->with('roles',$userRoles);

    }
    public function import()
    {
        Excel::import(new UsersImport,request()->file('file'));
        return back();
    }

    public function addUser(){
        $email=strtolower($_POST['email']);
        $email_check = Users::where('email',$email)->first();
        if($email_check){
            return redirect('users')->with('status', 'email_error');
        }
        else{

            $user = new Users;
            $user->email    = $email;
            $user->first_name    = $_POST['first_name'];
            $user->last_name    = $_POST['last_name'];
            $user->company = $_POST['company'];
            $user->title = $_POST['title'];
            $user->customer_type = $_POST['customer_type'];
            $user->phone    = $_POST['phone'];
            $user->password =  Hash::make($email);
            $user->temporary_pw_status =  1;
            $user->role = $_POST['role'] ?? 2; // default to normal user
            $user->save();

            $data = new \stdClass();
            $data->sender = 'Havtech EventsHub';
            $data->email = $email;
            $data->temporary_pw_status= 1;
            $data->password = $email;
            Mail::to($email)->send(new SendInvites($data));

            $userupdate=Users::where('id', $user->id)->update(['email_sent' => 1]);
            return redirect('users')->with('status', 'saved');
        }
    }

    public function editUser(Request $request){
        $user = Users::where('id',$request->id_edit)->first();
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->company = $request->company;
        $user->phone = $request->phone;
        $user->email = $request->email;
        $user->password =  Hash::make($request->password);
        $user->role = $_POST['role'] ?? 2; // default to normal user
        if($user->save()){
            return redirect('users')->with('status', 'saved');
        }
    }

    public function userDelete(Request $request){
        $delete=Users::where('id', $request->user_id)->first();
        $delete->delete();
        return back();
    }
    public function makeAdmin(Request $request){
        $makeAdmin=Users::where('id', $request->user_id)->first();
        if($makeAdmin->role==1){
            $makeAdmin->role = 0;            
        }
        else{
            $makeAdmin->role = 1;
        }
        $makeAdmin->save();
        return back();
    }

    
}
