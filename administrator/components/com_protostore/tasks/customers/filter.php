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

use Joomla\Input\Input;

use Protostore\Customer\CustomerFactory;


class protostoreTask_filter
{

	public function getResponse(Input $data)
	{

		// init
		$response = array();

		$searchTerm = $data->getString('searchTerm', null);

		if($searchTerm == "null") {
			$searchTerm = null;
		}

		$response['customers'] = CustomerFactory::getList(
			$data->getInt('limit', 0),
			$data->getInt('offset', 0),
			$searchTerm
		);



		return $response;
	}

}
