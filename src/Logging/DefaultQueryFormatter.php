<?php

namespace LevelCredit\LevelCreditApi\Logging;

class DefaultQueryFormatter implements QueryFormatterInterface
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
     * @param string $queryString
     * @return string
     */
    public function format(string $queryString): string
    {
        parse_str($queryString, $query);

        $this->doFormat($query);

        // remove array index inside parentheses from query string
        return preg_replace('/%5B[0-9]+%5D/simU', '%5B%5D', http_build_query($query));
    }

    /**
     * @param array $query
     */
    protected function doFormat(array &$query): void
    {
        foreach ($query as $key => &$value) {
            foreach ($this->replacements as $pattern => $replacement) {
                if (1 === preg_match($pattern, $key)) {
                    if (is_array($value) && $this->isAssocArray($value)) {
                        $this->doFormat($value);
                    } elseif (is_array($value)) {
                        foreach ($value as &$item) {
                            $item = $replacement;
                        }
                    } else {
                        $value = $replacement;
                    }
                    break(1);
                }
            }
            if (is_array($value) && $this->isAssocArray($value)) {
                $this->doFormat($value);
            }
        }
    }

    /**
     * @param array $array
     * @return bool
     */
    protected function isAssocArray(array $array): bool
    {
        if (empty($array)) {
            return false;
        }

        return array_keys($array) !== range(0, count($array) - 1);
    }
}
