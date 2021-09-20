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

use ArrayAccess;
use SimpleXMLElement;

use function is_array;

/**
 * Validates if the input is an array or if the input can be used as an array.
 *
 * Instance of `ArrayAccess` or `SimpleXMLElement` are also considered as valid.
 *
 * @author Alexandre Gomes Gaigalas <alexandre@gaigalas.net>
 * @author Emmerson Siqueira <emmersonsiqueira@gmail.com>
 * @author Henrique Moody <henriquemoody@gmail.com>
 */
final class ArrayVal extends AbstractRule
{
    /**
     * {@inheritDoc}
     */
    public function validate($input): bool
    {
        return is_array($input) || $input instanceof ArrayAccess || $input instanceof SimpleXMLElement;
    }
}
