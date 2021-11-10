<?php
/**
 * @package     Pro2Store - Customer Addresses
 *
 * @copyright   Copyright (C) 2020 Ray Lawlor - Pro2Store. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Uri\Uri;
use Joomla\CMS\Language\Text;
use Protostore\Zone\Zone;

$language = Factory::getLanguage();
$language->load('com_protostore', JPATH_ADMINISTRATOR);

?>

<div class="uk-text-right uk-margin">
    <span uk-tooltip="Add New Address">
        <a class="uk-button uk-button-primary" href="#yps-addAddressModal" uk-toggle>
            <?= Text::_('COM_PROTOSTORE_MOD_CUSTOMERADDRESSES_ADD_NEW_ADDRESS'); ?>
            <span uk-icon="icon: plus-circle"></span>
        </a>
    </span>
</div>
<div class="uk-grid uk-child-width-1-<?= $params->get('grid_cols'); ?>@m" uk-grid>
	<?php foreach ($addresses as $address) : ?>
		<?php $id = $address->getId(); ?>
        <div>
            <div class="uk-card uk-card-body uk-card-default uk-margin">
                <ul class="uk-iconnav uk-flex-right">

                    <li uk-tooltip="Edit Address"><a href="#yps-editAddressModal<?= $id; ?>" uk-icon="icon: file-edit"
                                                     uk-toggle></a></li>

                    <li uk-tooltip="Delete Address"><a onclick="deleteAddress(<?= $id; ?>)" uk-icon="icon: trash"></a>
                    </li>
                </ul>
                <span id="address<?= $id; ?>">
                                <h5><?= $address->getName(); ?></h5>

                                    <?php foreach ($address->getAddressDetailsAsObject() as $line) : ?>
	                                    <?php if (!empty($line)) : ?>
		                                    <?= $line; ?>,
	                                    <?php endif; ?>
                                    <?php endforeach; ?>

                        </span>
            </div>
        </div>

        <div id="yps-editAddressModal<?= $id; ?>" class="uk-modal-container" uk-modal="stack: true">
            <div class="uk-modal-dialog uk-modal-body">
                <h2 class="uk-modal-title"><?= Text::_('COM_PROTOSTORE_MOD_CUSTOMERADDRESSES_ADD_NEW_ADDRESS'); ?>
                    "<?= $address->getName(); ?>"</h2>
                <p>
                <form id="yps_address_form<?= $id; ?>" onsubmit="saveAddress(<?= $id; ?>)">

                    <input id="yps_address_id<?= $id; ?>" type="hidden" name="address_id"
                           value="<?= $id; ?>">

                    <div class="uk-margin">
                        <label class="uk-form-label"
                               for="yps_address_title"><?= Text::_('COM_PROTOSTORE_MOD_CUSTOMERADDRESSES_ADDRESS_NAME'); ?></label>
                        <div class="uk-form-controls">
                            <input class="uk-input" id="yps_address_name<?= $id; ?>" type="text" name="name"
                                   placeholder="<?= Text::_('COM_PROTOSTORE_MOD_CUSTOMERADDRESSES_ADDRESS_NAME_PLACEHOLDER'); ?>"
                                   value="<?= $address->getName(); ?>" required>
                        </div>
                    </div>

					<?php if ($config->get('address_show') == 1): ?>


                        <div class="uk-margin">
                            <label class="uk-form-label"
                                   for="yps_address_address1<?= $id; ?>"><?= Text::_('COM_PROTOSTORE_MOD_CUSTOMERADDRESSES_ADDRESS_ADDRESS_LINE1'); ?></label>
                            <div class="uk-form-controls">
                                <input class="uk-input" id="yps_address_address1<?= $id; ?>" type="text" name="address1"
                                       placeholder="<?= Text::_('COM_PROTOSTORE_MOD_CUSTOMERADDRESSES_ADDRESS_ADDRESS_LINE1_PLACEHOLDER'); ?>"
                                       value="<?= $address->getAddress1(); ?>">
                            </div>
                        </div>

						<?php if ($config->get('addressline2_show')): ?>
                            <div class="uk-margin">
                                <label class="uk-form-label"
                                       for="yps_address_address2<?= $id; ?>"><?= Text::_('COM_PROTOSTORE_MOD_CUSTOMERADDRESSES_ADDRESS_ADDRESS_LINE2'); ?></label>
                                <div class="uk-form-controls">
                                    <input class="uk-input" id="yps_address_address2<?= $id; ?>" type="text"
                                           name="address2"
                                           placeholder="<?= Text::_('COM_PROTOSTORE_MOD_CUSTOMERADDRESSES_ADDRESS_ADDRESS_LINE2_PLACEHOLDER'); ?>"
                                           value="<?= $address->getAddress2(); ?>" <?= ($config->get('addressline2_required') ? 'required' : ''); ?>>
                                </div>
                            </div>
						<?php endif; ?>


						<?php if ($config->get('addressline3_show')): ?>
                            <div class="uk-margin">
                                <label class="uk-form-label"
                                       for="yps_address_address3<?= $id; ?>"><?= Text::_('COM_PROTOSTORE_MOD_CUSTOMERADDRESSES_ADDRESS_ADDRESS_LINE3'); ?></label>
                                <div class="uk-form-controls">
                                    <input class="uk-input" id="yps_address_address3<?= $id; ?>" type="text"
                                           name="address3"
                                           placeholder="<?= Text::_('COM_PROTOSTORE_MOD_CUSTOMERADDRESSES_ADDRESS_ADDRESS_LINE3_PLACEHOLDER'); ?>"
                                           value="<?= $address->getAddress3(); ?>" <?= ($config->get('addressline3_required') ? 'required' : ''); ?>>
                                </div>
                            </div>
						<?php endif; ?>
                        <div class="uk-margin">
                            <label class="uk-form-label"
                                   for="yps_address_town<?= $id; ?>"><?= Text::_('COM_PROTOSTORE_MOD_CUSTOMERADDRESSES_ADDRESS_TOWN'); ?></label>
                            <div class="uk-form-controls">
                                <input class="uk-input" id="yps_address_town<?= $id; ?>" type="text" name="town"
                                       placeholder="<?= Text::_('COM_PROTOSTORE_MOD_CUSTOMERADDRESSES_ADDRESS_TOWN_PLACEHOLDER'); ?>"
                                       value="<?= $address->getTown(); ?>">
                            </div>
                        </div>

						<?php if ($config->get('postcode_show')): ?>
                            <div class="uk-margin">
                                <label class="uk-form-label"
                                       for="yps_address_postcode<?= $id; ?>"><?= Text::_('COM_PROTOSTORE_MOD_CUSTOMERADDRESSES_ADDRESS_POSTCODE'); ?></label>
                                <div class="uk-form-controls">
                                    <input class="uk-input" id="yps_address_postcode<?= $id; ?>" type="text"
                                           name="postcode"
                                           placeholder="<?= Text::_('COM_PROTOSTORE_MOD_CUSTOMERADDRESSES_ADDRESS_POSTCODE_PLACEHOLDER'); ?>"
                                           value="<?= $address->getPostcode(); ?>" <?= ($config->get('postcode_required') ? 'required' : ''); ?>>
                                </div>
                            </div>
						<?php endif; ?>
                        <div class="uk-margin">
                            <label class="uk-form-label"
                                   for="yps_address_zone<?= $id; ?>"><?= Text::_('COM_PROTOSTORE_MOD_CUSTOMERADDRESSES_ADDRESS_STATE'); ?></label>
                            <div class="uk-form-controls">
                                <select class="uk-select" id="yps_address_zone<?= $id; ?>" name="zone">
                                    <option value=""
                                            disabled><?= Text::_('COM_PROTOSTORE_MOD_CUSTOMERADDRESSES_ADDRESS_STATE_SELECT_DEFAULT'); ?></option>
									<?php foreach (Zone::getZonesByCountryId($address->country_id) as $zone) : ?>
                                        <option value="<?= $zone->id; ?>"<?= ($zone->id == $address->zone_id ? 'selected' : ''); ?> ><?= $zone->zone_name; ?></option>
									<?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <div class="uk-margin">
                            <label class="uk-form-label"
                                   for="yps_address_country<?= $id; ?>"><?= Text::_('COM_PROTOSTORE_MOD_CUSTOMERADDRESSES_ADDRESS_COUNTRY'); ?></label>
                            <div class="uk-form-controls">
                                <select onchange="updateZones(this, <?= $id; ?>)" class="uk-select" name="country"
                                        id="yps_address_country<?= $id; ?>">
                                    <option value=""
                                            disabled><?= Text::_('COM_PROTOSTORE_MOD_CUSTOMERADDRESSES_ADDRESS_COUNTRY_SELECT_DEFAULT'); ?></option>
									<?php foreach ($countries as $country) : ?>
                                        <option value="<?= $country->id; ?>" <?= ($country->id == $address->country_id ? 'selected' : ''); ?>><?= $country->country_name; ?></option>
									<?php endforeach; ?>
                                </select>
                            </div>

                        </div>

					<?php endif; // ends 'address_show' ?>
					<?php if ($config->get('mtelephone_show')): ?>
                        <div class="uk-margin">
                            <label class="uk-form-label"
                                   for="yps_address_mobile<?= $id; ?>"><?= Text::_('COM_PROTOSTORE_MOD_CUSTOMERADDRESSES_ADDRESS_MOBILE'); ?></label>
                            <div class="uk-form-controls">
                                <input class="uk-input" id="yps_address_mobile<?= $id; ?>" type="text"
                                       name="mobilephone"
                                       placeholder="<?= Text::_('COM_PROTOSTORE_MOD_CUSTOMERADDRESSES_ADDRESS_MOBILE_PLACEHOLDER'); ?>"
                                       value="<?= $address->getMobilePhone(); ?>" <?= ($config->get('mtelephone_required') ? 'required' : ''); ?>>
                            </div>
                        </div>
					<?php endif; ?>
					<?php if ($config->get('telephone_show')): ?>
                        <div class="uk-margin">
                            <label class="uk-form-label"
                                   for="yps_address_phone<?= $id; ?>"><?= Text::_('COM_PROTOSTORE_MOD_CUSTOMERADDRESSES_ADDRESS_TEL'); ?></label>
                            <div class="uk-form-controls">
                                <input class="uk-input" id="yps_address_phone<?= $id; ?>" type="text" name="phone"
                                       placeholder="<?= Text::_('COM_PROTOSTORE_MOD_CUSTOMERADDRESSES_ADDRESS_TEL_PLACEHOLDER'); ?>"
                                       value="<?= $address->getPhone(); ?>" <?= ($config->get('telephone_required') ? 'required' : ''); ?>>
                            </div>
                        </div>
					<?php endif; ?>

					<?php if ($config->get('email_show')): ?>
                        <div class="uk-margin">
                            <label class="uk-form-label"
                                   for="yps_address_email<?= $id; ?>"><?= Text::_('COM_PROTOSTORE_MOD_CUSTOMERADDRESSES_ADDRESS_EMAIL'); ?></label>
                            <div class="uk-form-controls">
                                <input class="uk-input" id="yps_address_email<?= $id; ?>" type="email" name="email"
                                       placeholder="<?= Text::_('COM_PROTOSTORE_MOD_CUSTOMERADDRESSES_ADDRESS_EMAIL_PLACEHOLDER'); ?>"
                                       value="<?= $address->getEmail(); ?>" <?= ($config->get('email_required') ? 'required' : ''); ?>>
                            </div>
                        </div>
					<?php endif; ?>

                </form>

                </p>
                <p class="uk-text-right">
                    <button class="uk-button uk-button-default uk-modal-close"
                            type="button"><?= Text::_('COM_PROTOSTORE_MOD_CUSTOMERADDRESSES_ADDRESS_CANCEL'); ?></button>
                    <button class="uk-button uk-button-primary" type="submit" form="yps_address_form<?= $id; ?>"
                    ><?= Text::_('COM_PROTOSTORE_MOD_CUSTOMERADDRESSES_ADDRESS_SAVE'); ?>
                    </button>
                </p>
            </div>
        </div>

	<?php endforeach; ?>
</div>


<div id="yps-addAddressModal" class="uk-modal-container" uk-modal="stack: true">
    <div class="uk-modal-dialog uk-modal-body">
        <h2 class="uk-modal-title"><?= Text::_('COM_PROTOSTORE_MOD_CUSTOMERADDRESSES_ADD_NEW_ADDRESS'); ?></h2>
        <p>
        <form id="yps_address_form_add" onsubmit="addAddress()">
            <div class="uk-margin">
                <label class="uk-form-label"
                       for="yps_address_name_add"><?= Text::_('COM_PROTOSTORE_MOD_CUSTOMERADDRESSES_ADDRESS_NAME'); ?></label>
                <div class="uk-form-controls">
                    <input class="uk-input" id="yps_address_name_add" type="text" name="name"
                           placeholder="<?= Text::_('COM_PROTOSTORE_MOD_CUSTOMERADDRESSES_ADDRESS_NAME_PLACEHOLDER'); ?>"
                           value="" required>
                </div>
            </div>

			<?php if ($config->get('address_show')): ?>

                <div class="uk-margin">
                    <label class="uk-form-label"
                           for="yps_address_address1_add"><?= Text::_('COM_PROTOSTORE_MOD_CUSTOMERADDRESSES_ADDRESS_ADDRESS_LINE1'); ?></label>
                    <div class="uk-form-controls">
                        <input class="uk-input" id="yps_address_address1_add" type="text" name="address1"
                               placeholder="<?= Text::_('COM_PROTOSTORE_MOD_CUSTOMERADDRESSES_ADDRESS_ADDRESS_LINE1_PLACEHOLDER'); ?>"
                               value="" required>
                    </div>
                </div>


				<?php if ($config->get('addressline2_show')): ?>
                    <div class="uk-margin">
                        <label class="uk-form-label"
                               for="yps_address_address2_add"><?= Text::_('COM_PROTOSTORE_MOD_CUSTOMERADDRESSES_ADDRESS_ADDRESS_LINE2'); ?></label>
                        <div class="uk-form-controls">
                            <input class="uk-input" id="yps_address_address2_add" type="text" name="address2"
                                   placeholder="<?= Text::_('COM_PROTOSTORE_MOD_CUSTOMERADDRESSES_ADDRESS_ADDRESS_LINE2_PLACEHOLDER'); ?>"
                                   value="" <?= ($config->get('addressline2_required') ? 'required' : ''); ?>>
                        </div>
                    </div>
				<?php endif; ?>


				<?php if ($config->get('addressline3_show')): ?>
                    <div class="uk-margin">
                        <label class="uk-form-label"
                               for="yps_address_address3_add"><?= Text::_('COM_PROTOSTORE_MOD_CUSTOMERADDRESSES_ADDRESS_ADDRESS_LINE3'); ?></label>
                        <div class="uk-form-controls">
                            <input class="uk-input" id="yps_address_address3_add" type="text" name="address3"
                                   placeholder="<?= Text::_('COM_PROTOSTORE_MOD_CUSTOMERADDRESSES_ADDRESS_ADDRESS_LINE3_PLACEHOLDER'); ?>"
                                   value="" <?= ($config->get('addressline3_required') ? 'required' : ''); ?>>
                        </div>
                    </div>
				<?php endif; ?>

                <div class="uk-margin">
                    <label class="uk-form-label"
                           for="yps_address_town_add"><?= Text::_('COM_PROTOSTORE_MOD_CUSTOMERADDRESSES_ADDRESS_TOWN'); ?></label>
                    <div class="uk-form-controls">
                        <input class="uk-input" id="yps_address_town_add" type="text" name="town"
                               placeholder="<?= Text::_('COM_PROTOSTORE_MOD_CUSTOMERADDRESSES_ADDRESS_TOWN_PLACEHOLDER'); ?>"
                               value="">
                    </div>
                </div>
				<?php if ($config->get('postcode_show')): ?>
                    <div class="uk-margin">
                        <label class="uk-form-label"
                               for="yps_address_postcode_add"><?= Text::_('COM_PROTOSTORE_MOD_CUSTOMERADDRESSES_ADDRESS_POSTCODE'); ?></label>
                        <div class="uk-form-controls">
                            <input class="uk-input" id="yps_address_postcode_add" type="text" name="postcode"
                                   placeholder="<?= Text::_('COM_PROTOSTORE_MOD_CUSTOMERADDRESSES_ADDRESS_POSTCODE_PLACEHOLDER'); ?>"
                                   value="" <?= ($config->get('postcode_required') ? 'required' : ''); ?>>
                        </div>
                    </div>
				<?php endif; ?>
                <div class="uk-margin">
                    <label class="uk-form-label"
                           for="yps_address_zone_add"><?= Text::_('COM_PROTOSTORE_MOD_CUSTOMERADDRESSES_ADDRESS_STATE'); ?></label>
                    <div class="uk-form-controls">
                        <select class="uk-select" id="yps_address_zone_add" name="zone">
                            <option value=""
                                    disabled><?= Text::_('COM_PROTOSTORE_MOD_CUSTOMERADDRESSES_ADDRESS_STATE_SELECT_DEFAULT'); ?></option>
							<?php foreach (Zone::getZonesByCountryId($countries[0]->id) as $zone) : ?>
                                <option value="<?= $zone->id; ?>"><?= $zone->zone_name; ?></option>
							<?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="uk-margin">
                    <label class="uk-form-label"
                           for="yps_address_country_add"><?= Text::_('COM_PROTOSTORE_MOD_CUSTOMERADDRESSES_ADDRESS_COUNTRY'); ?></label>
                    <div class="uk-form-controls">
                        <select onchange="updateZones(this, '_add' )" class="uk-select" name="country"
                                id="yps_address_country_add">
                            <option value=""
                                    disabled><?= Text::_('COM_PROTOSTORE_MOD_CUSTOMERADDRESSES_ADDRESS_COUNTRY_SELECT_DEFAULT'); ?></option>
							<?php foreach ($countries as $country) : ?>
                                <option value="<?= $country->id; ?>"><?= $country->country_name; ?></option>
							<?php endforeach; ?>
                        </select>
                    </div>

                </div>


			<?php endif; // ends 'address_show' ?>

			<?php if ($config->get('mtelephone_show')): ?>
                <div class="uk-margin">
                    <label class="uk-form-label"
                           for="yps_address_mobile_add"><?= Text::_('COM_PROTOSTORE_MOD_CUSTOMERADDRESSES_ADDRESS_MOBILE'); ?></label>
                    <div class="uk-form-controls">
                        <input class="uk-input" id="yps_address_mobile_add" type="text" name="mobilephone"
                               placeholder="<?= Text::_('COM_PROTOSTORE_MOD_CUSTOMERADDRESSES_ADDRESS_MOBILE_PLACEHOLDER'); ?>"
                               value="" <?= ($config->get('mtelephone_required') ? 'required' : ''); ?>>
                    </div>
                </div>
			<?php endif; ?>
			<?php if ($config->get('telephone_show')): ?>
                <div class="uk-margin">
                    <label class="uk-form-label"
                           for="yps_address_phone_add"><?= Text::_('COM_PROTOSTORE_MOD_CUSTOMERADDRESSES_ADDRESS_TEL'); ?></label>
                    <div class="uk-form-controls">
                        <input class="uk-input" id="yps_address_phone_add" type="text" name="phone"
                               placeholder="<?= Text::_('COM_PROTOSTORE_MOD_CUSTOMERADDRESSES_ADDRESS_TEL_PLACEHOLDER'); ?>"
                               value="" <?= ($config->get('telephone_required') ? 'required' : ''); ?>>
                    </div>
                </div>
			<?php endif; ?>

			<?php if ($config->get('email_show')): ?>
                <div class="uk-margin">
                    <label class="uk-form-label"
                           for="yps_address_email_add"><?= Text::_('COM_PROTOSTORE_MOD_CUSTOMERADDRESSES_ADDRESS_EMAIL'); ?></label>
                    <div class="uk-form-controls">
                        <input class="uk-input" id="yps_address_email_add" type="email" required name="email"
                               placeholder="<?= Text::_('COM_PROTOSTORE_MOD_CUSTOMERADDRESSES_ADDRESS_EMAIL_PLACEHOLDER'); ?>"
                               value="" <?= ($config->get('email_required') ? 'required' : ''); ?>>
                    </div>
                </div>
			<?php endif; ?>


        </form>

        </p>
        <p class="uk-text-right">
            <button class="uk-button uk-button-default uk-modal-close"
                    type="button"><?= Text::_('COM_PROTOSTORE_MOD_CUSTOMERADDRESSES_ADDRESS_CANCEL'); ?></button>
            <button class="uk-button uk-button-primary"
                    type="submit"
                    form="yps_address_form_add"><?= Text::_('COM_PROTOSTORE_MOD_CUSTOMERADDRESSES_ADDRESS_SAVE'); ?></button>
        </p>
    </div>
</div>


<script>

    function updateZones(selectObject, id) {
        let sel = document.getElementById('yps_address_zone' + id);
        let country_id = selectObject.value;

        var length = sel.options.length;
        for (i = length - 1; i >= 0; i--) {
            sel.options[i] = null;
        }

        fetch("<?php echo Uri::root(); ?>index.php?option=com_ajax&plugin=protostore_ajaxhelper&method=post&task=getzonesbycountryid&format=raw&country_id=" + country_id, {
            method: 'post'
        }).then(function (res) {
            return res.json();
        }).then(function (response) {
            console.log(response);
            if (response.success) {
                // TODO - TRANSLATE

                let zones = response.data;

                zones.forEach(function (zone) {

                    var opt = document.createElement('option');

                    opt.appendChild(document.createTextNode(zone.zone_name));

                    opt.value = zone.id;

                    sel.appendChild(opt);

                });

            }
        });
    }


    function deleteAddress(id) {
        UIkit.modal.confirm('Are you sure you wish to delete?', {stack: true}).then(function () {

            const params = {
                address_id: id
            }
            const url = Object.keys(params).map(function (k) {
                return encodeURIComponent(k) + '=' + encodeURIComponent(params[k])
            }).join('&')


            fetch("<?php echo Uri::root(); ?>index.php?option=com_ajax&plugin=protostore_ajaxhelper&method=post&task=deleteaddress&format=raw&" + url, {
                method: 'post'
            }).then(function (res) {
                return res.json();
            }).then(function (response) {
                console.log(response);
                if (response.data.status == 'ok') {
                    UIkit.notification({
                        message: '<span uk-icon=\'icon: check\'></span> Address Deleted',
                        status: 'success',
                        pos: 'top-center'
                    });
                    location.reload();
                } else if (response.data.status == 'ko') {
                    UIkit.notification({
                        message: '<span uk-icon=\'icon: ban\'></span> Error in form',
                        status: 'warning',
                        pos: 'top-center'
                    });
                }
            });


        }, function () {
            console.log('Rejected.')
        });
    }

    // function for preventing default form behaviour
    var yps_prevent = function (event) {
        event.preventDefault();
    };


	<?php foreach ($addresses as $address) : ?>
	<?php $id = $address->getId(); ?>
    var editform<?= $id; ?> = document.getElementById("yps_address_form<?= $id; ?>");
    editform<?= $id; ?>.addEventListener("submit", yps_prevent, true);
	<?php endforeach;?>


    function saveAddress(id) {
        UIkit.modal.confirm('Are you sure you wish to save?', {stack: true}).then(function () {

            var kvpairs = [];
            var form = document.getElementById("yps_address_form" + id);
            for (var i = 0; i < form.elements.length; i++) {
                var e = form.elements[i];
                kvpairs.push(encodeURIComponent(e.name) + "=" + encodeURIComponent(e.value));
            }
            var queryString = kvpairs.join("&");


            fetch("<?php echo Uri::root(); ?>index.php?option=com_ajax&plugin=protostore_ajaxhelper&method=post&task=saveaddress&format=raw&" + queryString, {
                method: 'post'
            }).then(function (res) {
                return res.json();
            }).then(function (response) {
                console.log(response);
                if (response.data.status == 'ok') {
                    UIkit.notification({
                        message: '<span uk-icon=\'icon: check\'></span> Address Edited',
                        status: 'success',
                        pos: 'top-center'
                    });
                    location.reload();
                } else if (response.data.status == 'ko') {
                    UIkit.notification({
                        message: '<span uk-icon=\'icon: ban\'></span> Error in form',
                        status: 'warning',
                        pos: 'top-center'
                    });
                }
            });
        });
    }

    var form = document.getElementById("yps_address_form_add");

    // attach event listener
    form.addEventListener("submit", yps_prevent, true);

    function addAddress() {

        UIkit.modal.confirm('Are you sure you wish to save?', {stack: true}).then(function () {
            UIkit.modal("#yps-addAddressModal").hide();

            var kvpairs = [];
            var form = document.getElementById("yps_address_form_add");
            for (var i = 0; i < form.elements.length; i++) {
                var e = form.elements[i];
                kvpairs.push(encodeURIComponent(e.name) + "=" + encodeURIComponent(e.value));
            }
            var queryString = kvpairs.join("&");


            fetch("<?php echo Uri::root(); ?>index.php?option=com_ajax&plugin=protostore_ajaxhelper&method=post&task=addaddress&format=raw&" + queryString, {
                method: 'post'
            }).then(function (res) {
                return res.json();
            }).then(function (response) {
                console.log(response);
                if (response.data.status == 'ok') {
                    UIkit.notification({
                        message: '<span uk-icon=\'icon: check\'></span> Address Added',
                        status: 'success',
                        pos: 'top-center'
                    });
                    location.reload();
                } else if (response.data.status == 'ko') {
                    UIkit.notification({
                        message: '<span uk-icon=\'icon: ban\'></span> Error in form',
                        status: 'warning',
                        pos: 'top-center'
                    });
                }
            });


        });
    }


</script>
