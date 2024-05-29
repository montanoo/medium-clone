<?php

use App\Models\User;

test('user can subscribe to our blog', function () {
    $user = User::factory()->createOne();

    $this->assertFalse($user['is_premium']);
    $this->actingAs($user)->postJson(route('blog.subscribe'), ['type' => 'yearly', 'card' => '1234123412341234', 'cvv' => '123', 'expiration' => '1212']);

    $updatedUser = User::query()->firstOrFail('id', $user->id)->get();
    $this->assertTrue($updatedUser[0]->is_premium);
});


test('only users can subscribe to our blog', function () {
    $user = User::factory()->createOne();

    $this->assertFalse($user['is_premium']);
    $response = $this->postJson(route('blog.subscribe'), ['type' => 'yearly', 'card' => '1234123412341234', 'cvv' => '123', 'expiration' => '1212']);

    $response->assertStatus(401);

    $updatedUser = User::query()->firstOrFail('id', $user->id)->get();
    $this->assertFalse($updatedUser[0]->is_premium);
});


test('only subscribed can unsubscribe to our blog', function () {
    $user = User::factory()->createOne();

    $this->assertFalse($user['is_premium']);
    $response = $this->actingAs($user)->postJson(route('blog.unsubscribe'));

    $response->assertStatus(422);

    $updatedUser = User::query()->firstOrFail('id', $user->id)->get();
    $this->assertFalse($updatedUser[0]->is_premium);
});


test('only unsubscribed can subscribe to our blog', function () {
    $user = User::factory()->createOne();

    $this->assertFalse($user['is_premium']);
    $this->actingAs($user)->postJson(route('blog.subscribe'), ['type' => 'yearly', 'card' => '1234123412341234', 'cvv' => '123', 'expiration' => '1212']);

    $updatedUser = User::query()->firstOrFail('id', $user->id)->get();
    $this->assertTrue($updatedUser[0]->is_premium);

    $response = $this->actingAs($updatedUser[0])->postJson(route('blog.subscribe'), ['type' => 'yearly', 'card' => '1234123412341234', 'cvv' => '123', 'expiration' => '1212']);
    $response->assertStatus(422);

    $updatedUser = User::query()->firstOrFail('id', $user->id)->get();
    $this->assertTrue($updatedUser[0]->is_premium);
});
