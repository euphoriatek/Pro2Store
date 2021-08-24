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

use Protostore\Currency\CurrencyFactory;


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

		$currencies = CurrencyFactory::getList(
			$data->getInt('limit', 0),
			$data->getInt('offset', 0),
			$publishedOnly,
			$searchTerm
		);

		$response['items'] = $currencies;

		return $response;
	}

}
