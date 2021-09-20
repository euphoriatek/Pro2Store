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

use Joomla\Input\Input;

use Protostore\Order\OrderFactory;


class protostoreTask_filter
{

	public function getResponse(Input $data)
	{


		// init
		$response = array();

		// pull out the values since VUE has them untyped.
		$searchTerm = $data->getString('searchTerm');

		if ($searchTerm == "null")
		{
			$searchTerm = null;
		}

		$dateFrom = $data->getString('dateFrom');

		if ($dateFrom == "null")
		{
			$dateFrom = null;
		}


		$dateTo = $data->getString('dateTo', null);

		if ($dateTo == "null")
		{
			$dateTo = null;
		}

		$status = $data->getString('status', null);

		if ($status == "null" || $status == "0")
		{
			$status = null;
		}


		$response['items'] = OrderFactory::getList(
			$data->getInt('limit', 0),
			$data->getInt('offset', 0),
			$searchTerm,
			null,
			$status,
			null,
			$dateFrom,
			$dateTo
		);


		return $response;
	}

}
