<?php

namespace App\Http\Controllers\API\Auth\SocialLite;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use Exception;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;


class FacebookController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return mixed
     */
    public function redirectToFacebook() : mixed
    {
        return Socialite::driver('facebook')->redirect();
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function handleFacebookCallback()
    {
        try {

            $user = Socialite::driver('facebook')->user();

            $finduser = User::where('facebook_id', $user->id)->first();

            if ($finduser) {

                Auth::login($finduser);

               // return redirect()->intended('dashboard');
            } else {
                $newUser = User::updateOrCreate(['email' => $user->email], [

                    'first_name' => $user->name,

                    'facebook_id' => $user->id,
                    'password' => encrypt('123456dummy')
                ]);

                Auth::login($newUser);

                //return redirect()->intended('dashboard');
            }
        } catch (Exception $e) {
            dd($e->getMessage());
        }
    }
}
