<?php

/**
 * @package   Pro2Store
 * @author    Ray Lawlor - pro2.store
 * @copyright Copyright (C) 2021 Ray Lawlor - pro2.store
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 *
 */

declare(strict_types=1);

namespace Respect\Validation\Rules;

use function is_array;
use function mb_detect_encoding;
use function mb_stripos;
use function mb_strpos;
use function reset;

/**
 * Validates whether the input starts with a given value.
 *
 * @author Alexandre Gomes Gaigalas <alexandre@gaigalas.net>
 * @author Henrique Moody <henriquemoody@gmail.com>
 * @author Marcelo Araujo <msaraujo@php.net>
 */
final class StartsWith extends AbstractRule
{
    /**
     * @var mixed
     */
    private $startValue;

    /**
     * @var bool
     */
    private $identical;

    /**
     * @param mixed $startValue
     */
    public function __construct($startValue, bool $identical = false)
    {
        $this->startValue = $startValue;
        $this->identical = $identical;
    }

    /**
     * {@inheritDoc}
     */
    public function validate($input): bool
    {
        if ($this->identical) {
            return $this->validateIdentical($input);
        }

        return $this->validateEquals($input);
    }

    /**
     * @param mixed $input
     */
    protected function validateEquals($input): bool
    {
        if (is_array($input)) {
            return reset($input) == $this->startValue;
        }

        return mb_stripos($input, $this->startValue, 0, (string) mb_detect_encoding($input)) === 0;
    }

    /**
     * @param mixed $input
     */
    protected function validateIdentical($input): bool
    {
        if (is_array($input)) {
            return reset($input) === $this->startValue;
        }

        return mb_strpos($input, $this->startValue, 0, (string) mb_detect_encoding($input)) === 0;
    }
}
