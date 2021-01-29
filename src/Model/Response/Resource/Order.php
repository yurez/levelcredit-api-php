<?php

namespace LevelCredit\LevelCreditApi\Model\Response\Resource;

use JMS\Serializer\Annotation as Serializer;
use LevelCredit\LevelCreditApi\Enum\OrderDeliveryMethod;
use LevelCredit\LevelCreditApi\Enum\OrderPaymentType;
use LevelCredit\LevelCreditApi\Enum\OrderStatus;
use LevelCredit\LevelCreditApi\Model\Response\SubModel\OrderError;

class Order extends BaseResource
{
    /**
     * @var string
     * @see OrderStatus
     *
     * @Serializer\Type("string")
     */
    protected $status;

    /**
     * @var string
     *
     * @Serializer\Type("string")
     */
    protected $referenceId;

    /**
     * @var string
     *
     * @Serializer\Type("string")
     */
    protected $message;

    /**
     * @var OrderError
     *
     * @Serializer\Type("LevelCredit\LevelCreditApi\Model\Response\SubModel\OrderError")
     */
    protected $error;

    /**
     * @var string
     * @see OrderPaymentType
     *
     * @Serializer\Type("string")
     */
    protected $type;

    /**
     * @var float
     *
     * @Serializer\Type("float")
     */
    protected $total;

    /**
     * @var float
     *
     * @Serializer\Type("float")
     */
    protected $fee;

    /**
     * @var \DateTime
     *
     * @Serializer\Type("DateTime<'U'>")
     */
    protected $createdAt;

    /**
     * @var string
     * @see OrderDeliveryMethod
     *
     * @Serializer\Type("string")
     */
    protected $deliveryMethod;

    /**
     * @return string
     */
    public function getStatus(): ?string
    {
        return $this->status;
    }

    /**
     * @return string
     */
    public function getReferenceId(): ?string
    {
        return $this->referenceId;
    }

    /**
     * @return string
     */
    public function getMessage(): ?string
    {
        return $this->message;
    }

    /**
     * @return OrderError|null
     */
    public function getError(): ?OrderError
    {
        return $this->error;
    }

    /**
     * @return string
     * @see OrderPaymentType
     */
    public function getType(): ?string
    {
        return $this->type;
    }

    /**
     * @return float
     */
    public function getTotal(): ?float
    {
        return $this->total;
    }

    /**
     * @return float
     */
    public function getFee(): ?float
    {
        return $this->fee;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt(): ?\DateTime
    {
        return $this->createdAt;
    }

    /**
     * @return string|null
     * @see OrderDeliveryMethod
     */
    public function getDeliveryMethod(): ?string
    {
        return $this->deliveryMethod;
    }
}
