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

use function is_string;
use function mb_strstr;
use function preg_match;

/**
 * Validates whether the input is a valid slug.
 *
 * @author Carlos André Ferrari <caferrari@gmail.com>
 * @author Danilo Correa <danilosilva87@gmail.com>
 * @author Henrique Moody <henriquemoody@gmail.com>
 * @author Nick Lombard <github@jigsoft.co.za>
 */
final class Slug extends AbstractRule
{
    /**
     * {@inheritDoc}
     */
    public function validate($input): bool
    {
        if (!is_string($input) || mb_strstr($input, '--')) {
            return false;
        }

        if (!preg_match('@^[0-9a-z\-]+$@', $input)) {
            return false;
        }

        return preg_match('@^-|-$@', $input) === 0;
    }
}
