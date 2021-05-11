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

use Protostore\Currency\CurrencyFactory;
use Protostore\Coupon\CouponFactory;
use Protostore\Cartitem\Cartitem;

use Protostore\Total\Total;
use Protostore\Shipping\Shipping;

use Protostore\Tax\Tax;


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

	public string $total;
	public int $totalInt;
	public string $subtotal;
	public int $subtotalInt;
	public string $tax;
	public int $taxInt;
	public string $totalShipping;
	public string $totalDiscount;
	public int $totalDiscountInt;


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

		$currency = CurrencyFactory::getCurrent();

		$this->cartItems = CartFactory::getCartItems($this->id);
		$this->count     = CartFactory::getCount($this->cartItems);

		$this->subtotalInt = Total::getSubTotal($this);
		$this->subtotal    = CurrencyFactory::translate($this->subtotalInt, $currency);

		$this->totalInt = Total::getGrandTotal($this);
		$this->total    = CurrencyFactory::translate($this->totalInt, $currency);

		$this->taxInt = Tax::calculateTotalTax($this);
		$this->tax    = CurrencyFactory::translate($this->taxInt, $currency);


		$this->totalShipping = Shipping::calculateTotalShippingForCart($this, $currency);


		$couponDiscount = CouponFactory::calculateDiscount($this);

		if ($couponDiscount > $this->subtotalInt)
		{
			$this->totalDiscount    = CurrencyFactory::translate($this->subtotalInt, $currency);
			$this->totalDiscountInt = $this->subtotal;
		}
		else
		{
			$discount               = CouponFactory::calculateDiscount($this);
			$this->totalDiscount    = CurrencyFactory::translate($discount, $currency);
			$this->totalDiscountInt = CouponFactory::calculateDiscount($this);
		}


	}


}
