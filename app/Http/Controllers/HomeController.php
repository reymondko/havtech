<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;
use Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      if(Auth::check()) {
          return redirect('/dashboard');
      } else {
          return view('auth.login');
      }
    }

    public function downloadFile(Request $request, $file) {
      return response()->download(storage_path('app/public/'.$file));
    }

    public function uploadFile(Request $request, $file) {
      return response()->download(storage_path('app/public/'.$file));
    }

    public function emailInvite(Request $request) {
      return view('mail.event_invite', ['email' => 'James','password' => 'waw']);
    }

    public function passwordResetSuccess(Request $request) {
      Auth::logout();
      return view('auth.password-reset-success');
    }

    /**
     * Saves image to the public folder
     * 
     * @return \Illuminate\Http\Response
     */
    public function saveAdminFile(Request $request){
      if($request->file('image')){
        $image = $request->file('image');
        $name = md5($image->getClientOriginalName() . time()).'.'.$image->getClientOriginalExtension();
        $upload = Image::make($image)->save(public_path().'/uploads/' . $name);
        return '/uploads/'.$name;
      }
    }
}
