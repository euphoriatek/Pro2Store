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
defined('_JEXEC') or die('Restricted access');

use Protostore\Language\LanguageFactory;

class ProtostroreView
{



	public function __construct()
	{

		LanguageFactory::load();

	}




}
