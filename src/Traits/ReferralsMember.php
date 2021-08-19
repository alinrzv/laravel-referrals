<?php

namespace Pmn\Referrals\Traits;

use Pmn\Referrals\Models\ReferralLink;
use Pmn\Referrals\Models\ReferralProgram;

/**
 * Trait ReferralsMember
 * @package Pmn\Referrals\Traits
 */
trait ReferralsMember {

    public function getReferrals()
    {
        return ReferralProgram::all()->map(function ($program) {
            return ReferralLink::getReferral($this, $program);
        })->filter();
    }

    public function referralProgram()
    {
        return $this->hasOne(ReferralProgram::class, 'id', 'referral_program_id');
    }

}