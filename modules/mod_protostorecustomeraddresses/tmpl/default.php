<?php
/**
 * @package     Pro2Store - Customer Addresses
 *
 * @copyright   Copyright (C) 2020 Ray Lawlor - Pro2Store. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

use Joomla\CMS\Uri\Uri;
use Joomla\CMS\Language\Text;

use Protostore\Address\Address;
use Protostore\Country\Country;
use Protostore\Customer\Customer;
use Protostore\Zone\Zone;

/** @var $params */
/** @var $config */
/** @var $customer Customer */
/** @var $addresses array */
/** @var $address Address */
/** @var $zones array */
/** @var $zone Zone */
/** @var $countries array */
/** @var $country Country */

\Protostore\Language\LanguageFactory::load();

$id = uniqid('yps_customer_addresses');

echo "{emailcloak=off}";


?>
<div id="<?= $id; ?>">
    <div class="uk-text-right uk-margin">
    <span uk-tooltip="Add New Address">
        <a class="uk-button uk-button-primary" href="#yps-addAddressModal" uk-toggle>
            <?= Text::_('COM_PROTOSTORE_MOD_CUSTOMERADDRESSES_ADD_NEW_ADDRESS'); ?>
            <span uk-icon="icon: plus-circle"></span>
        </a>
    </span>
    </div>
    <div class="uk-grid uk-child-width-1-<?= $params->get('grid_cols'); ?>@m" uk-grid>
        <div v-for="address in addresses">
            <div>
                <div class="uk-card uk-card-body uk-card-default uk-margin">
                    <ul class="uk-iconnav uk-flex-right">
                        <li uk-tooltip="<?= Text::_('COM_PROTOSTORE_CUSTOMER_TOOLTIP_EDIT_ADDRESS'); ?>">
                            <a @click="openEditModal(address)"
                               uk-icon="icon: file-edit">
                            </a>
                        </li>

                        <li uk-tooltip="Delete Address">
                            <a :click="deleteAddress(address.id)" uk-icon="icon: trash"></a>
                        </li>
                    </ul>
                    <span><h5>{{address.name}}</h5>{{address.address_as_csv}}</span>
                </div>
            </div>

        </div>
    </div>

    <div id="yps-editAddressModal" class="uk-modal-container" uk-modal="">

        <div class="uk-modal-dialog uk-modal-body">

            <h2 class="uk-modal-title">
				<?= Text::_('COM_PROTOSTORE_MOD_CUSTOMERADDRESSES_ADD_NEW_ADDRESS'); ?>
                "{{addressForEdit.name}}"
            </h2>

            <form @submit.prevent="submitUpdateAddress()">
                <input type="hidden" name="address_id" v-model="addressForEdit.id">

                <div class="uk-margin">
                    <label class="uk-form-label"
                           for="yps_editaddress_title">
                        <?= Text::_('COM_PROTOSTORE_MOD_CUSTOMERADDRESSES_ADDRESS_NAME'); ?>
                    </label>
                    <div class="uk-form-controls">
                        <input class="uk-input" type="text" v-model="addressForEdit.name"
                               name="name" id="yps_editaddress_title"
                               placeholder="<?= Text::_('COM_PROTOSTORE_MOD_CUSTOMERADDRESSES_ADDRESS_NAME_PLACEHOLDER'); ?>"
                               required>
                    </div>
                </div>

				<?php if ($config->get('address_show') == 1): ?>

                    <div class="uk-margin">
                        <label class="uk-form-label"
                               for="yps_editaddress_address1"><?= Text::_('COM_PROTOSTORE_MOD_CUSTOMERADDRESSES_ADDRESS_ADDRESS_LINE1'); ?></label>
                        <div class="uk-form-controls">
                            <input class="uk-input" type="text" v-model="addressForEdit.address1"
                                   name="address1"
                                   id="yps_editaddress_address1"
                                   placeholder="<?= Text::_('COM_PROTOSTORE_MOD_CUSTOMERADDRESSES_ADDRESS_ADDRESS_LINE1_PLACEHOLDER'); ?>">
                        </div>
                    </div>

					<?php if ($config->get('addressline2_show')): ?>
                        <div class="uk-margin">
                            <label class="uk-form-label"
                                   for="yps_editaddress_address2"><?= Text::_('COM_PROTOSTORE_MOD_CUSTOMERADDRESSES_ADDRESS_ADDRESS_LINE2'); ?></label>
                            <div class="uk-form-controls">
                                <input class="uk-input" v-model="addressForEdit.address2"
                                       type="text"
                                       name="address2"
                                       id="yps_editaddress_address2"
                                       placeholder="<?= Text::_('COM_PROTOSTORE_MOD_CUSTOMERADDRESSES_ADDRESS_ADDRESS_LINE2_PLACEHOLDER'); ?>"
									<?= ($config->get('addressline2_required') ? 'required' : ''); ?>>
                            </div>
                        </div>
					<?php endif; ?>


					<?php if ($config->get('addressline3_show')): ?>
                        <div class="uk-margin">
                            <label class="uk-form-label"
                                   for="yps_editaddress_address3"><?= Text::_('COM_PROTOSTORE_MOD_CUSTOMERADDRESSES_ADDRESS_ADDRESS_LINE3'); ?></label>
                            <div class="uk-form-controls">
                                <input class="uk-input"
                                       type="text"
                                       id="yps_editaddress_address3"
                                       name="address3" v-model="addressForEdit.address23"
                                       placeholder="<?= Text::_('COM_PROTOSTORE_MOD_CUSTOMERADDRESSES_ADDRESS_ADDRESS_LINE3_PLACEHOLDER'); ?>"
									<?= ($config->get('addressline3_required') ? 'required' : ''); ?>>
                            </div>
                        </div>
					<?php endif; ?>
                    <div class="uk-margin">
                        <label class="uk-form-label"
                               for="yps_editaddress_town"><?= Text::_('COM_PROTOSTORE_MOD_CUSTOMERADDRESSES_ADDRESS_TOWN'); ?></label>
                        <div class="uk-form-controls">
                            <input class="uk-input" type="text" v-model="addressForEdit.town"
                                   name="town"
                                   id="yps_editaddress_town"
                                   placeholder="<?= Text::_('COM_PROTOSTORE_MOD_CUSTOMERADDRESSES_ADDRESS_TOWN_PLACEHOLDER'); ?>"
                            >
                        </div>
                    </div>

					<?php if ($config->get('postcode_show')): ?>
                        <div class="uk-margin">
                            <label class="uk-form-label"
                                   for="yps_editaddress_postcode"><?= Text::_('COM_PROTOSTORE_MOD_CUSTOMERADDRESSES_ADDRESS_POSTCODE'); ?></label>
                            <div class="uk-form-controls">
                                <input class="uk-input" v-model="addressForEdit.postcode"
                                       type="text"
                                       name="postcode"
                                       id="yps_editaddress_postcode"
                                       placeholder="<?= Text::_('COM_PROTOSTORE_MOD_CUSTOMERADDRESSES_ADDRESS_POSTCODE_PLACEHOLDER'); ?>"
									<?= ($config->get('postcode_required') ? 'required' : ''); ?>>
                            </div>
                        </div>
					<?php endif; ?>
                    <div class="uk-margin">
                        <label class="uk-form-label"
                               for="yps_editaddress_zone"><?= Text::_('COM_PROTOSTORE_MOD_CUSTOMERADDRESSES_ADDRESS_STATE'); ?></label>
                        <div class="uk-form-controls">
                            <select class="uk-select" id="yps_editaddress_zone" name="zone"
                                    v-model="addressForEdit.zone">
                                <option value=""
                                        disabled><?= Text::_('COM_PROTOSTORE_MOD_CUSTOMERADDRESSES_ADDRESS_STATE_SELECT_DEFAULT'); ?></option>
								<?php foreach (Zone::getZonesByCountryId($address->country_id) as $zone) : ?>
                                    <option value="<?= $zone->id; ?>"<?= ($zone->id == $address->zone ? 'selected' : ''); ?> ><?= $zone->zone_name; ?></option>
								<?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="uk-margin">
                        <label class="uk-form-label"
                               for="yps_editaddress_country">
							<?= Text::_('COM_PROTOSTORE_MOD_CUSTOMERADDRESSES_ADDRESS_COUNTRY'); ?>
                        </label>
                        <div class="uk-form-controls">
                            <select @change="updateZones()" class="uk-select"
                                    id="yps_editaddress_country"
                                    name="country"
                                    v-model="addressForEdit.country">
                                <option value="" disabled><?= Text::_('COM_PROTOSTORE_MOD_CUSTOMERADDRESSES_ADDRESS_COUNTRY_SELECT_DEFAULT'); ?></option>
								<?php foreach ($countries as $country) : ?>
                                    <option value="<?= $country->id; ?>" :selected="addressForEdit.country === <?= $country->id; ?>"><?= $country->country_name; ?></option>
								<?php endforeach; ?>
                            </select>
                        </div>

                    </div>

				<?php endif; // ends 'address_show' ?>
				<?php if ($config->get('mtelephone_show')): ?>
                    <div class="uk-margin">
                        <label class="uk-form-label"
                               for="yps_editaddress_mobile"><?= Text::_('COM_PROTOSTORE_MOD_CUSTOMERADDRESSES_ADDRESS_MOBILE'); ?></label>
                        <div class="uk-form-controls">
                            <input class="uk-input" id="yps_editaddress_mobile" type="text"
                                   v-model="addressForEdit.mobile_phone"
                                   name="mobilephone"
                                   placeholder="<?= Text::_('COM_PROTOSTORE_MOD_CUSTOMERADDRESSES_ADDRESS_MOBILE_PLACEHOLDER'); ?>"
								<?= ($config->get('mtelephone_required') ? 'required' : ''); ?>>
                        </div>
                    </div>
				<?php endif; ?>
				<?php if ($config->get('telephone_show')): ?>
                    <div class="uk-margin">
                        <label class="uk-form-label"
                               for="yps_editaddress_phone"><?= Text::_('COM_PROTOSTORE_MOD_CUSTOMERADDRESSES_ADDRESS_TEL'); ?></label>
                        <div class="uk-form-controls">
                            <input class="uk-input" id="yps_editaddress_phone" type="text"
                                   v-model="addressForEdit.phone"
                                   name="phone"
                                   placeholder="<?= Text::_('COM_PROTOSTORE_MOD_CUSTOMERADDRESSES_ADDRESS_TEL_PLACEHOLDER'); ?>"
								<?= ($config->get('telephone_required') ? 'required' : ''); ?>>
                        </div>
                    </div>
				<?php endif; ?>

				<?php if ($config->get('email_show')): ?>
                    <div class="uk-margin">
                        <label class="uk-form-label"
                               for="yps_editaddress_email"><?= Text::_('COM_PROTOSTORE_MOD_CUSTOMERADDRESSES_ADDRESS_EMAIL'); ?></label>
                        <div class="uk-form-controls">
                            <input class="uk-input" id="yps_editaddress_email" type="email"
                                   v-model="addressForEdit.email"
                                   name="email"
                                   placeholder="<?= Text::_('COM_PROTOSTORE_MOD_CUSTOMERADDRESSES_ADDRESS_EMAIL_PLACEHOLDER'); ?>"
								<?= ($config->get('email_required') ? 'required' : ''); ?>>
                        </div>
                    </div>
				<?php endif; ?>
                <p class="uk-text-right">
                    <button class="uk-button uk-button-default uk-modal-close"
                            type="button"><?= Text::_('COM_PROTOSTORE_MOD_CUSTOMERADDRESSES_ADDRESS_CANCEL'); ?></button>
                    <button class="uk-button uk-button-primary" type="submit"
                    ><?= Text::_('COM_PROTOSTORE_MOD_CUSTOMERADDRESSES_ADDRESS_SAVE'); ?>
                    </button>
                </p>
            </form>


        </div>
    </div>

    <div id="yps-addAddressModal" class="uk-modal-container" uk-modal="stack: true">
        <div class="uk-modal-dialog uk-modal-body">
            <h2 class="uk-modal-title"><?= Text::_('COM_PROTOSTORE_MOD_CUSTOMERADDRESSES_ADD_NEW_ADDRESS'); ?></h2>
            <form class="uk-margin-bottom" @submit.prevent="submitAddAddress">

                <legend class="uk-legend"><?= Text::_('COM_PROTOSTORE_ELM_CART_USER_ADDRESS_LEGEND'); ?></legend>

                <div class="uk-margin">
                    <label class="uk-form-label"
                           for="yps_cart_name"><?= Text::_('COM_PROTOSTORE_MOD_CUSTOMERADDRESSES_ADDRESS_NAME'); ?></label>
                    <div class="uk-form-controls">
                        <input class="uk-input" id="yps_cart_name" type="text"
                               placeholder="<?= Text::_('COM_PROTOSTORE_MOD_CUSTOMERADDRESSES_ADDRESS_NAME_PLACEHOLDER'); ?>"
                               required name="name" v-model="newAddress.name">
                    </div>
                </div>

				<?php if ($config->get('address_show', 1)): ?>

                    <div class="uk-margin">
                        <label class="uk-form-label"
                               for="yps_cart_address1"><?= Text::_('COM_PROTOSTORE_MOD_CUSTOMERADDRESSES_ADDRESS_ADDRESS_LINE1'); ?></label>
                        <div class="uk-form-controls">
                            <input class="uk-input" id="yps_cart_address1" type="text" required name="address1"
                                   :class="{ 'uk-form-danger' : formErrorsList['address1'] !== undefined ? true : false}"
                                   :style="formErrorsList['address1'] !== undefined ? 'border-colour: red; border-style: solid; border-width: 1px;' : ''"
                                   placeholder="<?= Text::_('COM_PROTOSTORE_MOD_CUSTOMERADDRESSES_ADDRESS_ADDRESS_LINE1_PLACEHOLDER'); ?>"
                                   v-model="newAddress.address1">
                        </div>
                    </div>

					<?php if ($config->get('addressline2_show', 1)): ?>

                        <div class="uk-margin">
                            <label class="uk-form-label"
                                   for="yps_cart_address2"><?= Text::_('COM_PROTOSTORE_MOD_CUSTOMERADDRESSES_ADDRESS_ADDRESS_LINE2'); ?></label>
                            <div class="uk-form-controls">
                                <input class="uk-input" id="yps_cart_address2" type="text" name="address2"
                                       :class="{ 'uk-form-danger' : formErrorsList['address2'] !== undefined ? true : false}"
                                       :style="formErrorsList['address2'] !== undefined ? 'border-colour: red; border-style: solid; border-width: 1px;' : ''"
                                       placeholder="<?= Text::_('COM_PROTOSTORE_MOD_CUSTOMERADDRESSES_ADDRESS_ADDRESS_LINE2_PLACEHOLDER'); ?>" <?= ($config->get('addressline2_required') ? 'required' : ''); ?>
                                       v-model="newAddress.address2">
                            </div>
                        </div>
					<?php endif; ?>


					<?php if ($config->get('addressline3_show', 1)): ?>
                        <div class="uk-margin">
                            <label class="uk-form-label"
                                   for="yps_cart_address3"><?= Text::_('COM_PROTOSTORE_MOD_CUSTOMERADDRESSES_ADDRESS_ADDRESS_LINE3'); ?></label>
                            <div class="uk-form-controls">
                                <input class="uk-input" id="yps_cart_address3" type="text" name="address3"
                                       :class="{ 'uk-form-danger' : formErrorsList['address3'] !== undefined ? true : false}"
                                       :style="formErrorsList['address3'] !== undefined ? 'border-colour: red; border-style: solid; border-width: 1px;' : ''"
                                       placeholder="<?= Text::_('COM_PROTOSTORE_MOD_CUSTOMERADDRESSES_ADDRESS_ADDRESS_LINE3_PLACEHOLDER'); ?>" <?= ($config->get('addressline3_required') ? 'required' : ''); ?>
                                       v-model="newAddress.address3">
                            </div>
                        </div>

					<?php endif; ?>
					<?php if ($config->get('town_show', 1)): ?>
                        <div class="uk-margin">
                            <label class="uk-form-label"
                                   for="yps_cart_town"><?= Text::_('COM_PROTOSTORE_MOD_CUSTOMERADDRESSES_ADDRESS_TOWN'); ?></label>
                            <div class="uk-form-controls">
                                <input class="uk-input" id="yps_cart_town"
                                       :class="{ 'uk-form-danger' : formErrorsList['town'] !== undefined ? true : false}"
                                       :style="formErrorsList['town'] !== undefined ? 'border-colour: red; border-style: solid; border-width: 1px;' : ''"
                                       type="text" name="town"
                                       placeholder="<?= Text::_('COM_PROTOSTORE_MOD_CUSTOMERADDRESSES_ADDRESS_TOWN_PLACEHOLDER'); ?>" <?= ($config->get('town_required') ? 'required' : ''); ?>
                                       v-model="newAddress.town">
                            </div>
                        </div>
					<?php endif; ?>
					<?php if ($config->get('postcode_show', 1)): ?>
                        <div class="uk-margin">
                            <label class="uk-form-label"
                                   for="yps_cart_postcode"><?= Text::_('COM_PROTOSTORE_MOD_CUSTOMERADDRESSES_ADDRESS_POSTCODE'); ?></label>
                            <div class="uk-form-controls">
                                <input class="uk-input" id="yps_cart_postcode" type="text" name="postcode"
                                       :class="{ 'uk-form-danger' : formErrorsList['postcode'] !== undefined ? true : false}"
                                       :style="formErrorsList['postcode'] !== undefined ? 'border-colour: red; border-style: solid; border-width: 1px;' : ''"
                                       placeholder="<?= Text::_('COM_PROTOSTORE_MOD_CUSTOMERADDRESSES_ADDRESS_POSTCODE_PLACEHOLDER'); ?>" <?= ($config->get('postcode_required') ? 'required' : ''); ?>
                                       v-model="newAddress.postcode">
                            </div>
                        </div>
					<?php endif; ?>
                    <div class="uk-margin">
                        <label class="uk-form-label"
                               for="yps_cart_country"><?= Text::_('COM_PROTOSTORE_MOD_CUSTOMERADDRESSES_ADDRESS_COUNTRY'); ?></label>
                        <div class="uk-form-controls">
                            <select @change="updateZones()" class="uk-select" id="yps_cart_country" name="country"
                                    v-model="newAddress.country"
                                    :class="{ 'uk-form-danger' : formErrorsList['country'] !== undefined ? true : false}"
                                    :style="formErrorsList['country'] !== undefined ? 'border-colour: red; border-style: solid; border-width: 1px;' : ''"
                            >
                                <option value=""><?= Text::_('COM_PROTOSTORE_MOD_CUSTOMERADDRESSES_ADDRESS_COUNTRY_SELECT_DEFAULT'); ?></option>
								<?php foreach ($props['countries'] as $country) : ?>
                                    <option value="<?= $country->id; ?>"><?= $country->country_name; ?></option>
								<?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="uk-margin">
                        <label class="uk-form-label"
                               for="yps_cart_zone"><?= Text::_('COM_PROTOSTORE_MOD_CUSTOMERADDRESSES_ADDRESS_STATE'); ?></label>
                        <div class="uk-form-controls">
                            <select class="uk-select" id="yps_cart_zone" name="zone" v-model="newAddress.zone"
                                    :class="{ 'uk-form-danger' : formErrorsList['zone'] !== undefined ? true : false}"
                                    :style="formErrorsList['zone'] !== undefined ? 'border-colour: red; border-style: solid; border-width: 1px;' : ''"
                            >
                                <option value=""
                                        disabled><?= Text::_('COM_PROTOSTORE_MOD_CUSTOMERADDRESSES_ADDRESS_STATE_SELECT_DEFAULT'); ?></option>
                                <option v-for="zone in zones" :value="zone.id">{{ zone.zone_name }}</option>
                            </select>
                        </div>
                    </div>

				<?php endif; // ends 'address_show' ?>
				<?php if ($config->get('mtelephone_show', 1)): ?>
                    <div class="uk-margin">
                        <label class="uk-form-label"
                               for="yps_cart_mobile"><?= Text::_('COM_PROTOSTORE_MOD_CUSTOMERADDRESSES_ADDRESS_MOBILE'); ?></label>
                        <div class="uk-form-controls">
                            <input class="uk-input" id="yps_cart_mobile" type="text" name="mobilephone"
                                   :class="{ 'uk-form-danger' : formErrorsList['mobilephone'] !== undefined ? true : false}"
                                   :style="formErrorsList['mobilephone'] !== undefined ? 'border-colour: red; border-style: solid; border-width: 1px;' : ''"
                                   placeholder="<?= Text::_('COM_PROTOSTORE_MOD_CUSTOMERADDRESSES_ADDRESS_MOBILE_PLACEHOLDER'); ?>" <?= ($config->get('mtelephone_required') ? 'required' : ''); ?>
                                   v-model="newAddress.mobilephone">
                        </div>
                    </div>
				<?php endif; ?>
				<?php if ($config->get('telephone_show', 1)): ?>
                    <div class="uk-margin">
                        <label class="uk-form-label"
                               for="yps_cart_phone"><?= Text::_('COM_PROTOSTORE_MOD_CUSTOMERADDRESSES_ADDRESS_TEL'); ?></label>
                        <div class="uk-form-controls">
                            <input class="uk-input" id="yps_cart_phone" type="text" name="phone"
                                   :class="{ 'uk-form-danger' : formErrorsList['phone'] !== undefined ? true : false}"
                                   :style="formErrorsList['phone'] !== undefined ? 'border-colour: red; border-style: solid; border-width: 1px;' : ''"
                                   placeholder="<?= Text::_('COM_PROTOSTORE_MOD_CUSTOMERADDRESSES_ADDRESS_TEL_PLACEHOLDER'); ?>" <?= ($config->get('telephone_required') ? 'required' : ''); ?>
                                   v-model="newAddress.phone">
                        </div>
                    </div>
				<?php endif; ?>

				<?php if ($config->get('email_show', 1)): ?>
                    <div class="uk-margin">
                        <label class="uk-form-label"
                               for="yps_cartsignup_email"><?= Text::_('COM_PROTOSTORE_MOD_CUSTOMERADDRESSES_ADDRESS_EMAIL'); ?></label>
                        <div class="uk-form-controls">
                            <input class="uk-input" id="yps_cart_email" type="email" name="email"
                                   :class="{ 'uk-form-danger' : formErrorsList['email'] !== undefined ? true : false}"
                                   :style="formErrorsList['email'] !== undefined ? 'border-colour: red; border-style: solid; border-width: 1px;' : ''"
                                   placeholder="<?= Text::_('COM_PROTOSTORE_MOD_CUSTOMERADDRESSES_ADDRESS_EMAIL_PLACEHOLDER'); ?>" <?= ($config->get('email_required') ? 'required' : ''); ?>
                                   v-model="newAddress.email">
                        </div>
                    </div>
				<?php endif; ?>
                <div class="uk-grid">
                    <div class="uk-width-expand">
                        <button class="uk-button uk-button-primary" type="submit">
							<?= Text::_('COM_PROTOSTORE_MOD_CUSTOMERADDRESSES_ADDRESS_CONFIRM'); ?>
                        </button>
                    </div>
                    <div class="uk-width-auto">
                        <div class="uk-text-right">
                            <button id="yps__cancel" class="uk-button uk-button-default"
                                    type="button">
								<?= Text::_('COM_PROTOSTORE_MOD_CUSTOMERADDRESSES_ADDRESS_CANCEL'); ?>
                            </button>
                        </div>
                    </div>
                </div>

            </form>

            <p class="uk-text-right">
                <button class="uk-button uk-button-default uk-modal-close"
                        type="button"><?= Text::_('COM_PROTOSTORE_MOD_CUSTOMERADDRESSES_ADDRESS_CANCEL'); ?></button>
                <button class="uk-button uk-button-primary"
                        type="submit"
                        form="yps_address_form_add"><?= Text::_('COM_PROTOSTORE_MOD_CUSTOMERADDRESSES_ADDRESS_SAVE'); ?></button>
            </p>
        </div>
    </div>
</div>
<script>
    //VUE!
    const <?= $id; ?> = {
        data() {
            return {
                addresses: <?= json_encode($addresses); ?>,
                newAddress: {
                    name: '',
                    address1: '',
                    address2: '',
                    address3: '',
                    town: '',
                    country: '',
                    zone: '',
                    postcode: '',
                    mobilephone: '',
                    phone: '',
                    email: '',
                },
                addressForEdit: {
                    name: '',
                    address1: '',
                    address2: '',
                    address3: '',
                    town: '',
                    country: '',
                    zone: '',
                    postcode: '',
                    mobilephone: '',
                    phone: '',
                    email: '',
                },
                formErrors: '',
                formErrorsList: '',
            }

        },
        async beforeMount() {


        },
        mounted() {


        },
        methods: {
            openEditModal(address) {
                this.addressForEdit = address;
                UIkit.modal('#yps-editAddressModal').show()

            },
            async submitAddAddress() {
                await UIkit.modal.confirm('<?= Text::_('COM_PROTOSTORE_ADDRESS_SAVE_CONFIRM'); ?>', {stack: true});
                await UIkit.modal("#yps-addAddressModal").hide();

                this.loading = true;

                const request = await fetch("<?= Uri::base(); ?>index.php?option=com_ajax&plugin=protostore_ajaxhelper&method=post&task=task&type=address.addAddress&format=raw", {
                    method: 'POST',
                    mode: 'cors',
                    cache: 'no-cache',
                    credentials: 'same-origin',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    redirect: 'follow',
                    referrerPolicy: 'no-referrer',
                    body: JSON.stringify(this.newAddress)
                });

                const response = await request.json();

                if (response.success) {

                    if (response.data.status === 'ok') {
                        this.loading = false;
                        UIkit.notification({
                            message: '<span uk-icon=\'icon: check\'></span> <?= Text::_('COM_PROTOSTORE_ELM_CART_USER_ALERT_ADDRESS_ADDED'); ?>',
                            status: 'success',
                            pos: 'top-center'
                        });
                        this.updateCustomerAddresses();
                    } else {
                        this.loading = false;
                        UIkit.notification({
                            message: '<span uk-icon=\'icon: ban\'></span> <?= Text::_('COM_PROTOSTORE_ELM_CART_USER_ALERT_ERROR_IN_ADDRESS_FORM'); ?>',
                            status: 'warning',
                            pos: 'top-center'
                        });
                        this.formErrors = response.data.errors;
                        this.formErrorsList = response.data.errorsList;
                    }

                } else {
                    UIkit.notification({
                        message: 'ERROR',
                        status: 'danger',
                        pos: 'top-center'
                    });
                }


            },
            async updateCustomerAddresses() {


                const params = {
                    customer_id: <?= $customer->id; ?>
                }

                const request = await fetch("<?= Uri::base(); ?>index.php?option=com_ajax&plugin=protostore_ajaxhelper&method=post&task=task&type=address.getCustomerAddresses&format=raw", {
                    method: 'POST',
                    mode: 'cors',
                    cache: 'no-cache',
                    credentials: 'same-origin',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    redirect: 'follow',
                    referrerPolicy: 'no-referrer',
                    body: JSON.stringify(params)
                });

                const response = await request.json();

                if (response.success) {
                    this.addresses = response.data;

                } else {
                    UIkit.notification({
                        message: 'ERROR',
                        status: 'danger',
                        pos: 'top-center'
                    });
                }

            },
            async submitUpdateAddress(){

            },
            updateZones(countryId){

            },
            deleteAddress(i) {
            }
        }
    }
    Vue.createApp(<?= $id; ?>).mount('#<?= $id; ?>')

</script>
