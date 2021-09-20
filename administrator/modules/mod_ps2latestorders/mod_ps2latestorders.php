<?php
/**
 * @package   Pro2Store
 * @author    Ray Lawlor - pro2.store
 * @copyright Copyright (C) 2021 Ray Lawlor - pro2.store
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 *
 */

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Helper\ModuleHelper;

Factory::getDocument()->addScript('../media/com_protostore/js/vue/modules/latestorders/latestorders.min.js', array('type' => 'text/javascript'), array('defer' => 'defer'));


// Render the module
require ModuleHelper::getLayoutPath('mod_ps2latestorders', $params->get('layout', 'default'));
