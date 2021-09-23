<?php
/**
 * @package     Pro2Store - Shortcodes
 *
 * @copyright   Copyright (C) 2021 Ray Lawlor - Pro2Store. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

use Joomla\CMS\Factory;

use Protostore\Order\Order;
use Protostore\Order\OrderedProduct;
use Protostore\Order\OrderFactory;

class PlgContentProtostore_shortcodes extends JPlugin
{


	/**
	 * @var $order Order
	 * @since 1.6
	 */
	private $order;


	/**
	 * Plugin that loads Pro2Store snippets within content
	 *
	 * @param   string    $context  The context of the content being passed to the plugin.
	 * @param   object   &$article  The article object.  Note $article->text is also available
	 * @param   mixed    &$params   The article params
	 * @param   integer   $page     The 'page' number
	 *
	 * @return  mixed   true if there is an error. Void otherwise.
	 *
	 * @throws Exception
	 * @since   1.6
	 */
	public function onContentPrepare($context, &$article, &$params, $page = 0)
	{
		// Don't run this plugin when the content is being indexed
		if ($context === 'com_finder.indexer')
		{
			return true;
		}


		// Simple performance check to determine whether bot should process further
		if (strpos($article->text, 'pro2store_') === false)
		{
			return true;
		}


		// Expression to search for (positions)
		$regex = '/{pro2store_(.*?)}/i';


		// Find all instances of plugin and put in $matches for loadposition
		// $matches[0] is full pattern match, $matches[1] is the position
		preg_match_all($regex, $article->text, $matches, PREG_SET_ORDER);

		// No matches, skip this
		if ($matches)
		{

			//ok there are matches... let's access the cookie.
			if ($this->order = $this->getOrder())
			{
				foreach ($matches as $match)
				{

					$output = $this->render($match[0]);

					// We should replace only first occurrence in order to allow positions with the same name to regenerate their content:
					if (($start = strpos($article->text, $match[0])) !== false)
					{
						$article->text = substr_replace($article->text, $output, $start, strlen($match[0]));
					}

				}
			}


		}

	}


	/**
	 * @throws Exception
	 * @since 1.6
	 */
	private function getOrder(): ?Order
	{
		$hash = Factory::getApplication()->input->get('confirmation');

		if (is_null($hash))
		{
			return null;
		}

		return OrderFactory::getOrderByHash($hash);

	}
	

	private function render($shortcode)
	{

		if (isset($this->order))
		{


			switch ($shortcode)
			{
				case '{pro2store_order_number}':
					return $this->order->order_number;
				case '{pro2store_order_total}':
					return $this->order->order_total;
				case '{pro2store_order_discount_total}':
					return $this->order->discount_total_formatted;
				case '{pro2store_customer_name}':
					return $this->order->customer_name;
				case '{pro2store_customer_address}':
					return $this->order->billing_address;
				case '{pro2store_customer_email}':
					return $this->order->customer_email;
				case '{pro2store_product_list_table}':
					return $this->getProductTable();
				case '{pro2store_payment_type}':
					return $this->order->payment_method;
				default:
					return '';
			}

		}


	}


	private function getProductTable()
	{

		$html = array();

		$html[] = '<table class="uk-table uk-table-striped"><tbody>';

		/** @var $product OrderedProduct $product */
		foreach ($this->order->ordered_products as $product)
		{
			$html[] = '<tr>';
			$html[] = '<td>' . $product->j_item_name . '</td>';
			$html[] = '<td> x' . $product->amount . '</td>';
			$html[] = '<td>' . $product->price_at_sale_formatted . '</td>';
			$html[] = '</tr>';
		}

		$html[] = '</tbody></table>';

		return implode('', $html);

	}


}
