<?php

namespace App\Http\Controllers;

use App\Enums\HttpStatusCode;
use App\Http\Requests\ContactRequest;
use App\Http\Resources\EmergencyContact;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;

class UserEmegencyContact extends Controller
{
    //

    public function index(Request $request, int $id) : JsonResponse
    {
        //
        /** @var User $user */
        $user = User::find($id);

        $contacts = $user->contacts();

        $contacts = QueryBuilder::for($contacts)
            ->allowedFilters(['name', 'phone_number','type', 'relationship'])
            ->allowedSorts(['name', 'phone_number', 'type','relationship'])
            ->allowedFields(['name', 'phone_number','type', 'relationship'])
            ->allowedIncludes(['user'])
            ->paginate(25);


        return $this->success(
            message: 'User Contacts List',
             /** @phpstan-ignore-next-line */
            data:EmergencyContact::collection($contacts),
            status: HttpStatusCode::SUCCESSFUL->value
        );
    }

    public function store(
        ContactRequest $request, int $id) : JsonResponse
    {
        //
        /** @var User $user */
        $user = User::find($id);

        $contact = $user->contacts()->create(
            array_merge(
                $request->validated()['data']['attributes'],
                ['status' => 1]
            )
        );
        return $this->success(
            message: 'New Contact',
             /** @phpstan-ignore-next-line */
            data:new EmergencyContact($contact),
            status: HttpStatusCode::CREATED->value
        );
    }

}