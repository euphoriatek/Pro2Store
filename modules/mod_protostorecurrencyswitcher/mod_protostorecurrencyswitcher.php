<?php
/**
 * @package     Pro2Store - Currency Switcher
 *
 * @copyright   Copyright (C) 2020 Ray Lawlor - Pro2Store. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;


use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Helper\ModuleHelper;
use Joomla\CMS\Plugin\PluginHelper;

if (!ComponentHelper::getComponent('com_protostore', true)->enabled) {
    return;
}

if (!PluginHelper::isEnabled('system', 'protostore')) {
    return;
}


require ModuleHelper::getLayoutPath('mod_protostorecurrencyswitcher', $params->get('layout', 'default'));
