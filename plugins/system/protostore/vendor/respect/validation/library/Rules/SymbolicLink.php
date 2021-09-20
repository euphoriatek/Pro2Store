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

use SplFileInfo;

use function is_link;
use function is_string;

/**
 * Validates if the given input is a symbolic link.
 *
 * @author Henrique Moody <henriquemoody@gmail.com>
 * @author Gus Antoniassi <gus.antoniassi@gmail.com>
 */
final class SymbolicLink extends AbstractRule
{
    /**
     * {@inheritDoc}
     */
    public function validate($input): bool
    {
        if ($input instanceof SplFileInfo) {
            return $input->isLink();
        }

        return is_string($input) && is_link($input);
    }
}
