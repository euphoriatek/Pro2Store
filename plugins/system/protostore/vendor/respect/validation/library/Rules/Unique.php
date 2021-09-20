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

use function array_unique;
use function is_array;

use const SORT_REGULAR;

/**
 * Validates whether the input array contains only unique values.
 *
 * @author Henrique Moody <henriquemoody@gmail.com>
 * @author Krzysztof Śmiałek <admin@avensome.net>
 * @author Paul Karikari <paulkarikari1@gmail.com>
 */
final class Unique extends AbstractRule
{
    /**
     * {@inheritDoc}
     */
    public function validate($input): bool
    {
        if (!is_array($input)) {
            return false;
        }

        return $input == array_unique($input, SORT_REGULAR);
    }
}
