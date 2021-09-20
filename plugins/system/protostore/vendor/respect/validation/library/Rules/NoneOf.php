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

use Respect\Validation\Exceptions\NoneOfException;

use function count;

/**
 * @author Alexandre Gomes Gaigalas <alexandre@gaigalas.net>
 * @author Henrique Moody <henriquemoody@gmail.com>
 */
final class NoneOf extends AbstractComposite
{
    /**
     * {@inheritDoc}
     */
    public function assert($input): void
    {
        $exceptions = $this->getAllThrownExceptions($input);
        $numRules = count($this->getRules());
        $numExceptions = count($exceptions);
        if ($numRules !== $numExceptions) {
            /** @var NoneOfException $noneOfException */
            $noneOfException = $this->reportError($input);
            $noneOfException->addChildren($exceptions);

            throw $noneOfException;
        }
    }

    /**
     * {@inheritDoc}
     */
    public function validate($input): bool
    {
        foreach ($this->getRules() as $rule) {
            if ($rule->validate($input)) {
                return false;
            }
        }

        return true;
    }
}
