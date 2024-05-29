<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSubscriptionHistoryRequest;
use App\Http\Requests\UpdateSubscriptionHistoryRequest;
use App\Models\SubscriptionHistory;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\ValidationException;

class SubscriptionHistoryController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(SubscriptionHistory::class, options: ['except' => ['index', 'show']]);
    }
    /**
     * Display a listing of the resource based on user.
     */
    public function index()
    {
        return SubscriptionHistory::query()->with(['user'])->paginate();
    }

    public function my()
    {
        $history = SubscriptionHistory::query()->where('user_id', auth()->user()->id)->get();

        if (is_null($history)) {
            throw ValidationException::withMessages(['empty' => 'User has no history ']);
        }

        return ['data' => $history];
    }

    public function myActive()
    {
        $history = SubscriptionHistory::query()->where(['is_expired' => false], ['user_id' => auth()->user()->id])->latest()->paginate();

        if (is_null($history)) {
            throw ValidationException::withMessages(['empty' => 'User has no history ']);
        }

        return $history;
    }

    public function subscribe(StoreSubscriptionHistoryRequest $request)
    {
        if (auth()->user()->is_premium) {
            throw ValidationException::withMessages(['error' => 'User is already premium']);
        }

        $request->validate([
            'card' => ['required', 'digits:16'],
            'cvv' => ['required', 'digits:3'],
            'expiration' => ['required']
        ]);

        $history = new SubscriptionHistory($request->validated());
        $history->user_id = auth()->user()->id;
        $history->is_expired = false;
        if ($request->type === 'yearly') {
            $history->expires_at = Carbon::now()->addYear();
        } else {
            $history->expires_at = Carbon::now()->addMonth();
        }
        $history->save();

        $this->updateUser();

        return $history->load(['user']);
    }

    public function updateUser($status = true)
    {
        $user = User::query()->findOrFail(auth()->user()->id);
        $user->update(['is_premium' => $status]);
    }

    public function unsubscribe()
    {
        if (!auth()->user()->is_premium) {
            throw ValidationException::withMessages(['error' => 'User is not premium']);
        }

        $history = SubscriptionHistory::query()->where(['is_expired' => false], ['user_id' => auth()->user()->id])->latest();
        $history->update(['is_expired' => true]);

        $this->updateUser(false);

        return ['success' => 'You unsubscribed from our blog!'];
    }
}
