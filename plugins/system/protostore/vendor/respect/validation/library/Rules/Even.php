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

use function filter_var;

use const FILTER_VALIDATE_INT;

/**
 * Validates whether the input is an even number or not.
 *
 * @author Henrique Moody <henriquemoody@gmail.com>
 * @author Jean Pimentel <jeanfap@gmail.com>
 * @author Paul Karikari <paulkarikari1@gmail.com>
 */
final class Even extends AbstractRule
{
    /**
     * {@inheritDoc}
     */
    public function validate($input): bool
    {
        if (filter_var($input, FILTER_VALIDATE_INT) === false) {
            return false;
        }

        return (int) $input % 2 === 0;
    }
}
