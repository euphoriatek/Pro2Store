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
use function trim;

/**
 * Validates whether the input is not empty
 *
 * @author Alexandre Gomes Gaigalas <alexandre@gaigalas.net>
 * @author Bram Van der Sype <bram.vandersype@gmail.com>
 * @author Henrique Moody <henriquemoody@gmail.com>
 */
final class NotEmpty extends AbstractRule
{
    /**
     * {@inheritDoc}
     */
    public function validate($input): bool
    {
        if (is_string($input)) {
            $input = trim($input);
        }

        return !empty($input);
    }
}
