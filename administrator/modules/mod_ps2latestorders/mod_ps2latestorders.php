<?php
/**
 * @package     Pro2Store
 * @subpackage  mod_ps2latestorders
 *
 * @copyright   Copyright (C)  2021 Ray Lawlor - Pro2Store. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Helper\ModuleHelper;

Factory::getDocument()->addScript('../media/com_protostore/js/vue/modules/latestorders/latestorders.min.js', array('type' => 'text/javascript'), array('defer' => 'defer'));


// Render the module
require ModuleHelper::getLayoutPath('mod_ps2latestorders', $params->get('layout', 'default'));