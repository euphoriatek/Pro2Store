<?php

/**
 * @package     Pro2Store - Add To Cart
 *
 * @copyright   Copyright (C) 2020 Ray Lawlor - Pro2Store. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

use Joomla\CMS\Uri\Uri;

use Protostore\Checkoutnote\Checkoutnote;

return [

	'transforms' => [


		'render' => function ($node, array $params) {


            $node->props['baseUrl'] = Uri::base();


			if ($currentNote = Checkoutnote::getCurrentNote())
			{
				$node->props['note'] = $currentNote->note;
			} else {
				$node->props['note'] = '';
			}


		},

	]

];
