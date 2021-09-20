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

use function is_resource;

/**
 * Validates whether the input is a resource.
 *
 * @author Henrique Moody <henriquemoody@gmail.com>
 */
final class ResourceType extends AbstractRule
{
    /**
     * {@inheritDoc}
     */
    public function validate($input): bool
    {
        return is_resource($input);
    }
}
