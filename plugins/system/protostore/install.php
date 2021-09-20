<?php
/**
 * @package   Pro2Store
 * @author    Ray Lawlor - pro2.store
 * @copyright Copyright (C) 2021 Ray Lawlor - pro2.store
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 *
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
