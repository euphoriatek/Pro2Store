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

use Respect\Validation\Helpers\CanValidateUndefined;

use function in_array;

/**
 * Abstract class for searches into arrays.
 *
 * @author Henrique Moody <henriquemoody@gmail.com>
 */
abstract class AbstractSearcher extends AbstractRule
{
    use CanValidateUndefined;

    /**
     * @return mixed[]
     */
    abstract protected function getDataSource(): array;

    /**
     * {@inheritDoc}
     */
    public function validate($input): bool
    {
        $dataSource = $this->getDataSource();
        if ($this->isUndefined($input) && empty($dataSource)) {
            return true;
        }

        return in_array($input, $dataSource, true);
    }
}
