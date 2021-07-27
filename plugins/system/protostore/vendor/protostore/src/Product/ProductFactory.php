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
use Joomla\CMS\Categories\Categories;
use Joomla\CMS\Helper\TagsHelper;
use Joomla\Input\Input;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Uri\Uri;
use Joomla\CMS\Table\Table;
use Joomla\CMS\Filter\OutputFilter;

use Brick\Math\BigDecimal;
use Brick\Money\Exception\UnknownCurrencyException;

use Protostore\Currency\CurrencyFactory;
use Protostore\Productoption\ProductoptionFactory;
use Protostore\Utilities\Utilities;

use StathisG\GreekSlugGenerator\GreekSlugGenerator;

use Exception;
use stdClass;


class ProductFactory
{


	/**
	 * @param   int  $joomla_item_id
	 *
	 * @return Product|PurchaseProduct|SubscriptionProduct|null
	 * @since 1.0
	 */

	public static function get(int $joomla_item_id)
	{

		$db = Factory::getDbo();

		$query = $db->getQuery(true);

		$query->select('*');
		$query->from($db->quoteName('#__protostore_product'));
		$query->where($db->quoteName('joomla_item_id') . ' = ' . $db->quote($joomla_item_id));

		$db->setQuery($query);

		$result = $db->loadObject();
		if ($result && is_object($result))
		{

			switch ($result->product_type)
			{
				case 0:
					return new PurchaseProduct($result);
				case 1:
					return new SubscriptionProduct($result);
			}

			return new Product($result);
		}

		return null;
	}


	/**
	 * @param   int          $limit
	 *
	 * @param   int          $offset
	 * @param   int          $category
	 * @param   string|null  $searchTerm
	 * @param   string       $orderBy
	 * @param   string       $orderDir
	 *
	 * @return array
	 * @since version
	 */

	public static function getList(int $limit = 0, int $offset = 0, int $category = 0, string $searchTerm = null, string $orderBy = 'id', string $orderDir = 'DESC'): ?array
	{

		$products = array();

		$db = Factory::getDbo();

		$query = $db->getQuery(true);

		$query->select('id');
		$query->from($db->quoteName('#__content'));

		if ($searchTerm)
		{
			$query->where($db->quoteName('title') . ' LIKE ' . $db->quote('%' . $searchTerm . '%'), 'OR');
		}

		if ($category)
		{
			$query->where($db->quoteName('catid') . ' = ' . $db->quote($category));
		}

		$query->order($orderBy . ' ' . $orderDir);

		$db->setQuery($query, $offset, $limit);

		$results = $db->loadColumn();

		if ($results)
		{
			foreach ($results as $result)
			{
				$query = $db->getQuery(true);

				$query->select('*');
				$query->from($db->quoteName('#__protostore_product'));
				$query->where($db->quoteName('joomla_item_id') . ' = ' . $db->quote($result));
				$db->setQuery($query);

				$result = $db->loadObject();

				if ($result)
				{
					$products[] = new Product($result);
				}


			}


			return $products;
		}


		return null;
	}


	/**
	 * @param   Input  $data
	 *
	 *
	 * @return Product
	 * @throws Exception
	 * @since 1.6
	 */
	public static function saveFromInputData(Input $data)
	{

//		return $data->json->get('title');

		// if there's no item id, then we need to create a new product
		if ($data->json->getInt('itemid') === 0)
		{
			return self::createNewProduct($data);
		}


		// product exists so we can run an update


		// get current product object
		$currentProduct = self::get($data->json->getInt('itemid'));

//		return $currentProduct;

		// set up Joomla Item:

		$currentProduct->joomlaItem->title       = $data->json->getString('title', $currentProduct->joomlaItem->title);
		$currentProduct->joomlaItem->introtext   = $data->json->getString('introtext', $currentProduct->joomlaItem->introtext);
		$currentProduct->joomlaItem->fulltext    = $data->json->getString('fulltext', $currentProduct->joomlaItem->fulltext);
		$currentProduct->joomlaItem->modified_by = Factory::getUser()->id;
		$currentProduct->joomlaItem->modified    = Utilities::getDate();
		$currentProduct->joomlaItem->images      = self::processImagesForSave(
			$data->json->getString('teaserimage', $currentProduct->teaserImagePath),
			$data->json->getString('fullimage', $currentProduct->fullimage)
		);
		$currentProduct->joomlaItem->featured    = $data->json->getInt('featured', $currentProduct->joomlaItem->featured);
		$currentProduct->joomlaItem->state       = $data->json->getInt('state', $currentProduct->joomlaItem->state);
		$currentProduct->joomlaItem->publish_up  = $data->json->getString('publish_up_date', $currentProduct->joomlaItem->publish_up);
		$currentProduct->joomlaItem->catid       = $data->json->getInt('category', $currentProduct->joomlaItem->catid);


		// with prices... we need to run it through the Brick system first.
		$base_price = $data->json->getFloat('base_price', $currentProduct->base_price);


		if ($base_price)
		{
			$currentProduct->base_price = CurrencyFactory::toInt($base_price);
		}
		else
		{
			$currentProduct->base_price = 0;
		}

		$discount = $data->json->getFloat('discount', $currentProduct->discount);
		if ($discount)
		{
			$currentProduct->discount = CurrencyFactory::toInt($discount);
		}

		$currentProduct->shipping_mode = $data->json->getString('shipping_mode', $currentProduct->shipping_mode);

		if ($currentProduct->shipping_mode == 'flat')
		{

			$currentProduct->flatfee = $data->json->getFloat('flatfee', $currentProduct->flatfee);

			if ($currentProduct->flatfee)
			{
				$currentProduct->flatfee = CurrencyFactory::toInt($currentProduct->flatfee);
			}

		}
		else
		{
			$currentProduct->flatfee = 0;
		}


		$currentProduct->manage_stock  = $data->json->getInt('manage_stock', $currentProduct->manage_stock);
		$currentProduct->stock         = $data->json->getInt('stock', $currentProduct->stock);
		$currentProduct->maxPerOrder   = $data->json->getInt('maxPerOrder', $currentProduct->maxPerOrder);
		$currentProduct->taxable       = $data->json->getInt('taxable', $currentProduct->taxable);
		$currentProduct->weight        = $data->json->getInt('weight', $currentProduct->weight);
		$currentProduct->product_type  = $data->json->getInt('product_type', $currentProduct->product_type);
		$currentProduct->weight_unit   = $data->json->getString('weight_unit', $currentProduct->weight_unit);
		$currentProduct->sku           = $data->json->getString('sku', $currentProduct->sku);
		$currentProduct->discount_type = $data->json->getString('discount_type', $currentProduct->discount_type);
		$currentProduct->tags          = json_decode($data->json->getString('tags', json_encode($currentProduct->tags)));

		$currentProduct->options       = $data->json->getString('options', $currentProduct->options);
		$currentProduct->variants      = $data->json->getString('variants', $currentProduct->variants);
		$currentProduct->variantLabels = $data->json->getString('variantLabels', $currentProduct->variantLabels);
		$currentProduct->variantList   = $data->json->getString('variantList', $currentProduct->variantList);


		if (self::commitToDatabase($currentProduct))
		{
			return self::get($data->json->getInt('itemid'));
		}

	}


	/**
	 * @param   Input  $data
	 *
	 * @return Product
	 *
	 * @throws Exception
	 * @since 1.6
	 */

	private static function createNewProduct(Input $data): Product
	{

		$db = Factory::getDbo();

		// create the Joomla Item

		$content        = new stdClass();
		$content->id    = 0;
		$content->title = $data->json->getString('title');

		//alias:
		// Workaround for Greek titles.
		$alias = GreekSlugGenerator::getSlug($content->title);
		$alias = OutputFilter::stringUrlUnicodeSlug($alias);
		$alias = Utilities::generateUniqueAlias($alias);

		$content->alias            = $alias;
		$content->introtext        = $data->json->getString('introtext');
		$content->fulltext         = $data->json->getString('fulltext');
		$content->state            = $data->json->getInt('state');
		$content->catid            = $data->json->getInt('category');
		$content->featured         = $data->json->getInt('featured');
		$content->created          = Utilities::getDate();
		$content->created_by       = Factory::getUser()->id;
		$content->created_by_alias = Factory::getUser()->alias;
		$content->modified         = Utilities::getDate();;
		$content->modified_by = Factory::getUser()->id;
		$content->publish_up  = $data->json->getString('publish_up_date');
		$content->images      = self::processImagesForSave(
			$data->json->getString('teaserimage'),
			$data->json->getString('fullimage')
		);
		if (!$db->insertObject('#__content', $content))
		{
			return false;
		}


		// create the item in Pro2Store Products table


		$product                 = new stdClass();
		$product->joomla_item_id = $db->insertid();


		$base_price          = $data->json->getFloat('base_price', 0);
		$product->base_price = CurrencyFactory::toInt($base_price);


		$product->shipping_mode = $data->json->getString('shipping_mode');


		// flat fee
		if ($product->shipping_mode == 'flat')
		{

			$product->flatfee = $data->json->getFloat('flatfee', 0);
			$product->flatfee = CurrencyFactory::toInt($product->flatfee);

		}
		else
		{
			$product->flatfee = 0;
		}

		$discount = $data->json->getFloat('discount', 0);
		if ($discount)
		{
			$product->discount = CurrencyFactory::toInt($discount);
		}


		$product->manage_stock  = $data->json->getInt('manage_stock', 0);
		$product->stock         = $data->json->getInt('stock', 0);
		$product->maxPerOrder   = $data->json->getInt('maxPerOrder', 0);
		$product->taxable       = $data->json->getInt('taxable', 0);
		$product->weight        = $data->json->getInt('weight', 0);
		$product->product_type  = $data->json->getInt('product_type', 1);
		$product->weight_unit   = $data->json->getString('weight_unit');
		$product->sku           = $data->json->getString('sku');
		$product->discount_type = $data->json->getString('discount_type');


		if (!$db->insertObject('#__protostore_product', $product))
		{
			return false;
		}


		// Create the Variants

		if ($data->json->getString('variants'))
		{
			self::commitVariants($product);
		}


		// Create the Tags

		if ($tags = $data->json->getString('tags'))
		{

			$tags = Utilities::processTagsByName($tags);

			$table = Table::getInstance('Content');
			$table->load($product->joomla_item_id);
			$table->newTags = $tags;
			$table->store();
			if (!$table->store())
			{

				return false;

			}
		}


		// Create the Old Options (deprecated)
		if ($data->json->getString('options'))
		{
			self::commitOptions($product);
		}

		return self::get($product->joomla_item_id);

	}


	/**
	 * @param   Product  $product
	 *
	 * @return bool
	 *
	 * @throws Exception
	 * @since 1.6
	 */

	private static function commitToDatabase(Product $product): bool
	{


		$db = Factory::getDbo();

		$insertProduct = new stdClass();

		$insertProduct->id             = $product->id;
		$insertProduct->joomla_item_id = $product->joomla_item_id;
		$insertProduct->sku            = $product->sku;
		$insertProduct->base_price     = $product->base_price;
		$insertProduct->shipping_mode  = $product->shipping_mode;
		$insertProduct->flatfee        = $product->flatfee;
		$insertProduct->weight         = $product->weight;
		$insertProduct->weight_unit    = $product->weight_unit;
		$insertProduct->manage_stock   = $product->manage_stock;
		$insertProduct->stock          = $product->stock;
		$insertProduct->discount       = $product->discount;
		$insertProduct->maxPerOrder    = $product->maxPerOrder;
		$insertProduct->discount_type  = $product->discount_type;
		$insertProduct->taxable        = $product->taxable;
		$insertProduct->product_type   = $product->product_type;

		$result = $db->updateObject('#__protostore_product', $insertProduct, 'joomla_item_id');

		if ($result)
		{
			// now do the Joomla Item

			$jresult = $db->updateObject('#__content', $product->joomlaItem, 'id');

			if ($jresult)
			{
				// now do variants

				self::commitVariants($product);


			}

			// now do options

			self::commitOptions($product);


			// now do TAGS

			if ($tags = $product->tags)
			{

				$tags = Utilities::processTagsByName($tags);

				$table = Table::getInstance('Content');
				$table->load($product->joomla_item_id);
				$table->newTags = $tags;

			}
			else
			{
				$table = Table::getInstance('Content');
				$table->load($product->joomla_item_id);

			}
			$table->store();

		}


		return true;

	}


	/**
	 * @param $options
	 * @param $product
	 *
	 *
	 * @throws Exception
	 * @since 1.6
	 */

	private static function commitOptions($product)
	{

		$db = Factory::getDbo();

		$product_options = json_decode($product->options);

		$i = 0;

		//now reinsert the new ones
		foreach ($product_options as $option)
		{

			if (isset($option->id))
			{
				//first check if the option exists
				$query = $db->getQuery(true);

				$query->select('*');
				$query->from($db->quoteName('#__protostore_product_option_values'));
				$query->where($db->quoteName('id') . ' = ' . $db->quote($option->id));

				$db->setQuery($query);

				$option_exists = $db->loadObject();
			}
			else
			{
				$option_exists = false;
			}


			if ($option_exists)
			{

				if ($option->modifiervalue)
				{

					if ($option->modifiertype == 'perc')
					{
						$modifiervalue = $option->modifiervalue;
					}
					else
					{

						$modifiervalue = CurrencyFactory::toInt($option->modifiervalueFloat);
					}

				}
				else
				{
					$modifiervalue = "";
				}
				//run update
				$query = $db->getQuery(true);

				$fields = array(
					$db->quoteName('optionname') . ' = ' . $db->quote($option->optionname),
					$db->quoteName('optiontype') . ' = ' . $db->quote($option->optiontype),
					$db->quoteName('modifier') . ' = ' . $db->quote($option->modifier),
					$db->quoteName('modifiertype') . ' = ' . $db->quote($option->modifiertype),
					$db->quoteName('modifiervalue') . ' = ' . $db->quote($modifiervalue),
					$db->quoteName('optionsku') . ' = ' . $db->quote($option->optionsku),
					$db->quoteName('ordering') . ' = ' . $db->quote($i)
				);

				$conditions = array(
					$db->quoteName('id') . ' = ' . $db->quote($option->id),
				);

				$query->update($db->quoteName('#__protostore_product_option_values'))->set($fields)->where($conditions);

				$db->setQuery($query);

				$db->execute();
			}
			else
			{
				//run insert
				$object               = new stdClass();
				$object->id           = 0;
				$object->product_id   = $product->id;
				$object->optiontype   = $option->optiontype;
				$object->optionname   = $option->optionname;
				$object->modifier     = $option->modifier;
				$object->modifiertype = $option->modifiertype;
				if ($option->modifiertype == 'perc')
				{
					$object->modifiervalue = $option->modifiervalue;
				}
				else
				{

					$object->modifiervalue = CurrencyFactory::toInt($option->modifiervalueFloat);
				}
				$object->optionsku = $option->optionsku;
				$object->ordering  = $i;

				$db->insertObject('#__protostore_product_option_values', $object);
			}
			$i++;


		}


	}


	/**
	 * @param $product
	 *
	 * @return bool
	 *
	 * @throws Exception
	 * @since 1.6
	 */

	public static function commitVariants($product): bool
	{

		$db = Factory::getDbo();

		// check if we already have variants for this product
		$query = $db->getQuery(true);

		$query->select('*');
		$query->from($db->quoteName('#__protostore_product_variant'));
		$query->where($db->quoteName('product_id') . ' = ' . $db->quote($product->id));
		$db->setQuery($query);

		$currentVariants = $db->loadObject();

		if ($currentVariants)
		{
			// if we have variants already,  update

			$currentVariants->variants      = $product->variants;
			$currentVariants->variantLabels = $product->variantLabels;
			$currentVariants->variantList   = self::processVariantPrices($product);

			$db->updateObject('#__protostore_product_variant', $currentVariants, 'product_id');

			return true;

		}
		else
		{
			// if not, then insert new ones

			$object                = new stdClass();
			$object->product_id    = $product->id;
			$object->variants      = $product->variants;
			$object->variantLabels = $product->variantLabels;
			$object->variantList   = $product->variantList;

			$db->insertObject('#__protostore_product_variant', $object);

			return true;

		}

	}


	/**
	 * @param   int          $limit
	 * @param   int          $offset
	 * @param   string|null  $searchTerm
	 * @param   string|null  $optionType
	 *
	 * @return array|false
	 *
	 * @since 1.6
	 */

	public static function getOptionList(int $limit = 0, int $offset = 0, string $searchTerm = null, string $optionType = null)
	{

		$options = array();

		$db = Factory::getDbo();

		$query = $db->getQuery(true);

		$query->select('*');
		$query->from($db->quoteName('#__protostore_product_option'));

		if ($searchTerm)
		{
			$query->where($db->quoteName('name') . ' LIKE ' . $db->quote('%' . $searchTerm . '%'), 'OR');
			$query->where($db->quoteName('option_type') . ' LIKE ' . $db->quote('%' . $searchTerm . '%'), 'OR');

		}

		if ($optionType)
		{
			$query->where($db->quoteName('option_type') . ' = ' . $db->quote($optionType));
		}


		$db->setQuery($query, $offset, $limit);

		$results = $db->loadObjectList();

		if ($results)
		{
			foreach ($results as $result)
			{
				$options[] = new Option($result);

			}

			return $options;
		}


		return false;

	}


	/**
	 * @param $joomla_item_id
	 *
	 * @return false|JoomlaItem
	 *
	 * @since 1.6
	 */

	public static function getJoomlaItem($joomla_item_id): ?JoomlaItem
	{


		$db = Factory::getDbo();

		$query = $db->getQuery(true);

		$query->select('*');
		$query->from($db->quoteName('#__content'));
		$query->where($db->quoteName('id') . ' = ' . $db->quote($joomla_item_id));

		$db->setQuery($query);

		$result = $db->loadObject();
		if ($result && is_object($result))
		{

			return new JoomlaItem($result);
		}

		return false;

	}


	/**
	 * @param   string  $type
	 * @param   int     $joomla_item_id
	 * @param   int     $catid
	 *
	 * @return string|null
	 *
	 * @since 1.6
	 */

	public static function getRoute(string $type, int $joomla_item_id, int $catid): string
	{

		switch ($type)
		{
			case 'item':
				return Route::_('index.php?option=com_content&view=article&id=' . $joomla_item_id . '&catid=' . $catid);
			case 'category':
				return Route::_('index.php?option=com_content&view=category&layout=blog&id=' . $catid);
		}


	}


	/**
	 * @param $price
	 *
	 * @return BigDecimal
	 *
	 * @throws UnknownCurrencyException
	 * @since version
	 */

	public static function getFloat($price): BigDecimal
	{

		return CurrencyFactory::toFloat($price);

	}


	/**
	 * @param $price
	 *
	 * @return string
	 *
	 * @throws UnknownCurrencyException
	 * @since 1.6
	 */

	public static function getFormattedPrice($price): string
	{

		return CurrencyFactory::formatNumberWithCurrency($price);

	}


	/**
	 * @param $category_id
	 *
	 * @return string|null
	 *
	 * @since 1.6
	 */

	public static function getCategoryName($category_id): ?string
	{

		$categories   = Categories::getInstance('content');
		$categoryNode = $categories->get($category_id);   // returns the category node for category with id=12

		return $categoryNode->title;

	}


	/**
	 * @param $joomla_item_id
	 *
	 * @return array
	 *
	 * @since 1.6
	 */

	public static function getTags($joomla_item_id)
	{

		$tagsHelper = new TagsHelper();

		return $tagsHelper->getItemTags('com_content.article', $joomla_item_id);


	}


	/**
	 *
	 * @return array|null
	 *
	 * @since 1.6
	 */

	public static function getAvailableTags(): ?array
	{

		$tags = array();

		$db = Factory::getDbo();

		$query = $db->getQuery(true);

		$query->select('*');
		$query->from($db->quoteName('#__tags'));
		$query->where($db->quoteName('published') . ' = 1');
		$query->where($db->quoteName('title') . ' != ' . $db->quote('ROOT'));

		$db->setQuery($query);

		$results = $db->loadObjectList();
		if ($results)
		{
			foreach ($results as $result)
			{

				$tags[] = new Tag($result);


			}


			return $tags;
		}


		return null;


	}


	/**
	 * @param $product_id
	 *
	 * @return array|false
	 *
	 * @since 1.6
	 */

	public static function getOptions($product_id)
	{

		return ProductoptionFactory::getProductOptions($product_id);

	}


	/**
	 * @param $id
	 *
	 * @return mixed
	 *
	 * @since 1.6
	 */

	public static function togglePublished($id)
	{
		$db = Factory::getDbo();

		$query = 'UPDATE `#__content` SET `state` = IF(`state`=1, 0, 1) WHERE id = ' . $id . ';';
		$db->setQuery($query);
		$db->execute();

		$query = $db->getQuery(true);

		$query->select('*');
		$query->from($db->quoteName('#__content'));
		$query->where($db->quoteName('id') . ' = ' . $db->quote($id));

		$db->setQuery($query);

		$item = $db->loadObject();

		return $item->state;


	}


	/**
	 * @param $image
	 *
	 * @return false|string
	 *
	 * @since version
	 */

	public static function getImagePath($image): ?string
	{

		if ($image)
		{
			return Uri::root() . $image;
		}

		return false;


	}


	/**
	 * @param $product_id
	 *
	 * @return false|Variant
	 *
	 * @since 1.6
	 */

	public static function getVariantData($product_id): ?Variant
	{
		$db = Factory::getDbo();

		$query = $db->getQuery(true);

		$query->select('*');
		$query->from($db->quoteName('#__protostore_product_variant'));
		$query->where($db->quoteName('product_id') . ' = ' . $db->quote($product_id));

		$db->setQuery($query);

		$result = $db->loadObject();
		if ($result && is_object($result))
		{

			return new Variant($result);
		}

		return null;


	}


	/**
	 * @param   string  $variantList
	 *
	 * @return string
	 *
	 * @throws Exception
	 * @since 1.6
	 */

	public static function retrieveVariantPrices(string $variantList): string
	{
		$variantList = json_decode($variantList);

		foreach ($variantList as $variant)
		{
			$variant->price = CurrencyFactory::toFloat($variant->price);
		}

		return json_encode($variantList);
	}


	/**
	 * @param   string  $teaserImage
	 * @param   string  $fullImage
	 *
	 *
	 * @return false|string
	 * @since 1.6
	 */

	private static function processImagesForSave(string $teaserImage, string $fullImage): string
	{

		$images = array();

		$images['image_intro']    = $teaserImage;
		$images['image_fulltext'] = $fullImage;

		return json_encode($images);

	}


	/**
	 * @param $product
	 *
	 * @return string
	 *
	 * @throws Exception
	 * @since 1.6
	 */

	private static function processVariantPrices($product): string
	{

		$variantList = json_decode($product->variantList);

		foreach ($variantList as $variant)
		{

			if ($variant->price)
			{
				$variant->price = CurrencyFactory::toInt($variant->price);
			}
			else
			{
				$variant->price = $product->base_price;
			}

		}

		return json_encode($variantList);

	}
}
