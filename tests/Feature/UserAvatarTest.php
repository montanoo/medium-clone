<?php

use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

test('user_avatar_can_be_stored_at_creating_user', function () {
    $response = $this->postJson(route('user.register'), [
        'name' => fake()->sentence(1),
        'email' => fake()->email(),
        'password' => 'testtest123123A',
        'avatar_url' => UploadedFile::fake()->image('avatar.jpg'),
    ]);

    $this->assertDatabaseHas('users', ['avatar_url' => $response->original['user']['avatar_url']]);
});

test('user_avatar_can_be_updated_after_creating_user', function () {
    // given
    $user = User::factory()->createOne();

    // post to users.store
    $response = $this->actingAs($user)->putJson(route('users.update', [$user->id]), [
        'name' => fake()->sentence(1),
        'avatar_url' => UploadedFile::fake()->image('avatar.jpg'),
    ]);

    // check for status
    $response->assertStatus(200);

    // check if avatar_url exists in database
    $this->assertDatabaseHas('users', ['avatar_url' => $response->original['avatar_url']]);

    // check if image has been stored
    $user = User::find($user->id);
    Storage::disk('public')->assertExists(Str::after($user->avatar_url, env('APP_URL') . '/storage/'));

    // if i update again, do i have two files?
    $response = $this->actingAs($user)->putJson(route('users.update', [$user->id]), [
        'name' => fake()->sentence(1),
        'avatar_url' => UploadedFile::fake()->image('avatar.jpg'),
    ]);

    // assert that the file no longer exists!
    Storage::disk('public')->assertMissing(Str::after($user->avatar_url, env('APP_URL') . '/storage/'));
});

test('only_logged_in_users_can_change_avatar', function () {
    // given
    $user = User::factory()->createOne();

    // post to users.store
    $response = $this->putJson(route('users.update', [$user->id]), [
        'name' => fake()->sentence(1),
        'avatar_url' => UploadedFile::fake()->image('avatar.jpg'),
    ]);

    // check for status (unauthenticated)
    $response->assertStatus(401);
});

test('user_avatar_can_only_update_their_own', function () {
    // given
    $user = User::factory()->createOne();
    $userTwo = User::factory()->createOne();

    // post to users.store
    $response = $this->actingAs($userTwo)->putJson(route('users.update', [$user->id]), [
        'name' => fake()->sentence(1),
        'avatar_url' => UploadedFile::fake()->image('avatar.jpg'),
    ]);

    // check for status (forbidden)
    $response->assertStatus(403);
});


test('user_avatar_has_to_be_an_image', function () {
    // given
    $user = User::factory()->createOne();

    // post to users.store
    $response = $this->actingAs($user)->putJson(route('users.update', [$user->id]), [
        'name' => fake()->sentence(1),
        'avatar_url' => UploadedFile::fake()->create('test.txt'),
    ]);

    // check for status (unprocessable content)
    $response->assertStatus(422);
});


test('user_avatar_is_available_for_everyone', function () {
    // given
    $user = User::factory()->createOne();

    // post to users.store
    $this->actingAs($user)->putJson(route('users.update', [$user->id]), [
        'name' => fake()->sentence(1),
        'avatar_url' => UploadedFile::fake()->image('avatar.jpg'),
    ]);

    $getRequest = $this->getJson(route('users.show', [$user->id]));

    $getRequest->assertJsonStructure(['avatar_url']);

    // check if image has been stored
    $user = User::find($user->id);
    Storage::disk('public')->assertExists(Str::after($user->avatar_url, 'storage/'));
});
