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

use function preg_match;

/**
 * Validates whether the input contains only vowels.
 *
 * @author Henrique Moody <henriquemoody@gmail.com>
 * @author Nick Lombard <github@jigsoft.co.za>
 */
final class Vowel extends AbstractFilterRule
{
    /**
     * {@inheritDoc}
     */
    protected function validateFilteredInput(string $input): bool
    {
        return preg_match('/^[aeiouAEIOU]+$/', $input) > 0;
    }
}
