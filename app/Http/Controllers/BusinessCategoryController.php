<?php

namespace App\Http\Controllers;

use App\Enums\HttpStatusCode;
use App\Models\BusinessCategory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;

class BusinessCategoryController extends Controller
{
    //
    public function index(Request $request) : JsonResponse
    {
        $busCat = QueryBuilder::for(BusinessCategory::class)
            ->allowedFilters([
                'name',
                'description'
            ])
            ->allowedSorts([
                'name',
                'description'
            ])
            ->allowedFields([
                'name',
                'description'
            ])
            ->allowedIncludes([
                'businesses'
            ])

            ->paginate(25);

        return $this->success(
            message: 'All Business Categories',
            data: [
                'type' => 'business category',
                'attribute' => [$busCat],
            ],
            status: HttpStatusCode::SUCCESSFUL->value

        );
    }

    public function store(Request $request) : JsonResponse
    {
        $check = BusinessCategory::where('name',$request->data['attributes']['name'])->
            first();
        if(!$check){
            $busCat = new BusinessCategory();
            $busCat->name = $request->data['attributes']['name'];
            $busCat->description = $request->data['attributes']['description'];
            $busCat->save();

            return $this->success(
                message: 'New Business Categories',
                data: [
                    'type' => 'business category',
                    'attribute' => [$busCat],
                ],
                status: HttpStatusCode::CREATED->value
            );

        }else{
            return $this->success(
                message: 'Business Category already exist',
                data: [

                ],
                status: HttpStatusCode::FORBIDDEN->value
            );
        }
    }

    // function update

    public function update(Request $request,int $id) : JsonResponse
    {

        $busCat = BusinessCategory::find($id);
        /** @var BusinessCategory  $busCat*/

        $busCat->name = $request->data['attributes']['name'];
        $busCat->description = $request->data['attributes']['description'];
        $busCat->save();

        return $this->success(
            message: 'Business Category Updated',
            data: [
                'type' => 'Business Category',
                'attribute' => [$busCat]
            ],
            status: HttpStatusCode::SUCCESSFUL->value
        );

    }

    public function show(int $id): JsonResponse
    {
        $busCat = BusinessCategory::find($id);
        return $this->success(
            message: 'Business Category Deleted',
            data: [
                'type' => 'Business Category',
                'attributes' => [$busCat]

            ],
            status: HttpStatusCode::SUCCESSFUL->value
        );
    }

    public function destroy(int $id) :JsonResponse
    {
        $busCat = BusinessCategory::findOrFail($id)->delete();
        return $this->success(
            message: 'Business Category Deleted',
            data: [
                'type' => 'Business Category',

            ],
            status: HttpStatusCode::SUCCESSFUL->value
        );

    }
}