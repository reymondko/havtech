<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use App\User;

class UpdateUserPassEmailToLowerCase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:to_lowercase';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Updates email and password of the users to lowercase where the temporary password is not yet set and has not yet logged in';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // Get users where temporary password `temporary_pw_status` = 0
        // And remember token `remember_token` = null

        $users = User::whereNull('remember_token')->where('temporary_pw_status',1)->get();
        if($users){
            foreach($users as $user){
                print(' updating user: ' . $user->email);
                $user->email = strtolower($user->email);
                $user->password = Hash::make(strtolower($user->email));
                $user->save();
            }
        }
    }
}
