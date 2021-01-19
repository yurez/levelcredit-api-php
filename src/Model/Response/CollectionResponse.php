<?php

namespace LevelCredit\LevelCreditApi\Model\Response;

use Doctrine\Common\Collections\ArrayCollection;

abstract class CollectionResponse extends BaseResponse
{
    /**
     * @var ArrayCollection
     */
    protected $elements;

    /**
     * @var int
     */
    protected $totalCount;

    public function __construct()
    {
        $this->elements = new ArrayCollection();
        $this->totalCount = 0;

        parent::__construct();
    }

    /**
     * @return ArrayCollection
     */
    public function getElements(): ArrayCollection
    {
        return $this->elements;
    }

    /**
     * @param ArrayCollection $collection
     */
    public function setElements(ArrayCollection $collection): void
    {
        $this->elements = $collection;
    }

    /**
     * @return int
     */
    public function getTotalCount(): int
    {
        return $this->totalCount;
    }

    /**
     * @param int $totalCount
     */
    public function setTotalCount(int $totalCount): void
    {
        $this->totalCount = $totalCount;
    }
}
