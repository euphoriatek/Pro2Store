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

use DateTimeInterface;

use function date;
use function is_numeric;
use function is_scalar;
use function sprintf;
use function strtotime;

/**
 * Validates if a year is leap.
 *
 * @author Danilo Correa <danilosilva87@gmail.com>
 * @author Henrique Moody <henriquemoody@gmail.com>
 * @author Jayson Reis <santosdosreis@gmail.com>
 */
final class LeapYear extends AbstractRule
{
    /**
     * {@inheritDoc}
     */
    public function validate($input): bool
    {
        if (is_numeric($input)) {
            $date = strtotime(sprintf('%d-02-29', (int) $input));

            return (bool) date('L', (int) $date);
        }

        if (is_scalar($input)) {
            return $this->validate((int) date('Y', (int) strtotime((string) $input)));
        }

        if ($input instanceof DateTimeInterface) {
            return $this->validate($input->format('Y'));
        }

        return false;
    }
}
