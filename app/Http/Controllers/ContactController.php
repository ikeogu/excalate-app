<?php

namespace App\Http\Controllers;

use App\Enums\HttpStatusCode;
use App\Http\Requests\ContactRequest;
use App\Http\Resources\EmergencyContact;
use App\Models\Contact;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;
use App\Models\User;

class ContactController extends Controller
{

    public function index() : JsonResponse
    {
        /** @var User $user */
        $user = auth()->user();
        /** @phpstan-ignore-next-line */
        $contacts = $user->contacts->get();
        return $this->success(
            message: 'Contacts',
            data: [
                EmergencyContact::collection($contacts)

            ],
            status: HttpStatusCode::SUCCESSFUL->value
        );
    }

    public function store(ContactRequest $request) : JsonResponse
    {
        /** @var User $user */
        $user = auth()->user();

        $input = $request->validated()['data']['attributes'];
        $input['status'] = 1;
        $contact = Contact::create($input);
        $contact->user()->associate($user);
        $contact->save();

        return $this->success(
            message: 'New Contact Added',
            data: [
                new EmergencyContact($contact)
            ],
            status: HttpStatusCode::CREATED->value
        );

    }


    public function show(Contact $contact) : JsonResponse
    {
        //
        /** @var User $user */
        $user = auth()->user();
        /** @phpstan-ignore-next-line */
        $contact = $user->contacts->findOrFail($contact->id);

        return $this->success(
            message: 'Contact',
            data: [
                new EmergencyContact($contact)
            ],
            status: HttpStatusCode::SUCCESSFUL->value
        );
    }


    public function update(
        ContactRequest $request, Contact $contact) : JsonResponse
    {
        //
        $user = auth()->user();
        /** @phpstan-ignore-next-line */
        $contact = $user->contacts->findOrFail($contact->id);

        $input = $request->validated()['data']['attributes'];
        $contact->update($input);

        return $this->success(
            message: 'Contact Updated',
            data: [
                new EmergencyContact($contact)
            ],
            status: HttpStatusCode::SUCCESSFUL->value
        );
    }

    public function destroy(Contact $contact) : JsonResponse
    {
        /** @var User $user */
        $user = auth()->user();
        /** @phpstan-ignore-next-line */
        $contact = $user->contacts->findOrFail($contact->id);
        $contact->delete();

        return $this->success(
            message: 'Contact Deleted',
            data: [
                new EmergencyContact($contact)
            ],
            status: HttpStatusCode::SUCCESSFUL->value
        );
    }
}
