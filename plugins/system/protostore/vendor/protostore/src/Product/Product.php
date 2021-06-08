<?php

/**
 * @package   Pro2Store - Helper
 * @author    Ray Lawlor - pro2.store
 * @copyright Copyright (C) 2020 Ray Lawlor - pro2.store
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

// no direct access

namespace Protostore\Product;

defined('_JEXEC') or die('Restricted access');

use Joomla\CMS\Factory;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Uri\Uri;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Helper\TagsHelper;

use Protostore\Currency\Currency;
use Protostore\Currency\CurrencyFactory;
use Protostore\Price\Price;
use Protostore\Utilities\Utilities;
use Protostore\Productoptions\Productoptions;

use Brick\Money\Money;
use Brick\Money\Context\CashContext;
use Brick\Math\RoundingMode;

use stdClass;


class Product
{

	public $joomla_item_id;
	public $baseprice;
	public $basepricefloat;
	public $baseprice_formatted;
	public $sku;
	public $flatfee;
	public $options;
	public $options_for_edit;
	public $discount;
	public $discountfloat;
	public $discount_type;
	public $discounted_total;
	public $category;
	public $joomlaItem;
	public $teaserImagePath;
	public $fullImagePath;
	public $title;
	public $published;
	public $tags;
	public $taxable;
	public $publishupDate;
	public $publishupTime;
	public $link;
	public $category_link;
	public $product_type;

	public $shipping_mode;
	public $weight;
	public $weight_unit;
	public ?int $manage_stock;
	public ?int $stock;
	public int $maxPerOrder;


//
//    public $variantTypes;
//    public $variantList;


	/**
	 * Product constructor.
	 *
	 * @param   null  $joomla_item_id
	 */


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
		$this->baseprice                  = $data->base_price;
		$this->basepricefloat             = $this->getBasePriceFloat();
		$this->baseprice_formatted        = $this->getFormattedBasePrice();
		$this->options                    = Productoptions::getOptions($this->joomla_item_id);
		$this->options_for_edit           = Productoptions::getOptionsForEdit($this->joomla_item_id);
		$this->flatfeefloat               = $this->getFlatFeeFloat();
		$this->discountfloat              = $this->getDiscountFloat();
		$this->discounted_total           = $this->getDiscountedTotal();
		$this->discounted_total_formatted = $this->getFormattedDiscountPrice();


		$this->setCategoryName();
		$this->setJoomlaItem();
		$this->setTeaserImagePath();
		$this->setFullImagePath();
		$this->setTitle();
		$this->setPublishUpDate();
		$this->setPublishUpTime();

		$this->published     = $this->getPublishedState();
		$this->tags          = $this->getTags();
		$this->link          = Route::_('index.php?option=com_content&view=article&id=' . $this->joomla_item_id . '&catid=' . $this->joomlaItem->catid);
		$this->category_link = Route::_('index.php?option=com_content&view=category&layout=blog&id=' . $this->joomlaItem->catid);
	}


	private function _checkIfProduct($joomla_item_id)
	{

		$query = $this->db->getQuery(true);

		$query->select('*');
		$query->from($this->db->quoteName('#__protostore_product'));
		$query->where($this->db->quoteName('joomla_item_id') . ' = ' . $this->db->quote($joomla_item_id));

		$this->db->setQuery($query);

		$result = $this->db->loadObject();

		if ($result)
		{
			return $result;
		}
		else
		{
			return false;
		}


	}


	public function setPublishUpDate()
	{
		$this->publishupDate = HtmlHelper::date($this->joomlaItem->publish_up, 'Y-m-d');
	}

//    public function getVariantTypes()
//    {
//
//        $query = $this->db->getQuery(true);
//
//        $query->select('*');
//        $query->from($this->db->quoteName('#__protostore_variant'));
//        $query->where($this->db->quoteName('joomla_item_id') . ' = ' . $this->db->quote($this->joomla_item_id));
//
//        $this->db->setQuery($query);
//
//        $variants = $this->db->loadObjectList();
//
//
//        foreach ($variants as $variant) {
//            $options = explode(',', $variant->options);
//            $variant->options = $options;
//
//        }
//
//        $this->variantTypes = $variants;
//
//
//    }

//    public function getVariantList()
//    {
//
//
//        $query = $this->db->getQuery(true);
//
//        $query->select('*');
//        $query->from($this->db->quoteName('#__protostore_variant_values'));
//        $query->where($this->db->quoteName('joomla_item_id') . ' = ' . $this->db->quote($this->joomla_item_id));
//
//        $this->db->setQuery($query);
//
//        $variantList = $this->db->loadObjectList();
//
//        foreach ($variantList as $variant) {
//            $active = ($variant->active == 1 ? true: false);
//            $variant->active = $active;
//            $default = ($variant->default == 1 ? true: false);
//            $variant->default = $default;
//
//        }
//
//        $this->variantList = $variantList;
//    }


	public function setPublishUpTime()
	{
		$this->publishupTime = HtmlHelper::date($this->joomlaItem->publish_up, 'H:i');
	}


	private function setTeaserImagePath()
	{

		if ($this->getImage_intro()->path)
		{
			$this->teaserImagePath = Uri::root() . $this->getImage_intro()->path;
		}
		else
		{
			$this->teaserImagePath = '';
		}


	}

	private function setFullImagePath()
	{

		if ($this->getImage_fulltext()->path)
		{
			$this->fullImagePath = Uri::root() . $this->getImage_fulltext()->path;
		}
		else
		{
			$this->fullImagePath = '';
		}


	}


	private function setCategoryName()
	{
		$this->category = Utilities::getCategory($this->joomla_item_id);
	}

	/**
	 *
	 * Function setJoomlaItem
	 *
	 * Sets the joomla item values
	 *
	 *
	 * @return void
	 *
	 */


	private function setJoomlaItem()
	{
		$this->joomlaItem = Utilities::getItem($this->joomla_item_id);
	}

	/**
	 * Function setTitle
	 *
	 * get's the Joomla Item title and sets the value
	 *
	 */

	private function setTitle()
	{
		$this->title = $this->joomlaItem->title;
	}


	/**
	 *
	 * Function getBasePriceFloat
	 *
	 * Returns the simple float value of the integer baseprice from the database.
	 *
	 * @return \Brick\Math\BigDecimal|string
	 * @throws \Brick\Money\Exception\UnknownCurrencyException
	 */

	public function getBasePriceFloat()
	{

		if ($this->baseprice)
		{

			$baseprice = Money::ofMinor((int) $this->baseprice, CurrencyFactory::getCurrent()->iso, new CashContext(1), RoundingMode::DOWN);

			return $baseprice->getAmount();

		}
		else
		{
			return 0;
		}


	}

	/**
	 *
	 * Function getFlatFeeFloat
	 *
	 * Returns the simple float value of the integer flatfee from the database.
	 *
	 * @return \Brick\Math\BigDecimal|string
	 * @throws \Brick\Money\Exception\UnknownCurrencyException
	 * @throws \Exception
	 */

	public function getFlatFeeFloat()
	{


		if ($this->flatfee)
		{


			$flatfee = Money::ofMinor((int) $this->flatfee, CurrencyFactory::getCurrent()->iso, new CashContext(1), RoundingMode::DOWN);

			return $flatfee->getAmount();


		}
		else
		{
			return 0;
		}


	}

	/**
	 *
	 * Function getDiscountFloat
	 *
	 * Returns the simple float value of the integer discount from the database.
	 *
	 * @return \Brick\Math\BigDecimal|string
	 * @throws \Brick\Money\Exception\UnknownCurrencyException
	 */

	public function getDiscountFloat()
	{


		if ($this->discount)
		{

			$discount = Money::ofMinor((int) $this->discount, CurrencyFactory::getCurrent()->iso, new CashContext(1), RoundingMode::DOWN);

			return $discount->getAmount();


		}
		else
		{
			return 0;
		}

	}

	/**
	 *
	 * Function getDiscountTotal
	 *
	 * Returns the simple integer value of the calculated total discount.
	 *
	 * @return \Brick\Math\BigDecimal|string
	 * @throws \Brick\Money\Exception\UnknownCurrencyException
	 */

	public function getDiscountedTotal()
	{

		return Price::calculatePriceAfterDiscount($this->joomla_item_id);

	}

	/**
	 *
	 * function getFormattedBasePrice
	 *
	 * Returns the formatted base price in the default currency
	 *
	 *
	 * @return mixed|string
	 */


	public function getFormattedDiscountPrice()
	{

		if ($this->discounted_total)
		{
			return Currency::formatNumberWithCurrency($this->discounted_total, CurrencyFactory::getCurrent()->iso);
		}


	}


	/**
	 *
	 * function getFormattedBasePrice
	 *
	 * Returns the formatted base price in the default currency
	 *
	 *
	 * @return mixed|string
	 */


	public function getFormattedBasePrice()
	{


		if ($this->baseprice)
		{
			return Currency::formatNumberWithCurrency($this->baseprice, CurrencyFactory::getCurrent()->iso);
		}


	}


	/**
	 * @return mixed
	 *
	 * @since 1.0
	 */
	public function getPublishedState()
	{
		return $this->joomlaItem->state;
	}

	/**
	 * @return mixed
	 *
	 * @since 1.0
	 */
	public function getBaseprice()
	{
		return $this->baseprice;
	}


	/**
	 * @return mixed
	 *
	 * @since 1.0
	 */
	public function getSku()
	{
		return $this->sku;
	}

	/**
	 * @return mixed
	 * @since 1.0
	 */
	public function getOptions($decode = false)
	{

		if ($decode)
		{
			return json_decode($this->options, true);
		}

		return $this->options;
	}

	/**
	 * @return mixed
	 * @since 1.0
	 */
	public function getDiscount()
	{
		return $this->discount;
	}

	/**
	 * @return mixed
	 * @since 1.0
	 */
	public function getCategory()
	{
		return $this->category;
	}

	/**
	 * @return object
	 * @since 1.0
	 */
	public function getJoomlaItem()
	{
		return $this->joomlaItem;
	}

	/**
	 * @return mixed|null
	 */
	public function getjoomla_item_id()
	{
		return $this->joomla_item_id;
	}


	/**
	 * @return string
	 */
	public function getStock()
	{

		return (string) $this->stock;
	}

	/**
	 * @return mixed
	 */
	public function getStockString()
	{

		if ($this->stock === 0)
		{
			return (string) "0";
		}
		else
		{
			return (string) $this->stock;
		}

	}

	/**
	 * @return mixed
	 */
	public function getWeight()
	{
		return $this->weight;
	}

	/**
	 * @return mixed
	 */
	public function getWeightUnit()
	{
		return $this->weight_unit;
	}

	/**
	 * @return mixed
	 */
	public function getFlatfee()
	{
		return $this->flatfee;
	}

	/**
	 * @return object
	 */
	public function getImage_fulltext()
	{
		$images = json_decode($this->getJoomlaItem()->images);


		$image = new stdClass();

		if (isset($images->image_fulltext))
		{
			$image->path = $images->image_fulltext;
		}
		else
		{
			$image->path = '';
		}


		return $image;


	}

	/**
	 * @return object
	 */
	public function getImage_intro()
	{
		$images = json_decode($this->getJoomlaItem()->images);

		$image = new stdClass();

		if (isset($images->image_intro))
		{
			$image->path = $images->image_intro;
		}
		else
		{
			$image->path = '';
		}


		return $image;

	}


	public static function getCurrentStock($product_id)
	{
		$db = Factory::getDbo();

		$query = $db->getQuery(true);

		$query->select('stock');
		$query->from($db->quoteName('#__protostore_product'));
		$query->where($db->quoteName('id') . ' = ' . $db->quote($product_id));

		$db->setQuery($query);

		return $db->loadResult();
	}


	public function getTags()
	{

		$tagsHelper = new TagsHelper();

		$currentTags = $tagsHelper->getTagIds($this->joomlaItem->id, "com_content.article");

		return $tagsHelper->getTagNames(explode(',', $currentTags));

		$query = $this->db->getQuery(true);

		$query->select(array('title'));
		$query->from($this->db->quoteName('#__tags'));
		$query->where($this->db->quoteName('id') . ' IN (' . $currentTags . ')');

		$this->db->setQuery($query);

		return $this->db->loadObjectList();


	}


}
