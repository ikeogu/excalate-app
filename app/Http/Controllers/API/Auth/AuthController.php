<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\Controller;

use App\Models\User;
use App\Enums\HttpStatusCode;
use App\Http\Requests\ConfirmEmailRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegistrationRequest;
use App\Http\Requests\VerifyOtpRequest;
use App\Http\Resources\UserResource;
use App\Models\BusinessProfile;
use App\Models\EmailVerification;
use App\Services\VerificationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Laravel\Passport\RefreshTokenRepository;
use Laravel\Passport\Token;
use Laravel\Passport\TokenRepository;

class AuthController extends  Controller
{


    public function registerSuperAdmin(RegistrationRequest $request): JsonResponse
    {

        return $this->register($request, 1);
    }

    public function registerUser(RegistrationRequest $request) : JsonResponse
    {
        return $this->register($request, 3);
    }

    public function registerAdmin(RegistrationRequest $request) : JsonResponse
    {
        return $this->register($request, 2);
    }


    /**
     * Register api
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(RegistrationRequest $request,  int $role) : JsonResponse
    {

        $input = $request->validated()['data']['attributes'];

        $input['password'] = bcrypt($input['password']);
        $input['role'] = $role;
        $user = User::create($input);
        if($role == 3){
            $user->assignRole('user');
        }else if($role == 1){
            $user->assignRole('super admin');
        }else{
            $user->assignRole('admin');
        }

        if($request->has('data.relationships')){
            $businessInput = $request->validated()['data']['relationships']['business']['data'];
            $businessInput['user_id'] = $user->id;
            $businessInput['business_category_id'] =
                $request->validated()['data']['relationships']['business']['data']['category_id'];

            $user->business_profile()->create(Arr::except(
                $businessInput,'category_id'));

        }

        $accessToken = $user->createToken("API Token",
            ($user->role =="super admin" || $user->role == "admin") ? ['admin']: ['user'])->
            accessToken;

        VerificationService::generateAndSendOtp($user);

        return $this->success(
            message: 'Registration successful',
            data: [
                'type' => 'user',
                'attributes' => [
                    'user' => $user,
                    'access_token' => $accessToken,
                ],
            ],
            status: HttpStatusCode::SUCCESSFUL->value
        );

    }

    /**
     * Login api
     *
     * @return JsonResponse
     */
    public function login(LoginRequest $request) : JsonResponse
    {

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return $this->success(
                message: 'The provided credentials are incorrect.',
                status: HttpStatusCode::FORBIDDEN->value
            );
        }

        $accessToken = $user->createToken("API Token",
            ($user->role == "super admin" || $user->role == "admin") ? ['admin'] : ['user'])->
                accessToken;

        return $this->success(
            message: 'Login suceessful',
            data: [
                'type' => 'user',
                'id' => $user->id,
                'attribute' => $user,
                'token' => $accessToken,

            ],
            status: HttpStatusCode::SUCCESSFUL->value
        );
    }

    public function verifyOtp(VerifyOtpRequest $request): JsonResponse
    {
        /** @var User */
        $loggedUser = auth()->user();

        /** @var EmailVerification */
        $isValidOtp = EmailVerification::firstWhere(['email' =>
            $loggedUser->email, 'otp' => $request->otp]);

        if (now()->greaterThan($isValidOtp->expired_at)) {
            return $this->failure(
                message: 'OTP expired',
                status: HttpStatusCode::BAD_REQUEST->value
            );
        }

        return DB::transaction(function () use ($loggedUser, $isValidOtp) {
            $loggedUser->update(['email_verified_at' => now()]);

            $isValidOtp->delete();

            return $this->success(
                message: 'OTP verified successfully'
            );
        });
    }

    public function resendOtp(): JsonResponse
    {
        /** @var User */
        $loggedUser = auth()->user();

        VerificationService::generateAndSendOtp($loggedUser);

        return $this->success(
            message: 'OTP resent successfully'
        );
    }

    public function confirmEmail(ConfirmEmailRequest $request): JsonResponse
    {
        /** @var User */
        $user = auth()->user();
        VerificationService::generateAndSendOtp($user);
        return $this->success(message: 'A token has be sent to your mail');
    }

    public function verifyForgetonPasswordOtp(VerifyOtpRequest $request): JsonResponse
    {
        /** @var User */
        $loggedUser = auth()->user();

        /** @var EmailVerification */
        $isValidOtp = EmailVerification::firstWhere(['email' =>
             $loggedUser->email, 'otp' => $request->otp]);

        if (now()->greaterThan($isValidOtp->expired_at)) {
            return $this->failure(
                message: 'OTP expired',
                status: HttpStatusCode::BAD_REQUEST->value
            );
        }

        return DB::transaction(function () use ($loggedUser, $isValidOtp) {
            $loggedUser->update(['email_verified_at' => now()]);

            $isValidOtp->delete();

            return $this->success(
                message: 'OTP verified successfully'
            );
        });
    }

    public function resendForgetonPasswordOtp(): JsonResponse
    {
        /** @var User */
        $loggedUser = auth()->user();

        VerificationService::generateAndSendOtp($loggedUser);

        return $this->success(
            message: 'OTP resent successfully'
        );
    }


    //sanctum logout
    public function logout(): JsonResponse
    {

        /** @phpstan-ignore-next-line */
        auth()->user()->tokens()->delete();

        return $this->success(
            message: 'Logout successful'
        );
    }

    // get access token sanctum

    public function getAccessToken(Request $request) : JsonResponse
    {
        $user = User::where('email', $request->data['email'])->first();

        if (!$user || !Hash::check($request->data['password'], $user->password)) {
            return $this->success(
                message: 'The provided credentials are incorrect.',
                status: HttpStatusCode::FORBIDDEN->value
            );
        }

        $token = $user->createToken("API Token")->accessToken;

        return $this->success(
            message: 'Access token generated',
            data: [
                'type' => 'user',
                'id' => $user->id,
                'token' => $token,
                'attribute' => $user,
            ],
            status: HttpStatusCode::SUCCESSFUL->value
        );
    }


}