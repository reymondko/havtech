<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Gate;
use App\Models\InventoryFields;
use App\Models\Locations;
use App\Models\CustomOrders;
use App\Models\Inventory;
use App\Models\NotificationSettings;
use App\Models\NotificationOrderSettings;
use App\Models\Skus;
use App\Models\Users;
use App\Models\CaseLabelRequiredFields;
use Carbon\Carbon;

class SettingsController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {   
        $usr = Users::where('id',\Auth::user()->id)->first();
        $user = array(
            'first_name' => \Auth::user()->first_name,
            'last_name' => \Auth::user()->last_name,
            'email' => \Auth::user()->email,
            'receive_notifications' => $usr->receive_notifications
        );
        $data = array(
            'user' => $user
        );

        return view('layouts/settings/userprofile')->with('data',$data);
    }

    public function updateUserSettings(){
        if($_POST){
            \Auth::user()->first_name = $_POST['first_name'];
            \Auth::user()->last_name = $_POST['last_name'];
            \Auth::user()->email = $_POST['email'];

            if($_POST['password'] != null){
                \Auth::user()->password = Hash::make($_POST['password']);
                
            }

            if(isset($_POST['receive_notifications'])){
                $notif = 1;                
            }
            else{
                $notif = 0;
            }
            
            $usr = Users::where('id',\Auth::user()->id)->first();
            $usr->receive_notifications = $notif;
            $usr->save();

            if(\Auth::user()->save()){
                return redirect('settings')->with('status', 'saved');
            }else{
                return redirect('settings')->with('status', 'error_saving');
            }
            

        }
    }
}
