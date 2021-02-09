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

use function json_decode;
use function json_last_error;
use function json_encode;

class JsonBodyFormatter implements BodyFormatterInterface
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
     * @param string $body
     * @return string
     */
    public function format(string $body): string
    {
        if (false == $body) {
            return $body;
        }

        $data = json_decode($body, true);

        if (json_last_error() !== JSON_ERROR_NONE || false == is_array($data)) {
            return $body;
        }

        $this->doFormat($data);

        return json_encode($data);
    }

    /**
     * @param array $data
     */
    protected function doFormat(array &$data): void
    {
        foreach ($data as $key => &$value) {
            if (is_array($value)) {
                $this->doFormat($value);
            } else {
                foreach ($this->replacements as $pattern => $replacement) {
                    if (1 === preg_match($pattern, $key)) {
                        $value = $replacement;
                        break(1);
                    }
                }
            }
        }
    }
}
