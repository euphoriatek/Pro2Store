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

use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Access\Exception\NotAllowed;
use Joomla\CMS\Version;

HTMLHelper::_('behavior.keepalive');


// Access check.
if (!Factory::getUser()->authorise('core.manage', 'com_protostore'))
{
	throw new NotAllowed(Text::_('JERROR_ALERTNOAUTHOR'), 403);
}

if(\Protostore\Setup\SetupFactory::isSetup()) {
	include ( JPATH_ADMINISTRATOR . '/components/com_protostore/views/wrapper/bootstrapWrapper.php');
	new bootstrapWrapper();
} else {
	include ( JPATH_ADMINISTRATOR . '/components/com_protostore/views/wrapper/bootstrapWrapper.php');
	new bootstrapWrapper();
}



?>

<?php if (Version::MAJOR_VERSION === 3) : ?>

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

<?php endif; ?>






