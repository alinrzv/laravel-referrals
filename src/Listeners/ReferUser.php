<?php

namespace Pmn\Referrals\Listeners;

use Pmn\Referrals\Events\UserReferred;
use Pmn\Referrals\Models\ReferralLink;

class ReferUser {

    public function handle(UserReferred $event)
    {
        \Log::debug('ReferUser listener: UserReferred fired!');
        if (empty($event->referralId)) {
            \Log::debug('ReferralId not provided so skipping logic'. $event->referralId);
            return;
        }

        $referralLink = ReferralLink::find($event->referralId);

        if (empty($referralLink)) {
            \Log::warn('Referral Link not found for referralId '. $event->referralId);
            return;
        }

        $referralLink->relationships()->create(['user_id' => $event->user->id]);
    }
}
