# LevelCredit API Client

LevelCredit API Client is part of LevelCredit SDKs. The Client was created like wrapper for send requests to API. 
The client use [Guzzle](https://github.com/guzzle/guzzle) for send http requests. 
The client supports adding log handler for logging messages [LogHandlerInterface](https://github.com/levelcredit/levelcredit-api-php/blob/master/src/Logging/LogHandlerInterface.php).   

## Installation

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

$client = LevelCreditApiClient::create('<CLIENT_ID>', '<CLIENT_SECRET>') // client_id and client_secret are optional
//    ->setClientId('<YOUR_CLIENT_ID>') // you can change client_id for each request if needed
//    ->setClientSecret('<YOUR_CLIENT_SECRET>') // client_secret the same
//    ->setBaseUri('<YOUR_SPECIFIC_BASE_URL>') // by default will use production levelcredit url
//    ->addLogHandler() // you can add additional log handler. See [LogHandler Component] for more details
//    ->disableLogHandlers() // you can disable(means remove) all log handlers if needed 
//    ->setSerializer() // internal method for change serializer by default will use JSON format
;
try {
    $accessTokenResponse = $client->getAccessTokenByUsernamePassword('<USERNAME>', '<PASSWORD>');

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

## About

### Components

#### ApiClient

Implemented such methods for LeveCredit API:

* getAccessTokenByUsernamePassword
* getAccessTokenByRefreshToken
* createTradelineSync
* addDataToTradelineSync
* patchTradelineSync
* getPartnerUsers
* payProduct

#### Serializer 

#### LogHandler

#### MessageFormatter

#### HeaderFormatter

#### QueryFormatter

#### BodyFormatter

### Requirements

- LevelCredit API Client works with PHP 7.3 or above with curl and json extensions.
- [Guzzle](https://github.com/guzzle/guzzle) should be 7 version. 
- [JmsSerializer](https://github.com/schmittjoh/serializer) should be at least 2.0 version.

### License

