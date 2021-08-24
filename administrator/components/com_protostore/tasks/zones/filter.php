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

use Protostore\Country\CountryFactory;
use Joomla\Input\Input;

class protostoreTask_filter
{

	public function getResponse(Input $data)
	{

		// init
		$response = array();


		$publishedOnly = !($data->getString('publishedOnly', false) == "false");

		$response['zones'] = CountryFactory::getZoneList(
			$data->getInt('limit', 0),
			$data->getInt('offset', 0),
			$publishedOnly,
			$data->getString('searchTerm', null),
			$data->getInt('country_id', null)
		);



		return $response;
	}

}
