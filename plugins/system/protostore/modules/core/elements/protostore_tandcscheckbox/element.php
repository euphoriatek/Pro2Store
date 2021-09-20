<?php

/**
 * @package   Pro2Store
 * @author    Ray Lawlor - pro2.store
 * @copyright Copyright (C) 2021 Ray Lawlor - pro2.store
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 *
 */

defined('_JEXEC') or die;


use Joomla\CMS\Uri\Uri;
use Joomla\CMS\Router\Route;

use Protostore\Utilities\Utilities;
use Protostore\Tandcs\TandcsFactory;


return [

	// Define transforms for the element node
	'transforms' => [

		// The function is executed before the template is rendered
		'render' => function ($node, array $params) {


			$node->props['baseUrl'] = Uri::base();

			$node->props['checked'] = TandcsFactory::isChecked();



			$termsUrl = Utilities::getUrlFromMenuItem(45);


			$node->props['termsUrl'] = Route::_($termsUrl . '&Itemid=' . $termsId);

		},

	]

];

?>
