Intellipush SDK for PHP
====================

This repository contains the open source PHP SDK that allows you to access Intellipush from your PHP app.


Installation
-----



Usage
-----

Install the lastest version with: ``composer require 'intellipush/intellipush-php-sdk-v2:dev-master'``

If you need help getting started with Composer:
[click here](https://getcomposer.org/doc/00-intro.md#installation-linux-unix-osx)

```
$applicationPath = __DIR__ . '/../YOUR APPLICATION PATH';

require_once $applicationPath . 'vendor/autoload.php';

use Intellipush\Intellipush;

$key = 'YOUR KEY';
$secret = 'YOUR SECRET';
$countrycode = '0047';
$phoneNumber = 'xxxxxxxx';

//Send an SMS
$response = Intellipush::auth($key, $secret)->sms('Hello World.', $countrycode, $phoneNumber);

var_dump($response);


```


Read more on:

[Intellipush PHP SDK documentation ](https://www.intellipush.com/documentation/php-sdk)


Sign up
-----
[Intellipush.com](https://www.intellipush.com)


Enjoy our service!

/Intellipush
