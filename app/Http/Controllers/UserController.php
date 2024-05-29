<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\File;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;


class UserController extends Controller
{

    public function __construct()
    {
        $this->authorizeResource(User::class, options: ['except' => ['index', 'show']]);
    }


    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        return User::query()
            ->when(
                request('is_premium') !== null,
                function ($query, $isPremium) {
                    $query->where('is_premium', '=', filter_var($isPremium, FILTER_VALIDATE_BOOLEAN));
                }
            )
            ->paginate();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request)
    {
        $request->validate([
            'avatar_url' => [
                'nullable',
                File::image()
            ],
        ]);
        $credentials = $request->validated();

        $user = User::query()->firstWhere('email', $credentials['email']);
        if (!is_null($user)) {
            throw ValidationException::withMessages(['email' => 'User already exists']);
        }

        $user = new User($credentials);

        if ($avatar_url = $request->file('avatar_url')) {
            $avatarPath = $avatar_url->storePublicly('public/avatars');
            $user->avatar_url = Storage::url($avatarPath);
        }
        $user->save();


        $token = $user->createToken('my token name')->plainTextToken;

        return ['token' => $token, 'user' => $user];
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        //
        return $user;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        // validate the type of image.
        $request->validate([
            'avatar_url' => [
                'nullable',
                File::image()
            ],
        ]);

        // check if there's any image.
        if ($avatarPhoto = $request->file('avatar_url')) {
            // remove existing image from storage.
            if ($user->avatar_url) {
                $avatarPath = str_replace(env('APP_URL') . '/storage/', '', $user->avatar_url);
                Storage::disk('public')->delete($avatarPath);
            }

            // store new image
            $avatarUrl = $avatarPhoto->storePublicly('public/avatars');
            $user->avatar_url = Storage::url($avatarUrl);
        }

        $user->update($request->validated());

        return $user;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $user->delete();

        return response()->noContent();
    }

    public function register(StoreUserRequest $request)
    {
        // Separate method to avoid "store" policy being triggered.

        return $this->store($request);
    }

    public function my()
    {
        return request()->user();
    }

    public function login(Request $request)
    {
        $request->validate([
            'avatar_url' => [
                'nullable',
                File::image()
            ],
        ]);

        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', Password::min(8)->letters()],
        ]);

        $user = User::query()->firstWhere('email', $credentials['email']);

        if (is_null($user)) {
            throw ValidationException::withMessages(['email' => 'Wrong credentials']);
        }

        if (!Hash::check($credentials['password'], $user->password)) {
            throw ValidationException::withMessages(['email' => 'Wrong credentials']);
        }

        $token = $user->createToken('my token name')->plainTextToken;

        return ['token' => $token];
    }

    public function logout()
    {
        $user = request()->user();

        $user->tokens()->delete();

        return response()->noContent();
    }

    public function uploadAvatar(Request $request)
    {
        $request->validate([
            'img' => [
                'nullable',
                File::image()
            ],
        ]);

        $user = request()->user();
        // check if there's any image.
        if ($avatarPhoto = $request->file('img')) {
            // remove existing image from storage.
            if ($user->avatar_url) {
                $avatarPath = str_replace(env('APP_URL') . '/storage/', '', $user->avatar_url);
                Storage::disk('public')->delete($avatarPath);
            }

            // store new image
            $avatarUrl = $avatarPhoto->storePublicly('public/avatars');
            $user->avatar_url = Storage::url($avatarUrl);
        }

        $user->save();

        return $user;
    }

    // public function removeAvatar(Request $request, User $user)
    // {
    //     if ($user->avatar_url) {
    //         $avatarPath = str_replace('/storage', '', $user->avatar_url);
    //         Storage::disk('public')->delete($avatarPath);
    //     }
    //     $user->avatar_url = null;

    //     $user->update();

    //     return $user;
    // }
}