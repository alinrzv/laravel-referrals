# Simple Referrals system for Laravel 8 , modded version 

Based on original https://github.com/pdazcom/laravel-referrals

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE)
[![Total Downloads][ico-downloads]][link-downloads]

A simple system of referrals with the ability to assign different programs for different users.

This package was created based on the [lesson](https://blog.damirmiladinov.com/laravel/building-laravel-referral-system.html#.Wc4eA6xJaHo) 
author is Damir Miladinov, with some minor changes, for which I express my gratitude to him.

## Installation
Via Composer

``` bash
$ composer require pmn/laravel-referrals
```

Then in config/app.php add service-provider and facade alias:

```
'providers' => [
    ...
    Pmn\Referrals\Providers\ReferralsServiceProvider::class,
    ...
];
```

## Usage

First of all you need to run:
```
php artisan vendor:publish --provider='Pmn\Referrals\Providers\ReferralsServiceProvider' 
```
Then change new migrations if it need and run `php artisan migrate`

Add middleware to your `web` group in `Http/Kernel.php`:

```
'web' => [
    ...
    \Pmn\Referrals\Http\Middleware\StoreReferralCode::class,
],

```

Add `Pmn\Referrals\Traits\ReferralsMember` to your `Users` model:

```
    class User extends Authenticatable {
        use ReferralsMember;
        ...
    }


```

In event provider app/Providers/EventServiceProvider.php define event and listener under $listen property.

        'Pmn\Referrals\Events\UserReferred' => [
	    'Pmn\Referrals\Listeners\ReferUser',
	],

```

```

Then in `Http/Controllers/Auth/RegisterController.php` add event dispatcher:

```
...
use Pmn\Referrals\Events\UserReferred;

...
// overwrite registered function
public function registered(Request $request)
{
    // dispatch user referred event here
    event(new UserReferred(request()->cookie('ref'), $user));
}
```

From this point all referral links would be attach new users as referrals to users owners of this links.

And then you need to create a referral program in DB and attach it to users by `referral_program_id` field:

```
    php artisan tinker
    
    Pmn\Referrals\Models\ReferralProgram::create(['name'=>'example', 'title' => 'Example Program', 'description' => 'Laravel Referrals made easy thanks to laravel-referrals package based on an article by Damir Miladinov,', 'uri' => 'register']);
```

add association to config `referrals.programs`:
```
    ...
    'example' => App\ReferralPrograms\ExampleProgram.php
```
and create an reward class `App\ReferralPrograms\ExampleProgram.php` for referral program:

```
<?php

namespace App\ReferralPrograms;

use Pmn\Referrals\Programs\AbstractProgram;

class ExampleProgram extends AbstractProgram {

    const ROYALTY_PERCENT = 30;

    /**
    *   It can be anything that will allow you to calculate the reward.   
    * 
    *   @param $rewardObject
    */
    public function reward($rewardObject)
    {
        $this->recruitUser->balance = $this->recruitUser->balance + $rewardObject * (self::ROYALTY_PERCENT/100);
        $this->recruitUser->save();
    }

}
```

create referral link:
```
php artisan tinker

Pmn\Referrals\Models\ReferralLink::create(['user_id' => 1, 'referral_program_id' => 1]);
```

and finally dispatch reward event in any place of your code:

```
event(Pmn\Referrals\Events\ReferralCase(['example'], $referralUser, $rewardObject))
```

From this point all referrals action you need would be reward recruit users by code logic in your reward classes.

Create many programs and their reward classes. Enjoy!

### Bonus Content 

If you want to list all the users for a given Referral Link, simply use

```php
$referralLink->referredUsers()
```

## Contact

---

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.

[ico-version]: https://img.shields.io/packagist/v/pmn/laravel-referrals.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/pmn/laravel-referrals/master.svg?style=flat-square
[ico-scrutinizer]: https://img.shields.io/scrutinizer/coverage/g/pmn/laravel-referrals.svg?style=flat-square
[ico-code-quality]: https://img.shields.io/scrutinizer/g/pmn/laravel-referrals.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/pmn/laravel-referrals.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/pmn/laravel-referrals
[link-travis]: https://travis-ci.org/pmn/laravel-referrals
[link-scrutinizer]: https://scrutinizer-ci.com/g/pmn/laravel-referrals/code-structure
[link-code-quality]: https://scrutinizer-ci.com/g/pmn/laravel-referrals
[link-downloads]: https://packagist.org/packages/pmn/laravel-referrals
[link-author]: https://github.com/pmn
[link-contributors]: ../../contributors
