<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Models\RegistrationPasswords;
use Mail;

class WebsiteRegisterAccountController extends Controller
{
    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    public function create(Request $request)
    {
        
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'company' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'phone' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
           // 'password' => 'required|string|min:6',
        ]);

        $user = new User;
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->company = $request->company;
        $user->title = $request->title;
        //$user->customer_type = $request->customer_type;
        $user->customer_type = 1;
        $user->phone = $request->phone;
        $user->email = $request->email;
        $user->enabled = 0;
        //$user->password = bcrypt($request->password);
        $user->password = bcrypt($request->email);
        $user->save();

        if($user){
            $data = [
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
                'company' => $user->company, 
                'phone' => $user->phone,
                'email' => $user->email
            ];
            // Store password in plaintext version
            // it will be used and deleted during approval
            $rp = new RegistrationPasswords;
            $rp->user_id = $user->id;
            //$rp->registration_password = $request->password;
            $rp->registration_password = $user->email;
            $rp->save();

            $to_email = $request->email;
            $to_name = $request->first_name;
            Mail::send('emails.registration',[], function($message) use ($to_email, $to_name) {
                $message->to($to_email, $to_name)
                        ->subject('Thank you for registering an account with havtechevents.com');
                $message->from('marketing@havtech.com','Havtech Events');
            });

            //$to_email = "reymondb@codev.com";
            $to_email = "marketing@havtech.com";
            $to_name = "havtech";
            Mail::send('emails.newregistration',$data, function($message) use ($to_email, $to_name) {
                $message->to($to_email, $to_name)
                        ->subject('New Account created from havtechevents.com');
                $message->from('marketing@havtech.com','Havtech Events');
            });


            return redirect()->route('create_account_success');
            //return view('frontend.createaccountsuccess');
        }
        return redirect()->route('homepage');
    }

    public function success(){
        return view('frontend.createaccountsuccess');
    }

}
