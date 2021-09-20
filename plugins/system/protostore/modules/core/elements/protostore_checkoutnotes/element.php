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

use Protostore\Checkoutnote\CheckoutnoteFactory;

return [

	'transforms' => [


		'render' => function ($node, array $params) {


            $node->props['baseUrl'] = Uri::base();
			$node->props['note'] = '';
			$node->props['note'] = '';

			if ($currentNote = CheckoutnoteFactory::getCurrentNote())
			{
				$node->props['note'] = $currentNote->note;
			}


		},

	]

];
