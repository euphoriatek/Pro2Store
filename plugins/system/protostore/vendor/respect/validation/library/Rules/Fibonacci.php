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

use function is_numeric;

/**
 * Validates whether the input follows the Fibonacci integer sequence.
 *
 * @author Danilo Correa <danilosilva87@gmail.com>
 * @author Henrique Moody <henriquemoody@gmail.com>
 * @author Samuel Heinzmann <samuel.heinzmann@swisscom.com>
 */
final class Fibonacci extends AbstractRule
{
    /**
     * {@inheritDoc}
     */
    public function validate($input): bool
    {
        if (!is_numeric($input)) {
            return false;
        }

        $sequence = [0, 1];
        $position = 1;
        while ($input > $sequence[$position]) {
            ++$position;
            $sequence[$position] = $sequence[$position - 1] + $sequence[$position - 2];
        }

        return $sequence[$position] === (int) $input;
    }
}
