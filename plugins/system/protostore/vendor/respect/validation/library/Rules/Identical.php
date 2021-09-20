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

/**
 * Validates if the input is identical to some value.
 *
 * @author Henrique Moody <henriquemoody@gmail.com>
 */
final class Identical extends AbstractRule
{
    /**
     * @var mixed
     */
    private $compareTo;

    /**
     * Initializes the rule.
     *
     * @param mixed $compareTo
     */
    public function __construct($compareTo)
    {
        $this->compareTo = $compareTo;
    }

    /**
     * {@inheritDoc}
     */
    public function validate($input): bool
    {
        return $input === $this->compareTo;
    }
}
