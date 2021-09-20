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

use Protostore\Discount\DiscountFactory;


class protostoreTask_filter
{

	public function getResponse($data)
	{

		// init
		$response = array();

		$searchTerm = $data->getString('searchTerm', null);

		if($searchTerm == "null") {
			$searchTerm = null;
		}

		$discounts = DiscountFactory::getList(
			$data->getInt('limit', 0),
			$data->getInt('offset', 0),
			$searchTerm
		);

		$response['items'] = $discounts;

		return $response;
	}

}
