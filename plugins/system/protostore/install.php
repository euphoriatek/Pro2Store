<?php
/**
 * @package     Pro2Store
 * @subpackage  com_protostore
 *
 * @copyright   Copyright (C) 2020 Ray Lawlor - ZOOModsPlus - https://www.zoomodsplus.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

class plgSystemProtostoreInstallerScript
{
    public function install($parent) {}

    public function uninstall($parent) {}

    public function update($parent) {}

    public function preflight($type, $parent) {}

    public function postflight($type, $parent)
    {
        if (!in_array($type, ['install', 'update'])) {
            return;
        }

        JFactory::getDBO()->setQuery(
            "UPDATE #__extensions SET "
            .($type == 'install' ? 'enabled = 1, ' : '')
            ."ordering = 0 WHERE type = 'plugin' AND folder = 'system' AND element = 'protostore'"
        )->execute();
    }
}
