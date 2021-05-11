<?php
/**
 * @package   Pro2Store - Helper
 * @author    Ray Lawlor - pro2.store
 * @copyright Copyright (C) 2020 Ray Lawlor - pro2.store
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */


namespace Protostore\Cart;

// no direct access
defined('_JEXEC') or die('Restricted access');

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Plugin\PluginHelper;
use Protostore\Cartitem\Cartitem;
use Protostore\Currency\Currency;
use Protostore\Product\Product;
use Protostore\Total\Total;
use Protostore\Shipping\Shipping;
use Protostore\Coupon\Coupon;
use Protostore\Utilities\Utilities;
use Protostore\Orderlog\Orderlog;
use Protostore\Checkoutnote\Checkoutnote;
use Protostore\Tax\Tax;

use stdClass;

class Cart
{


	public $id;
	public $user_id;
	public $cookie_id;
	public $coupon_id;
	public $shipping_address_id;
	public $billing_address_id;
	public $shipping_type;
	public $count;
	public $cartItems;


	public function __construct($data)
	{

		if ($data)
		{
			$this->hydrate($data);
			$this->init($data);
		}

	}

	private function hydrate($data)
	{
		foreach ($data as $key => $value)
		{

			if (property_exists($this, $key))
			{
				$this->{$key} = $value;
			}

		}
	}

	private function init($data)
	{

		$this->cartItems = CartFactory::getCartItems($this->id);
		$this->count = CartFactory::getCount($this->cartItems);

	}


	/**
	 *
	 *
	 * @since 1.5
	 */
	public function save()
	{

		$object = new stdClass();
		$object->id = $this->id;
		$object->user_id = $this->user_id;
		$object->cookie_id = $this->cookie_id;
		$object->coupon_id = $this->coupon_id;
		$object->billing_address_id = $this->billing_address_id;
		$object->shipping_address_id = $this->shipping_address_id;

		$this->db->updateObject('#__protostore_cart', $object, 'id');
	}

}
