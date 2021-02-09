<?php

/* Copyright(c) 2021 by RentTrack, Inc.  All rights reserved.
 *
 * This software contains proprietary and confidential information of
 * RentTrack Inc., and its suppliers.  Except as may be set forth
 * in the license agreement under which this software is supplied, use,
 * disclosure, or  reproduction is prohibited without the prior express
 * written consent of RentTrack, Inc.
 *
 * The license terms of service are hosted at https://github.com/levelcredit/levelcredit-api-php/blob/master/LICENSE
 */

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
