<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Auth;
use App\Models\Events;
use App\Models\EventAttendees;
use App\User;
use App\Models\CustomerType;
use Carbon\Carbon;
use DateTime;

class WebsiteProfileController extends Controller
{
    public function edit(){
        if (Gate::allows('user', auth()->user()) || Gate::allows('admin', auth()->user())) {
            $user = Auth::user();
            $customer_types = CustomerType::get();
            return view('frontend.editaccount',compact(['user','customer_types']));
        }
        return redirect()->route('homepage');
    }

    public function saveEdit(Request $request){
        if (Gate::allows('user', auth()->user())) {
            $user = Auth::user();
            $customer_type = CustomerType::where('id',$request->customer_type ?? $user->customer_type)->first();
            $user->first_name = $request->first_name ?? $user->first_name;
            $user->last_name = $request->last_name ?? $user->last_name;
            $user->company = $request->company ?? $user->company;
            $user->industry= $customer_type->type;
            $user->title = $request->title ?? $user->title;
            $user->customer_type = $request->customer_type ?? $user->customer_type;
            $user->phone = $request->phone ?? $user->phone;
            if($request->password){
                $user->password = Hash::make($request->password);
            }
            $user->save();
            return redirect()->route('edit_user_account',['#saved'])->with('status', 'saved');
        }
        return redirect()->route('homepage');
    }
}
