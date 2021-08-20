<?php

namespace Pmn\Referrals\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider;
use Illuminate\Support\Facades\Event;

class ReferralsServiceProvider extends EventServiceProvider
{

    /**
     * Register bindings in the container.
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../../config/referrals.php', 'referrals');
    }

    /**
     * Perform post-registration booting of services.
     */
    public function boot()
    {
    
    	parent::boot();
    	
    	/*
	    protected $listen = [
	        'Pmn\Referrals\Events\UserReferred' => [
	            'Pmn\Referrals\Listeners\ReferUser',
	        ],
	        'Pmn\Referrals\Events\ReferralCase' => [
	            'Pmn\Referrals\Listeners\RewardUser',
	        ],
	    ];
    
	The $listen mapping is only used to register listeners at an app-level inside App\Providers\EventServiceProvider, not within a package's ServiceProvider.
	The proper way to register an event listener from within a package's ServiceProvier in my opinion is to simply use the Event facade in it's boot function.
	*/

	Event::listen('Pmn\Referrals\Events\UserReferred', 'Pmn\Referrals\Listeners\ReferUser');
	Event::listen('Pmn\Referrals\Events\ReferralCase', 'Pmn\Referrals\Listeners\RewardUser');
        
        // resolve config
        $this->publishes([__DIR__ . '/../../config/referrals.php' => config_path('referrals.php')], 'config');

        // resolve migrations
        $migrationsPath = __DIR__ . '/../../database/migrations/2017_09_23_1100';
        $this->publishes([
            "{$migrationsPath}00_create_referral_programs_table.php" => database_path('migrations/' . date("Y_m_d_Hi") . "00_create_referral_programs_table.php"),
            "{$migrationsPath}01_create_referral_links_table.php" => database_path('migrations/' . date("Y_m_d_Hi") . "01_create_referral_links_table.php"),
            "{$migrationsPath}02_create_referral_relationships_table.php" => database_path('migrations/' . date("Y_m_d_Hi") . "02_create_referral_relationships_table.php"),
            "{$migrationsPath}03_add_allowed_ref_program_to_users.php" => database_path('migrations/' . date("Y_m_d_Hi") . "03_add_allowed_ref_program_to_users.php"),
        ], 'migrations');
    }
}
