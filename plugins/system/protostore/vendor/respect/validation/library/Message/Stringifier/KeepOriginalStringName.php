<?php

/**
 * @package   Pro2Store
 * @author    Ray Lawlor - pro2.store
 * @copyright Copyright (C) 2021 Ray Lawlor - pro2.store
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 *
 */

declare(strict_types=1);

namespace Respect\Validation\Message\Stringifier;

use Respect\Validation\Message\ParameterStringifier;

use function is_string;
use function Respect\Stringifier\stringify;

final class KeepOriginalStringName implements ParameterStringifier
{
    /**
     * {@inheritDoc}
     */
    public function stringify(string $name, $value): string
    {
        if ($name === 'name' && is_string($value)) {
            return $value;
        }

        return stringify($value);
    }
}
