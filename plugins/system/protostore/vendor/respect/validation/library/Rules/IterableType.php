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

use Respect\Validation\Helpers\CanValidateIterable;

/**
 * Validates whether the pseudo-type of the input is iterable or not.
 *
 * @author Henrique Moody <henriquemoody@gmail.com>
 */
final class IterableType extends AbstractRule
{
    use CanValidateIterable;

    /**
     * {@inheritDoc}
     */
    public function validate($input): bool
    {
        return $this->isIterable($input);
    }
}
