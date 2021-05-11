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


//
//    private function getProductOptions()
//    {
//
//        $selectedOptions = array();
//
//
//        foreach ($this->item_options as $option) {
//
//
//            foreach ($option as $selectedOption) {
//                $selectedOptions[] = new Productoption($selectedOption->optionid);
//            }
//        }
//
//
//        return $selectedOptions;
//
//    }
//
//
//    private function getProductById($joomla_item_id)
//    {
//
//        $query = $this->db->getQuery(true);
//
//        $query->select('*');
//        $query->from($this->db->quoteName('#__protostore_product'));
//        $query->where($this->db->quoteName('joomla_item_id') . ' = ' . $this->db->quote($joomla_item_id));
//
//        $this->db->setQuery($query);
//
//        return $this->db->loadObject();
//
//    }
//
//

//
//    private function isManageStockEnabled()
//    {
//        $query = $this->db->getQuery(true);
//
//        $query->select('manage_stock');
//        $query->from($this->db->quoteName('#__protostore_product'));
//        $query->where($this->db->quoteName('id') . ' = ' . $this->db->quote($this->product->id));
//
//        $this->db->setQuery($query);
//
//        return $this->db->loadResult();
//    }
//
//    private function getCountOfThisItem()
//    {
//
//
//        $query = $this->db
//            ->getQuery(true)
//            ->select('COUNT(*)')
//            ->from($this->db->quoteName('#__protostore_carts'))
//            ->where($this->db->quoteName('cart_id') . " = " . $this->db->quote($this->cart_id), 'AND');
//        $query->where($this->db->quoteName('item_options') . " = " . $this->db->quote($this->item_options_raw), 'AND');
//        $query->where($this->db->quoteName('joomla_item_id') . " = " . $this->db->quote($this->joomla_item_id));
//        $this->db->setQuery($query);
//        return $this->db->loadResult();
//
//    }
//
//
//    public function saveCount($newCount)
//    {
//        $db = Factory::getDbo();
//
//        $object = new stdClass();
//        $object->id = $this->cart_itemid;
//        $object->amount = $newCount;
//
//        $result = $db->updateObject('#__protostore_carts', $object, 'id');
//
//        if ($result) {
//            return true;
//        } else {
//            return false;
//        }
//
//    }
//
//
//    public static function change($change, $cart_id, $item_options_raw, $cartItemId)
//    {
//
//        $db = Factory::getDbo();
//
//
//        if ($change < 0) {
//            // remove
//
//            $change = abs($change);
//
//            for ($x = 1; $x <= $change; $x++) {
//
//                $query = $db->getQuery(true);
//
//                $conditions = array(
//                    $db->quoteName('cart_id') . " = " . $db->quote($cart_id),
//                    $db->quoteName('item_options') . ' = ' . $db->quote($item_options_raw)
//                );
//
//                $query->delete($db->quoteName('#__protostore_carts'));
//                $query->where($conditions);
//                $query->setLimit('1');
//                $db->setQuery($query);
//                $db->execute();
//
//            }
//
//
//        } else {
//            // create
//
//            $query = $db->getQuery(true);
//
//            $query->select('*');
//            $query->from($db->quoteName('#__protostore_carts'));
//            $query->where($db->quoteName('id') . ' = ' . $db->quote($cartItemId));
//            $db->setQuery($query);
//            $result = $db->loadObject();
//
//
//            for ($x = 1; $x <= $change; $x++) {
//                $result->id = 0;
//                $db->insertObject('#__protostore_carts', $result);
//
//            }
//
//        }
//
//
//    }
//
//
//    public static function removeAll($cartitemid)
//    {
//
//        $db = Factory::getDbo();
//        $query = $db->getQuery(true);
//
//        $conditions = array(
//            $db->quoteName('id') . " = " . $db->quote($cartitemid),
//        );
//
//        $query->delete($db->quoteName('#__protostore_carts'));
//        $query->where($conditions);
//        $db->setQuery($query);
//        $db->execute();
//    }


}
