<?php

namespace Pmn\Referrals\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class ReferralProgram
 * @package Pmn\Referrals\Models
 */
class ReferralProgram extends Model {

    /**
     * @var array
     */
    protected $fillable = ['name', 'uri', 'lifetime_minutes', 'title', 'description'];

    public function links()
    {
        return $this->hasMany(ReferralLink::class, 'referral_program_id', 'id');
    }

}
