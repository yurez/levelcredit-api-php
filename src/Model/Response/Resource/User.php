<?php

namespace LevelCredit\LevelCreditApi\Model\Response\Resource;

use Doctrine\Common\Collections\ArrayCollection;
use JMS\Serializer\Annotation as Serializer;

class User extends BaseResource
{
    /**
     * @var string
     */
    protected $firstName;

    /**
     * @var string
     */
    protected $lastName;

    /**
     * @var string
     */
    protected $email;

    /**
     * @var ArrayCollection|Subscription[]
     *
     * @Serializer\Type("ArrayCollection<LevelCredit\LevelCreditApi\Model\Response\Resource\Subscription>")
     */
    protected $subscriptions;

    /**
     * @return string
     */
    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    /**
     * @return string
     */
    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    /**
     * @return string
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @return ArrayCollection|Subscription[]
     */
    public function getSubscriptions(): ?ArrayCollection
    {
        return $this->subscriptions;
    }
}
