Intellipush SDK for PHP
====================

This repository contains the open source PHP SDK that allows you to access Intellipush from your PHP app.


Installation
-----



Usage
-----

Install the lastest version with: ``composer require intellipush/intellipush-php-sdk-v2``

```
use Intellipush\Intellipush;

$key = 'YOUR KEY';
$secret = 'YOUR SECRET';
$phoneNumber = '004799988777';

//Send an SMS
$response = Intellipush::auth($key, $secret)->sms('Hello World.', $phoneNumber);

var_dump($response);


```


Read more on:

[Intellipush PHP SDK documentation ](https://www.intellipush.com/documentation/php-sdk)


Sign up
-----
[Intellipush.com](https://www.intellipush.com)


Enjoy our service!

/Intellipush
