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
use Joomla\CMS\Language\Text;
use Joomla\CMS\Uri\Uri;

$language = Factory::getLanguage();
$language->load('com_protostore', JPATH_ADMINISTRATOR);

$app = Factory::getApplication();
$config = $app->getParams('com_protostore');

?>

<form id="yps_cart_<?= $props['formType']; ?>_form" class="<?= $props['formHide']; ?> uk-margin-bottom" onsubmit="<?= $props['formType']; ?>Submit()">

        <legend class="uk-legend"><?= $props['formTitle']; ?> <?= Text::_('COM_PROTOSTORE_ELM_CART_USER_ADDRESS_LEGEND'); ?></legend>

        <div class="uk-margin">
            <label class="uk-form-label" for="yps_cart_<?= $props['formType']; ?>_name"><?= Text::_('COM_PROTOSTORE_MOD_CUSTOMERADDRESSES_ADDRESS_NAME'); ?></label>
            <div class="uk-form-controls">
                <input class="uk-input" id="yps_cart_<?= $props['formType']; ?>_name" type="text" placeholder="<?= Text::_('COM_PROTOSTORE_MOD_CUSTOMERADDRESSES_ADDRESS_NAME_PLACEHOLDER'); ?>" required name="name">
            </div>
        </div>

        <?php if ($config->get('address_show', 1)): ?>

        <div class="uk-margin">
            <label class="uk-form-label" for="yps_cart_<?= $props['formType']; ?>_address1"><?= Text::_('COM_PROTOSTORE_MOD_CUSTOMERADDRESSES_ADDRESS_ADDRESS_LINE1'); ?></label>
            <div class="uk-form-controls">
                <input class="uk-input" id="yps_cart_<?= $props['formType']; ?>_address1" type="text" required name="address1"
                       placeholder="<?= Text::_('COM_PROTOSTORE_MOD_CUSTOMERADDRESSES_ADDRESS_ADDRESS_LINE1_PLACEHOLDER'); ?>">
            </div>
        </div>

        <?php if ($config->get('addressline2_show', 1)): ?>

        <div class="uk-margin">
            <label class="uk-form-label" for="yps_cart_<?= $props['formType']; ?>_address2"><?= Text::_('COM_PROTOSTORE_MOD_CUSTOMERADDRESSES_ADDRESS_ADDRESS_LINE2'); ?></label>
            <div class="uk-form-controls">
                <input class="uk-input" id="yps_cart_<?= $props['formType']; ?>_address2" type="text" name="address2"
                       placeholder="<?= Text::_('COM_PROTOSTORE_MOD_CUSTOMERADDRESSES_ADDRESS_ADDRESS_LINE2_PLACEHOLDER'); ?>" <?= ($config->get('addressline2_required') ? 'required' : ''); ?>>
            </div>
        </div>
        <?php endif; ?>


        <?php if ($config->get('addressline3_show', 1)): ?>
        <div class="uk-margin">
            <label class="uk-form-label" for="yps_cart_<?= $props['formType']; ?>_address3"><?= Text::_('COM_PROTOSTORE_MOD_CUSTOMERADDRESSES_ADDRESS_ADDRESS_LINE3'); ?></label>
            <div class="uk-form-controls">
                <input class="uk-input" id="yps_cart_<?= $props['formType']; ?>_address3" type="text" name="address3"
                       placeholder="<?= Text::_('COM_PROTOSTORE_MOD_CUSTOMERADDRESSES_ADDRESS_ADDRESS_LINE3_PLACEHOLDER'); ?>" <?= ($config->get('addressline3_required') ? 'required' : ''); ?>>
            </div>
        </div>

            <?php endif; ?>
        <div class="uk-margin">
            <label class="uk-form-label" for="yps_cart_<?= $props['formType']; ?>_town"><?= Text::_('COM_PROTOSTORE_MOD_CUSTOMERADDRESSES_ADDRESS_TOWN'); ?></label>
            <div class="uk-form-controls">
                <input class="uk-input" id="yps_cart_<?= $props['formType']; ?>_town" type="text" name="town" placeholder="<?= Text::_('COM_PROTOSTORE_MOD_CUSTOMERADDRESSES_ADDRESS_TOWN_PLACEHOLDER'); ?>">
            </div>
        </div>
        <?php if ($config->get('postcode_show', 1)): ?>
        <div class="uk-margin">
            <label class="uk-form-label" for="yps_cart_<?= $props['formType']; ?>_postcode"><?= Text::_('COM_PROTOSTORE_MOD_CUSTOMERADDRESSES_ADDRESS_POSTCODE'); ?></label>
            <div class="uk-form-controls">
                <input class="uk-input" id="yps_cart_<?= $props['formType']; ?>_postcode" type="text" name="postcode"
                       placeholder="<?= Text::_('COM_PROTOSTORE_MOD_CUSTOMERADDRESSES_ADDRESS_POSTCODE_PLACEHOLDER'); ?>" <?= ($config->get('postcode_required') ? 'required' : ''); ?>>
            </div>
        </div>
            <?php endif; ?>
        <div class="uk-margin">
            <label class="uk-form-label" for="yps_cart_<?= $props['formType']; ?>_country"><?= Text::_('COM_PROTOSTORE_MOD_CUSTOMERADDRESSES_ADDRESS_COUNTRY'); ?></label>
            <div class="uk-form-controls">
                <select onchange="updateZones(this)" class="uk-select" id="yps_cart_<?= $props['formType']; ?>_country" name="country">
                    <option value=""><?= Text::_('COM_PROTOSTORE_MOD_CUSTOMERADDRESSES_ADDRESS_COUNTRY_SELECT_DEFAULT'); ?></option>
					<?php foreach ($props['countries'] as $country) : ?>
                        <option value="<?= $country->id; ?>"><?= $country->country_name; ?></option>
					<?php endforeach; ?>
                </select>
            </div>
        </div>
        <div class="uk-margin">
            <label class="uk-form-label" for="yps_cart_<?= $props['formType']; ?>_zone"><?= Text::_('COM_PROTOSTORE_MOD_CUSTOMERADDRESSES_ADDRESS_STATE'); ?></label>
            <div class="uk-form-controls">
                <select class="uk-select" id="yps_cart_<?= $props['formType']; ?>_zone" name="zone">
                    <option value="" disabled><?= Text::_('COM_PROTOSTORE_MOD_CUSTOMERADDRESSES_ADDRESS_STATE_SELECT_DEFAULT'); ?></option>
                </select>
            </div>
        </div>

        <?php endif; // ends 'address_show' ?>
        <?php if ($config->get('mtelephone_show', 1)): ?>
        <div class="uk-margin">
            <label class="uk-form-label" for="yps_cart_<?= $props['formType']; ?>_mobile"><?= Text::_('COM_PROTOSTORE_MOD_CUSTOMERADDRESSES_ADDRESS_MOBILE'); ?></label>
            <div class="uk-form-controls">
                <input class="uk-input" id="yps_cart_<?= $props['formType']; ?>_mobile" type="text" name="mobilephone"
                       placeholder="<?= Text::_('COM_PROTOSTORE_MOD_CUSTOMERADDRESSES_ADDRESS_MOBILE_PLACEHOLDER'); ?>" <?= ($config->get('mtelephone_required') ? 'required' : ''); ?>>
            </div>
        </div>
        <?php endif; ?>
        <?php if ($config->get('telephone_show', 1)): ?>
        <div class="uk-margin">
            <label class="uk-form-label" for="yps_cart_<?= $props['formType']; ?>_phone"><?= Text::_('COM_PROTOSTORE_MOD_CUSTOMERADDRESSES_ADDRESS_TEL'); ?></label>
            <div class="uk-form-controls">
                <input class="uk-input" id="yps_cart_<?= $props['formType']; ?>_phone" type="text" name="phone"
                       placeholder="<?= Text::_('COM_PROTOSTORE_MOD_CUSTOMERADDRESSES_ADDRESS_TEL_PLACEHOLDER'); ?>" <?= ($config->get('telephone_required') ? 'required' : ''); ?>>
            </div>
        </div>
        <?php endif; ?>

        <?php if ($config->get('email_show', 1)): ?>
        <div class="uk-margin">
            <label class="uk-form-label" for="yps_cartsignup_email"><?= Text::_('COM_PROTOSTORE_MOD_CUSTOMERADDRESSES_ADDRESS_EMAIL'); ?></label>
            <div class="uk-form-controls">
                <input class="uk-input" id="yps_cart_<?= $props['formType']; ?>_email" type="email" name="email"
                       placeholder="<?= Text::_('COM_PROTOSTORE_MOD_CUSTOMERADDRESSES_ADDRESS_EMAIL_PLACEHOLDER'); ?>" <?= ($config->get('email_required') ? 'required' : ''); ?>>
            </div>
        </div>
        <?php endif; ?>
        <div class="uk-grid">
            <div class="uk-width-expand">
                <button id="yps_<?= $props['formType']; ?>_confirm" class="uk-button uk-button-primary" type="submit" form="yps_cart_<?= $props['formType']; ?>_form">
                    <?= Text::_('COM_PROTOSTORE_MOD_CUSTOMERADDRESSES_ADDRESS_CONFIRM'); ?>
                </button>
            </div>
            <div class="uk-width-auto">
                <div class="uk-text-right">
                    <button id="yps_<?= $props['formType']; ?>_cancel" class="uk-button uk-button-default"
                            type="button">
                        <?= Text::_('COM_PROTOSTORE_MOD_CUSTOMERADDRESSES_ADDRESS_CANCEL'); ?>
                    </button>
                </div>
            </div>
        </div>

</form>


<script>


    //function updateZones(selectObject) {
    //    let sel = document.getElementById('yps_cart_<?//= $props['formType']; ?>//_zone');
    //    let country_id = selectObject.value;
    //
    //    var length = sel.options.length;
    //    for (i = length-1; i >= 0; i--) {
    //        sel.options[i] = null;
    //    }
    //
    //    fetch("<?php //echo Uri::root(); ?>//index.php?option=com_ajax&plugin=protostore_ajaxhelper&method=post&task=getzonesbycountryid&format=raw&country_id=" + country_id, {
    //        method: 'post'
    //    }).then(function (res) {
    //        return res.json();
    //    }).then(function (response) {
    //        ;
    //        if (response.success) {
    //            // TODO - TRANSLATE
    //
    //            let zones = response.data;
    //
    //            zones.forEach(function (zone) {
    //
    //                var opt = document.createElement('option');
    //
    //                opt.appendChild( document.createTextNode(zone.zone_name) );
    //
    //                opt.value = zone.id;
    //
    //                sel.appendChild(opt);
    //
    //            });
    //
    //        }
    //    });
    //}
</script>
