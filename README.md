# Laravel Doorkeeper

[![Build Status](https://img.shields.io/travis/artisanry/Doorkeeper/master.svg?style=flat-square)](https://travis-ci.org/artisanry/Doorkeeper)
[![PHP from Packagist](https://img.shields.io/packagist/php-v/artisanry/doorkeeper.svg?style=flat-square)]()
[![Latest Version](https://img.shields.io/github/release/artisanry/Doorkeeper.svg?style=flat-square)](https://github.com/artisanry/Doorkeeper/releases)
[![License](https://img.shields.io/packagist/l/artisanry/Doorkeeper.svg?style=flat-square)](https://packagist.org/packages/artisanry/Doorkeeper)

This package counts the amount of records on a relationship and compares them with a given set of limits. This can be useful to determine if a user has reached the limit of files he can upload or something similar.

## Installation

Require this package, with [Composer](https://getcomposer.org/), in the root directory of your project.

``` bash
$ composer require artisanry/doorkeeper
```

## Usage

#### Model
``` php
<?php
namespace App;

use Artisanry\Doorkeeper\Traits\Doorkeeper;
use Artisanry\Doorkeeper\Contracts\DoorkeeperContract;

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

## Testing

``` bash
$ phpunit
```

## Security

If you discover a security vulnerability within this package, please send an e-mail to hello@basecode.sh. All security vulnerabilities will be promptly addressed.

## Credits

This project exists thanks to all the people who [contribute](../../contributors).

## License

Mozilla Public License Version 2.0 ([MPL-2.0](./LICENSE)).
