<?php
/**
 * @package   Pro2Store
 * @author    Ray Lawlor - pro2.store
 * @copyright Copyright (C) 2021 Ray Lawlor - pro2.store
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 *
 */

use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;

defined('_JEXEC') or die;

/**
 *
 * @since		2.0
 */
class plgQuickiconProtostore extends JPlugin
{
    public function __construct(&$subject, $config)
    {
        parent::__construct($subject, $config);

        $app = Factory::getApplication();

        // only in Admin and only if the component is enabled
        if ($app->getClientId() !== 1 || ComponentHelper::getComponent('com_protostore', true)->enabled === false) {
            return;
        }

		\Protostore\Language\LanguageFactory::load();

    }

    public function onGetIcons($context)
    {
        if ($context != $this->params->get('context', 'mod_quickicon')) {
            return;
        }


        return array(array(
            'link'      => 'index.php?option=com_protostore',
            'image'     => 'fas fa-shopping-cart',
            'text'      => Text::_('COM_PROTOSTORE'),
            'id'        => 'plg_quickicon_protostore',
        ));
    }
}
