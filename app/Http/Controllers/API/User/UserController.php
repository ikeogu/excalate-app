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

        $users = QueryBuilder::for(User::class)->
            allowedFilters([
                AllowedFilter::exact('id'),
                AllowedFilter::exact('email'),
                AllowedFilter::exact('first_name'),
                AllowedFilter::exact('last_name'),
                AllowedFilter::exact('phone'),
                AllowedFilter::exact('role'),
                AllowedFilter::exact('status'),
                AllowedFilter::exact('created_at'),
                AllowedFilter::exact('updated_at'),
            ])->
            allowedSorts(
                'id',
                'email',
                'first_name',
                'last_name',
                'phone',
                'role',
                'status',
                'created_at',
                'updated_at',
            )->
            allowedFields(
                'id',
                'email',
                'first_name',
                'last_name',
                'phone',
                'role',
                'status',
                'created_at',
                'updated_at',
            )->
            allowedIncludes('role')->

            paginate($size);

        return $this->success(
            message: 'Users listed successfully',
            data: [
                'type' => 'users',
                'attributes' => UserResource::collection($users),
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

    public function update(HttpRequest $request, User $user): JsonResponse
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
                    'attributes' => new UserResource($user)],
                status: HttpStatusCode::SUCCESSFUL->value
            );
        });
    }


    public function updatePassword(PasswordUpdateRequest $request): JsonResponse
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

   public function getUserById(HttpRequest $request,int $id): JsonResponse
   {
       /** @var User */
       $user = User::find($id);

       return $this->success(
           message: "User listed successfully",
           data: [
               'type' => 'user',
               'id' => $user->id,
               'attributes' => new UserResource($user)],
           status: HttpStatusCode::SUCCESSFUL->value
       );
   }
}