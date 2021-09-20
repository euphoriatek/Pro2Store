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

use function ctype_digit;
use function mb_strlen;

/**
 * Validates a Dutch citizen service number (BSN).
 *
 * @see https://nl.wikipedia.org/wiki/Burgerservicenummer
 *
 * @author Henrique Moody <henriquemoody@gmail.com>
 * @author Ronald Drenth <ronalddrenth@gmail.com>
 * @author William Espindola <oi@williamespindola.com.br>
 */
final class Bsn extends AbstractRule
{
    /**
     * {@inheritDoc}
     */
    public function validate($input): bool
    {
        if (!ctype_digit($input)) {
            return false;
        }

        if (mb_strlen($input) !== 9) {
            return false;
        }

        $sum = -1 * $input[8];
        for ($i = 9; $i > 1; --$i) {
            $sum += $i * $input[9 - $i];
        }

        return $sum !== 0 && $sum % 11 === 0;
    }
}
