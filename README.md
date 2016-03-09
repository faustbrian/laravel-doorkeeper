# Laravel Doorkeeper

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
[![Build Status][ico-travis]][link-travis]
[![Coverage Status][ico-scrutinizer]][link-scrutinizer]
[![Quality Score][ico-code-quality]][link-code-quality]
[![Total Downloads][ico-downloads]][link-downloads]

This package counts the amount of records on a relationship and compares them with a given set of limits. This can be useful to determine if a user has reached the limit of files he can upload or something similar.

## Install

Via Composer

``` bash
$ composer require draperstudio/laravel-doorkeeper
```

## Usage


## Example

#### Model
``` php
<?php
namespace App;

use DraperStudio\Doorkeeper\Traits\Doorkeeper;
use DraperStudio\Doorkeeper\Contracts\DoorkeeperContract;

class User extends Model implements DoorkeeperContract
{
    use Doorkeeper;


    public $limits = [
        'posts' => 5,
        'files' => 10,
    ];

    public function posts() {
        return $this->hasMany('App\Post');
    }

    public function files() {
        return $this->hasMany('App\File');
    }
}
```

#### Middleware
``` php
<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class ReachedLimits
{

    public function handle($request, Closure $next)
    {
        $user = Auth::check();

        if ( $user->limits($user->subscription->limits)->fails() ) {
            return redirect()->route('billing');
        }

        return $next($request);
    }
}
```

#### Controller
``` php
<?php
namespace App\Http\Controllers;

class DashboardController
{

    public function __construct()
    {
        $this->middleware('reachedLimits');
    }


    public function index()
    {
        return view('dashboard');
    }
}
```

## Methods

#### Perform checks with custom limits.
``` php
$user->limits($user->subscription->limits)->passes();
$user->limits($user->subscription->limits)->fails();
```

#### Perform a check and see if it passes.
``` php
$user->passes();
```

#### Perform a check and see if it fails.
``` php
$user->fails();
```

#### Get all limits.
``` php
$user->allowed();
```

#### Get a specific limit.
``` php
$user->allowed('posts');
```

#### Get the current counter.
``` php
$user->current();
```

#### Get a specific counter.
``` php
$user->current('posts');
```

#### Check if the overall limit has been reached.
``` php
$user->maxed();
```

#### Check if a specific limit has been reached.
``` php
$user->maxed('posts');
```

## Change log

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Testing

``` bash
$ composer test
```

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) and [CONDUCT](CONDUCT.md) for details.

## Security

If you discover any security related issues, please email hello@draperstudio.tech instead of using the issue tracker.

## Credits

- [DraperStudio][link-author]
- [All Contributors][link-contributors]

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/DraperStudio/laravel-doorkeeper.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/DraperStudio/Laravel-Doorkeeper/master.svg?style=flat-square
[ico-scrutinizer]: https://img.shields.io/scrutinizer/coverage/g/DraperStudio/laravel-doorkeeper.svg?style=flat-square
[ico-code-quality]: https://img.shields.io/scrutinizer/g/DraperStudio/laravel-doorkeeper.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/DraperStudio/laravel-doorkeeper.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/DraperStudio/laravel-doorkeeper
[link-travis]: https://travis-ci.org/DraperStudio/Laravel-Doorkeeper
[link-scrutinizer]: https://scrutinizer-ci.com/g/DraperStudio/laravel-doorkeeper/code-structure
[link-code-quality]: https://scrutinizer-ci.com/g/DraperStudio/laravel-doorkeeper
[link-downloads]: https://packagist.org/packages/DraperStudio/laravel-doorkeeper
[link-author]: https://github.com/DraperStudio
[link-contributors]: ../../contributors
