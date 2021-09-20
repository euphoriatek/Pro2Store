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

use stdClass;

use function array_filter;
use function is_array;
use function is_numeric;
use function is_string;
use function trim;

/**
 * Validates if the given input is not a blank value (null, zeros, empty strings or empty arrays, recursively).
 *
 * @author Danilo Correa <danilosilva87@gmail.com>
 * @author Henrique Moody <henriquemoody@gmail.com>
 */
final class NotBlank extends AbstractRule
{
    /**
     * {@inheritDoc}
     */
    public function validate($input): bool
    {
        if (is_numeric($input)) {
            return $input != 0;
        }

        if (is_string($input)) {
            $input = trim($input);
        }

        if ($input instanceof stdClass) {
            $input = (array) $input;
        }

        if (is_array($input)) {
            $input = array_filter($input, __METHOD__);
        }

        return !empty($input);
    }
}
