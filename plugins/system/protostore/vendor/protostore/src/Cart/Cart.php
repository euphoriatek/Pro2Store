<?php

/**
 * @package   Pro2Store
 * @author    Ray Lawlor - pro2.store
 * @copyright Copyright (C) 2021 Ray Lawlor - pro2.store
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 *
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

	public $totalWithTaxInt;
	public $totalWithTax;

	public $totalWithDefaultTaxInt;
	public $totalWithDefaultTax;

	public $subtotal;
	public $subtotalInt;

	public $subtotalWithTaxInt;
	public $subtotalWithTax;

	public $subtotalWithDefaultTaxInt;
	public $subtotalWithDefaultTax;

	public $tax;
	public $taxInt;

	public $default_tax;
	public $default_taxInt;

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
	 * @since 2.0
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
	 * @since 2.0
	 */


	private function init()
	{

		$currency = CurrencyFactory::getCurrent();

		$this->cartItems = CartFactory::getCartItems($this->id);
		$this->count     = CartFactory::getCount($this->cartItems);

		$this->subtotalInt = CartFactory::getSubTotal($this);
		$this->subtotal    = CurrencyFactory::translate($this->subtotalInt, $currency);

		$this->totalInt = CartFactory::getGrandTotal($this);
		$this->total    = CurrencyFactory::translate($this->totalInt, $currency);

		$this->taxInt = TaxFactory::getTotalTax($this);
		$this->tax    = CurrencyFactory::translate($this->taxInt, $currency);

		$this->default_taxInt = TaxFactory::getTotalDefaultTax($this->subtotalInt);
		$this->default_tax    = CurrencyFactory::translate($this->default_taxInt, $currency);

		$this->totalWithTaxInt = $this->totalInt + $this->taxInt;
		$this->totalWithTax = CurrencyFactory::translate($this->totalWithTaxInt, $currency);

		$this->totalWithDefaultTaxInt = $this->totalInt + $this->default_taxInt;
		$this->totalWithDefaultTax = CurrencyFactory::translate($this->totalWithDefaultTaxInt, $currency);

		$this->subtotalWithTaxInt = $this->subtotalInt + $this->taxInt;
		$this->subtotalWithTax = CurrencyFactory::translate($this->subtotalWithTaxInt, $currency);

		$this->subtotalWithDefaultTaxInt = $this->subtotalInt + $this->default_taxInt;
		$this->subtotalWithDefaultTax = CurrencyFactory::translate($this->subtotalWithDefaultTaxInt, $currency);

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
