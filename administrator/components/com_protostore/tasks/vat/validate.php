<?php
/**
 * @package   Pro2Store
 * @author    Ray Lawlor - pro2.store
 * @copyright Copyright (C) 2021 Ray Lawlor - pro2.store
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 *
 */
// no direct access

use Joomla\Input\Input;
use Protostore\Vat\VatFactory;

defined('_JEXEC') or die('Restricted access');



class protostoreTask_validate
{

	public function getResponse(Input $data)
	{

		return VatFactory::validate($data);

	}

}
