<?php

namespace App\Http\Controllers;

use App\Enums\HttpStatusCode;
use App\Http\Requests\BusinessProfileRequest;
use App\Http\Resources\BusinessProfileResource;
use App\Models\BusinessCategory;
use App\Models\BusinessProfile;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;
use App\Models\User;

class BusinessProfileController extends Controller
{
    //

    public function index() : JsonResponse
    {
        //
        $businessProfiles = QueryBuilder::for(BusinessProfile::class)->
            allowedFilters([
                'name',
                'description',
                'business_category_id'
            ])->
            allowedSorts([
                'name',
                'description',
                'business_category_id'
            ])->

            allowedFields([
                'name',
                'description',
                'business_category_id'
            ])->
            allowedIncludes([
                'business_category'
            ])->
            paginate(25);

        return $this->success(
            message: 'Business Profiles',
             /** @phpstan-ignore-next-line */
            data: BusinessProfileResource::collection($businessProfiles),
            status: HttpStatusCode::SUCCESSFUL->value
        );
    }

    //store function

    public function store(BusinessProfileRequest $request) : JsonResponse
    {

        try {
            //code...
            $input = $request->validated()['data']['attributes'];
            $input['business_category_id'] = $request->validated()
                ['data']['relationships']['business_category']['category_id'];
            /** @var  User $user*/
            $user = auth()->user();
            $input['user_id'] = $user->id;

            $businessProfile = BusinessProfile::create($input);


            $category = BusinessCategory::find($input['business_category_id']);

            $businessProfile->user()->associate($user);
            $businessProfile->business_category()->associate($category);
            $businessProfile->save();


            return $this->success(
                message: 'New Business Profile',
                 /** @phpstan-ignore-next-line */
                data: new BusinessProfileResource($businessProfile),
                status: HttpStatusCode::CREATED->value
            );
        } catch (\Throwable $th) {
            //throw $th;

            return $this->failure(
                message: $th->getMessage(),
                status: HttpStatusCode::BAD_REQUEST->value
            );
        }

    }

    //show function

    public function show(BusinessProfile $businessProfile) : JsonResponse
    {
        //
        try {
            //code...
            return $this->success(
                message: 'Business Profile',
                 /** @phpstan-ignore-next-line */
                data:new BusinessProfileResource($businessProfile),
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

    //update function

    public function update(BusinessProfileRequest $request,
        BusinessProfile $businessProfile) : JsonResponse
    {
        //
        $input = $request->validated()['data']['attributes'];

        $businessProfile->update($input);

        $user = auth()->user();
        $category = BusinessCategory::find($input['busness_cat_id']);

        $businessProfile->user()->associate($user);
        $businessProfile->business_category()->associate($category);

        return $this->success(
            message: 'Business Profile Updated',
             /** @phpstan-ignore-next-line */
            data: new BusinessProfileResource($businessProfile),
            status: HttpStatusCode::SUCCESSFUL->value
        );
    }

    //destroy function

    public function destroy(BusinessProfile $businessProfile) : JsonResponse
    {
        //

        try {
            //code...
            $businessProfile->delete();

            return $this->success(
                message: 'Business Profile Deleted',
                 /** @phpstan-ignore-next-line */
                data:new BusinessProfileResource($businessProfile),
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

    //user business profile

    public function userProfile(int $id) : JsonResponse
    {
        //
        $businessProfile = QueryBuilder::for(BusinessProfile::class)->
            allowedFilters([
                'name',
                'description',
                'business_category_id'
            ])->
            allowedSorts([
                'name',
                'description',
                'business_category_id'
            ])->
            allowedIncludes([
                'business_category'
            ])->
            allowedFields([
                'name',
                'description',
                'business_category_id'
            ])->
            where('user_id', $id)->
            paginate(25);

        return $this->success(
            message: 'Business Profiles',
             /** @phpstan-ignore-next-line */
            data:BusinessProfileResource::collection($businessProfile)
            ,
            status: HttpStatusCode::SUCCESSFUL->value
        );
    }

}