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

use Exception;

use Protostore\Currency\CurrencyFactory;
use Protostore\Product\ProductFactory;
use Protostore\Tax\TaxFactory;


class CartItem
{

	public $id;
	public $cart_id;
	public $joomla_item_id;
	public $item_options;
	public $bought_at_price;
	public $added;
	public $order_id;
	public $cookie_id;
	public $user_id;
	public $amount;

	public $bought_at_price_formatted;
	public $total_bought_at_price_formatted;
	public $total_bought_at_price_with_tax_formatted;
	public $manage_stock_enabled;
	public $product;
	public $totalCost;
	public $selected_options;
	public $variant_id;
	public $selected_variant;

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

		$this->bought_at_price_formatted       = CurrencyFactory::translate($this->bought_at_price);
		$this->total_bought_at_price_formatted = CurrencyFactory::translate(($this->bought_at_price * $this->amount));

		$this->total_bought_at_price_with_tax           = (($this->bought_at_price * $this->amount) + TaxFactory::getTotalDefaultTax(($this->bought_at_price * $this->amount)));
		$this->total_bought_at_price_with_tax_formatted = CurrencyFactory::translate($this->total_bought_at_price_with_tax);
		$this->product                                  = ProductFactory::get($this->joomla_item_id);
		$this->manage_stock_enabled                     = $this->product->manage_stock;
		$this->totalCost                                = $this->amount * $this->bought_at_price;
		$this->selected_options                         = CartFactory::getSelectedOptions($this->item_options);
		$this->selected_variant                         = CartFactory::getSelectedVariant($this->variant_id);

	}


}
