<?php

namespace App\Http\Controllers\Auth;

use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use App\Models\UserLogs;
use App\Models\LastLogin;

class ApiAuthController extends Controller
{
    /**
     * User login through API
     *
     * @param Request $request
     *   The request parameter containing `email` and `password`
     *
     * @return String
     *   JSON encoded response string
     */
    public function login(Request $request) {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string'
        ]);
        $credentials = request(['email', 'password']);
        if(!Auth::attempt($credentials))
            return response()->json([
                'message' => 'Unauthorized'
            ], 401);
        $user = $request->user();
        $tokenResult = $user->createToken('Personal Access Token');
        $token = $tokenResult->token;
        if ($request->remember_me)
            $token->expires_at = Carbon::now()->addWeeks(1);
        $token->save();


        $userLogs = new UserLogs;
        $userLogs->users_id = $user->id;
        //$userLogs->companies_id = \Auth::user()->companies_id;
        $userLogs->log_type = 'login';
        $userLogs->ip = $_SERVER['REMOTE_ADDR'];
        $userLogs->created_at = Carbon::now();
        $userLogs->updated_at = Carbon::now();
        $userLogs->save();

        return response()->json([
            'access_token' => $tokenResult->accessToken,
            'token_type' => 'Bearer',
            'expires_at' => Carbon::parse(
                $tokenResult->token->expires_at
            )->toDateTimeString()
        ]);
    }

    /**
     * User login for the first time through API
     * and replace password
     *
     * @param Request $request
     *   The request parameter containing `email` and `password`
     *
     * @return String
     *   JSON encoded response string
     */
    public function loginFirstTime(Request $request) {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string'
        ]);
        if($request->password_new == null || $request->password_new == ''){
            return response()->json([
                'message' => 'Invalid New Password'
            ], 401);
        }
        $credentials = request(['email', 'password']);
        if(!Auth::attempt($credentials))
            return response()->json([
                'message' => 'Unauthorized'
            ], 401);
        $user = $request->user();
        $tokenResult = $user->createToken('Personal Access Token');
        $token = $tokenResult->token;
        $logged_in_user = User::where('id',$user->id)->first();
        $logged_in_user->password = Hash::make($request->password_new);
        $logged_in_user->temporary_pw_status = 0;
        $logged_in_user->save();

        if ($request->remember_me)
            $token->expires_at = Carbon::now()->addWeeks(1);
        $token->save();
        return response()->json([
            'access_token' => $tokenResult->accessToken,
            'token_type' => 'Bearer',
            'expires_at' => Carbon::parse(
                $tokenResult->token->expires_at
            )->toDateTimeString()
        ]);
    }

    /**
     * User logout through API
     * Revokes access token
     * @param Illuminate\Http\Request;
     * @return String
     *   JSON encoded response string
     */
    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        return response()->json([
            'message' => 'Successfully logged out'
        ]);
    }

    /**
     * Sends Reset Password
     * @param Illuminate\Http\Request;
     * @return String
     *   JSON encoded response string
     */
    public function sendResetEmail(Request $request)
    {

        $user_email = User::select('email')
                            ->where('email',$request->email)
                            ->first();

        if(isset($user_email)){
            $response = Password::sendResetLink(['email' => $user_email->email], function (Message $message) {
                $message->subject($this->getEmailSubject());
            });
            return response()->json(['message'=>'success']);
        }else{
            return response()->json(['message'=>'Email does not belong to user'],500);
        }
    }
}
