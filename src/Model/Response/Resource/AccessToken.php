<?php

namespace LevelCredit\LevelCreditApi\Model\Response\Resource;

class AccessToken
{
    /**
     * @var string
     */
    protected $accessToken;

    /**
     * @var int
     */
    protected $expiresIn;

    /**
     * @var string
     */
    protected $tokenType;

    /**
     * @var string
     */
    protected $scope;

    /**
     * @var string
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
