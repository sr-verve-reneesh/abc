# WHITEMAIL API (PHP 5 Package)

[![Version]](https://packagist.org/packages/reneeshkuttan/whitemail-api)

WhitemailApi is a PHP wrapper to whitemail api.

## Contents

- [Installation](#installation)
- [Usage](#usage)
    - [Requirements](#requirements)
    - [Initialization](#initialization)
    - [Identify](#identify)
    - [Track](#track)
- [License](#license)

## Installation

1) In order to install WhitemailApi, just add the following to your composer.json. Then run `composer update`:

```json
"reneeshkuttan/whitemail-api": "1.0.x-dev"
```

## Usage

### Requirements
Before you start, acquire your `API End Point` url and `API Key` from whitemail's company settings page.


### Initializtion
Let's start by creating a new instance of `WhitemailApi`:

```php
$api = new WhitemailApi($apiEndPoint, $apiKey);
```

### Identify

Now we can send a call through the API to identify a subscriber

```php
$subscriberData = [
	'email'  => "johns@example.com",
            'fields' => [
                'first_name' => 'John',
                'last_name' => 'Sam'
             ]
];

$api->identify($subscriberData);

```
On succesful request, the identify function would return an associative array with the details of the subscriber:

```php
Array ( [success] => 1 [message] => Subscriber succesfully created/updated [subscriber] => Array ( [id] => 31 [uris] => Array ( [show] => http://client1.whitemail5.dev/subscriber/31 ) ) ) 
```

### Track

Use the `track()` function to track subscriber events:

```php
$trackData = [
            'email' => 'johns@example.com',
            'eventName' => 'purchased',
            'data' => ['amt' => '40.50'],
        ];

$api->track($trackData);
```

On success this would return an associative array with status information

```php
Array ( [success] => 1 [message] => Subscriber event added ) 
```

## License

WhitemailApi is free software distributed under the terms of the MIT license.
