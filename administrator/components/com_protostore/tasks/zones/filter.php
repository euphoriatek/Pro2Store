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
