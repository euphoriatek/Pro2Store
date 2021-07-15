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


class Product
{

	// base
	public int $id;
	public int $joomla_item_id;
	public ?string $sku;


	// Pricing
	public int $base_price = 0;
	public ?\Brick\Math\BigDecimal $basepriceFloat;
	public string $baseprice_formatted;
	public ?int $show_discount;
	public ?int $discount;
	public ?\Brick\Math\BigDecimal $discountFloat;
	public ?string $discount_type;
	public int $discounted_total;

	// Old Options
	public $options;

	// Joomla Item
	public ?string $categoryName;
	public ?JoomlaItem $joomlaItem;
	public ?array $images;
	public ?string $teaserImagePath;
	public ?string $fullImagePath;
	public $tags;
	public ?int $taxable;
	public ?string $link;
	public ?string $category_link;
	public string $product_type;
	public bool $published;


	// Shipping
	public ?string $shipping_mode;
	public ?int $flatfee;
	public \Brick\Math\BigDecimal $flatfeeFloat;
	public ?int $weight;
	public ?string $weight_unit;

	// Stock
	public ?int $manage_stock;
	public ?int $stock;
	public ?int $maxPerOrder;

	// Variants
	public $variants;
	public $variantLabels;
	public $variantList;


	public function __construct($data)
	{

		if ($data)
		{
			$this->hydrate($data);
			$this->init($data);
		}

	}

	/**
	 *
	 * Function to simply "hydrate" the database values directly to the class parameters.
	 *
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
	 * Function to "hydrate" all non-database values.
	 *
	 * @throws \Brick\Money\Exception\UnknownCurrencyException
	 * @since 1.6
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
		$this->basepriceFloat      = ProductFactory::getFloat(($this->base_price ?: 0));
		$this->baseprice_formatted = ProductFactory::getFloat(($this->base_price ?: 0));
		$this->flatfeeFloat        = ProductFactory::getFloat(($this->flatfee ?: 0));
		$this->show_discount       = ($this->discount ? 1 : 0);
		$this->discountFloat       = ProductFactory::getFloat(($this->discount ?: 0));

		//		$this->discounted_total           = $this->getDiscountedTotal(); // todo
		//		$this->discounted_total_formatted = $this->getFormattedDiscountPrice(); // todo

		$this->options = ProductFactory::getOptions($this->id);

		$this->categoryName = ProductFactory::getCategoryName($this->joomlaItem->catid);

		$this->tags = ProductFactory::getTags($this->joomla_item_id);


		$this->link          = ProductFactory::getRoute('item', $this->joomla_item_id, $this->joomlaItem->catid);
		$this->category_link = ProductFactory::getRoute('category', $this->joomla_item_id, $this->joomlaItem->catid);

		$variantData = ProductFactory::getVariantData($this->id);

		if ($variantData)
		{
			$this->variants      = $variantData->variants;
			$this->variantLabels = $variantData->variantLabels;
			$this->variantList   = $variantData->variantList;
		}


	}


}
