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

use Protostore\Product\ProductFactory;
use Joomla\Input\Input;

class protostoreTask_refreshVariants
{

	/**
	 * @param   Input  $data
	 *
	 * @return array
	 *
	 * @since 1.6
	 */
	public function getResponse(Input $data): array
	{


		return ProductFactory::getRefreshedVariantData($data);


	}

}
