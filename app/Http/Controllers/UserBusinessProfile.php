<?php

namespace App\Http\Controllers;

use App\Enums\HttpStatusCode;
use App\Http\Resources\BusinessProfileResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserBusinessProfile extends Controller
{

    public function index(int $id) : JsonResponse
    {
        //
        /** @var User $user */
        $user = User::find($id);
        $businessProfiles = $user->business_profile()->get();
        return $this->success(
            message: 'Business Profiles',
             /** @phpstan-ignore-next-line */
            data: BusinessProfileResource::collection($businessProfiles),
            status: HttpStatusCode::SUCCESSFUL->value
        );
    }


    public function store(Request $request, mixed $id) : JsonResponse
    {
        //
        /** @var User $user */
        $user = User::find($id);
        $businessProfile = $user->business_profile()->create($request->all());
        return $this->success(
            message: 'New Business Profile',
             /** @phpstan-ignore-next-line */
            data:new BusinessProfileResource($businessProfile),
            status: HttpStatusCode::CREATED->value
        );
    }



    public function update(Request $request, mixed $id) : JsonResponse
    {
        /** @var User $user */
        $user = User::find($id);
        $businessProfile = $user->business_profile()->
            findOrFail($request->id)->
            update($request->all());
        return $this->success(
            message: 'Business Profile Updated',
             /** @phpstan-ignore-next-line */
            data:new BusinessProfileResource($businessProfile),
            status: HttpStatusCode::SUCCESSFUL->value
        );
    }


    public function destroy(Request $request, mixed $id) : JsonResponse
    {
        /** @var User $user */
        $user = User::find($id);
        $businessProfile = $user->business_profile()->
            findOrFail($request->id)->
            delete();
        return $this->success(
            message: 'Business Profile Deleted',
             /** @phpstan-ignore-next-line */
            data:new BusinessProfileResource($businessProfile),
            status: HttpStatusCode::SUCCESSFUL->value
        );
    }
}
