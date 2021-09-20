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

use function end;
use function is_array;
use function mb_detect_encoding;
use function mb_strlen;
use function mb_strripos;
use function mb_strrpos;

/**
 * Validates only if the value is at the end of the input.
 *
 * @author Alexandre Gomes Gaigalas <alexandre@gaigalas.net>
 * @author Henrique Moody <henriquemoody@gmail.com>
 * @author Hugo Hamon <hugo.hamon@sensiolabs.com>
 * @author William Espindola <oi@williamespindola.com.br>
 */
final class EndsWith extends AbstractRule
{
    /**
     * @var mixed
     */
    private $endValue;

    /**
     * @var bool
     */
    private $identical;

    /**
     * @param mixed $endValue
     */
    public function __construct($endValue, bool $identical = false)
    {
        $this->endValue = $endValue;
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
    private function validateEquals($input): bool
    {
        if (is_array($input)) {
            return end($input) == $this->endValue;
        }

        $encoding = (string) mb_detect_encoding($input);
        $endPosition = mb_strlen($input, $encoding) - mb_strlen($this->endValue, $encoding);

        return mb_strripos($input, $this->endValue, 0, $encoding) === $endPosition;
    }

    /**
     * @param mixed $input
     */
    private function validateIdentical($input): bool
    {
        if (is_array($input)) {
            return end($input) === $this->endValue;
        }

        $encoding = (string) mb_detect_encoding($input);
        $endPosition = mb_strlen($input, $encoding) - mb_strlen($this->endValue, $encoding);

        return mb_strrpos($input, $this->endValue, 0, $encoding) === $endPosition;
    }
}
