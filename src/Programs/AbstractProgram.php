<?php

namespace Pmn\Referrals\Programs;

use Pmn\Referrals\Contracts\ProgramInterface;
use Pmn\Referrals\Models\ReferralProgram;

abstract class AbstractProgram implements ProgramInterface {

    /**
     * @var ReferralProgram
     */
    protected $program;

    /**
     * User who attracted the referral.
     *
     * @var mixed
     */
    protected $recruitUser;

    /**
     * Attracted user
     *
     * @var
     */
    protected $referralUser;

    public function __construct(ReferralProgram $program, $recruitUser, $referralUser)
    {
        $this->program = $program;
        $this->recruitUser = $recruitUser;
        $this->referralUser = $referralUser;
    }

}