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

use Respect\Validation\Helpers\CanValidateUndefined;

/**
 * Validates if the given input is not optional.
 *
 * By optional we consider null or an empty string ('').
 *
 * @author Danilo Correa <danilosilva87@gmail.com>
 * @author Henrique Moody <henriquemoody@gmail.com>
 */
final class NotOptional extends AbstractRule
{
    use CanValidateUndefined;

    /**
     * {@inheritDoc}
     */
    public function validate($input): bool
    {
        return $this->isUndefined($input) === false;
    }
}
