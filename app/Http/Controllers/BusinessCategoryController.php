<?php

namespace App\Http\Controllers;

use App\Enums\HttpStatusCode;
use App\Models\BusinessCategory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BusinessCategoryController extends Controller
{
    //
    public function index() : JsonResponse
    {
        $busCat = BusinessCategory::all();

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

    public function update(Request $request, $id) : JsonResponse
    {
        $busCat = BusinessCategory::find($id);
        if($busCat){
            $busCat->name = $request->data['attributes']['name'];
            $busCat->description = $request->data['attributes']['description'];
            $busCat->save();
        }

        return $this->success(
            message: 'Business Category Updated',
            data: [
                'type' => 'Business Category',
                'attribute' => [$busCat]
            ],
            status: HttpStatusCode::SUCCESSFUL->value
        );

    }

    public function show($id): JsonResponse
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

    public function destroy($id) :JsonResponse
    {
        $busCat = BusinessCategory::find($id)->delete();
        return $this->success(
            message: 'Business Category Deleted',
            data: [
                'type' => 'Business Category',

            ],
            status: HttpStatusCode::SUCCESSFUL->value
        );

    }
}