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
use Joomla\CMS\Language\Text;
use Joomla\CMS\Access\Exception\NotAllowed;

HTMLHelper::_('behavior.keepalive');


// Access check.
if (!Factory::getUser()->authorise('core.manage', 'com_protostore'))
{
	throw new NotAllowed(Text::_('JERROR_ALERTNOAUTHOR'), 403);
}

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





