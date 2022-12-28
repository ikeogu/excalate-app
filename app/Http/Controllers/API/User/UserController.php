<?php

namespace App\Http\Controllers\API\User;

use App\Enums\HttpStatusCode;
use App\Http\Controllers\Controller;
use App\Http\Requests\PasswordUpdateRequest;
use App\Http\Requests\RegistrationRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Services\VerificationService;
use Illuminate\Http\Request as HttpRequest;
use Illuminate\Support\Facades\Request;
use DB;
use Dotenv\Exception\ValidationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB as FacadesDB;
use Illuminate\Support\Facades\Hash;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;


class UserController extends Controller
{
    //

    public function index(HttpRequest $request) : JsonResponse
    {

        $page = $request->query('page');
        $size = $page['size'] ?? 25;

        //get all users, allow filtering,sort and includes by name, email, phone number, role

        $users = QueryBuilder::for(User::class)
            ->allowedFilters([
                AllowedFilter::exact('id'),
                AllowedFilter::partial('first_name'),
                AllowedFilter::partial('last_name'),
                AllowedFilter::partial('email'),
                AllowedFilter::partial('phone_number'),
                AllowedFilter::exact('role'),
            ])
            ->allowedSorts([
                'id',
                'first_name',
                'last_name',
                'email',
                'phone_number',
                'role',
            ])->allowedFields([
                'id',
                'first_name',
                'last_name',
                'email',
                'phone_number',
                'role',
            ])
            ->allowedIncludes([
                'business_profile',
                'user_proximity_plan',
                'user_proximity_plan.proximity_plan',

            ])
            ->paginate($size)->appends(Request::all());

        return $this->success(
            message: 'Users listed successfully',
            data: [
                'type' => 'users',
                // convert each user id to string to avoid integer overflow
                'attributes' => $users->map(function ($user) {
                    $user->toArray()['id'] = strval($user->id);
                    return $user;
                }),
            ],
            status: HttpStatusCode::SUCCESSFUL->value
        );
    }
/*
    public function getUserById(HttpRequest $request, $id): JsonResponse
    {

    }
 */
    public function store(Request $request): JsonResponse
    {
        return FacadesDB::transaction(function () use ($request) {
            $user = User::create($request->all());

            VerificationService::generateAndSendOtp($user);

            $user->syncRoles($request->role);

            return $this->success(
                message: "User created successfully",
                data: [
                    'type' => 'user',
                    'id' => $user->id,
                    'attributes' => new UserResource($user)],
                status: HttpStatusCode::CREATED->value
            );
        });
    }

    public function update(
        HttpRequest $request, User $user): JsonResponse
    {
        return FacadesDB::transaction(function () use ($request, $user) {

            $user->update($request->all());

            if ($request->has('avatar')) {
                $user->addMediaFromRequest('avatar')->
                toMediaCollection('avatar');
            }

            $user->syncRoles($request->role);

            return $this->success(
                message: "User updated successfully",
                data: [
                    'type' => 'user',
                    'id' => $user->id,
                    'attributes' => new UserResource($user)
                ],
                status: HttpStatusCode::SUCCESSFUL->value
            );
        });
    }


    public function updatePassword(
        PasswordUpdateRequest $request): JsonResponse
    {
        /** @var User $user */
        $user = $request->user();

        if (!Hash::check($request->old_password, $user->password)) {
            return $this->success(
                message: 'Old password is incorrect',
                status: HttpStatusCode::UNPROCESSABLE_ENTITY->value
            );

        }

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return $this->success(
            message: "Password updated successfully",
            data: [
                'type' => 'user',
                'id' => $user->id,
                'attributes' => new UserResource($user)],
            status: HttpStatusCode::SUCCESSFUL->value
        );
    }

    public function destroy(User $user): JsonResponse
    {
        $user->delete();

        return $this->success(
            message: "User deleted successfully",
            status: HttpStatusCode::SUCCESSFUL->value
        );
   }

   //get user by id

   public function getUserById(Request $request,int $id): JsonResponse
   {

       try {
            //code...

            /** @var User */
            $user = User::findOrFail($id);
            return $this->success(
                message: "User listed successfully",
                data: [
                   new UserResource($user)
                ],
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

}