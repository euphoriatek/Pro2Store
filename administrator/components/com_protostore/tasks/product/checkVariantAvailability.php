<?php
/**
 * @package   Pro2Store
 * @author    Ray Lawlor - pro2.store
 * @copyright Copyright (C) 2021 Ray Lawlor - pro2.store
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 *
 */
// no direct access


defined('_JEXEC') or die('Restricted access');

use Brick\Money\Exception\UnknownCurrencyException;
use Joomla\Input\Input;
use Protostore\Product\ProductFactory;

class protostoreTask_checkVariantAvailability
{

	/**
	 * @since 1.6
	 */
	public function getResponse(Input $data): array
	{


		return ProductFactory::checkVariantAvailability($data->json->getInt('joomla_item_id'), $data->json->getString('selected'));
	}

}
