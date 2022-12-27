<?php

namespace App\Http\Controllers;

use App\Enums\HttpStatusCode;
use App\Http\Requests\UserProximityPlanRequest;
use App\Http\Resources\UserProximityPlanResource;
use App\Models\ProximityPlan;
use App\Models\UserProximityPlan;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;

class UserProximityPlanController extends Controller
{
    //

    public function index(Request $request) : JsonResponse
    {
        $userProximityPlan = UserProximityPlanResource::collection(
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
            message: 'User Proximity Plans',
            data: [
                UserProximityPlanResource::collection($userProximityPlan)
            ],
            status: HttpStatusCode::SUCCESSFUL->value
        );
    }

    public function store(
        UserProximityPlanRequest $request) : JsonResponse
    {
        //
        $input = $request->validated()['data']['attributes'];
        /** @var ProximityPlan $plan */
        $plan = ProximityPlan::findOrFail($input['proximity_plan_id']);
        /** @var User  $user*/
        $user = auth()->user();
        $input =  array_merge($input, [
            'end_date' => now()->addDays($plan->duration ?? 0),
            'start_date' => now(),
            'status' => 'active',
        ]);

        $userProximityPlan =$user->user_proximity_plans()->create($input);

        return $this->success(
            message: 'User Proximity Plan',
            data: [
               new UserProximityPlanResource($userProximityPlan),
            ],
            status: HttpStatusCode::SUCCESSFUL->value
        );
    }

    public function show(int $id) : JsonResponse
    {
        /** @var User $user */
        $user = auth()->user();
        /** @var UserProximityPlan $userProximityPlan */
        $userProximityPlan = $user->user_proximity_plans()->find($id);

        return $this->success(
            message: 'User Proximity Plan',
            data: [
                new UserProximityPlanResource($userProximityPlan),
            ],
            status: HttpStatusCode::SUCCESSFUL->value
        );
    }


    public function update(
        UserProximityPlanRequest $request,int $id) : JsonResponse
    {
        //
        $input = $request->validated()['data']['attributes'];
        /** @var User $user */
        $user = auth()->user();
        /** @var UserProximityPlan $userProximityPlan */
        $userProximityPlan = $user->user_proximity_plans()->find($id);
        $userProximityPlan->update($input);

        return $this->success(
            message: 'User Proximity Plan',
            data: [
                'type' => 'user_proximity_plan',
                'attributes' => new UserProximityPlanResource($userProximityPlan),
            ],
            status: HttpStatusCode::SUCCESSFUL->value
        );
    }

    public function destroy(int $id) : JsonResponse
    {
        //
        /** @var User $user */
        $user = auth()->user();
        /** @var UserProximityPlan $userProximityPlan */
        $userProximityPlan = $user->user_proximity_plans()->find($id);
        $userProximityPlan->delete();

        return $this->success(
            message: 'User Proximity Plan',
            data: [
               new UserProximityPlanResource($userProximityPlan),
            ],
            status: HttpStatusCode::SUCCESSFUL->value
        );
    }
}
