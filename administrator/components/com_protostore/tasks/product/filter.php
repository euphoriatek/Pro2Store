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

use Protostore\Product\ProductFactory;


class protostoreTask_filter
{

	public function getResponse($data)
	{

		// init
		$response = array();

		$products = ProductFactory::getList(
			$data->getInt('limit', 0),
			$data->getInt('offset', 0),
			$data->getInt('category', null),
			$data->getString('searchTerm', null)
		);

		$response['items'] = $products;

		return $response;
	}

}
