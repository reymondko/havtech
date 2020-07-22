<?php

namespace App\Imports;

use App\Models\Users;
use App\Models\CustomerType;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use App\Mail\SendInvites;
use DB;
use Mail;
class UsersImport implements ToCollection, WithHeadingRow
{
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) 
        {
            $email_check = Users::where('email',$row['email'])->first();
            if($email_check){
            }
            else{
                $customer_type=1;
                $checkCustomerType = CustomerType::where('type','like','%'.$row['customer_type']."%")->first();
                if($checkCustomerType){
                    $customer_type=$checkCustomerType->id;
                }
                $email=strtolower($row['email']);
                $user=Users::create([
                    'first_name' => $row['first_name'],
                    'last_name' => $row['last_name'], 
                    'email' => $email,
                    'company' => $row['company_name'], 
                    'phone' =>  $row['phone'],
                    'customer_type' =>  $customer_type,
                    'password' =>  Hash::make($email),
                    'temporary_pw_status' =>  1,
                ]);
                
                $data = new \stdClass();
                $data->sender = 'Havtech EventsHub';
                $data->email = $row['email'];
                $data->temporary_pw_status= $row['email'];
                $data->password = $row['email'];
                Mail::to($row['email'])->send(new SendInvites($data));
                $userupdate=Users::where('id', $user->id)->update(['email_sent' => 1]);
            }
        }
    }
}
