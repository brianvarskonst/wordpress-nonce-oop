# Wordpress Nonce
WordPress Nonce in an Object Oriented Way,\
this Library implements the WordPress Nonces functionality (wp_nonce_*()) like a Manager.

## Requirements
- PHP 7.2+
- Composer
- WordPress 5.2.0+

## Development Requirements
- PHPUnit 8
- brain/monkey ~1.4

## Features
* PSR-4 autoloading compliant structure
* Unit-Testing with PHPUnit 8
* Easy to use in framework or even include in a plain php plugin file

## Install
### Installation with Composer

Install with [Composer](https://getcomposer.org):

```sh
$ composer require brianvarskonst/wordpress-nonce-oop
```

### Clone this Repository
1. Create a demo `composer.json` file in your plugin.
2. Run `composer install`
3. It will load plugin dependency in a `vendor/` folder

### Run the tests
To run tests, executes commands below:

```sh
$ composer install
$ cd vendor/brianvarskonst/wordpress-nonce-oop
$ vendor/bin/phpunit
```

## Usage
### Example
I have created a example plugin ([wp-nonce-test-plugin](https://github.com/brianvarskonst/wordpress-nonce-oop/blob/master/wp-nonce-test-plugin.php)) to use this nonce manager with an example implementation of [NonceTest](https://github.com/brianvarskonst/wordpress-nonce-oop/blob/master/NonceTest.php) Class`.

### The Configuration:
Wordpress Nonce need an action to find the current action which is secured by a nonce.
Usually forms or Urls passes the nonce. 

- The first parameter (`string: actionName`) of the configuration defines the action name of this nonce.  
- The second parameter (`string: requestName`) is for request key. In this case, we would expect the nonce to be in `$_REQUEST['request_name']`.
- The third parameter (`int: lifetime`) is for the lifetime, defines the lifetime of the nonce, its expect an integer in seconds - this is optional, default value is 24 hours.

Note: The time interval from an nonce is not an regular time interval, 1 tick is equal half a second, this is the reason why we double the lifetime, note that please.

```php
use NoncesManager\Configuration;

$configuration = new Configuration( 
	'action', 
	'request_name',
    30 
);
```

### Create an new NonceManager
The Nonce Manager has 2 dynamic init methods, you can inject dependencies by call the `NonceManager::create` method - or you can only pass the configuration by using `NonceManager::build`.


```php
use NoncesManager\NonceManager;

// Build an new NonceManager Instance without injected Dependencies 
$NonceManager = NonceManager::build($configuration);

// Create an new NonceManager Instance with injected Dependencies
use NoncesManager\Nonces\Types\FieldType;
use NoncesManager\Nonces\Types\UrlType;
use NoncesManager\Nonces\Verification\Verifier;

$fieldType = new FieldType($configuration);
$urlType = new UrlType($configuration);
$verifier = new Verifier($configuration);

$NonceManager = NonceManager::create($configuration, $fieldType, $urlType, $verifier);
```

### Create an new Nonce (wp_create_nonce)
To create a simple Nonce, use `NonceCreate`:

```php
$NonceManager->Nonce()->create();
$nonce = $NonceManager->Nonce()->getNonce();
```

### Create an new Nonce Url (wp_nonce_url)
To add a nonce to an URL, you can use

```php
$url = $NonceManager->Url();
$url->generate('http://example.com/');

$urlString = $url->getUrl();
```
Return Url will be: String: `http://example.com/?request_name=$nonce`

### Create an new Nonce Field (wp_nonce_field)
Replicate `wp_nonce_field()` functionality by adding two parameters: `(bool) $referer` and `(bool) $echo`. Both are set to `false` by default. 

Set `$referer` to `true`, field will be appended with the URL of the current page. 
Set `$echo` to `true`, it will echo the field, before `create_url()`.

To add a form field:

```php
$field = $NonceManager->Field();
$field->generate();

$fieldString = $field->getField();
```
Return field will be: String: `<input type="hidden" name="request_name" value="$nonce">`

### Verify a Nonce
Verify an nonce was right created, you can use `NonceVerify`:

```php
$verifier = $this->NonceManager->Verifier();
$isVerified = $verifier->verify();
```

Return verify will be: Boolean: `true` or `false`

#### Alternative verify Ways:
Note: At the moment not beautiful tested, and need to solve the mentioned Todo's!
But the admin and ajax referer check should be work.

```php
$verifier = $this->NonceManager->Verifier();

// Check Admin Referer:
$isAdminReferer = $verifier->checkAdminReferer();

// Check Ajax Referer:
$isAjaxReferer = $verifier->checkAjaxReferer();
```

## Todos
- Implement RequestArgumentParser for the verifier methods: `checkAdminReferer & checkAjaxReferer`
- Upgrade brain\monkey from ~1.4 to lastest Version 2
- Make better test for wp_verify_nonce, check_admin_referer and check_ajax_referer in Verifier Class
- Make better test for NonceAbstract Class

## Ideas
- Add more manager behavior, manage all nonces used in the current wordpress installation

## Credits
* [Wordpress Nonces Documentation](https://codex.wordpress.org/WordPress_Nonces)
* [PHP Unit Testing Documentation](https://phpunit.de)

## License
[GPL2](https://www.gnu.de/documents/gpl-2.0.en.html)