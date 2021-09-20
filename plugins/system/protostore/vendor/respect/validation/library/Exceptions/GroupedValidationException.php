<?php

/**
 * @package   Pro2Store
 * @author    Ray Lawlor - pro2.store
 * @copyright Copyright (C) 2021 Ray Lawlor - pro2.store
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 *
 */

declare(strict_types=1);

namespace Respect\Validation\Exceptions;

use function count;

/**
 * @author Alexandre Gomes Gaigalas <alexandre@gaigalas.net>
 * @author Henrique Moody <henriquemoody@gmail.com>
 */
class GroupedValidationException extends NestedValidationException
{
    public const NONE = 'none';
    public const SOME = 'some';

    /**
     * {@inheritDoc}
     */
    protected $defaultTemplates = [
        self::MODE_DEFAULT => [
            self::NONE => 'All of the required rules must pass for {{name}}',
            self::SOME => 'These rules must pass for {{name}}',
        ],
        self::MODE_NEGATIVE => [
            self::NONE => 'None of there rules must pass for {{name}}',
            self::SOME => 'These rules must not pass for {{name}}',
        ],
    ];

    /**
     * {@inheritDoc}
     */
    protected function chooseTemplate(): string
    {
        $numRules = $this->getParam('passed');
        $numFailed = count($this->getChildren());

        return $numRules === $numFailed ? self::NONE : self::SOME;
    }
}
