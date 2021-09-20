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
use Protostore\Cart\Cart;

class protostoreTask_update
{

	/**
	 * @param $data
	 *
	 * @return Cart
	 *
	 * @since 1.5
	 */
	public function getResponse($data)
	{

		return CartFactory::get();
	}

}
