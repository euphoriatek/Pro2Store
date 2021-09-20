<?php

/**
 * @package   Pro2Store
 * @author    Ray Lawlor - pro2.store
 * @copyright Copyright (C) 2021 Ray Lawlor - pro2.store
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 *
 */

declare(strict_types=1);

namespace Respect\Stringifier;

use Respect\Stringifier\Stringifiers\ClusterStringifier;

function stringify($value): string
{
    static $stringifier;

    if (null === $stringifier) {
        $stringifier = ClusterStringifier::createDefault();
    }

    return $stringifier->stringify($value, 0) ?? '#ERROR#';
}
