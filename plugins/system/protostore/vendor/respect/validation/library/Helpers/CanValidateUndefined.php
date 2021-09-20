<?php

/**
 * @package   Pro2Store
 * @author    Ray Lawlor - pro2.store
 * @copyright Copyright (C) 2021 Ray Lawlor - pro2.store
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 *
 */

declare(strict_types=1);

namespace Respect\Validation\Helpers;

use function in_array;

/**
 * Helper to identify values that Validation consider as "undefined".
 *
 * @author Henrique Moody <henriquemoody@gmail.com>
 */
trait CanValidateUndefined
{
    /**
     * Finds whether the value is undefined or not.
     *
     * @param mixed $value
     */
    private function isUndefined($value): bool
    {
        return in_array($value, [null, ''], true);
    }
}
