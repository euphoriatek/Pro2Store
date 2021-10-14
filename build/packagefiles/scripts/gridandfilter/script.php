<?php
/**
 * @package   Pro2Store
 * @author    Ray Lawlor - pro2.store
 * @copyright Copyright (C) 2021 Ray Lawlor - pro2.store
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 *
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

JHTML::_('behavior.modal');

use Joomla\CMS\Factory;

/**
 * Script File of Protostore Component
 * @since 1.0
 */
class pkg_protostore_gridandfilterInstallerScript
{
    /**
     * Constructor
     *
     * @param JAdapterInstance $parent The object responsible for running this script
     *
     * @since 1.0
     */
    public function __construct(JAdapterInstance $parent)
    {
    }

    /**
     * Called on installation
     *
     * @param JAdapterInstance $parent The object responsible for running this script
     *
     * @return  boolean  True on success
     * @since 1.0
     */
    public function install(JAdapterInstance $parent)
    {


    }

    /**
     * Called on uninstallation
     *
     * @param JAdapterInstance $parent The object responsible for running this script
     * @since 1.0
     */
    public function uninstall(JAdapterInstance $parent)
    {


    }

    /**
     * Called on update
     *
     * @param JAdapterInstance $parent The object responsible for running this script
     *
     * @return  boolean  True on success
     * @since 1.0
     */
    public function update(JAdapterInstance $parent)
    {
    }

    /**
     * Called before any type of action
     *
     * @param string $type Which action is happening (install|uninstall|discover_install|update)
     * @param JAdapterInstance $parent The object responsible for running this script
     *
     * @return  boolean  True on success
     * @since 1.0
     */
    public function preflight($type, JAdapterInstance $parent)
    {


    }

    /**
     * Called after any type of action
     *
     * @param string $type Which action is happening (install|uninstall|discover_install|update)
     * @param JAdapterInstance $parent The object responsible for running this script
     *
     * @return  boolean  True on success
     * @since 1.0
     */
    public function postflight($type, JAdapterInstance $parent)
    {

        if ($type == 'install') {

            $db = Factory::getDbo();
            
            // Prepare plugin object
            $plugin = new stdClass();
            $plugin->type = 'plugin';
            $plugin->element = 'protostore_gridandfilter';
            $plugin->folder = (string)'content';
            $plugin->enabled = 1;
            // Update record
            $db->updateObject('#__extensions', $plugin, array('type', 'element', 'folder'));



            $plugin = new stdClass();
            $plugin->type = 'plugin';
            $plugin->element = 'protostore_gridandfilter';
            $plugin->folder = (string)'system';
            $plugin->enabled = 1;
            // Update record
            $db->updateObject('#__extensions', $plugin, array('type', 'element', 'folder'));


        }


        $elementJson = JPATH_SITE . '/plugins/system/protostore_gridandfilter/modules/gridandfilter/elements/protostore_gridandfilter/element.json';
        if (file_exists($elementJson)) {

            $json = file_get_contents($elementJson);

            $jsonData = json_decode($json);

            $jsonData->fields->root_category->options = \Protostore\Utilities\Utilities::getCategoriesForOptions();

            file_put_contents($elementJson, json_encode($jsonData));
        }
    }

}
