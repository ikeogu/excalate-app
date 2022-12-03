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
            allowedIncludes([
                'business_category'
            ])->
            allowedFields([
                'name',
                'description',
                'business_category_id'
            ])->
            paginate(25);

        return $this->success(
            message: 'Business Profiles',
            data: [
                'type' => 'busness profile',
                'attributes' => [$businessProfiles],

            ],
            status: HttpStatusCode::SUCCESSFUL->value
        );
    }

    //store function

    public function store(BusinessProfileRequest $request) : JsonResponse
    {

        $input = $request->validated()['data']['attributes'];

        $businessProfile = BusinessProfile::create($input);

        $user = auth()->user();
        $category = BusinessCategory::find($input['busness_cat_id']);

        $businessProfile->user()->associate($user);
        $businessProfile->business_category()->associate($category);


        return $this->success(
            message: 'New Business Profile',
            data: [
                'type' => 'busness profile',
                'attributes' => [new BusinessProfileResource($businessProfile)],

            ],
            status: HttpStatusCode::CREATED->value
        );

    }

    //show function

    public function show(BusinessProfile $businessProfile) : JsonResponse
    {
        //
        return $this->success(
            message: 'Business Profile',
            data: [
                'type' => 'busness profile',
                'attributes' => [ new BusinessProfileResource($businessProfile) ],

            ],
            status: HttpStatusCode::SUCCESSFUL->value
        );
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
            data: [
                'type' => 'busness profile',
                'attributes' => [new BusinessProfileResource($businessProfile)],

            ],
            status: HttpStatusCode::SUCCESSFUL->value
        );
    }

    //destroy function

    public function destroy(BusinessProfile $businessProfile) : JsonResponse
    {
        //
        $businessProfile->delete();

        return $this->success(
            message: 'Business Profile Deleted',
            data: [
                'type' => 'busness profile',
                'attributes' => [new BusinessProfileResource($businessProfile)],

            ],
            status: HttpStatusCode::SUCCESSFUL->value
        );
    }

}
