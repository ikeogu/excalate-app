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

    /**
     * @return JsonResponse
     */

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
        $request = $request->data['attributes'];
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
        $accessToken = $user->createToken("$user->first_name $user->last_name token")->accessToken;

        return $this->success(
            message: 'Registration successful',
            data: ['token' => $accessToken],
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
            throw ValidationException::withMessages([
                'password' => ['Password mismatched'],
            ]);
        }

        $token = $user->createToken("$user->first_name $user->last_name token")->
            accessToken;

        return $this->success(
            message: 'Login suceessful',
            data: [
                'type' => 'user',
                'id' => $user->id,
                'attribute' => new UserResource($user),
                'token' => $token,

            ],
            status: HttpStatusCode::SUCCESSFUL->value
        );
    }

    public function verifyOtp(VerifyOtpRequest $request): JsonResponse
    {
        /** @var User */
        $loggedUser = auth()->user();

        /** @var EmailVerification */
        $isValidOtp = EmailVerification::firstWhere(['email' => $loggedUser->email, 'otp' => $request->otp]);

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
        $isValidOtp = EmailVerification::firstWhere(['email' => $loggedUser->email, 'otp' => $request->otp]);

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


    public function logout(): JsonResponse
    {
        /** @var User $user */
        $user = auth()->user();

        /** @var Token $token */
        $token = $user->token();

        $tokenRepository = app(TokenRepository::class);
        $refreshTokenRepository = app(RefreshTokenRepository::class);

        // Revoke an access token...
        $tokenRepository->revokeAccessToken($token->id);

        // Revoke all of the token's refresh tokens...
        $refreshTokenRepository->revokeRefreshTokensByAccessTokenId($token->id);

        return response()->json(['message' => 'Logged out successfully']);
    }


    //get access token
    public function getAccessToken(Request $request): JsonResponse
    {

        $http = new \GuzzleHttp\Client;

        $response = $http->post(config('app.url') . '/oauth/token', [
            'form_params' => [
                'grant_type' => $request->data['grant_type'],
                'client_id' => config('passport.personal_access_client.id'),
                'client_secret' => config('passport.personal_access_client.secret'),
                'username' => $request->data['email'],
                'password' => $request->data['password'],
                'scope' => '',
            ],
        ]);

        return $this->success(
            message: 'Token generated successfully',
            data: json_decode((string)$response->getBody(), true)
        );
    }



    // oauth token


}