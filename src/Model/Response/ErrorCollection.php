<?php

namespace LevelCredit\LevelCreditApi\Model\Response;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * @method Error[] toArray()
 */
class ErrorCollection extends ArrayCollection
{
    /**
     * @return string
     */
    public function __toString(): string
    {
        $messages = [];

        foreach ($this->toArray() as $error) {
            $messages[] = $this->normalizeMessage($error->getMessage());
        }

        return $messages ? implode('. ', $messages) . '.' : '';
    }

    /**
     * @param string $message
     * @return string
     */
    protected function normalizeMessage(string $message): string
    {
        return rtrim($message, "\t\n\r\0\x0B.");
    }
}
