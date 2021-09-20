<?php

/**
 * @package   Pro2Store
 * @author    Ray Lawlor - pro2.store
 * @copyright Copyright (C) 2021 Ray Lawlor - pro2.store
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 *
 */

declare(strict_types=1);

namespace Brick\Math\Exception;

/**
 * Base class for all math exceptions.
 *
 * This class is abstract to ensure that only fine-grained exceptions are thrown throughout the code.
 */
class MathException extends \RuntimeException
{
}
