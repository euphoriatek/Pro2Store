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

use Exception;

use Protostore\Currency\CurrencyFactory;
use Protostore\Product\ProductFactory;


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
	public $manage_stock_enabled;
	public $product;
	public $totalCost;
	public $selected_options;

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

		$this->bought_at_price_formatted = CurrencyFactory::translate($this->bought_at_price);
		$this->product                   = ProductFactory::get($this->joomla_item_id);
		$this->manage_stock_enabled      = $this->product->manage_stock;
		$this->totalCost                 = $this->amount * $this->bought_at_price;
		$this->selected_options          = CartFactory::getSelectedOptions($this->item_options);

	}


}
