<?php

namespace App\Policies;

use App\Models\SubscriptionHistory;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class SubscriptionHistoryPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        dd($user);

        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, SubscriptionHistory $subscriptionHistory): bool
    {
        dd($user);
        dd($subscriptionHistory);
        return true; //
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, SubscriptionHistory $subscriptionHistory): bool
    {
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, SubscriptionHistory $subscriptionHistory): bool
    {
        return false;
    }
}
