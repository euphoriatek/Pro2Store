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


use Protostore\Coupon\CouponFactory;
use Protostore\Currency\CurrencyFactory;
use Protostore\Shipping\ShippingFactory;
use Protostore\Tax\TaxFactory;

use Exception;


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

	public $total;
	public $totalInt;
	public $subtotal;
	public $subtotalInt;
	public $tax;
	public $taxInt;
	public $totalShipping;
	public $totalShippingFormatted;
	public $totalDiscount;
	public $totalDiscountInt;


	public function __construct($data)
	{

		if ($data)
		{
			$this->hydrate($data);
			$this->init();
		}

	}

	/**
	 * @param $data
	 *
	 *
	 * @since 1.6
	 */

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

	/**
	 *
	 *
	 * @throws Exception
	 * @since 1.6
	 */


	private function init()
	{


		$this->cartItems = CartFactory::getCartItems($this->id);
		$this->count     = CartFactory::getCount($this->cartItems);

		$this->subtotalInt = CartFactory::getSubTotal($this);
		$this->subtotal    = CurrencyFactory::translate($this->subtotalInt);

		$this->totalInt = CartFactory::getGrandTotal($this);
		$this->total    = CurrencyFactory::translate($this->totalInt);

		$this->taxInt = TaxFactory::getTotalTax($this);
		$this->tax    = CurrencyFactory::translate($this->taxInt);


		$this->totalShipping = ShippingFactory::getShipping($this);
		$this->totalShippingFormatted = ShippingFactory::getShippingFormatted($this);


		$couponDiscount = CouponFactory::calculateDiscount($this);

		if ($couponDiscount > $this->subtotalInt)
		{
			$this->totalDiscount    = CurrencyFactory::translate($this->subtotalInt);
			$this->totalDiscountInt = $this->subtotal;
		}
		else
		{
			$discount               = CouponFactory::calculateDiscount($this);
			$this->totalDiscount    = CurrencyFactory::translate($discount);
			$this->totalDiscountInt = CouponFactory::calculateDiscount($this);
		}


	}


}
