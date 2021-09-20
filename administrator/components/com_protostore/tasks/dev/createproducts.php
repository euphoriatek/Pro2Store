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

use Protostore\Cart\CartFactory;

class protostoreTask_createproducts
{

	public function getResponse($data)
	{

		// init
		$response   = array();
		$cartItemId = $data->get('cartitemid');

		$response['status'] = CartFactory::removeAll($cartItemId);
		return $response;
	}

}
