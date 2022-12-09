<?php

use App\Enums\HttpStatusCode;
use App\Models\User;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Notification;

use function Pest\Laravel\assertDatabaseCount;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\assertDatabaseMissing;

 it('cannot register if validation error', function () {
    $data = [
        'first_name' => 'Ibrahim',
        'last_name' => 'Lasisi',
        'email' => 'invalid email',
        'password' => 'Password1!',
        'c_password' => 'Password1!',
        'phone_number' => '08012345678',
    ];

    $response = $this->post(route('ruser'), $data);

    //$response->assertStatus(HttpStatusCode::VALIDATION_ERROR->value);

    assertDatabaseCount('users', 0);
    assertDatabaseMissing('users', Arr::except($data, ['password','c_password']));
});

/* it('can login', function () {
    $user = User::factory()->create();

    $res = $this->post(route('login'), [
        'email' => $user->email,
        'password' => 'password',
    ]);

    $res->assertOk();
    expect($res['data']['user'])->toBeArray();
    expect($res['data']['token'])->toBeString();
});

it('can not login with invalid password', function () {
    $user = User::factory()->create();

    $response = $this->post(route('login'), [
        'email' => $user->email,
        'password' => 'wrong-password',
    ]);

    expect($response['message'])->toBe('Password mismatched');
    $response->assertStatus(422);
    $this->assertGuest();
}); */
/*
it('can view user details', function () {
    actingAs();

    $response = $this->get(route('user-details'));

    $response->assertOk();

    expect($response)->toBeObject();
});

it('can not access protected route', function () {
    $response = $this->get(route('user-details'), ['HTTP_Accept' => 'application/json']);

    $response->assertStatus(401);
});

it('can logout', function () {
    actingAs();

    $response = $this->delete(route('logout'), ['HTTP_Accept' => 'application/json']);

    $response->assertOk();
    expect($response['message'])->toBeString()->toBe('Logged out successfully');
}); */
