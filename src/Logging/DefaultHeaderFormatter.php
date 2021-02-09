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

namespace LevelCredit\LevelCreditApi\Logging;

class DefaultHeaderFormatter implements HeaderFormatterInterface
{
    /**
     * @var array
     */
    protected $replacements;

    public function __construct()
    {
        $this->replacements = [];
    }

    /**
     * @param array $replacements
     * @return static
     */
    public static function create(array $replacements = []): self
    {
        return (new static())->setReplacements($replacements);
    }

    /**
     * @param string $pattern
     * @param string $replacement
     * @return static
     */
    public function addReplacement(string $pattern, string $replacement): self
    {
        $this->replacements[$pattern] = $replacement;

        return $this;
    }

    /**
     * key   - regexp pattern
     * value - replacement
     *
     * @param array $replacements
     * @return static
     */
    public function setReplacements(array $replacements): self
    {
        $this->replacements = $replacements;

        return $this;
    }

    /**
     * @param string $name
     * @param array $values
     * @return array
     */
    public function format(string $name, array $values): array
    {
        foreach ($this->replacements as $pattern => $replacement) {
            if (1 === preg_match($pattern, $name)) {
                return [$replacement];
            }
        }

        return $values;
    }
}
