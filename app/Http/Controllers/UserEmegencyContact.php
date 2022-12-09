<?php

namespace App\Http\Controllers;

use App\Enums\HttpStatusCode;
use App\Http\Requests\ContactRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserEmegencyContact extends Controller
{
    //

    public function index(int $id) : JsonResponse
    {
        //
        /** @var User $user */
        $user = User::find($id);
        $contacts = $user->contacts()->get();
        return $this->success(
            message: 'Contacts',
            data: [
                'type' => 'emergency_contacts',
                'attributes' => $contacts,

            ],
            status: HttpStatusCode::SUCCESSFUL->value
        );
    }

    public function store(ContactRequest $request, int $id) : JsonResponse
    {
        //
        /** @var User $user */
        $user = User::find($id);
        $contact = $user->contacts()->create($request->all());
        return $this->success(
            message: 'New Contact',
            data: [
                'type' => 'emergency_contacts',
                'attributes' => $contact,

            ],
            status: HttpStatusCode::CREATED->value
        );
    }



}