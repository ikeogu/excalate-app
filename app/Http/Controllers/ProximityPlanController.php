<?php

namespace App\Http\Controllers;

use App\Enums\HttpStatusCode;
use App\Http\Requests\ProximityPlanRequest;
use App\Http\Resources\ProximityPlanResource;
use App\Models\ProximityPlan;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;

class ProximityPlanController extends Controller
{
    // index method for preximity plan

    public function index(Request $request) : JsonResponse
    {

        $proximityPlan = ProximityPlanResource::collection(
            QueryBuilder::for(ProximityPlan::class)->
            allowedFilters([
                'name',
                'description',
                'price',
                'duration',
                'status'
            ])->
            allowedSorts([
                'name',
                'description',
                'price',
                'duration',
                'status'
            ])->
            allowedFields([
                'name',
                'description',
                'price',
                'duration',
                'status'
            ])->
            paginate(25)
        )->response()->getData(true);

        return $this->success(
            message: 'Proximity Plans',
             /** @phpstan-ignore-next-line */
            data:ProximityPlanResource::collection($proximityPlan),
            status: HttpStatusCode::SUCCESSFUL->value
        );
    }

    // store method for proximity plan

    public function store(ProximityPlanRequest $request) : JsonResponse
    {
        $input = $request->validated()['data']['attributes'];

        $proximityPlan = ProximityPlan::create($input);

        return $this->success(
            message: 'Proximity plan updated successfully',
             /** @phpstan-ignore-next-line */
            data:new ProximityPlanResource($proximityPlan),
            status: HttpStatusCode::SUCCESSFUL->value
        );
    }

    // show method for proximity plan

    public function show(ProximityPlan $proximityPlan) : JsonResponse
    {
        return $this->success(
            message: 'Proximity plan',
             /** @phpstan-ignore-next-line */
            data:new ProximityPlanResource($proximityPlan),
            status: HttpStatusCode::SUCCESSFUL->value
        );
    }

    // update method for proximity plan

    public function update(ProximityPlanRequest $request,
         ProximityPlan $proximityPlan) : JsonResponse
    {
        $input = $request->validated()['data']['attributes'];

        $proximityPlan->update($input);

        return $this->success(
            message: 'Proximity plan updated successfully',
             /** @phpstan-ignore-next-line */
            data :new ProximityPlanResource($proximityPlan),
            status: HttpStatusCode::SUCCESSFUL->value
        );
    }

    // destroy method for proximity plan

    public function destroy(ProximityPlan $proximityPlan) : JsonResponse
    {
        $proximityPlan->delete();

        return $this->success(
            message: 'Proximity plan updated successfully',
             /** @phpstan-ignore-next-line */
            data:new ProximityPlanResource($proximityPlan),
            status :HttpStatusCode::SUCCESSFUL->value
        );
    }

}