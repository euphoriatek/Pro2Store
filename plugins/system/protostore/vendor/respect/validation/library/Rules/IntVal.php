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

use function ctype_digit;
use function is_int;

/**
 * Validates if the input is an integer.
 *
 * @author Adam Benson <adam.benson@bigcommerce.com>
 * @author Alexandre Gomes Gaigalas <alexandre@gaigalas.net>
 * @author Andrei Drulchenko <andrdru@gmail.com>
 * @author Danilo Benevides <danilobenevides01@gmail.com>
 * @author Henrique Moody <henriquemoody@gmail.com>
 */
final class IntVal extends AbstractRule
{
    /**
     * {@inheritDoc}
     */
    public function validate($input): bool
    {
        if (is_int($input)) {
            return true;
        }

        return ctype_digit($input);
    }
}
