<?php

/**
 * @package     Pro2Store - T&C's Checkbox
 *
 * @copyright   Copyright (C) 2020 Ray Lawlor - Pro2Store. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;


use Joomla\CMS\Uri\Uri;
use Joomla\CMS\Router\Route;

use Protostore\Config\Config;
use Protostore\Utilities\Utilities;
use Protostore\Tandcs\TandcsFactory;


return [

	// Define transforms for the element node
	'transforms' => [

		// The function is executed before the template is rendered
		'render' => function ($node, array $params) {


			$node->props['baseUrl'] = Uri::base();

			$node->props['checked'] = TandcsFactory::isChecked();

			$configHelper = new Config();
			$termsId = $configHelper->termsConditionsItemid;
			$termsUrl = Utilities::getUrlFromMenuItem($termsId);


			$node->props['termsUrl'] = Route::_($termsUrl . '&Itemid=' . $termsId);

		},

	]

];

?>
