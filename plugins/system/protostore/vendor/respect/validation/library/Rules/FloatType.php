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

use function is_float;

/**
 * Validates whether the type of the input is float.
 *
 * @author Henrique Moody <henriquemoody@gmail.com>
 * @author Reginaldo Junior <76regi@gmail.com>
 */
final class FloatType extends AbstractRule
{
    /**
     * {@inheritDoc}
     */
    public function validate($input): bool
    {
        return is_float($input);
    }
}
