<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Models\Events;
use App\Models\Notifications;
use App\Models\EventPhotos;
use DB;


class DashboardController extends Controller
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
        // if (Gate::allows('user-only', auth()->user()) || Gate::allows('company-only', auth()->user())) {
        $upcomingevents=Events::select('id','event_name','start_date','end_date') 
                ->latest()
                ->limit(17)->select(DB::raw('DATE_FORMAT(start_date, "%b %e, %Y at %l:%i %p") as start_date'),'id','event_name','end_date')
                ->get();
                //->orderBy('notif_date','desc')
        $notifs =Notifications::select('id','title','notif_date')->latest()->paginate(10);  
        $photos =EventPhotos::select('id','filename','resized_name','original_name')->latest()->paginate(10);  

            $data = array(
                'upcomingevents' => $upcomingevents,
                'notifs' =>$notifs,
                'photos' =>$photos
                /*,
                'location_colors' => $locationColors,
                'latest_scan' => $lastScannedItems*/
            );
            return view('layouts/dashboard/home')->with('data', $data);
        //}elseif(Gate::allows('tpl-only', auth()->user())){
        //   return redirect('/thirdparty/dashboard');
        //}
    
       

    }
}
