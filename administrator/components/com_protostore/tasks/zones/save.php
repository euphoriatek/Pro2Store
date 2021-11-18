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

use Protostore\Country\CountryFactory;
use Protostore\Country\Zone;
use Joomla\Input\Input;


class protostoreTask_save
{

	/**
	 * @param   Input  $data
	 *
	 * @return Zone;
	 *
	 * @throws Exception
	 * @since 2.0
	 */
	public function getResponse(Input $data): Zone
	{

		return CountryFactory::saveZoneFromInputData($data);


	}

}
