<?php
/**
 * @package     Pro2Store
 * @subpackage  com_protostore
 *
 * @copyright   Copyright (C) 2021 Ray Lawlor - Pro2Store - https://pro2.store. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
// no direct access


defined('_JEXEC') or die('Restricted access');

use Protostore\Customer\CustomerFactory;
use Joomla\Input\Input;

class protostoreTask_save
{

	/**
	 * @param   Input  $data
	 *
	 * @return bool
	 *
	 * @throws Exception
	 * @since 1.6
	 */
	public function getResponse(Input $data)
	{


		return CustomerFactory::saveFromInputData($data);


	}

}
