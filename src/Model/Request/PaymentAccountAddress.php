<?php

namespace LevelCredit\LevelCreditApi\Model\Request;

class PaymentAccountAddress extends BaseRequest
{
    /**
     * @var string
     */
    protected $street;

    /**
     * @var string
     */
    protected $city;

    /**
     * @var string
     */
    protected $state;

    /**
     * @var string
     */
    protected $zip;

    /**
     * @param string $street
     * @return static
     */
    public function setStreet(string $street): self
    {
        $this->street = $street;

        return $this;
    }

    /**
     * @param string $city
     * @return static
     */
    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }

    /**
     * @param string $state
     * @return static
     */
    public function setState(string $state): self
    {
        $this->state = $state;

        return $this;
    }

    /**
     * @param string $zip
     * @return static
     */
    public function setZip(string $zip): self
    {
        $this->zip = $zip;

        return $this;
    }
}
