<?php

use App\Enums\HttpStatusCode;
use App\Models\User;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Notification;

use function Pest\Laravel\assertDatabaseCount;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\assertDatabaseMissing;


it('register successfully', function () {
    $regdata = [
        'data' => array(
            'type' => 'user',
            'attributes' => array(
                'first_name' => 'Ibrahim',
                'last_name' => 'Lasisi',
                'email' => 'ilasisi90@gmail.com',
                'password' => 'Password1!',
                'password_confirmation' => 'Password1!',
                'phone_number' => '08012345678',
            ),
        ),
    ];

    $response = $this->postJson(route('register_user'));

    $response->assertStatus(HttpStatusCode::SUCCESSFUL->value);

    assertDatabaseCount('users', 1);

    assertDatabaseHas('users', Arr::except($regdata, [
        ['data']['attributes']['password'],
        ['data']['attributes']['password_confirmation']
    ]));
    //assertDatabaseCount('email_verifications', 1);
});