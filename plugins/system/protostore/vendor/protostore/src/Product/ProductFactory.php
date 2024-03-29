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


use Joomla\CMS\Date\Date;
use Joomla\CMS\Factory;
use Joomla\CMS\Categories\Categories;
use Joomla\CMS\Helper\TagsHelper;
use Joomla\CMS\Language\Text;
use Joomla\Input\Input;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Uri\Uri;
use Joomla\CMS\Table\Table;
use Joomla\CMS\Filter\OutputFilter;
use Joomla\CMS\Filesystem\File as JoomlaFile;
use Joomla\Utilities\ArrayHelper;

use Brick\Math\BigDecimal;
use Brick\Money\Exception\UnknownCurrencyException;

use Protostore\Currency\CurrencyFactory;
use Protostore\Productoption\Productoption;
use Protostore\Productoption\ProductoptionFactory;
use Protostore\Tag\TagFactory;
use Protostore\Tax\TaxFactory;
use Protostore\Utilities\Utilities;

use StathisG\GreekSlugGenerator\GreekSlugGenerator;

use Exception;
use stdClass;


class ProductFactory
{


	/**
	 * @param   int  $joomla_item_id
	 *
	 * @return Product|null
	 * @since 2.0
	 */

	public static function get(int $joomla_item_id): ?Product
	{

		$db = Factory::getDbo();

		$query = $db->getQuery(true);

		$query->select('*');
		$query->from($db->quoteName('#__protostore_product'));
		$query->where($db->quoteName('joomla_item_id') . ' = ' . $db->quote($joomla_item_id));

		$db->setQuery($query);

		$result = $db->loadObject();
		if ($result)
		{

			return new Product($result);
		}

		return null;
	}


	/**
	 * @param   int          $limit
	 *
	 * @param   int          $offset
	 * @param                $category
	 * @param   string|null  $searchTerm
	 * @param   string       $orderBy
	 * @param   string       $orderDir
	 *
	 * @return array
	 * @since 2.0
	 */

	public static function getList(int $limit = 0, int $offset = 0, $category = 0, string $searchTerm = null, string $orderBy = 'id', string $orderDir = 'DESC'): ?array
	{

		$products = array();

		$db = Factory::getDbo();

		$query = $db->getQuery(true);

		$query->select('id');
		$query->from($db->quoteName('#__content'));

		if ($searchTerm)
		{
			$query->where($db->quoteName('title') . ' LIKE ' . $db->quote('%' . $searchTerm . '%'));
		}

		if ($category)
		{

			if (is_array($category))
			{
				$query->where($db->quoteName('catid') . ' IN (' . implode(', ', $category) . ')');
			}
			elseif (is_int($category))
			{
				$query->where($db->quoteName('catid') . ' = ' . $db->quote($category));
			}


		}

		$query->order($orderBy . ' ' . $orderDir);

		$db->setQuery($query, $offset, $limit);

		$contentResults = $db->loadColumn();

		if ($contentResults)
		{
			foreach ($contentResults as $contentId)
			{
				$query = $db->getQuery(true);

				$query->select('*');
				$query->from($db->quoteName('#__protostore_product'));
				$query->where($db->quoteName('joomla_item_id') . ' = ' . $db->quote($contentId));
				$db->setQuery($query);

				$productResult = $db->loadObject();

				if ($productResult)
				{
					$products[] = new Product($productResult);
				}


			}


			return $products;
		}


		return null;
	}


	/**
	 * @param   string  $searchTerm
	 * @param           $categories
	 * @param           $tags
	 * @param   float   $priceFrom
	 * @param   float   $priceTo
	 *
	 * @return array|null
	 * @since 2.0
	 */


	public static function filterList(string $searchTerm, $categories, $tags, float $priceFrom, float $priceTo): ?array
	{

		$products = array();

		$db = Factory::getDbo();


		if ($tags)
		{

			//if we have tags ... do a search on the Tags table

			$query = $db->getQuery(true);

			$query->select('a.content_item_id');
			$query->from($db->quoteName('#__contentitem_tag_map', 'a'));
			$query->join('INNER', $db->quoteName('#__content', 'b') . ' ON ' . $db->quoteName('a.content_item_id') . ' = ' . $db->quoteName('b.id'));

			$query->where($db->quoteName('a.tag_id') . ' IN ( ' . implode(',', $tags) . ')');
			$query->where($db->quoteName('b.state') . ' = 1');

			if ($categories)
			{
				if (!is_array($categories))
				{
					$categories = array($categories);
				}

				//add in categories if we have any
				$query->where($db->quoteName('b.catid') . ' IN ( ' . implode(',', $categories) . ')');
			}

			if ($searchTerm)
			{
				//add in text search if we have it
				$query->where($db->quoteName('b.title') . ' LIKE ' . $db->quote('%' . $searchTerm . '%'));
			}

		}
		elseif ($categories)
		{
			if (!is_array($categories))
			{
				$categories = array($categories);
			}

			$query = $db->getQuery(true);
			$query->select('a.id');
			$query->from($db->quoteName('#__content', 'a'));

			$query->where($db->quoteName('a.catid') . ' IN (' . implode(',', $categories) . ')');

			if ($searchTerm)
			{
				//add in text search if we have it
				$query->where($db->quoteName('a.title') . ' LIKE ' . $db->quote('%' . $searchTerm . '%'));
			}

		}
		else
		{

			$query = $db->getQuery(true);

			$query->select('id');
			$query->from($db->quoteName('#__content'));

			if ($searchTerm)
			{
				//add in text search if we have it
				$query->where($db->quoteName('title') . ' LIKE ' . $db->quote('%' . $searchTerm . '%'));
			}


		}

		// set pricing search to true
		$searchPrice = true;

		if ($priceFrom == '' && $priceTo == '')
		{
			// if we're null on both from and to, then set searchPrice to false
			$searchPrice = false;
		}
		if ($priceFrom == 'null' && $priceTo == 'null')
		{
			// if we're null on both from and to, then set searchPrice to false
			$searchPrice = false;
		}

		// if we have a search price, set the null values to numbers to allow searching.
		if ($searchPrice)
		{
			if ($priceFrom == 'null' || $priceFrom == '')
			{
				// lower price can go to zero
				$priceFrom = 0;
			}
			if ($priceTo == 'null' || $priceTo == '')
			{
				// higher price goes to some astronomical value
				// this allows searches to have a min price but no max price
				$priceTo = 99999999;
			}

			// now use Brick to convert the number to minor int.
			// Currency ISO code doesn't matter here
			$priceFrom = \Brick\Money\Money::of($priceFrom, 'EUR', new \Brick\Money\Context\CashContext(1), \Brick\Math\RoundingMode::DOWN);
			$priceFrom = $priceFrom->getMinorAmount()->toInt();
			$priceTo   = \Brick\Money\Money::of($priceTo, 'EUR', new \Brick\Money\Context\CashContext(1), \Brick\Math\RoundingMode::DOWN);
			$priceTo   = $priceTo->getMinorAmount()->toInt();
		}

		$db->setQuery($query);

		$contentResults = $db->loadColumn();


		if ($contentResults)
		{
			foreach ($contentResults as $contentId)
			{
				$query = $db->getQuery(true);

				$query->select('*');
				$query->from($db->quoteName('#__protostore_product'));
				$query->where($db->quoteName('joomla_item_id') . ' = ' . $db->quote($contentId));
				$db->setQuery($query);

				$productResult = $db->loadObject();

				if ($productResult)
				{
					// do pricing
					if ($searchPrice)
					{
						if ($productResult->base_price > $priceFrom && $productResult->base_price < $priceTo)
						{
							$products[] = new Product($productResult);
						}
					}
					else
					{
						$products[] = new Product($productResult);
					}


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
	 * @return null|Product
	 * @throws Exception
	 * @since 2.0
	 */
	public static function saveFromInputData(Input $data)
	{


		// if there's no item id, then we need to create a new product
		if ($data->json->getInt('itemid') === 0)
		{

			return self::createNewProduct($data);
		}


		// product exists so we can run an update


		// get current product object
		$currentProduct = self::get($data->json->getInt('itemid'));

		// set up Joomla Item:

		$currentProduct->joomlaItem->title       = $data->json->getString('title', $currentProduct->joomlaItem->title);
		$currentProduct->joomlaItem->introtext   = $data->json->getString('introtext', $currentProduct->joomlaItem->introtext);
		$currentProduct->joomlaItem->fulltext    = $data->json->getString('fulltext', $currentProduct->joomlaItem->fulltext);
		$currentProduct->joomlaItem->access      = $data->json->getInt('access', $currentProduct->joomlaItem->access);
		$currentProduct->joomlaItem->modified_by = Factory::getUser()->id;
		$currentProduct->joomlaItem->modified    = Utilities::getDate();
		$currentProduct->joomlaItem->images      = self::processImagesForSave(
			$data->json->getString('teaserimage', $currentProduct->teaserImagePath),
			$data->json->getString('fullimage', $currentProduct->fullImagePath)
		);
		$currentProduct->joomlaItem->featured    = $data->json->getInt('featured', $currentProduct->joomlaItem->featured);
		$currentProduct->joomlaItem->state       = $data->json->getInt('state', $currentProduct->joomlaItem->state);
		$currentProduct->joomlaItem->publish_up  = $data->json->getString('publish_up_date', $currentProduct->joomlaItem->publish_up);
		$currentProduct->joomlaItem->catid       = $data->json->getInt('category', $currentProduct->joomlaItem->catid);
		$currentProduct->joomlaItem->language    = $data->json->getString('language', $currentProduct->joomlaItem->language);


		// with prices... we need to run it through the Brick system first.
		$base_price = $data->json->getFloat('base_price');


		if ($base_price)
		{
			$currentProduct->base_price = CurrencyFactory::toInt($base_price);
		}
		else
		{
			$currentProduct->base_price = $data->json->getFloat('base_price', $currentProduct->base_price);;
		}

		$discount = $data->json->getFloat('discount');
		if ($discount)
		{
			$currentProduct->discount = CurrencyFactory::toInt($discount);
		}
		else
		{
			$currentProduct->discount = $data->json->getFloat('discount', $currentProduct->discount);
		}

		$currentProduct->shipping_mode = $data->json->getString('shipping_mode', $currentProduct->shipping_mode);

		if ($currentProduct->shipping_mode == 'flat')
		{

			$currentProduct->flatfee = $data->json->getFloat('flatfee');

			if ($currentProduct->flatfee)
			{
				$currentProduct->flatfee = CurrencyFactory::toInt($currentProduct->flatfee);
			}

		}
		else
		{
			$currentProduct->flatfee = $data->json->getFloat('flatfee', $currentProduct->flatfee);
		}


		$currentProduct->manage_stock  = $data->json->getInt('manage_stock', $currentProduct->manage_stock);
		$currentProduct->stock         = $data->json->getInt('stock', $currentProduct->stock);
		$currentProduct->maxPerOrder   = $data->json->getInt('maxPerOrder', $currentProduct->maxPerOrder);
		$currentProduct->taxable       = $data->json->getInt('taxable', $currentProduct->taxable);
		$currentProduct->weight        = $data->json->getInt('weight', $currentProduct->weight);
		$currentProduct->weight_unit   = $data->json->getString('weight_unit', $currentProduct->weight_unit);
		$currentProduct->sku           = $data->json->getString('sku', $currentProduct->sku);
		$currentProduct->discount_type = $data->json->getString('discount_type', $currentProduct->discount_type);
		$currentProduct->tags          = $data->json->getString('tags');

		// custom fields
		$currentProduct->custom_fields = $data->json->get('custom_fields', $currentProduct->custom_fields, 'ARRAY');

		$currentProduct->options     = $data->json->get('options', $currentProduct->options, 'ARRAY');
		$currentProduct->variants    = $data->json->get('variants', $currentProduct->variants, 'ARRAY');
		$currentProduct->variantList = $data->json->get('variantList', $currentProduct->variantList, 'ARRAY');


		if (self::commitToDatabase($currentProduct))
		{

			return self::get($data->json->getInt('itemid'));
		}

		return null;

	}


	/**
	 * @param   Input  $data
	 *
	 * @return null|Product
	 *
	 * @throws Exception
	 * @since 2.0
	 */

	private static function createNewProduct(Input $data)
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

		$content->alias = $alias;


		$content->introtext   = $data->json->getString('introtext');
		$content->fulltext    = $data->json->getString('fulltext');
		$content->state       = $data->json->getInt('state');
		$content->catid       = $data->json->getInt('category');
		$content->access      = $data->json->getInt('access');
		$content->featured    = $data->json->getInt('featured');
		$content->language    = $data->json->getString('language');
		$content->created     = Utilities::getDate();
		$content->created_by  = Factory::getUser()->id;
		$content->modified    = Utilities::getDate();
		$content->modified_by = Factory::getUser()->id;
		$content->publish_up  = $data->json->getString('publish_up_date');
		$content->urls        = '{"urla":"","urlatext":"","targeta":"","urlb":"","urlbtext":"","targetb":"","urlc":"","urlctext":"","targetc":""}';
		$content->attribs     = '{"article_layout":"","show_title":"","link_titles":"","show_tags":"","show_intro":"","info_block_position":"","info_block_show_title":"","show_category":"","link_category":"","show_parent_category":"","link_parent_category":"","show_author":"","link_author":"","show_create_date":"","show_modify_date":"","show_publish_date":"","show_item_navigation":"","show_hits":"","show_noauth":"","urls_position":"","alternative_readmore":"","article_page_title":"","show_publishing_options":"","show_article_options":"","show_urls_images_backend":"","show_urls_images_frontend":""}';
		$content->metadesc    = '';
		$content->metakey     = '';
		$content->metadata    = '';
		$content->language    = '*';
		$content->images      = self::processImagesForSave(
			$data->json->getString('teaserimage'),
			$data->json->getString('fullimage')
		);
		if (!$db->insertObject('#__content', $content))
		{
			return null;
		}




		// create the item in Pro2Store Products table


		$product                 = new stdClass();
		$product->joomla_item_id = $db->insertid();

		//		FIX j4 WORKFLOWS
		$object            = new stdClass();
		$object->item_id   = $product->joomla_item_id;
		$object->stage_id  = 1;
		$object->extension = 'com_content.article';

		$db->insertObject('#__workflow_associations', $object);


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
		$product->weight_unit   = $data->json->getString('weight_unit');
		$product->sku           = $data->json->getString('sku');
		$product->discount_type = $data->json->getString('discount_type');


		if (!$db->insertObject('#__protostore_product', $product))
		{
			return null;
		}

		// Create the Tags

		if ($tags = $data->json->getString('tags'))
		{
			TagFactory::saveTags($product->joomla_item_id, $tags);
		}


		return self::get($product->joomla_item_id);

	}


	/**
	 * @param   Product  $product
	 *
	 * @return bool
	 *
	 * @throws Exception
	 * @since 2.0
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

		$result = $db->updateObject('#__protostore_product', $insertProduct, 'joomla_item_id');

		if ($result)
		{
			// now do the Joomla Item

			$jresult = $db->updateObject('#__content', $product->joomlaItem, 'id');

			$j_item_id = $db->insertid();

			if ($jresult)
			{
				// now do variants & checkbox options

				self::commitVariants($product);
				self::commitOptions($product);

			}


			// now do TAGS

			if ($tags = $product->tags)
			{
				TagFactory::saveTags($product->joomla_item_id, $tags);
			}
			else
			{
				TagFactory::clearTags($product->joomla_item_id);
			}

		}


		// now do custom fields
		foreach ($product->custom_fields as $field)
		{
			// delete current field value
			$query = $db->getQuery(true);

			$conditions = array(
				$db->quoteName('item_id') . ' = ' . $db->quote($product->joomla_item_id),
				$db->quoteName('field_id') . ' = ' . $db->quote($field['id'])
			);

			$query->delete($db->quoteName('#__fields_values'));
			$query->where($conditions);

			$db->setQuery($query);
			$db->execute();

			// insert value back:

			$object           = new stdClass();
			$object->field_id = $field['id'];
			$object->item_id  = $product->joomla_item_id;
			$object->value    = $field['value'];

			$db->insertObject('#__fields_values', $object);

		}

		return true;

	}

	/**
	 * @param   Product  $product
	 *
	 *
	 * @throws Exception
	 * @since 2.0
	 */


	public static function commitOptions(Product $product): void
	{


		$db = Factory::getDbo();

		// check that there are options set
		if ($product->options)
		{
			// ok we have options - iterate them and either insert or update

			/** @var Productoption $option */
			foreach ($product->options as $option)
			{

				// convert to object to satisfy Joomla's DB system
				$option = (object) $option;


				//process value
				if (property_exists($option, 'modifier_valueFloat'))
				{
					$option->modifier_value = CurrencyFactory::toInt($option->modifier_valueFloat);
					unset($option->modifier_valueFloat);
				}
				// set the "product_id"
				$option->product_id = $product->joomla_item_id;

				// check if we have a new option by checking if the id is "0"
				if ($option->id == 0)
				{

					// sometimes the oprion is created and then set to "delete" by the user, so check that:
					if (!$option->delete)
					{

						// unset delete value, since Joomla's DB system doesn't know what to do when there's no coloumn for this node.
						unset($option->delete);

						// the option id is 0 and the "delete" value is false, that means this is a new option, run insert
						$db->insertObject('#__protostore_product_option', $option);

					}

				}
				else
				{

					if (property_exists($option, 'delete'))
					{
						// the option id is already set, check if this option is set to "delete"
						if (!$option->delete)
						{

							// unset delete value, since Joomla's DB system doesn't know what to do when there's no coloumn for this node.
							unset($option->delete);

							// the option id is already set and the "delete" value is false, so run update
							$db->updateObject('#__protostore_product_option', $option, 'id');

						}
						else
						{
							// the "delete" value is true, so remove:
							$query      = $db->getQuery(true);
							$conditions = array(
								$db->quoteName('id') . ' = ' . $db->quote($option->id)
							);

							$query->delete($db->quoteName('#__protostore_product_option'));
							$query->where($conditions);

							$db->setQuery($query);

							$db->execute();
						}

					}
					else
					{
						// update
						unset($option->modifier_value_translated);
						$db->updateObject('#__protostore_product_option', $option, 'id');
					}
				}


			}
		}
		else
		{
			// remove all current options

			$query      = $db->getQuery(true);
			$conditions = array(
				$db->quoteName('product_id') . ' = ' . $db->quote($product->joomla_item_id)
			);

			$query->delete($db->quoteName('#__protostore_product_option'));
			$query->where($conditions);

			$db->setQuery($query);

			$db->execute();

		}


	}


	/**
	 * @param   Product  $product
	 *
	 * @return bool
	 *
	 * @throws Exception
	 * @since 2.0
	 */

	public static function commitVariants(Product $product): bool
	{


		// init $variantIds - this array holds the ids for all the list variants in the current request - used for deleting removed variants.
		$variantIds = array();

		$db = Factory::getDbo();

		foreach ($product->variants as $variant)
		{
			// get the id for removal function later.
			$variantIds[] = $variant['id'];

			// check if this variant exists

			$query = $db->getQuery(true);

			$query->select('*');
			$query->from($db->quoteName('#__protostore_product_variant'));
			$query->where($db->quoteName('id') . ' = ' . $db->quote($variant['id']));

			$db->setQuery($query);

			$result = $db->loadObject();

			$object = new stdClass();
			if ($result)
			{
				//exists... update

				$object->id         = $variant['id'];
				$object->product_id = $product->joomla_item_id;
				$object->name       = $variant['name'];

				$db->updateObject('#__protostore_product_variant', $object, 'id');

			}
			else
			{
				// does not exist... insert
				$object->id         = 0;
				$object->product_id = $product->joomla_item_id;
				$object->name       = $variant['name'];

				$db->insertObject('#__protostore_product_variant', $object);


			}
			// now labels.

			self::saveLabels($variant);
		}

		// now delete all removed variants
		self::removeDeletedVariants($product->joomla_item_id, $variantIds);


		// now add/update the actual variantList data!

//		//for cleanup of removed variants & labels... collect the label_ids in an array for later use:
//		$variantListLabelIds = array();
//
//
//		foreach ($product->variantList as $variantListItem)
//		{
//
//
//			//check if the item already exists in the db
//			$query = $db->getQuery(true);
//
//			$query->select('*');
//			$query->from($db->quoteName('#__protostore_product_variant_data'));
//			$query->where($db->quoteName('id') . ' = ' . $db->quote($variantListItem['id']));
//
//			$db->setQuery($query);
//
//			$result = $db->loadObject();
//
//
//			// init the object for updating or inserting
//			$object             = new stdClass();
//			$object->product_id = $product->joomla_item_id;
//			$object->label_ids  = $variantListItem['label_ids'];
//			$object->price      = CurrencyFactory::toInt($variantListItem['price']);
//			$object->stock      = $variantListItem['stock'];
//			$object->sku        = $variantListItem['sku'];
//			$object->active     = ($variantListItem['active'] ? 1 : 0);
//			$object->default    = ($variantListItem['default'] ? 1 : 0);
//
//			if ($result)
//			{
//				// if so, update
//				$object->id = $variantListItem['id'];
//				$db->updateObject('#__protostore_product_variant_data', $object, 'id');
//			}
//			else
//			{
//				//if not, insert
//				$object->id = 0;
//				$db->insertObject('#__protostore_product_variant_data', $object);
//			}
//
//			//for cleanup of removed variants & labels... collect the ids in an array for later use:
////			$variantListLabelIds[] = explode(',', $variantListItem['label_ids']);
//			$variantListLabelIds[] = $variantListItem['label_ids'];
//
//
//		}

//		self::removeDeletedVariantListItems($product->joomla_item_id, $variantListLabelIds);


		return true;


	}


	/**
	 * @param   int    $product
	 * @param   array  $variantIds
	 *
	 * @return void
	 *
	 * @since 2.0
	 */

	private static function removeDeletedVariants(int $j_item_id, array $variantIds): void
	{

		$db = Factory::getDbo();

		$query = $db->getQuery(true);

		$query->select('*');
		$query->from($db->quoteName('#__protostore_product_variant'));
		$query->where($db->quoteName('product_id') . ' = ' . $db->quote($j_item_id));

		$db->setQuery($query);

		$currentVariantList = $db->loadObjectList();

		// iterate them and check if the id from the table is in the array

		foreach ($currentVariantList as $currentVariant)
		{

			if (in_array($currentVariant->id, $variantIds))
			{
				// if so, continue
				continue;
			}
			else
			{

				// if not, delete
				$query      = $db->getQuery(true);
				$conditions = array(
					$db->quoteName('id') . ' = ' . $db->quote($currentVariant->id)
				);
				$query->delete($db->quoteName('#__protostore_product_variant'));
				$query->where($conditions);
				$db->setQuery($query);
				$db->execute();

				//now remove labels too
				$query      = $db->getQuery(true);
				$conditions = array(
					$db->quoteName('variant_id') . ' = ' . $db->quote($currentVariant->id)
				);
				$query->delete($db->quoteName('#__protostore_product_variant_label'));
				$query->where($conditions);
				$db->setQuery($query);
				$db->execute();


			}

		}

	}

	/**
	 * @param   array  $variant
	 * @param   array  $labelIds
	 *
	 * @return void
	 *
	 * @since 2.0
	 */

	private static function removeDeletedVariantLabels(array $variant, array $labelIds): void
	{

		// create an array of all the label ids from the request ^^ (these are the ones that now exist on the product) - $labelIds
		// get all entries for this variant_id in an objectlist

		$db    = Factory::getDbo();
		$query = $db->getQuery(true);

		$query->select('*');
		$query->from($db->quoteName('#__protostore_product_variant_label'));
		$query->where($db->quoteName('variant_id') . ' = ' . $db->quote($variant['id']));

		$db->setQuery($query);

		$currentLabels = $db->loadObjectList();

		// iterate them and check if the id from the table is in the array

		foreach ($currentLabels as $currentLabel)
		{

			if (in_array($currentLabel->id, $labelIds))
			{
				// if so, continue
				continue;
			}
			else
			{
				// if not, delete
				$query      = $db->getQuery(true);
				$conditions = array(
					$db->quoteName('id') . ' = ' . $db->quote($currentLabel->id)
				);
				$query->delete($db->quoteName('#__protostore_product_variant_label'));
				$query->where($conditions);
				$db->setQuery($query);
				$db->execute();
			}

		}

	}

	/**
	 * @param   int    $joomla_item_id
	 * @param   array  $variantListLabelIds
	 *
	 * @return void
	 *
	 * @since 2.0
	 */

	private static function removeDeletedVariantListItems(int $joomla_item_id, array $variantListLabelIds): void
	{

		// create an array of all the item ids from the request ^^ (these are the ones that now exist on the product) - $variantListIds
		// get all entries for this product_id in an objectlist

		$db    = Factory::getDbo();
		$query = $db->getQuery(true);

		$query->select('*');
		$query->from($db->quoteName('#__protostore_product_variant_data'));
		$query->where($db->quoteName('product_id') . ' = ' . $db->quote($joomla_item_id));

		$db->setQuery($query);

		$currentItems = $db->loadObjectList();

		// iterate them and check if the id from the table is in the array

		foreach ($currentItems as $currentItem)
		{
			// make an array out of the current rows label_ids - for equality comparison
//			$currentItemLabelIdArray = explode(',', $currentItem->label_ids);


//			if (in_array($currentItemLabelIdArray, $variantListLabelIds))
			if (in_array($currentItem->label_ids, $variantListLabelIds))
			{
				// if so, continue
				continue;
			}
			else
			{
				// if not, delete
				$query      = $db->getQuery(true);
				$conditions = array(
					$db->quoteName('id') . ' = ' . $db->quote($currentItem->id)
				);
				$query->delete($db->quoteName('#__protostore_product_variant_data'));
				$query->where($conditions);
				$db->setQuery($query);
				$db->execute();
			}


		}

	}


	/**
	 * @param   int          $limit
	 * @param   int          $offset
	 * @param   string|null  $searchTerm
	 * @param   string|null  $optionType
	 *
	 * @return array
	 *
	 * @since 2.0
	 */

	public static function getOptionList(int $limit = 0, int $offset = 0, $searchTerm = null, string $optionType = null): ?array
	{

		$options = array();

		$db = Factory::getDbo();

		$query = $db->getQuery(true);

		$query->select('*');
		$query->from($db->quoteName('#__protostore_product_option'));

		if ($searchTerm)
		{
			$query->where($db->quoteName('name') . ' LIKE ' . $db->quote('%' . $searchTerm . '%'));

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


		return null;

	}

	/**
	 * @param   int  $j_item_id
	 *
	 *
	 * @return int
	 * @since 2.0
	 */

	public static function getCurrentStock(int $j_item_id): int
	{

		$db    = Factory::getDbo();
		$query = $db->getQuery(true);

		$query->select('stock');
		$query->from($db->quoteName('#__protostore_product'));
		$query->where($db->quoteName('joomla_item_id') . ' = ' . $db->quote($j_item_id));

		$db->setQuery($query);

		return $db->loadResult();

	}


	/**
	 * @param $joomla_item_id
	 *
	 * @return JoomlaItem
	 *
	 * @since 2.0
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

		return null;

	}

	/**
	 * @param   int  $j_item_id
	 *
	 * @return array|null
	 *
	 * @since 2.0
	 */


	public static function getCustomFields(int $j_item_id): ?array
	{

		return array();
	}


	/**
	 * @param   string  $type
	 * @param   int     $joomla_item_id
	 * @param   int     $catid
	 *
	 * @return string|null
	 *
	 * @since 2.0
	 */

	public static function getRoute(string $type, int $joomla_item_id, int $catid): ?string
	{

		switch ($type)
		{
			case 'category':
				return Route::_('index.php?option=com_content&view=category&layout=blog&id=' . $catid);
			default:
				// item
				return Route::_('index.php?option=com_content&view=article&id=' . $joomla_item_id . '&catid=' . $catid);
		}


	}


	/**
	 * @param $price
	 *
	 * @return BigDecimal
	 *
	 * @throws UnknownCurrencyException
	 * @since 2.0
	 */

	public static function getFloat($price): BigDecimal
	{

		return CurrencyFactory::toFloat($price);

	}


	/**
	 * @param   int  $price
	 *
	 * @return string
	 *
	 * @throws UnknownCurrencyException
	 * @since 2.0
	 */

	public static function getFormattedPrice(int $price): string
	{

		return CurrencyFactory::formatNumberWithCurrency($price);

	}

	/**
	 * @param   int  $price
	 *
	 * @return int
	 *
	 * @since 2.0
	 */

	public static function getPriceWithTax(int $price): int
	{

		return TaxFactory::getTotalDefaultTax($price) + $price;

	}


	/**
	 * @param $category_id
	 *
	 * @return string|null
	 *
	 * @since 2.0
	 */

	public static function getCategoryName($category_id): ?string
	{

		$categories   = Categories::getInstance('content');
		$categoryNode = $categories->get($category_id);   // returns the category node for category with id=12

		return $categoryNode->title;

	}


	/**
	 * @param   int  $joomla_item_id
	 *
	 * @return array
	 *
	 * @since 2.0
	 */

	public static function getTags(int $joomla_item_id): array
	{

		return TagFactory::getTags($joomla_item_id);

	}


	/**
	 * @param   int  $itemid
	 * @param   int  $catid
	 *
	 * @return array
	 *
	 * @since 2.0
	 */

	public static function getAvailableCustomFields(int $itemid, int $catid = 0): array
	{

		$db = Factory::getDbo();

		$availableFields = array();

		$query = $db->getQuery(true);

		$query->select('field_id');
		$query->from($db->quoteName('#__fields_categories'));
		$query->where($db->quoteName('category_id') . ' = ' . $db->quote($catid));

		$db->setQuery($query);

		$results = $db->loadColumn();


		if ($results)
		{


			$query = $db->getQuery(true);

			$query->select('*');
			$query->from($db->quoteName('#__fields'));
			$query->where($db->quoteName('context') . ' = ' . $db->quote('com_content.article'));
			$query->where($db->quoteName('state') . ' = 1');
			$query->where($db->quoteName('type') . ' IN (\'text\', \'list\', \'radio\', \'textarea\', \'media\', \'editor\')');
			$query->where($db->quoteName('id') . ' IN (' . implode(",", $results) . ')');
			$query->order('ordering ASC');

			$db->setQuery($query);

			$fields = $db->loadObjectList();

			foreach ($fields as $field)
			{

				$availableFields[] = new Customfield($field, $itemid);
			}


		}

		//now get fields that have no category i.e. set to "all"

		$query = $db->getQuery(true);

		$query->select('field_id');
		$query->from($db->quoteName('#__fields_categories'));

		$db->setQuery($query);

		$listedIds = $db->loadColumn();

		if (!$listedIds)
		{
			$listedIds = array(0);
		}
		$query = $db->getQuery(true);

		$query->select('*');
		$query->from($db->quoteName('#__fields'));
		$query->where($db->quoteName('context') . ' = ' . $db->quote('com_content.article'));
		$query->where($db->quoteName('state') . ' = 1');
		$query->where($db->quoteName('type') . ' IN (\'text\', \'list\', \'radio\', \'textarea\', \'media\', \'editor\')');
		$query->where($db->quoteName('id') . ' NOT IN (' . implode(",", $listedIds) . ')');
		$query->order('ordering ASC');

		$db->setQuery($query);

		$fields = $db->loadObjectList();

		foreach ($fields as $field)
		{

			$availableFields[] = new Customfield($field, $itemid);
		}


		return $availableFields;


	}


	/**
	 * @param   int  $custom_field_id
	 * @param   int  $itemId
	 *
	 * @return mixed|null
	 *
	 * @since 2.0
	 */

	public static function setCustomFieldValue(int $custom_field_id, int $itemId)
	{

		$db = Factory::getDbo();

		$query = $db->getQuery(true);

		$query->select('value');
		$query->from($db->quoteName('#__fields_values'));
		$query->where($db->quoteName('field_id') . ' = ' . $db->quote($custom_field_id));
		$query->where($db->quoteName('item_id') . ' = ' . $db->quote($itemId));

		$db->setQuery($query);

		return $db->loadResult();


	}


	/**
	 * @param   int  $j_item_id
	 *
	 * @return null|array
	 *
	 * @throws UnknownCurrencyException
	 * @since 2.0
	 */

	public static function getOptions(int $j_item_id): ?array
	{

		return ProductoptionFactory::getProductOptions($j_item_id);

	}

	/**
	 * @param   Input  $data
	 *
	 * @return bool
	 *
	 * @since 2.0
	 */

	public static function togglePublishedFromInputData(Input $data)
	{

		$response = false;

		$db = Factory::getDbo();

		$items = $data->json->get('items', '', 'ARRAY');

		/** @var Product $item */
		foreach ($items as $item)
		{

			$query = 'UPDATE ' . $db->quoteName('#__content') . ' SET ' . $db->quoteName('state') . ' = IF(' . $db->quoteName('state') . '=1, 0, 1) WHERE ' . $db->quoteName('id') . ' = ' . $db->quote($item['joomla_item_id']) . ';';
			$db->setQuery($query);
			$result = $db->execute();

			if ($result)
			{
				$response = true;
			}

		}

		return $response;
	}

	/**
	 * @param   Input  $data
	 *
	 * @return bool
	 *
	 * @since 2.0
	 */


	public static function batchUpdateCategory(Input $data)
	{


		$response = false;

		$db = Factory::getDbo();

		$items       = $data->json->get('items', '', 'ARRAY');
		$category_id = $data->json->get('category_id', '', 'INT');

		/** @var Product $item */
		foreach ($items as $item)
		{


			$object        = new stdClass();
			$object->id    = $item['joomla_item_id'];
			$object->catid = $category_id;
			$result        = $db->updateObject('#__content', $object, 'id');

			if ($result)
			{
				$response = true;
			}

		}

		return $response;

	}

	/**
	 * @param   Input  $data
	 *
	 * @return bool
	 *
	 * @since 2.0
	 */

	public static function batchUpdateStock(Input $data): bool
	{
		$response = false;

		$db = Factory::getDbo();

		$items = $data->json->get('items', '', 'ARRAY');
		$stock = $data->json->get('stock', '', 'INT');

		/** @var Product $item */
		foreach ($items as $item)
		{

			$object                 = new stdClass();
			$object->joomla_item_id = $item['joomla_item_id'];
			$object->stock          = $stock;
			$result                 = $db->updateObject('#__protostore_product', $object, 'joomla_item_id');

			if ($result)
			{
				$response = true;
			}

		}

		return $response;


	}


	/**
	 * @param $image
	 *
	 * @return false|string
	 *
	 * @since 2.0
	 */

	public static function getImagePath($image)
	{

		if ($image)
		{
			return Uri::root() . $image;
		}

		return false;


	}

	/**
	 * @param   Input  $data
	 *
	 * @return array
	 *
	 * @since 2.0
	 */

	public static function getRefreshedVariantData(Input $data): array
	{

		$j_item_id = $data->json->getInt('j_item_id');

		$response = array();

		$product = self::get($j_item_id);

		$response['variants']    = $product->variants;
		$response['variantList'] = $product->variantList;

		return $response;

	}

	/**
	 * @param   int  $j_item_id
	 *
	 * @return bool
	 *
	 * @since 2.0
	 */


	private static function variantScorchedEarth(int $j_item_id): bool
	{

		$response = true;

		$db = Factory::getDbo();

		$query = $db->getQuery(true);

		$conditions = array(
			$db->quoteName('product_id') . ' = ' . $db->quote($j_item_id)
		);

		$query->delete($db->quoteName('#__protostore_product_variant'));
		$query->where($conditions);

		$db->setQuery($query);

		$result = $db->execute();

		if (!$result)
		{
			$response = false;
		}

		$query = $db->getQuery(true);

		$conditions = array(
			$db->quoteName('product_id') . ' = ' . $db->quote($j_item_id)
		);

		$query->delete($db->quoteName('#__protostore_product_variant_label'));
		$query->where($conditions);

		$db->setQuery($query);

		$result = $db->execute();

		if (!$result)
		{
			$response = false;
		}

		$query = $db->getQuery(true);

		$conditions = array(
			$db->quoteName('product_id') . ' = ' . $db->quote($j_item_id)
		);

		$query->delete($db->quoteName('#__protostore_product_variant_data'));
		$query->where($conditions);

		$db->setQuery($query);

		$result = $db->execute();

		if (!$result)
		{
			$response = false;
		}

		return $response;
	}


	/**
	 *
	 * This function takes the variant data, and the Product id as POST $data variables.
	 * It saves the variants and the corresponding labels.
	 * It then runs the Cartesian Product over the variant labels.
	 * Then it saves the Variant List data
	 *
	 *
	 * @param   Input  $data
	 *
	 * @return bool
	 *
	 * @throws Exception
	 * @since 2.0
	 */


	public static function saveVariantsFromInputData(Input $data): bool
	{
		$db = Factory::getDbo();

		$variants = $data->json->get('variants', '', 'ARRAY');


		$j_item_id = $data->json->getInt('itemid');

		//check if variants exist if not delete.
		if (empty($variants))
		{
			return self::variantScorchedEarth($j_item_id);

		}

		$base_price = $data->json->getFloat('base_price');


		if ($base_price)
		{
			$base_price = CurrencyFactory::toInt($base_price);
		}
		else
		{
			$base_price = 0;
		}


		foreach ($variants as $variant)
		{


			// check to see if the id is in the table... if so, update. If not, insert... usual shit.

			$query = $db->getQuery(true);

			$query->select('*');
			$query->from($db->quoteName('#__protostore_product_variant'));
			$query->where($db->quoteName('id') . ' = ' . $db->quote($variant['id']));

			$db->setQuery($query);

			$result = $db->loadObject();


			if ($result)
			{
				// update
				$updateVariant             = new stdClass();
				$updateVariant->id         = $variant['id'];
				$updateVariant->product_id = $variant['product_id'];
				$updateVariant->name       = $variant['name'];
				$db->updateObject('#__protostore_product_variant', $updateVariant, 'id');

			}

			else
			{
				// insert
				$object             = new stdClass();
				$object->id         = 0;
				$object->product_id = $variant['product_id'];
				$object->name       = $variant['name'];

				$db->insertObject('#__protostore_product_variant', $object);

				// since this is a new variant... set the id.
				$variant['id'] = $db->insertid();

			}

			// get the id for removal function later.
			$variantIds[] = $variant['id'];


			// ok... now that the variants are saved... add the labels.
			self::saveLabels($variant);

		}
		self::removeDeletedVariants($j_item_id, $variantIds);

		// now the fancy bit... run the Cartesian of all the variant labels

		$labelArrays = array();


		// todo - Ahhh... this iteration doesn't contain the new label ids... i was lucky to spot this bug... need to get the new labels!!


		$variants = self::getVariantData($j_item_id);

		foreach ($variants->variants as $variant)
		{
			$labelArrays[] = $variant->labels;
		}

		$cartesianProduct = self::cartesian($labelArrays);

		$dbRowLabelIdsStringArray = array();

		foreach ($cartesianProduct as $node)
		{

			$dbRowLabelIds = array();
			// so $node is an array of the cartesian product of a particular selection.
			// iterate over $node to create the labelIds required for the db processing:

			foreach ($node as $var)
			{
				$dbRowLabelIds[] = $var->id;
			}

			// get the comma separated string
			$dbRowLabelIdsString = implode(',', $dbRowLabelIds);

			// for garbage collection
			$dbRowLabelIdsStringArray[] = $dbRowLabelIdsString;


			// now test the DB for update or insert

			$query = $db->getQuery(true);

			$query->select('*');
			$query->from($db->quoteName('#__protostore_product_variant_data'));
			$query->where($db->quoteName('label_ids') . ' = ' . $db->quote($dbRowLabelIdsString));
			$query->where($db->quoteName('product_id') . ' = ' . $db->quote($j_item_id));

			$db->setQuery($query);

			$result = $db->loadObject();

			if (!$result)
			{
				//insert

				$object             = new stdClass();
				$object->id         = 0;
				$object->product_id = $j_item_id;
				$object->label_ids  = $dbRowLabelIdsString;
				$object->price      = $base_price;
				$object->stock      = 0;
				$object->sku        = 0;
				$object->active     = 1;
				$object->default    = 0;

				$db->insertObject('#__protostore_product_variant_data', $object);

			}
			else
			{
				// todo - need to run an update!
//
			}


		}

		// set the valiantList data
		self::updateVariantValuesFromInputData($data);

		// do garbage collection:
		self::removeDeletedVariantListItems($j_item_id, $dbRowLabelIdsStringArray);

		// set a default if there isn't one
		self::setDefaultVariant($j_item_id);


		return true;

	}

	/**
	 * @param   Input  $data
	 *
	 * @return bool
	 *
	 * @since 2.0
	 */


	public static function updateVariantValuesFromInputData(Input $data): bool
	{

		$db = Factory::getDbo();


		$variantList = $data->json->get('variantList', '', 'ARRAY');


		foreach ($variantList as $variant)
		{


			$price = CurrencyFactory::toInt($variant['price']);


			$object          = new stdClass();
			$object->id      = $variant['id'];
			$object->price   = $price;
			$object->stock   = $variant['stock'];
			$object->sku     = $variant['sku'];
			$object->active  = ($variant['active'] ? 1 : 0);
			$object->default = ($variant['default'] ? 1 : 0);

			$db->updateObject('#__protostore_product_variant_data', $object, 'id');
		}

		return true;

	}

	/**
	 * @param   int  $j_item_id
	 *
	 *
	 * @return bool
	 * @since 2.0
	 *
	 */


	public static function setDefaultVariant(int $j_item_id): bool
	{

		// check if there is already a default, if so... just return

		$db    = Factory::getDbo();
		$query = $db->getQuery(true);

		$query->select('*');
		$query->from($db->quoteName('#__protostore_product_variant_data'));
		$query->where($db->quoteName('product_id') . ' = ' . $db->quote($j_item_id));
		$query->where($db->quoteName('default') . ' = ' . $db->quote(1));

		$db->setQuery($query);

		$result = $db->loadObjectList();

		if ($result)
		{
			return true;
		}
		else
		{
			// if not, set the first instance as default

			$query = $db->getQuery(true);

			$query->select('*');
			$query->from($db->quoteName('#__protostore_product_variant_data'));
			$query->where($db->quoteName('product_id') . ' = ' . $db->quote($j_item_id));
			$query->setLimit('1');
			$db->setQuery($query);

			$first = $db->loadObject();

			$first->default = 1;

			return $db->updateObject('#__protostore_product_variant_data', $first, 'id');
		}


	}

	/**
	 *
	 * This function saves the labels of the given variant
	 *
	 * @param   array  $variant
	 *
	 *
	 * @since 2.0
	 */

	private static function saveLabels(array $variant)
	{

		$db = Factory::getDbo();

		$labelIds = array();

		foreach ($variant['labels'] as $label)
		{

			$query = $db->getQuery(true);

			$query->select('*');
			$query->from($db->quoteName('#__protostore_product_variant_label'));
			$query->where($db->quoteName('id') . ' = ' . $db->quote($label['id']));

			$db->setQuery($query);

			$result = $db->loadObject();

			if ($result)
			{
				// update
				$updateLabel             = new stdClass();
				$updateLabel->id         = $label['id'];
				$updateLabel->variant_id = $variant['id'];
				$updateLabel->product_id = $variant['product_id'];
				$updateLabel->name       = $label['name'];
				$db->updateObject('#__protostore_product_variant_label', $updateLabel, 'id');

			}
			else
			{
				// insert
				$object             = new stdClass();
				$object->id         = 0;
				$object->variant_id = $variant['id'];
				$object->product_id = $variant['product_id'];
				$object->name       = $label['name'];

				$db->insertObject('#__protostore_product_variant_label', $object);

				// get the new label id
				$label['id'] = $db->insertid();

			}

			$labelIds[] = $label['id'];

		}

		// remove deleted labels
		self::removeDeletedVariantLabels($variant, $labelIds);


	}

	/**
	 * @param   array  $input
	 *
	 * @return array|array[]
	 *
	 * @since 2.0
	 */


	private static function cartesian(array $input): array
	{
		$result = array(array());

		foreach ($input as $key => $values)
		{
			$append = array();

			foreach ($result as $product)
			{
				foreach ($values as $item)
				{
					$product[$key] = $item;
					$append[]      = $product;
				}
			}

			$result = $append;
		}

		return $result;

	}


	/**
	 * @param   int  $j_item_id
	 *
	 * @return Variant
	 *
	 * @since 2.0
	 */

	public static function getVariantData(int $j_item_id): Variant
	{

		// init

		/** @var Variant $variantObject */
		$variantObject = new stdClass;


		// get the list of variants for this product
		$db    = Factory::getDbo();
		$query = $db->getQuery(true);

		$query->select('*');
		$query->from($db->quoteName('#__protostore_product_variant'));
		$query->where($db->quoteName('product_id') . ' = ' . $db->quote($j_item_id));

		$db->setQuery($query);

		$variants = $db->loadObjectList();

		$variantObject->variants = $variants;


		// now get the array of product prices(etc) for each combination of variant labels.

		$db    = Factory::getDbo();
		$query = $db->getQuery(true);

		$query->select('*');
		$query->from($db->quoteName('#__protostore_product_variant_data'));
		$query->where($db->quoteName('product_id') . ' = ' . $db->quote($j_item_id));

		$db->setQuery($query);

		$list = $db->loadObjectList();

		$variantObject->variantList = $list;

		return new Variant($variantObject);


	}


	/**
	 *
	 * Takes the raw db data for a variant and processes the data to make it useful for the UI:
	 *
	 * * Processes "1" and "0" to true and false
	 * * Gets the namedLabel... i.e. "Small / Red" etc.
	 * * sorts out Brick numbers
	 *
	 * @param   array  $variantList
	 *
	 * @return array
	 *
	 * @throws UnknownCurrencyException
	 * @since 2.0
	 *
	 */


	public static function processVariantData(array $variantList): array
	{


		foreach ($variantList as $variant)
		{

			// namedLabel
			if (isset($variant->label_ids))
			{
				$db       = Factory::getDbo();
				$labelIds = explode(',', $variant->label_ids);

				$namedLabels = array();
				foreach ($labelIds as $labelId)
				{

					$query = $db->getQuery(true);

					$query->select('name');
					$query->from($db->quoteName('#__protostore_product_variant_label'));
					$query->where($db->quoteName('id') . ' = ' . $db->quote($labelId));

					$db->setQuery($query);

					$namedLabels[] = $db->loadResult();

				}

				$variant->namedLabel = implode(' / ', $namedLabels);


			}

			// prices
			if (isset($variant->price))
			{
				$variant->priceInt = $variant->price;
				$variant->price    = CurrencyFactory::toFloat($variant->price);
			}

			// booleans
			$variant->default = $variant->default == 1;
			$variant->active  = $variant->active == 1;

		}

		return $variantList;


	}

	/**
	 * @param   int  $j_item_id
	 *
	 * @return array|mixed
	 *
	 * @since 2.0
	 */

	public static function getLabels(int $j_item_id)
	{

		$db    = Factory::getDbo();
		$query = $db->getQuery(true);

		$query->select('*');
		$query->from($db->quoteName('#__protostore_product_variant_label'));
		$query->where($db->quoteName('product_id') . ' = ' . $db->quote($j_item_id));

		$db->setQuery($query);

		return $db->loadObjectList();


	}

	/**
	 *
	 * this function adds the labels array to the variants
	 *
	 * @param   array  $variants
	 *
	 * @return array
	 *
	 * @since 2.0
	 */

	public static function attachVariantLabels(array $variants): array
	{

		// get the item id from the first variant
		$j_item_id = $variants[0]->product_id;

		$labels = self::getLabels($j_item_id);

		foreach ($variants as $variant)
		{

			foreach ($labels as $label)
			{
				if ($label->variant_id == $variant->id)
				{
					$variant->labels[] = $label;
				}


			}

		}

		return $variants;

	}


	/**
	 *
	 * This function takes in the product id and the users selected variants and processes this data in order to return:
	 *
	 * 1. the price, stock and sku for the selection
	 * 2. the active variants list, to allow the UI to update with new dropdowns if there is no stock or if the options is inactive.
	 *
	 * @param   int    $joomla_item_id
	 * @param   array  $selected
	 *
	 * @return array
	 * @throws UnknownCurrencyException
	 * @since 2.0
	 */

	public static function getSelectedVariant(int $joomla_item_id, array $selected): array
	{

		// init

		$response = array();


		// get the variant data for this product
		$productVariants = self::getVariantData($joomla_item_id);

		// get the actual variants list to allow us to grab the price, stock and sku - set it as a workable array using json_decode
		$productVariantsList = json_decode($productVariants->variantList);


		// iterate over the variants list and grab the price, stock and sku
		/** @var VariantListItem $productVariant */
		foreach ($productVariantsList as $productVariant)
		{
			if ($productVariant->identifier == $selected)
			{
				$response['identifier'] = $productVariant->identifier;
				$response['name']       = $productVariant->name;
				$response['priceInt']   = $productVariant->priceInt;
				$response['price']      = CurrencyFactory::translate(18500);
				$response['stock']      = $productVariant->stock;
				$response['sku']        = $productVariant->sku;
				$response['active']     = $productVariant->active;
			}


		}


		return $response;

	}


	/**
	 * @param   int    $joomla_item_id
	 * @param   array  $selected
	 *
	 * @return bool
	 *
	 * @since 2.0
	 */


	public static function checkVariantAvailability(int $joomla_item_id, array $selected): ?array
	{

		// init
		$response           = array();
		$response['active'] = true;

		$selected = implode(',', $selected);

		// get the product
		$product = self::get($joomla_item_id);

		// get the chosen variant selection
		$db    = Factory::getDbo();
		$query = $db->getQuery(true);

		$query->select('*');
		$query->from($db->quoteName('#__protostore_product_variant_data'));
		$query->where($db->quoteName('label_ids') . ' = ' . $db->quote($selected));

		$db->setQuery($query);

		// get the object
		$result = $db->loadObject();
		if ($result && is_object($result))
		{

			$selectedVariant = new SelectedVariant($result);
		}
		else
		{
			return null;
		}


		// check the stock
		if ($product->manage_stock == "1")
		{
			if ($selectedVariant->stock == 0)
			{
				$response['active'] = false;
				$response['reason'] = "oos";

			}
		}

		// check the active state
		if ($selectedVariant->active == "0")
		{
			$response['active'] = false;
			$response['reason'] = "not_active";
		}

		return $response;

	}

	/**
	 * @param   array  $variantList
	 *
	 *
	 * @return array
	 * @since 2.0
	 */

	public static function getVariantDefault(array $variantList): array
	{

		$default = array();

		foreach ($variantList as $variant)
		{
			if ($variant->default == 1)
			{
				$default = explode(',', $variant->label_ids);
			}


		}

		return $default;


	}


	/**
	 * @param   Input  $data
	 *
	 * @return bool
	 *
	 * @since 2.0
	 */

	public static function trashFromInputData(Input $data): bool
	{

		$db = Factory::getDbo();

		$items = $data->json->get('items', '', 'ARRAY');

		/** @var Product $item */
		foreach ($items as $item)
		{
			$item = (object) $item;

			$query      = $db->getQuery(true);
			$conditions = array(
				$db->quoteName('id') . ' = ' . $db->quote($item->id)
			);
			$query->delete($db->quoteName('#__protostore_product'));
			$query->where($conditions);
			$db->setQuery($query);
			$db->execute();

		}


		foreach ($items as $item)
		{

			$item = (object) $item;

			$query      = $db->getQuery(true);
			$conditions = array(
				$db->quoteName('id') . ' = ' . $db->quote($item->joomla_item_id)
			);
			$query->delete($db->quoteName('#__content'));
			$query->where($conditions);
			$db->setQuery($query);
			$db->execute();

		}

		return true;

	}


	/**
	 * @param $id
	 *
	 * @return File|null
	 *
	 * @since 2.0
	 */


	public static function getFile($id): ?File
	{

		$db    = Factory::getDbo();
		$query = $db->getQuery(true);

		$query->select('*');
		$query->from($db->quoteName('#__protostore_product_file'));
		$query->where($db->quoteName('id') . ' = ' . $db->quote($id));

		$db->setQuery($query);

		$result = $db->loadObject();
		if ($result && is_object($result))
		{


			return new File($result);
		}

		return null;

	}


	/**
	 * @param   int  $product_id
	 *
	 * @return array|null
	 *
	 * @since 2.0
	 */

	public static function getFiles(int $product_id): ?array
	{

		$db    = Factory::getDbo();
		$query = $db->getQuery(true);

		$query->select('*');
		$query->from($db->quoteName('#__protostore_product_file'));
		$query->where($db->quoteName('product_id') . ' = ' . $db->quote($product_id));

		$db->setQuery($query);

		$results = $db->loadObjectList();

		$files = array();

		if ($results)
		{
			foreach ($results as $result)
			{
				$files[] = new File($result);

			}

			return $files;
		}


		return null;

	}

	/**
	 * @param   int  $type
	 *
	 * @return string|void
	 *
	 * @since 2.0
	 */

	public static function getFileStabilityLevelString(int $type)
	{

		switch ($type)
		{
			case 1;
				return Text::_('COM_PROTOSTORE_FILE_STABILITY_TYPE_ALPHA');

			case 2;
				return Text::_('COM_PROTOSTORE_FILE_STABILITY_TYPE_BETA');

			case 3;
				return Text::_('COM_PROTOSTORE_FILE_STABILITY_TYPE_RELEASE_CANDIDATE');

			case 4;
				return Text::_('COM_PROTOSTORE_FILE_STABILITY_TYPE_RELEASE_STABLE');

		}

	}

	/**
	 * @param   Input  $data
	 *
	 * @return File
	 *
	 * @since 2.0
	 */


	public static function saveFileFromInputData(Input $data): ?File
	{

		$db = Factory::getDbo();

		// if there's no item id, then we need to create a new product
		if ($data->json->getInt('fileid'))
		{
			$current = self::getFile($data->json->getInt('fileid'));

			if ($current)
			{

				$update = new stdClass();

				$update->id                = $current->id;
				$update->download_access   = $data->json->getInt('download_access', $current->download_access);
				$update->product_id        = $data->json->getInt('product_id', $current->product_id);
				$update->filename          = $data->json->getString('filename', $current->filename);
				$update->filename_obscured = $data->json->getString('filename_obscured', $current->filename_obscured);
				$update->isjoomla          = $data->json->getInt('isjoomla', $current->isjoomla);
				$update->version           = $data->json->getString('version', $current->version);
				$update->type              = $data->json->getString('type', $current->type);
				$update->stability_level   = $data->json->getInt('stability_level', $current->stability_level);
				$update->php_min           = $data->json->getFloat('php_min', $current->php_min);
				$update->download_access   = $data->json->getInt('download_access', $current->download_access);
				$update->published         = $data->json->getInt('published', $current->published);

				$db->updateObject('#__protostore_product_file', $update, 'id');

				return self::getFile($current->id);
			}
			else
			{
				return self::createNewFile($data);
			}

		}

		return self::createNewFile($data);
	}

	/**
	 * @param   Input  $data
	 *
	 * @return ?File
	 *
	 * @since 2.0
	 */


	public static function createNewFile(Input $data): ?File
	{
		$db = Factory::getDbo();

		$file = new stdClass();

		$file->id                = 0;
		$file->product_id        = $data->json->get('product_id');
		$file->filename          = $data->json->getString('filename');
		$file->filename_obscured = $data->json->getString('filename_obscured');
		$file->isjoomla          = $data->json->getInt('isjoomla');
		$file->version           = $data->json->getString('version');
		$file->type              = $data->json->getString('type');
		$file->stability_level   = $data->json->getInt('stability_level');
		$file->php_min           = $data->json->getFloat('php_min');
		$file->download_access   = $data->json->getInt('download_access', 1);
		$file->downloads         = 0;
		$file->published         = $data->json->getInt('published');
		$file->created           = Utilities::getDate();


		$result = $db->insertObject('#__protostore_product_file', $file);

		if ($result)
		{
			return self::getFile($db->insertid());
		}

		return null;

	}

	/**
	 * @param   Input  $data
	 *
	 * @return array|false
	 *
	 * @since 2.0
	 */


	public static function uploadFileFromInputData(Input $data)
	{

		// first, create the MD5's for folder creation

		$md5_1 = md5(uniqid());
		$md5_2 = md5(uniqid());
		$md5_3 = md5(uniqid());
		$md5_4 = md5(uniqid());

		// build the path
		$path = $md5_1 . '/' . $md5_2 . '/' . $md5_3 . '/' . $md5_4;

		// get the file from the POST data
		$file = $data->files->get('files');
		$file = $file[0];

		// is this needed these days?
		jimport('joomla.filesystem.file');

		// sluggify the filename
		$filename = JoomlaFile::makeSafe($file['name']);
		$src      = $file['tmp_name'];

		// create the destination
		$dest = JPATH_SITE . '/images/pro2store_files/' . $path . '/' . $filename;

		//Upload the file
		if (JoomlaFile::upload($src, $dest))
		{

			$response['uploaded']     = true;
			$response['path']         = $path;
			$response['relativepath'] = $path . '/' . $filename;
			$response['filename']     = $filename;
			$response['dest']         = $dest;


			return $response;
		}
		else
		{
			return false;
		}


	}

	/**
	 * @param   Input  $data
	 *
	 * @return bool
	 *
	 * @since 2.0
	 */


	public static function saveStockFromInputData(Input $data): bool
	{

		$db = Factory::getDbo();

		$itemId = $data->json->getInt('itemid');
		$stock  = $data->json->getInt('stock');


		$object                 = new stdClass();
		$object->joomla_item_id = $itemId;
		$object->stock          = $stock;

		$result = $db->updateObject('#__protostore_product', $object, 'joomla_item_id');

		if ($result)
		{
			return true;
		}

		return false;

	}

	/**
	 * @param   Input  $data
	 *
	 * @return bool
	 *
	 * @throws Exception
	 * @since 2.0
	 */

	public static function savePriceFromInputData(Input $data): bool
	{

		$db = Factory::getDbo();

		$itemId     = $data->json->getInt('itemid');
		$priceFloat = $data->json->getFloat('base_priceFloat');


		if ($priceFloat)
		{
			$base_price = CurrencyFactory::toInt($priceFloat);
		}
		else
		{
			$base_price = 0;
		}

		$object                 = new stdClass();
		$object->joomla_item_id = $itemId;
		$object->base_price     = $base_price;

		$result = $db->updateObject('#__protostore_product', $object, 'joomla_item_id');

		if ($result)
		{
			return true;
		}

		return false;

	}


	/**
	 * @param   Input  $data
	 *
	 *
	 * @since 2.0
	 */

	public static function export(Input $data)
	{

		$response = false;

		$db = Factory::getDbo();

		$items = $data->json->get('items', '', 'ARRAY');


		$date = new Date('now');

		$filename = "export." . $date->toISO8601() . ".csv";

		header('Content-Type: application/csv');
		header('Content-Disposition: attachment; filename="' . $filename . '";');

		// clean output buffer
		ob_end_clean();

		$handle = fopen('php://output', 'w');

		// use keys as column titles
		fputcsv($handle, array_keys($array['0']), ',');

		foreach ($array as $value)
		{
			fputcsv($handle, $value, ',');
		}

		fclose($handle);

		// flush buffer
		ob_flush();

		// use exit to get rid of unexpected output afterward
		exit();

	}

	/**
	 * @param   string  $teaserImage
	 * @param   string  $fullImage
	 *
	 *
	 * @return false|string
	 * @since 2.0
	 */

	private static function processImagesForSave($teaserImage, $fullImage)
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
	 * @since 2.0
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
