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

use Respect\Validation\Exceptions\ComponentException;

use const FILTER_VALIDATE_URL;

/**
 * Validates whether the input is a URL.
 *
 * @author Henrique Moody <henriquemoody@gmail.com>
 */
final class Url extends AbstractEnvelope
{
    /**
     * Initializes the rule.
     *
     * @throws ComponentException
     */
    public function __construct()
    {
        parent::__construct(new FilterVar(FILTER_VALIDATE_URL));
    }
}
