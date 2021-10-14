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

use Protostore\MediaManager\MediaManagerFactory;
use Joomla\Input\Input;

class protostoreTask_addFolder
{

	/**
	 * @param   Input  $data
	 *
	 * @return bool
	 *
	 * @since 2.0
	 */

	public function getResponse(Input $data): bool
	{


		return MediaManagerFactory::addFolder($data);


	}


}