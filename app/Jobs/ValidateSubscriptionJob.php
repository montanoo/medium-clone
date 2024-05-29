<?php

namespace App\Jobs;

use App\Models\SubscriptionHistory;
use App\Models\User;

class ValidateSubscriptionJob
{
    public function __invoke()
    {
        $histories = SubscriptionHistory::query()->where(['expires_at', '<', date('Y-m-d H:i:s')], ['is_expired', false])->get();

        foreach ($histories as $history) {
            $history->update(['is_expired' => true]);
            $user = User::query()->findOrFail($history->user_id);
            $user->update(['is_premium' => false]);
        }
    }
}
