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

use Protostore\Shippingrate\ShippingrateFactory;


class protostoreTask_filter
{

	public function getResponse($data)
	{

		// init
		$response = array();

		$publishedOnly = $data->get('publishedOnly', false) === "true";
		$searchTerm = $data->getString('searchTerm', null);

		if($searchTerm == "null") {
			$searchTerm = null;
		}

		$response['items'] = ShippingrateFactory::getList(
			$data->getInt('limit', 0),
			$data->getInt('offset', 0),
			$publishedOnly,
			$data->getInt('country_id', 0)
		);


		return $response;
	}

}
