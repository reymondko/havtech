<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Models\Users;
use Illuminate\Support\Facades\Hash;
use App\Imports\UsersImport;
use App\Models\CustomerType;
use App\Mail\SendInvites;
use App\Models\DeclinedAccounts;
use App\Models\RegistrationPasswords;
use Mail;
use Maatwebsite\Excel\Facades\Excel;

use DB;
use Session;
use Extension;


class PendingAccountsController extends Controller
{
    public function index(){
        if (Gate::allows('admin', auth()->user())) {
            // Select Pending Users
            $users = Users::where('enabled',0)
                            ->where('deleted',0)
                            ->get();
            return view('layouts.pending')->with('users', $users);
        }
        return redirect()->route('homepage');
    }

    public function approve($param){
        if (Gate::allows('admin', auth()->user())) {

            if($param == 'all'){
                $users = Users::where('enabled',0)
                            ->where('deleted',0)
                            ->get();
            }else{
                $users = Users::where('enabled',0)
                            ->where('deleted',0)
                            ->where('id',$param)
                            ->get();
            }

            if($users){
                foreach($users as $user){

                    // Get Registration password to send with the Approval email
                    $rp = RegistrationPasswords::where('user_id',$user->id)->first();

                    $user->enabled = 1;

                    $to_email = $user->email;
                    $to_name = $user->first_name.' '.$user->last_name;
                    $data = ['password'=>$rp->registration_password];
                    
                    Mail::send('emails.approved',$data, function($message) use ($to_email, $to_name) {
                        $message->to($to_email, $to_name)
                                ->subject('Havtech Account Approved');
                        $message->from('marketing@havtech.com','Havtech Events');
                    });

                    // Delete the record after it has been used
                    if($rp){
                        $rp->delete();
                    }

                    $user->save();
                }
            }


            return redirect()->route('pending_accounts')->with('status', 'saved');
        }
        return redirect()->route('homepage');
    }

    public function delete($param){
        if (Gate::allows('admin', auth()->user())) {
            if($param == 'all'){
                $users = Users::where('enabled',0)
                            ->where('deleted',0)
                            ->get();
            }else{
                $users = Users::where('enabled',0)
                ->where('deleted',0)
                ->where('id',$param)
                ->get();
            }

            if($users){
                $deleted_users = [];
                foreach($users as $user){
                    $deleted_users[] = [
                        'email'=>$user->email,
                        'password' => $user->password,
                        'company' => $user->company,
                        'customer_type' => $user->customer_type,
                        'industry' => $user->industry,
                        'first_name' => $user->first_name,
                        'last_name' => $user->last_name,
                        'address1' => $user->address1,
                        'address2' => $user->address2,
                        'city' => $user->city,
                        'state' => $user->state,
                        'zip' => $user->zip,
                        'phone' => $user->phone,
                        'title' => $user->title
                    ];

                    $to_email = $user->email;
                    $to_name = $user->name;

                    Mail::send('emails.decline',[], function($message) use ($to_email, $to_name) {
                        $message->to($to_email, $to_name)
                                ->subject('Havtech Registration Declined');
                        $message->from('marketing@havtech.com','Havtech Events');
                    });

                    $user->delete();
                }
                if(count($deleted_users) > 0){
                    DeclinedAccounts::insert($deleted_users);
                }
            }

            return redirect()->route('pending_accounts')->with('status', 'deleted');
        }
        return redirect()->route('homepage');
    }
}
