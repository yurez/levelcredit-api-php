<?php

namespace LevelCredit\LevelCreditApi\Model\Response\Resource;

use JMS\Serializer\Annotation as Serializer;

class AccessToken
{
    /**
     * @var string
     *
     * @Serializer\Type("string")
     */
    protected $accessToken;

    /**
     * @var int
     *
     * @Serializer\Type("integer")
     */
    protected $expiresIn;

    /**
     * @var string
     *
     * @Serializer\Type("string")
     */
    protected $tokenType;

    /**
     * @var string
     *
     * @Serializer\Type("string")
     */
    protected $scope;

    /**
     * @var string
     *
     * @Serializer\Type("string")
     */
    protected $refreshToken;

    /**
     * @return string
     */
    public function getAccessToken(): string
    {
        return $this->accessToken;
    }

    /**
     * @return int
     */
    public function getExpiresIn(): int
    {
        return $this->expiresIn;
    }

    /**
     * @return string
     */
    public function getTokenType(): ?string
    {
        return $this->tokenType;
    }

    /**
     * @return string
     */
    public function getScope(): ?string
    {
        return $this->scope;
    }

    /**
     * @return string
     */
    public function getRefreshToken(): string
    {
        return $this->refreshToken;
    }
}
