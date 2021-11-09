<?php
/**
 * @package   Pro2Store
 * @author    Ray Lawlor - pro2.store
 * @copyright Copyright (C) 2021 Ray Lawlor - pro2.store
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 *
 */
// no direct access


defined('_JEXEC') or die('Restricted access');

use Protostore\Checkoutnote\CheckoutnoteFactory;
use Protostore\Checkoutnote\Checkoutnote;

class protostoreTask_save
{

	public function getResponse($data): ?Checkoutnote
	{

		return CheckoutnoteFactory::save($data);


	}

}
