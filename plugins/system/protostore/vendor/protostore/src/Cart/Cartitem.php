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
use Protostore\Currency\CurrencyFactory;
use Protostore\Product\ProductFactory;
use Protostore\Product\Product;
use Protostore\Productoption\Productoption;


use stdClass;

class CartItem
{

	public int $id;
	public int $cart_id;
	public int $joomla_item_id;
	public string $item_options;
	public int $bought_at_price;
	public string $added;
	public ?int $order_id;
	public ?string $cookie_id;
	public ?int $user_id;
	public int $amount;

	public ?string $bought_at_price_formatted;
	public int $manage_stock_enabled;
	public Product $product;
	public int $totalCost;
	public array $selected_options;

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

		$this->bought_at_price_formatted = CurrencyFactory::translate($this->bought_at_price);
		$this->product                   = ProductFactory::get($this->joomla_item_id);
		$this->manage_stock_enabled      = $this->product->manage_stock;
		$this->totalCost                 = $this->amount * $this->bought_at_price;
		$this->selected_options          = CartFactory::getSelectedOptions($this->item_options);

	}




}
