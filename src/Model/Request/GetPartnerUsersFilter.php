<?php

namespace LevelCredit\LevelCreditApi\Model\Request;

class GetPartnerUsersFilter
{
    /**
     * @var string
     */
    protected $residentId;

    /**
     * @var string
     */
    protected $email;

    public function getResidentId(): string
    {
        return $this->residentId;
    }

    public function setResidentId(string $residentId): self
    {
        $this->residentId = $residentId;

        return $this;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }
}
