<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use Illuminate\Testing\Fluent\AssertableJson;

uses(RefreshDatabase::class);

test('Login with valid credentials', function () {
    $user = User::factory()->create();

    $response = $this->post('/api/v1/login', [
        'email' => $user->email,
        'password' => 'password'
    ]);

    $response->assertStatus(200);

    $response->assertJson(function (AssertableJson $json){
        $json->has('status');
        $json->has('token');
    });
});

test('Login with invalid credentials', function () {
    $user = User::factory()->create();

    $response = $this->post('/api/v1/login', [
        'email' => $user->email,
        'password' => 'wrong_password'
    ]);

    $response->assertStatus(401);
});

test('Login without email', function () {
    $response = $this->post('/api/v1/login', [
        'password' => 'password'
    ]);

    $response->assertStatus(422);

    $response->assertJson(function (AssertableJson $json){
        $json->has('status');
        $json->has('errors');
    });
});
