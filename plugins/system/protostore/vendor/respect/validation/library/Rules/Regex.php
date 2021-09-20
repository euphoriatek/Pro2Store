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

use function is_scalar;
use function preg_match;

/**
 * Validates whether the input matches a defined regular expression.
 *
 * @author Alexandre Gomes Gaigalas <alexandre@gaigalas.net>
 * @author Danilo Correa <danilosilva87@gmail.com>
 * @author Henrique Moody <henriquemoody@gmail.com>
 */
final class Regex extends AbstractRule
{
    /**
     * @var string
     */
    private $regex;

    /**
     * Initializes the rule.
     */
    public function __construct(string $regex)
    {
        $this->regex = $regex;
    }

    /**
     * {@inheritDoc}
     */
    public function validate($input): bool
    {
        if (!is_scalar($input)) {
            return false;
        }

        return preg_match($this->regex, (string) $input) > 0;
    }
}
