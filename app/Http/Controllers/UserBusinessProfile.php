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
            data: [
               BusinessProfileResource::collection($businessProfiles)
            ],
            status: HttpStatusCode::SUCCESSFUL->value
        );
    }


    public function store(Request $request, int $id) : JsonResponse
    {
        //
        /** @var User $user */
        $user = User::find($id);
        $businessProfile = $user->business_profile()->create($request->all());
        return $this->success(
            message: 'New Business Profile',
            data: [
                new BusinessProfileResource($businessProfile)
            ],
            status: HttpStatusCode::CREATED->value
        );
    }



    public function update(Request $request,int $id) : JsonResponse
    {
        /** @var User $user */
        $user = User::find($id);
        $businessProfile = $user->business_profile()->
            findOrFail($request->id)->
            update($request->all());
        return $this->success(
            message: 'Business Profile Updated',
            data: [
               new BusinessProfileResource($businessProfile)
            ],
            status: HttpStatusCode::SUCCESSFUL->value
        );
    }


    public function destroy(Request $request, int $id) : JsonResponse
    {
        /** @var User $user */
        $user = User::find($id);
        $businessProfile = $user->business_profile()->
            findOrFail($request->id)->
            delete();
        return $this->success(
            message: 'Business Profile Deleted',
            data: [
                new BusinessProfileResource($businessProfile)
            ],
            status: HttpStatusCode::SUCCESSFUL->value
        );
    }
}
