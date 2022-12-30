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
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;
use Laravel\Passport\RefreshTokenRepository;
use Laravel\Passport\Token;
use Laravel\Passport\TokenRepository;


class AuthController extends  Controller
{


    public function registerSuperAdmin(
        RegistrationRequest $request): JsonResponse
    {

        return $this->register($request, 1);
    }

    public function registerUser(
        RegistrationRequest $request) : JsonResponse
    {
        return $this->register($request, 3);
    }

    public function registerAdmin(
        RegistrationRequest $request) : JsonResponse
    {
        return $this->register($request, 2);
    }


    /**
     * Register api
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(
        RegistrationRequest $request,
        int $role) : JsonResponse
    {

      try {
            //code...
            $input = $request->validated()['data']['attributes'];

            $input['password'] = bcrypt($input['password']);
            $input['role'] = $role;
            $user = User::create($input);
            if ($role == 3) {
                $user->assignRole('user');
            } else if ($role == 1) {
                $user->assignRole('super admin');
            } else {
                $user->assignRole('admin');
            }

            if (!empty($request->validated()['data']['relationships'])) {
                $businessInput = $request->validated()['data']
                    ['relationships']['business_profile']['data'];

                $businessInput['user_id'] = $user->id;
                $businessInput['business_category_id'] =
                    $request->validated()['data']['relationships']['business_profile']['data']['category_id'];

                $user->business_profile()->create(Arr::except(
                    $businessInput,
                    'category_id'
                ));
            }

            $accessToken = $user->createToken("$user->first_name
            $user->last_name token")->accessToken;

            VerificationService::generateAndSendOtp($user);

            return $this->success(
                message: 'Registration successful',
                data: [
                    'type' => 'user',
                    'id' => strval($user->id),
                    'attributes' => [
                        'access_token' => $accessToken
                    ],
                ],
                status: HttpStatusCode::SUCCESSFUL->value
            );
      } catch (\Throwable $th) {
        //throw $th;
        return $this->failure(
            message: $th->getMessage(),
            status: HttpStatusCode::BAD_REQUEST->value
        );
      }

    }

    /**
     * Login api
     *
     * @return JsonResponse
     */
    public function login(
        LoginRequest $request) : JsonResponse
    {

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return $this->failure(
                message: 'The provided credentials are incorrect.',
                status: HttpStatusCode::FORBIDDEN->value
            );
        }

        $accessToken = $user->createToken("$user->first_name
            $user->last_name token")->accessToken;

        return $this->success(
            message: 'Login suceessful',
            data: [
                'type' => 'user',
                'id' => strval($user->id),
                'attributes' => [
                    'access_token' => $accessToken
                ],
            ],
            status: HttpStatusCode::SUCCESSFUL->value
        );
    }

    public function verifyOtp(
        VerifyOtpRequest $request): JsonResponse
    {
       try {
            //code...
            /** @var User */
            $loggedUser = auth()->user();

            /** @var EmailVerification */
            $isValidOtp = EmailVerification::firstWhere(['email' =>
                $loggedUser->email, 'otp' => $request->data['attributes']['otp']]);

            if (now()->greaterThan($isValidOtp->expired_at)) {
                return $this->failure(
                    message: 'OTP expired',
                    status: HttpStatusCode::BAD_REQUEST->value
                );
            }

            return DB::transaction(function () use ($loggedUser, $isValidOtp) {
                $loggedUser->update(['email_verified_at' => now()]);

                $isValidOtp->delete();

                return $this->failure(
                    message: 'OTP verified successfully'
                );
            });
       } catch (\Throwable $th) {
        //throw $th;

        return $this->failure(
            message: 'Invalid OTP',
            status: HttpStatusCode::UNAUTHENTICATED->value
        );
       }
    }

    public function resendOtp(Request $request): JsonResponse
    {
        try {
            //code...
            /** @var User $loggedUser*/
            $loggedUser = auth()->user();

            $phone_number= $request->data['attributes']['phone_number'];
            $email = $request->data['attributes']['email'];

            /** @var User $user*/
            $user = User::where('email', $email)->
                orWhere('phone_number', $phone_number)->first();

            if($loggedUser->email != $user->email ||
                $loggedUser->phone_number != $user->phone_number){
                return $this->failure(
                    message: 'Invalid user',
                    status: HttpStatusCode::UNAUTHENTICATED->value
                );
            }

            VerificationService::generateAndSendOtp($user);

            return $this->success(
                message: 'OTP resent successfully',
                data: null,
                status: HttpStatusCode::SUCCESSFUL->value
            );
        } catch (\Throwable $th) {
            //throw $th;
            return $this->failure(
                message: 'User not found',
                status: HttpStatusCode::NOT_FOUND->value
            );
        }
    }

    public function confirmEmail(
        ConfirmEmailRequest $request): JsonResponse
    {
        /** @var User */
        $user = auth()->user();
        VerificationService::generateAndSendOtp($user);
        return $this->success(message: 'A token has be sent to your mail',
            data: null,
            status: HttpStatusCode::SUCCESSFUL->value
        );
    }

    public function verifyForgetonPasswordOtp(
        VerifyOtpRequest $request): JsonResponse
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
                message: 'OTP verified successfully',
                data: null,
                status: HttpStatusCode::SUCCESSFUL->value
            );
        });
    }

    public function resendForgetonPasswordOtp(): JsonResponse
    {
        /** @var User */
        $loggedUser = auth()->user();

        VerificationService::generateAndSendOtp($loggedUser);

        return $this->success(
            message: 'OTP resent successfully',
            data: null,
            status: HttpStatusCode::SUCCESSFUL->value
        );
    }

    public function logout(): JsonResponse
    {
        try {
            //code...
            /** @phpstan-ignore-next-line */
            Auth::guard('api')->user()->token()->revoke();

            return $this->success(
                message: 'Logout successful',
                data: null,
                status: HttpStatusCode::SUCCESSFUL->value
            );
        } catch (\Throwable $th) {
            //throw $th;
            return $this->failure(
                message: $th->getMessage(),
                status: HttpStatusCode::NOT_FOUND->value
            );
        }

    }

    // get access token sanctum

    public function getAccessToken(
        Request $request) : JsonResponse
    {
        $user = User::where('email',
            $request->data['email'])->first();

        if (!$user || !Hash::check(
                $request->data['password'], $user->password)) {
            return $this->failure(
                message: 'The provided credentials are incorrect.',
                status: HttpStatusCode::FORBIDDEN->value
            );
        }

        $token = $user->createToken("API Token")->accessToken;

        return $this->success(
            message: 'Access token generated',
            data: [
                'type' => 'user',
                'id' => strval($user->id),
                'attributes' => [
                    'access_token' => $token,
                    'user' => (object)
                        array_merge(['id' => strval($user->id)],
                            Arr::except($user->toArray(), ['id'])
                        )

                ],
            ],
            status: HttpStatusCode::SUCCESSFUL->value
        );
    }


    public function resetPassword(
        Request $request) : JsonResponse
    {
        // Validate the request data
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|confirmed|min:6',
            'token' => 'required'
        ]);

        // Get the user with the specified email
        $user = User::where('email', $request->email)->first();

        // If the user doesn't exist or the reset token is invalid, return an error response
        if (!$user || !Password::tokenExists($user, $request->token)) {
            return response()->json([
                'message' => 'The password reset token is invalid or has expired.'
            ], 422);
        }

        // Reset the user's password
        $user->password = Hash::make($request->password);
        $user->save();

        // Send the user an email to confirm the password reset
       // Mail::to($user)->send(new PasswordResetConfirmation($user));

        return response()->json([
            'message' => 'Your password has been reset successfully.'
        ]);
    }

}