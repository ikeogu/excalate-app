<?php

namespace App\Services;

use App\Models\EmailVerification;
use App\Models\User;
use App\Notifications\EmailVerificationNotification;
use App\Notifications\ForgotPassword;
use Illuminate\Support\Facades\DB;

class VerificationService
{
    public static function generateAndSendOtp(User $user): void
    {
        $otp = rand(100000, 999999);

        //check if otp already exist in the database
        $check_otp = EmailVerification::where('otp', $otp)->first();

        //if otp exist, generate new otp
        if(empty($check_otp)){
            DB::transaction(function () use ($user, $otp) {
                EmailVerification::updateOrCreate(
                    ['email' => $user->email],
                    ['email' => $user->email, 'otp' => $otp, 'expired_at' => now()->addMinutes(10)]
                );

                $user->notify(new EmailVerificationNotification($otp));
                if ($user->phone_number) {
                    static::send_otp($user->phone_number, "Your OTP is $otp");
                }
            });
        }else{
            static::generateAndSendOtp($user);
        }

    }

    //send sms otp
    public static function send_otp(string $recipients, mixed $message) : mixed
    {

         $number = preg_replace('/^0/', '234', $recipients);

        $curl = curl_init();
        $data = array(
            "api_key" => config('sms.termii_api_key'),
            "to" => $number,
            "from" => "ChurchVest",
            "sms" => $message,
            "type" => "plain",
            "channel" => "generic"
        );

        $post_data = json_encode($data);

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.ng.termii.com/api/sms/send",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => $post_data,
            CURLOPT_HTTPHEADER => array(
                "Content-Type: application/json"
            ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);
        return $response;
    }

    public static function generateAndSendOtpForgotPassword(User $user): void
    {
        $otp = rand(100000, 999999);

       $check_otp = EmailVerification::where('otp', $otp)->first();

        //if otp exist, generate new otp
        if(empty($check_otp)){
            DB::transaction(function () use ($user, $otp) {
                EmailVerification::updateOrCreate(
                    ['email' => $user->email],
                    ['email' => $user->email,
                    'otp' => $otp, 'expired_at' => now()->addMinutes(10)
                    ]
                );

                $user->notify(new ForgotPassword($otp));
                if ($user->phone_number) {
                    static::send_otp($user->phone_number, "Your OTP is $otp");
                }
            });
        }else{
           static::generateAndSendOtpForgotPassword($user);
        }
    }
}