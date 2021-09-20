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

use Respect\Validation\Rules\Sorted;

/**
 * @author Henrique Moody <henriquemoody@gmail.com>
 * @author Mikhail Vyrtsev <reeywhaar@gmail.com>
 */
final class SortedException extends ValidationException
{
    public const ASCENDING = 'ascending';
    public const DESCENDING = 'descending';

    /**
     * {@inheritDoc}
     */
    protected $defaultTemplates = [
        self::MODE_DEFAULT => [
            self::ASCENDING => '{{name}} must be sorted in ascending order',
            self::DESCENDING => '{{name}} must be sorted in descending order',
        ],
        self::MODE_NEGATIVE => [
            self::ASCENDING => '{{name}} must not be sorted in ascending order',
            self::DESCENDING => '{{name}} must not be sorted in descending order',
        ],
    ];

    /**
     * {@inheritDoc}
     */
    protected function chooseTemplate(): string
    {
        return $this->getParam('direction') === Sorted::ASCENDING ? self::ASCENDING : self::DESCENDING;
    }
}
