<?php

/**
 * @package   Pro2Store
 * @author    Ray Lawlor - pro2.store
 * @copyright Copyright (C) 2021 Ray Lawlor - pro2.store
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 *
 */

// no direct access

namespace Protostore\Product;

defined('_JEXEC') or die('Restricted access');


class Product
{

	// base
	public $id;
	public $joomla_item_id;
	public $sku;


	// Pricing
	public $base_price = 0;
	public $basepriceFloat;
	public $base_price_formatted;

	public $base_priceWithTax;
	public $base_priceWithTax_formatted;

	public $show_discount;

	public $discount;
	public $discountFloat;
	public $discount_formatted;


	public $discount_type;
	public $priceAfterDiscount;
	public $priceAfterDiscount_formatted;

	public $priceAfterDiscountWithTax;
	public $priceAfterDiscountWithTax_formatted;


	// Checkbox Options
	public $options;

	// Joomla Item
	public $categoryName;
	public $joomlaItem;
	public $images;
	public $teaserImagePath;
	public $fullImagePath;
	public $tags;
	public $taxable;
	public $link;
	public $category_link;
	public $published;


	// Shipping
	public $shipping_mode;
	public $flatfee;
	public $flatfeeFloat;
	public $weight;
	public $weight_unit;

	// Stock
	public $manage_stock;
	public $stock;
	public $maxPerOrder;

	// Variants
	public $variants;
	public $variantList;
	public $variantDefault;

	// custom fields
	public $custom_fields;


	public function __construct($data)
	{

		if ($data)
		{
			$this->hydrate($data);
			$this->init();
		}

	}

	/**
	 *
	 * Function to simply "hydrate" the database values directly to the class parameters.
	 *
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
	 * Function to "hydrate" all non-database values.
	 *
	 * @throws \Brick\Money\Exception\UnknownCurrencyException
	 * @since 2.0
	 */

	private function init()
	{

		// get the joomla item
		$this->joomlaItem = ProductFactory::getJoomlaItem($this->joomla_item_id);
		$this->published  = $this->joomlaItem->state == 1;

		// get the images
		$this->images = json_decode($this->joomlaItem->images, true);

		if ($this->images)
		{
			$this->teaserImagePath = ProductFactory::getImagePath($this->images['image_intro']);
			$this->fullImagePath   = ProductFactory::getImagePath($this->images['image_fulltext']);
		}


		// set the prices
		$this->basepriceFloat               = ProductFactory::getFloat(($this->base_price ?: 0));
		$this->base_price_formatted          = ProductFactory::getFormattedPrice(($this->base_price ?: 0));
		$this->flatfeeFloat                 = ProductFactory::getFloat(($this->flatfee ?: 0));
		$this->priceAfterDiscount           = $this->base_price - $this->discount;
		$this->priceAfterDiscount_formatted = ProductFactory::getFormattedPrice(($this->priceAfterDiscount ?: 0));

		if ($this->taxable === 1)
		{
			$this->base_priceWithTax          = ProductFactory::getPriceWithTax(($this->base_price ?: 0));
			$this->priceAfterDiscountWithTax = ProductFactory::getPriceWithTax(($this->priceAfterDiscount ?: 0));
		}
		else
		{
			$this->base_priceWithTax = 0;
			$this->discountWithTax  = 0;
		}
		$this->base_priceWithTax_formatted          = ProductFactory::getFormattedPrice(($this->base_priceWithTax ?: $this->base_price));
		$this->priceAfterDiscountWithTax_formatted = ProductFactory::getFormattedPrice(($this->priceAfterDiscountWithTax ?: 0));


		$this->show_discount      = ($this->discount ? 1 : 0);
		$this->discountFloat      = ProductFactory::getFloat(($this->discount ?: 0));
		$this->discount_formatted = ProductFactory::getFloat(($this->discount ?: 0));

		//		$this->discounted_total           = $this->getDiscountedTotal(); // todo
		//		$this->discounted_total_formatted = $this->getFormattedDiscountPrice(); // todo

		$this->options = ProductFactory::getOptions($this->joomla_item_id);

		$this->categoryName = ProductFactory::getCategoryName($this->joomlaItem->catid);

		$this->tags = ProductFactory::getTags($this->joomla_item_id);


		$this->link          = ProductFactory::getRoute('item', $this->joomla_item_id, $this->joomlaItem->catid);
		$this->category_link = ProductFactory::getRoute('category', $this->joomla_item_id, $this->joomlaItem->catid);

		$variantData = ProductFactory::getVariantData($this->joomla_item_id);


		if ($variantData)
		{
			$this->variants       = $variantData->variants;
			$this->variantList    = $variantData->variantList;
			$this->variantDefault = $variantData->default;
		}

		$this->custom_fields = ProductFactory::getCustomFields($this->id);


		//get files data if digital
//		if ($this->product_type == 2)
//		{
//			$this->files = ProductFactory::getFiles($this->id);
//		}
//		else
//		{
//			$this->files = null;
//		}


	}


}
