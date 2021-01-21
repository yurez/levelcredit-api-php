<?php

namespace LevelCredit\LevelCreditApi\Tests\Functional\Serializer;

use GuzzleHttp\Psr7\Response;
use LevelCredit\LevelCreditApi\Enum\TradelineSyncStatus;
use LevelCredit\LevelCreditApi\Enum\TradelineSyncType;
use LevelCredit\LevelCreditApi\Enum\UserStatus;
use LevelCredit\LevelCreditApi\Exception\SerializerException;
use LevelCredit\LevelCreditApi\Model\Request\CreateTradelineSyncRequest;
use LevelCredit\LevelCreditApi\Model\Request\GetPartnerUsersFilter;
use LevelCredit\LevelCreditApi\Model\Response\AccessTokenResponse;
use LevelCredit\LevelCreditApi\Model\Response\EmptyResponse;
use LevelCredit\LevelCreditApi\Model\Response\Resource\AccessToken;
use LevelCredit\LevelCreditApi\Model\Response\Resource\Subscription;
use LevelCredit\LevelCreditApi\Model\Response\Resource\Sync;
use LevelCredit\LevelCreditApi\Model\Response\Resource\User;
use LevelCredit\LevelCreditApi\Model\Response\SyncResourceResponse;
use LevelCredit\LevelCreditApi\Model\Response\UserCollectionResponse;
use LevelCredit\LevelCreditApi\Serializer\Serializer;
use PHPUnit\Framework\TestCase;

class SerializerTest extends TestCase
{
    /**
     * @test
     */
    public function shouldSerializeCreateTradelineSyncRequestFull(): void
    {
        $jsonRequest = '{"summary_email":"test@test.com","timeout_minutes":5,"type":"asynchronous"}';

        $request = CreateTradelineSyncRequest::create()
            ->setSummaryEmail('test@test.com')
            ->setTimeoutMinutes(5)
            ->setType(TradelineSyncType::ASYNCHRONOUS)
        ;

        $serializer = Serializer::create();
        $this->assertEquals(
            $jsonRequest,
            $serializer->serializeRequest($request)
        );
    }

    /**
     * @test
     */
    public function shouldSerializeCreateTradelineSyncRequestShort(): void
    {
        $jsonRequest = '{"type":"synchronous"}';

        $request = CreateTradelineSyncRequest::create()
            ->setType(TradelineSyncType::SYNCHRONOUS)
        ;

        $this->assertEquals(
            $jsonRequest,
            Serializer::create()->serializeRequest($request)
        );
    }

    /**
     * @test
     */
    public function shouldSerializeGetPartnerUsersFilterFull(): void
    {
        $result = [
            'email' => 'test@test.com',
            'resident_id' => 'test_resident_id',
            'status' => 'invite',
            'created_at' => '2020-02-29',
            'count' => 10,
            'offset' => 20,
        ];

        $query = GetPartnerUsersFilter::create()
            ->setEmail('test@test.com')
            ->setResidentId('test_resident_id')
            ->setStatus(UserStatus::INVITE)
            ->setCreatedAt(new \DateTime('2020-02-29'))
            ->setCount(10)
            ->setOffset(20)
        ;

        $this->assertEquals(
            $result,
            Serializer::create()->serializeQuery($query)
        );
    }

    /**
     * @test
     */
    public function shouldSerializeGetPartnerUsersFilterShort(): void
    {
        $result = [
            'email' => 'test@test.com',
        ];

        $query = GetPartnerUsersFilter::create()
            ->setEmail('test@test.com')
        ;

        $this->assertEquals(
            $result,
            Serializer::create()->serializeQuery($query)
        );
    }

    /**
     * @test
     */
    public function shouldSerializePayProductRequestFull(): void
    {
         $this->markTestIncomplete('Should finish');
    }

    /**
     * @test
     */
    public function shouldSerializePayProductRequestShort(): void
    {
        $this->markTestIncomplete('Should finish');
    }

    /**
     * @test
     */
    public function shouldDeserializeEmptyResponse(): void
    {
        $response = new Response(204);

        $responseModel = new EmptyResponse();
        $responseModel->setStatusCode(204);

        $this->assertEquals(
            $responseModel,
            Serializer::create()->deserializeResponse($response, new EmptyResponse())
        );
    }

    /**
     * @test
     */
    public function shouldThrowExceptionWhenGetEmptyResponseOnDeserializeNonEmptyResponse(): void
    {
        $this->expectException(SerializerException::class);
        $this->expectExceptionMessage('Invalid empty response');

        $response = new Response();

        Serializer::create()->deserializeResponse($response, new SyncResourceResponse(), Sync::class);
    }

    /**
     * @test
     */
    public function shouldDeserializeGeneralFailedResponse(): void
    {
        $json = '[
            {"parameter":"status","value":"deleted","message":"Unsupported status"},
            {"parameter":"_globals","value":null,"message":"Something went wrong."}, 
            {"parameter":"payment_account.bank","value":{},"message":"Can\'t be use"}
        ]';

        $response = new Response(400, [], $json);

        $responseModel =  Serializer::create()->deserializeResponse($response, new EmptyResponse());

        $this->assertEquals(400, $responseModel->getStatusCode());
        $this->assertCount(3, $responseModel->getErrors());
        $this->assertEquals(
            'Unsupported status. Something went wrong. Can\'t be use.',
            (string)$responseModel->getErrors()
        );
    }

    /**
     * @test
     */
    public function shouldDeserializeOAuthFailedResponse(): void
    {
        $json = '{"error": "invalid_grant","error_description": "The access token provided is invalid."}';

        $response = new Response(401, [], $json);

        $responseModel =  Serializer::create()->deserializeResponse($response, new EmptyResponse());

        $this->assertEquals(401, $responseModel->getStatusCode());
        $this->assertCount(1, $responseModel->getErrors());
        $this->assertEquals(
            'The access token provided is invalid.',
            (string)$responseModel->getErrors()
        );
    }

    /**
     * @test
     */
    public function shouldDeserializeAccessTokenResponse(): void
    {
        $json = '{
            "access_token": "ZjYwZDEzOTgzMjJmYjc4NWRlYjdkOWZjMTMzYjg3MTAyYWU2Mzc3NWVkYjRhZjBiOGY3ZjFmNzY4OTkzNTcxNA",
            "expires_in": 3600,
            "token_type": "bearer",
            "scope": null,
            "refresh_token": "MGMxODdmZjkxZmI1Mzk5ZDkzMTFhY2NmZDdiMDQ4Y2Y0MmE1ZDFiNWRiNjhmNzc5MWQzMWE1MTJhMTkwZjg0MA"
        }';

        $response = new Response(200, [], $json);
        /** @var AccessTokenResponse $responseModel */
        $responseModel =  Serializer::create()->deserializeResponse(
            $response,
            new AccessTokenResponse(),
            AccessToken::class
        );

        $this->assertInstanceOf(AccessTokenResponse::class, $responseModel);
        $this->assertEquals(200, $responseModel->getStatusCode());
        $this->assertEmpty($responseModel->getErrors());
        $this->assertInstanceOf(AccessToken::class, $resourceModel = $responseModel->getResource());
        $this->assertEmpty($resourceModel->getScope());
        $this->assertEquals(
            'ZjYwZDEzOTgzMjJmYjc4NWRlYjdkOWZjMTMzYjg3MTAyYWU2Mzc3NWVkYjRhZjBiOGY3ZjFmNzY4OTkzNTcxNA',
            $resourceModel->getAccessToken()
        );
        $this->assertEquals(3600, $resourceModel->getExpiresIn());
        $this->assertEquals('bearer', $resourceModel->getTokenType());
        $this->assertEquals(
            'MGMxODdmZjkxZmI1Mzk5ZDkzMTFhY2NmZDdiMDQ4Y2Y0MmE1ZDFiNWRiNjhmNzc5MWQzMWE1MTJhMTkwZjg0MA',
            $resourceModel->getRefreshToken()
        );
    }

    /**
     * @test
     */
    public function shouldDeserializeSyncResourceResponse(): void
    {
        $json = '{
            "id": 2744222269,
            "url": "https://my.levelcredit.com/api/tradeline/syncs/2744222269",
            "status": "waiting"
        }';

        $response = new Response(201, [], $json);
        /** @var SyncResourceResponse $responseModel */
        $responseModel =  Serializer::create()->deserializeResponse(
            $response,
            new SyncResourceResponse(),
            Sync::class
        );

        $this->assertInstanceOf(SyncResourceResponse::class, $responseModel);
        $this->assertEquals(201, $responseModel->getStatusCode());
        $this->assertEmpty($responseModel->getErrors());
        $this->assertInstanceOf(Sync::class, $resourceModel = $responseModel->getResource());
        $this->assertEmpty($resourceModel->getMessage());
        $this->assertEquals(2744222269, $resourceModel->getId());
        $this->assertEquals('https://my.levelcredit.com/api/tradeline/syncs/2744222269', $resourceModel->getUrl());
        $this->assertEquals(TradelineSyncStatus::WAITING, $resourceModel->getStatus());
    }

    /**
     * @test
     */
    public function shouldDeserializeUserCollectionResponse()
    {
        $json = '[
            {
                "id":4086667068,
                "url":"https://my.levelcredit.com/api/partner/users/4086667068",
                "first_name":"Ritaa",
                "last_name":"Rosemann",
                "email":"RitaR3322332@tradeline.com",
                "phone":"7032551402",
                "email_notification":true,
                "offer_notification":true,
                "culture":"en",
                "hmac":"038bec059ac42d1459e6d106cf6918235b6a7aefa5afaaa85959527d2fce19b9",
                "verify_status":"none",
                "verify_message":"",
                "subscriptions": [
                    {
                        "id": "5132551402",
                        "url": "https://my.levelcredit.com/api/subscriptions/5132551402"
                    }
                ],
                "partner": {
                    "name":"ce8b7",
                    "request_name":"NETSPEND",
                    "logo_name":"netspend.png",
                    "email_logo_name":"netspend.png",
                    "is_powered_by":false,
                    "secondary_logo":false,
                    "support_email":"help@renttrack.com",
                    "support_phone":"866-841-9090",
                    "back_to_link":false,
                    "manages_subscription":false
                },
                "is_report_only":true,
                "require_capture_ssn_dob":false,
                "created_at":"2021-01-12",
                "invite_code":"GML2UZRH4Y",
                "external_resident_ids":[],
                "has_dob_ssn":true,
                "has_ssn":true,
                "has_dob":true
            },
            {
                "id":2987939829,
                "url":"https://my.levelcredit.com/api/partner/users/2987939829",
                "first_name":"Ritta","last_name":"Roseeman",
                "email":"RitaR223343@tradeline.com",
                "phone":"7032551401",
                "email_notification":true,
                "offer_notification":true,
                "culture":"en",
                "hmac":"e059bc1f676100efd75d17f46abd10881ef53dc4840d35444bf9d18d3fb5fe86",
                "verify_status":"none",
                "verify_message":"",
                "subscriptions": [],
                "partner":{
                    "name":"ce8b7",
                    "request_name":"NETSPEND",
                    "logo_name":"netspend.png",
                    "email_logo_name":"netspend.png",
                    "is_powered_by":false,
                    "secondary_logo":false,
                    "support_email":"help@renttrack.com",
                    "support_phone":"866-841-9090",
                    "back_to_link":false,
                    "manages_subscription":false
                },
                "is_report_only":true,
                "require_capture_ssn_dob":false,
                "created_at":"2021-01-12",
                "invite_code":"GML2UY5LA1",
                "external_resident_ids":[],
                "has_dob_ssn":true,
                "has_ssn":true,
                "has_dob":true
            }
        ]';

        $response = new Response(200, ['X-Total-Count' => 30], $json);
        /** @var UserCollectionResponse $responseModel */
        $responseModel =  Serializer::create()->deserializeResponse(
            $response,
            new UserCollectionResponse(),
            User::class
        );

        $this->assertInstanceOf(UserCollectionResponse::class, $responseModel);
        $this->assertEquals(200, $responseModel->getStatusCode());
        $this->assertEmpty($responseModel->getErrors());
        $this->assertEquals(30, $responseModel->getTotalCount());
        $this->assertCount(2, $responseModel->getElements());
        /** @var User $firstUser */
        $firstUser = $responseModel->getElements()->first();

        $this->assertInstanceOf(User::class, $firstUser);
        $this->assertEquals(4086667068, $firstUser->getId());
        $this->assertEquals('https://my.levelcredit.com/api/partner/users/4086667068', $firstUser->getUrl());
        $this->assertEquals('Ritaa', $firstUser->getFirstName());
        $this->assertEquals('Rosemann', $firstUser->getLastName());
        $this->assertEquals('RitaR3322332@tradeline.com', $firstUser->getEmail());
        $this->assertCount(1, $firstUser->getSubscriptions());
        /** @var Subscription $subscription */
        $this->assertInstanceOf(Subscription::class, $subscription = $firstUser->getSubscriptions()->first());
        $this->assertEquals(5132551402, $subscription->getId());
        $this->assertEquals('https://my.levelcredit.com/api/subscriptions/5132551402', $subscription->getUrl());
    }
}
