<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends  BaseController
{
    //  register admin

    public function registerAdmin(Request $request)
    {
        return $this->register($request, 1);
    }

    public function registerUser(Request $request)
    {
        return $this->register($request, 3);
    }

    /**
     * Register api
     *
     * @return \Illuminate\Http\Response
     */
    public function register($request, $role){

        $validator = Validator::make($request->all(), [
            'first_name' => 'required',
            'last_name' => 'required',
            'phone_number' => 'required',
            'email' => 'required|email',
            'password' => 'required',
            'c_password' => 'required|same:password',

        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $input = $request->all();
        $input['role'] = $role;
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);
        $accessToken = $user->createToken('authToken')->accessToken;

        return response(['user' => $user, 'access_token' => $accessToken, 'message' => 'Register successfully'], 201);

    }

    /**
     * Login api
     *
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request){

        $loginData = $request->validate([
            'email' => 'email|required',
            'password' => 'required'
        ]);

        if (!auth()->attempt($loginData)) {
            return response(['message' => 'Invalid Credentials']);
        }

        $accessToken = auth()->user()->createToken('authToken')->accessToken;

        return response(['user' => auth()->user(), 'access_token' => $accessToken, 'message' => 'Login successfully'], 200);

    }

    /**
     * logout api
     *
     * @return \Illuminate\Http\Response
     */

    public function logout(Request $request) {
        $request->user()->token()->revoke();
        return $this->sendResponse([], 'User logout successfully.');
    }



}
