<?php

namespace LevelCredit\LevelCreditApi\Model\Request;

trait PaginationFilterTrait
{
    /**
     * @var int
     */
    protected $count;

    /**
     * @var int
     */
    protected $offset;

    /**
     * @param int $count
     * @return static
     */
    public function setCount(int $count): self
    {
        $this->count = $count;

        return $this;
    }

    /**
     * @param int $offset
     * @return static
     */
    public function setOffset(int $offset): self
    {
        $this->offset = $offset;

        return $this;
    }
}
