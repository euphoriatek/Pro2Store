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

use Psr\Http\Message\UploadedFileInterface;
use SplFileInfo;

use function is_scalar;
use function is_uploaded_file;

/**
 * Validates if the given data is a file that was uploaded via HTTP POST.
 *
 * @author Henrique Moody <henriquemoody@gmail.com>
 * @author Paul Karikari <paulkarikari1@gmail.com>
 */
final class Uploaded extends AbstractRule
{
    /**
     * {@inheritDoc}
     */
    public function validate($input): bool
    {
        if ($input instanceof SplFileInfo) {
            return $this->validate($input->getPathname());
        }

        if ($input instanceof UploadedFileInterface) {
            return true;
        }

        if (!is_scalar($input)) {
            return false;
        }

        return is_uploaded_file((string) $input);
    }
}
