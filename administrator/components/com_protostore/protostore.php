<?php
/**
 * @package     Pro2Store
 * @subpackage  com_protostore
 *
 * @copyright   Copyright (C) 2021 Ray Lawlor - Pro2Store - https://pro2.store. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;

HTMLHelper::_('behavior.keepalive');

$input = Factory::getApplication()->input;
$user  = Factory::getUser();

// Access check.
if (!$user->authorise('core.manage', 'com_protostore'))
{
	throw new JAccessExceptionNotallowed(JText::_('JERROR_ALERTNOAUTHOR'), 403);
}

// Add CSS file for all pages
$document = Factory::getDocument();
$document->addStyleSheet("https://cdn.jsdelivr.net/npm/uikit@latest/dist/css/uikit.min.css");
$document->addStyleSheet("/media/com_protostore/css/theme.css");
//$document->addStyleSheet("/media/com_protostore/css/themedark.css");
$document->addStyleSheet("/media/com_protostore/css/style.css");
$document->addStyleSheet("https://unpkg.com/primevue/resources/primevue.min.css");
$document->addStyleSheet("https://unpkg.com/primevue/resources/themes/saga-blue/theme.css");
$document->addScript("https://cdn.jsdelivr.net/npm/uikit@latest/dist/js/uikit.min.js");
$document->addScript("https://cdn.jsdelivr.net/npm/uikit@latest/dist/js/uikit-icons.min.js");
$document->addScript("https://kit.fontawesome.com/6afbbf2d93.js");
$document->addScript('../media/com_protostore/js/vue/bundle.min.js', array('type' => 'text/javascript'));



include ( JPATH_ADMINISTRATOR . '/components/com_protostore/views/wrapper/bootstrapWrapper.php');
new bootstrapWrapper();

?>



<script>
    // Remove Joomla admin headers and toolbars
    jQuery(document).ready(function () {
        jQuery('#isisJsData').hide();
        jQuery('header').hide();
    });
    // remove system message for Uikit Replacement
    jQuery(document).ready(function () {
        jQuery('#system-message-container').hide();
        jQuery('#j-main-container').removeClass('span10');

    });

    // jQuery('link[rel=stylesheet][href*="isis/css/template"]').remove();
</script>





