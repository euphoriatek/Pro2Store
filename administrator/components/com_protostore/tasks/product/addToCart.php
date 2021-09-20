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

class protostoreTask_addtocart
{

	public function getResponse($data)
	{

		// init
		$amount      = $data->json->get('amount', null, 'INT');
		$j_item_id   = $data->json->get('j_item_id', null, 'INT');
		$variant_ids = $data->json->get('variant', null, 'ARRAY');
		$options     = $data->json->get('options', null, 'ARRAY');


		return CartFactory::addToCart($j_item_id, $amount, $options, $variant_ids);
	}

}
