# WordPress Nonce
WordPress Nonce in an Object-Oriented Way, \
this Package provides the WordPress Nonces functionalities (wp_nonce_*()) in a more like Manager-way.

## Table of Contents

- [Introduction](#introduction)
- [Requirements](#requirements)
- [Features](#features)
- [Installation](#installation)
- [Usage](#usage)
- [Quality Assurance](#quality-assurance)

## Introduction

This package was created during the time and for the reason of applying to my current employer [Inpsyde GmbH](https://inpsyde.com/).

I stumbled across this package because I was going through my repositories and wanted to see what I wrote back then. 
It's always a special feeling, especially as a developer to look at old work of yourself, 
and it's also very nice to see how PHP has changed since then and of course yourself have changed as well.

I wrote this package in one night from Friday to Saturday, you can see that very much if you're check out the [initial version](https://github.com/brianvarskonst/wordpress-nonce-oop/tree/1.0.0), 
It was to complex than it should be and also not quite well-structured from an architecture perspective as my current standard.

That's the reason I decided to modernize it, to a new major version.

## Requirements
- PHP 7.4
- Composer 1 | 2

## Features
* PSR-4 Autoload compliant structure
* Integration &- Unit Tests with PHPUnit
* Easy to use in framework and provides missing object-oriented abstracted feature-set

## Installation

### Composer

Install with [Composer](https://getcomposer.org):

```sh
$ composer require brianvarskonst/wordpress-nonce-oop
```

## Usage

### NonceManager

The main part of this package as it's repository name speaking the `NonceManager`, 
which are just a compounded class to glue together the main functionalities.

You can use the static factory method, to instantiate a new instance of the `NonceManager` - class, 
or you can pass the needed dependencies via the constructor by yourself.

**Static Factory Method**
```php
use Bvsk\WordPress\NonceManager\NonceManager;

$nonceManager = NonceManager::createFromDefaults();
```

**Dependency-injection**
```php
use Bvsk\WordPress\NonceManager\NonceManager;
use Bvsk\WordPress\NonceManager\Nonces\Factory\AggregatedNonceFactory;
use Bvsk\WordPress\NonceManager\Nonces\Factory\SimpleNonceFactory;
use Bvsk\WordPress\NonceManager\Nonces\Factory\FieldNonceFactory;
use Bvsk\WordPress\NonceManager\Nonces\Factory\UrlNonceFactory;
use Bvsk\WordPress\NonceManager\Verification\NonceVerifier;

$nonceManager = new NonceManager(
    new AggregatedNonceFactory(
        new SimpleNonceFactory(),
        new FieldNonceFactory(),
        new UrlNonceFactory()
    ),
    new NonceVerifier()
);
```

#### Functionalities

##### Create new Nonces

```php
use Bvsk\WordPress\NonceManager\Nonces\SimpleNonce;

// Create default Simple Nonces without any custom data
$nonceManager->createNonce(SimpleNonce::class);

// Create default Simple Nonce
$nonceManager->createNonce(
    SimpleNonce::class,
    [
        'action' => -1,
        'requestName' => '_wpnonce',
        // Optional
        'lifetime' => DAY_IN_SECONDS
    ]
);

// Create default Simple Nonce
use Bvsk\WordPress\NonceManager\Nonces\FieldNonce;

$nonceManager->createNonce(
    FieldNonce::class
);

// Create default Simple Nonce

use Bvsk\WordPress\NonceManager\Nonces\UrlNonce;

$nonceManager->createNonce(
    UrlNonce::class,
    [
        'url' => 'https://www.example.com/test'
    ]
);
```

##### Verify Nonces

```php
$nonceManager->verify($nonce);
```

##### Extendability

If you need to add new components you have the ability to add them via the dependency-injection, which makes the `NonceManager` also extendable
for your custom functionalities. Every dependency provides and depend on its own interface/contract.
So you can each required dependency extend via creating custom implementations for: `NonceFactory`, `Nonce` and `Verifier`.

If you want to introduce for example a new verifier you can use the `Verfier` - interface and just implement it at you custom implementation 
and pass it via dependency injection at the NonceManager.

```php
use Bvsk\WordPress\NonceManager\Verification\Verifier;
use Bvsk\WordPress\NonceManager\NonceManager;

class FooBarVerifier implements Verifier
{
    public function verify(Nonce $nonce): bool
    {
        return $nonce->requestName === 'fooBar';
    }
    
    public function getAge(Nonce $nonce): string
    {
        return true;
    }
    
    public function renderMessageHasExpired(Nonce $nonce): void
    {
        echo esc_html__('FooBar nonce was expired', 'textdomain');
    }
}

$nonceManager = new NonceManager(
    new AggregatedNonceFactory(
        new SimpleNonceFactory(),
        new FieldNonceFactory(),
        new UrlNonceFactory()
    ),
    new FooBarVerifier()
);
```

## Quality Assurance

This Package provides a baseline of common used QA Code tools which you can run simply by custom composer script commands.

### PHP CodeSniffer

To ensure the quality of the code this package uses the Inpsyde Coding Standards, 
which are especially created for WordPress Projects. You can also use it for every other projects.
Provides a good set of coding rules via PHP CodeSniffer CLI Tool.

> PHP 7+ coding standards for Inpsyde WordPress projects.

```shell
$ composer cs
```

### Psalm

```shell
$ composer psalm
```

### Tests

#### Run all tests

```sh
$ composer tests
```

#### Run unit tests

```sh
$ composer tests:unit
```

#### Run integration tests

```sh
$ composer tests:integration
```

### Credits
* [WordPress Nonces Documentation](https://codex.wordpress.org/WordPress_Nonces)
* [PHPUnit Documentation](https://phpunit.de)
* [Brain-WP / BrainMonkey](https://github.com/Brain-WP/BrainMonkey)
* [Inpsyde Coding Standards](https://github.com/inpsyde/php-coding-standards)

## License

Copyright (c) 2022, Brianvarskonst under [MIT](LICENSE) License

## Contributing

All feedback / bug reports / pull requests are welcome.