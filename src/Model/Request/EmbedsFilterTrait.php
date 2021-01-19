<?php

namespace LevelCredit\LevelCreditApi\Model\Request;

trait EmbedsFilterTrait
{
    /**
     * @var array
     */
    protected $embeds;

    /**
     * @param string $embedded
     * @return static
     */
    public function addEmbedded(string $embedded): self
    {
        $this->embeds[] = $embedded;

        return $this;
    }
}
