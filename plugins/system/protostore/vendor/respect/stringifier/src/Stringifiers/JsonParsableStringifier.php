<?php

/**
 * @package   Pro2Store
 * @author    Ray Lawlor - pro2.store
 * @copyright Copyright (C) 2021 Ray Lawlor - pro2.store
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 *
 */

declare(strict_types=1);

namespace Respect\Stringifier\Stringifiers;

use const JSON_UNESCAPED_UNICODE;
use const JSON_UNESCAPED_SLASHES;
use function json_encode;
use Respect\Stringifier\Stringifier;

/**
 * Converts any value into JSON parsable string representation.
 *
 * @author Henrique Moody <henriquemoody@gmail.com>
 */
final class JsonParsableStringifier implements Stringifier
{
    /**
     * {@inheritdoc}
     */
    public function stringify($raw, int $depth): ?string
    {
        $string = json_encode($raw, (JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRESERVE_ZERO_FRACTION));
        if (false === $string) {
            return null;
        }

        return $string;
    }
}
