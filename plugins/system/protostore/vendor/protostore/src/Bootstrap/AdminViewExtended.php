<?php
/**
 * @package   Pro2Store
 * @author    Ray Lawlor - pro2.store
 * @copyright Copyright (C) 2021 Ray Lawlor - pro2.store
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 *
 */

namespace Protostore\Bootstrap;

// no direct access
use Protostore\Sidebarlink\Sidebarlink;

defined('_JEXEC') or die('Restricted access');


interface AdminViewExtended
{

	/**
	 *
	 * @return Sidebarlink
	 *
	 * @since 1.6
	 */
	public function onGetSidebarLink(): Sidebarlink;


}
