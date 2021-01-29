# LevelCredit API Client

LevelCredit API Client is low-level dependency of the LevelCredit SDK libraries. This project is alpha stage with no 
guarantee with backward compatibility in future versions. It is not recommended to program directly to this project, 
but instead, use the public methods to our higher-level SDK libraries. We reserve the right to remove this project 
completely. Consider yourself warned. 
The client uses [Guzzle](https://github.com/guzzle/guzzle) to send http requests.
The client supports adding a log handler for logging messages. See [LogHandlerInterface](https://github.com/levelcredit/levelcredit-api-php/blob/master/src/Logging/LogHandlerInterface.php).   

## Installation

You can use [composer](https://getcomposer.org) 

```bash
$ composer require levelcredit/levelcredit-api-php
```

## Basic Usage

```php
<?php

use LevelCredit\LevelCreditApi\LevelCreditApiClient;
use LevelCredit\LevelCreditApi\Exception\LevelCreditApiException;
use LevelCredit\LevelCreditApi\Enum\UserEmbeddedEntities;
use LevelCredit\LevelCreditApi\Model\Request\GetPartnerUsersFilter;
use LevelCredit\LevelCreditApi\Model\Response\Resource\User;

$client = LevelCreditApiClient::create(getenv('CLIENT_ID'), getenv('CLIENT_SECRET'))// client_id and client_secret are optional
//    ->setClientId(getenv('CLIENT_ID')) // you can change client_id for each request if needed
//    ->setClientSecret(getenv('CLIENT_SECRET')) // client_secret the same
//    ->setBaseUri(getenv('BASE_URL')) // by default https://my.sandbox2.renttrack.com
//    ->addLogHandler() // you can add additional log handler. See [LogHandler Component] for more details
//    ->disableLogHandlers() // you can disable(means remove) all log handlers if needed 
//    ->setSerializer() // internal method for change serializer by default will use JSON format
;
try {
    $accessTokenResponse = $client->getAccessTokenByUsernamePassword(getenv('USERNAME'), getenv('PASSWORD'));

    if ($accessTokenResponse->getStatusCode() != 200 || !$accessTokenResponse->getErrors()->isEmpty()) {
        // process error here
        $message = (string)$accessTokenResponse->getErrors();
        // ...
        // process error here
    } 
} catch (LevelCreditApiException $e) {
    // process exception here can be guzzle (like problem with connection or certificate), logic or serialization errors
}

// you can set access token for use it for each next requests
//$client->setAccessToken($accessTokenResponse->getResource()->getAccessToken());
try {
    $usersCollection = $client->getPartnerUsers(
        GetPartnerUsersFilter::create()
            ->setEmail('user@email.here') // added email for filtering
            ->addEmbedded(UserEmbeddedEntities::SUBSCRIPTIONS), // if we need  embedded additional resource or collection
        $accessTokenResponse->getResource()->getAccessToken() // or pass it here
    );
    
    if ($usersCollection->getStatusCode() != 200 || !$usersCollection->getErrors()->isEmpty()) {
        // process error here
        $message = (string)$accessTokenResponse->getErrors();
        // ...
        // process error here
    } 
    
    if ($usersCollection->getStatusCode() == 204 || $usersCollection->getElements()->isEmpty()) {
        // users not found
    }
    
    $totalCount = $usersCollection->getTotalCount(); // total count show how elements you can get without filters
    /** @var User $user */
    $user = $usersCollection->getElements()->first();
} catch (LevelCreditApiException $e) {
    // process exception here can be guzzle (like problem with connection or certificate), logic or serialization errors
}
```

## Running the Tests

Install the [Composer](http://getcomposer.org/) all dependencies:

```bash
$ php composer.phar install
```

To run the test suite, go to the project root and run:

```bash
$ php vendor/bin/phpunit
```

## About

### Components

#### [ApiClient](https://github.com/levelcredit/levelcredit-api-php/blob/master/src/LevelCreditApiClient.php)

Implemented such methods for LeveCredit API:

* getAccessTokenByUsernamePassword - get access_token and refresh_token by username+password
* getAccessTokenByRefreshToken - get new access_token and refresh_token by refresh_token
* createTradelineSync - create new tradeline sync record
* addDataToTradelineSync - add data to import 
* patchTradelineSync - run tradeline import
* getPartnerUsers - get partner user by filters
* payProduct - pay product

#### [Serializer](https://github.com/levelcredit/levelcredit-api-php/blob/master/src/Serializer/SerializerInterface.php)

Needs to serialize request models, and query filters and deserialize response.  

#### [LogHandler](https://github.com/levelcredit/levelcredit-api-php/blob/master/src/Logging/LogHandlerInterface.php)

Needs for processing logs from http client. Implemented [DefaultLogHandler](https://github.com/levelcredit/levelcredit-api-php/blob/master/src/Logging/DefaultLogHandler.php)
that uses [ExtendedMessageFormatter](https://github.com/levelcredit/levelcredit-api-php/blob/master/src/Logging/ExtendedMessageFormatter.php) 
for sensitive data sanitization and can be set any [PSR-3](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-3-logger-interface.md) 
compatibility logger.

#### [MessageFormatter](https://github.com/levelcredit/levelcredit-api-php/blob/master/src/Logging/MessageFormatterInterface.php)

Needs for formatting request and response to log string.

#### [HeaderFormatter](https://github.com/levelcredit/levelcredit-api-php/blob/master/src/Logging/HeaderFormatterInterface.php)

Used by [ExtendedMessageFormatter](https://github.com/levelcredit/levelcredit-api-php/blob/master/src/Logging/ExtendedMessageFormatter.php) 
for formatting headers and sensitive data sanitization. 

#### [QueryFormatter](https://github.com/levelcredit/levelcredit-api-php/blob/master/src/Logging/QueryFormatterInterface.php)

Used by [ExtendedMessageFormatter](https://github.com/levelcredit/levelcredit-api-php/blob/master/src/Logging/ExtendedMessageFormatter.php)
for formatting query string and sensitive data sanitization.

#### [BodyFormatter](https://github.com/levelcredit/levelcredit-api-php/blob/master/src/Logging/BodyFormatterInterface.php)

Used by [ExtendedMessageFormatter](https://github.com/levelcredit/levelcredit-api-php/blob/master/src/Logging/ExtendedMessageFormatter.php)
for formatting request and response bodies and sensitive data sanitization.

### Requirements

- LevelCredit API Client works with PHP 7.3 or above with curl and json extensions.
- [Guzzle](https://github.com/guzzle/guzzle) should be 7 version. 
- [JmsSerializer](https://github.com/schmittjoh/serializer) should be at least 2.0 version.

### License

