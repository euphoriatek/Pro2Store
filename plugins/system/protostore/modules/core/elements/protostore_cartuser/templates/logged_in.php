<?php
/**
 * @package   Pro2Store
 * @author    Ray Lawlor - pro2.store
 * @copyright Copyright (C) 2021 Ray Lawlor - pro2.store
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 *
 */

defined('_JEXEC') or die;

use Joomla\CMS\Language\Text;

use Protostore\Config\ConfigFactory;
use Protostore\Customer\CustomerFactory;
use Protostore\Language\LanguageFactory;

$id = uniqid('yps_loggedin');

LanguageFactory::load();

$customer = CustomerFactory::get();
$config   = ConfigFactory::get();


$addresses = $customer->addresses;

echo "{emailcloak=off}";

?>

<script id="yps_logged_in-data-addresses" type="application/json"><?= json_encode($addresses); ?></script>

<div v-cloak id="<?= $id; ?>">

    <div class="uk-grid uk-margin">
        <div class="uk-width-expand"><h5><?= Text::_('COM_PROTOSTORE_ELM_CART_USER_CHOOSE_A_SHIPPING_ADDRESS'); ?></h5>
        </div>
        <div class="uk-width-auto">
            <button @click="openAddAddressForm = !openAddAddressForm"
                    class="uk-button uk-button-primary"><?= Text::_('COM_PROTOSTORE_ELM_CART_USER_ADD_NEW_ADDRESS'); ?>
                <span
                        uk-icon="icon: plus-circle"></span></button>
        </div>
    </div>

    <!--ADDRESS FORM HERE-->
<!--    <button @click="updateCustomerAddresses" class="uk-button uk-button-primary">Update Addresses</button>-->
    <div v-show="openAddAddressForm">

		<?= $this->render("{$__dir}/forms/logged_in_address", compact('props')) ?>

    </div>


    <div v-if="addresses">

        <div v-cloak v-for="address in addresses" class="uk-width-1-1">
            <div @click="selectShippingAddress($event, address)"
                 class="uk-card uk-card-body uk-margin"
                 :class="address.isAssignedShipping ? 'uk-card-primary' : 'uk-card-default'">
                <ul class="uk-iconnav uk-flex-right">
                    <input v-model="address.isAssignedShipping"
                           class="uk-checkbox yps_address_checkbox"
                           type="checkbox">
                </ul>
                <div v-cloak class="uk-flex-left">
                    <h5 v-cloak>{{address.name}}</h5>
                    {{address.address_as_csv}}
                </div>
            </div>
        </div>


        <div class="uk-margin uk-grid-small uk-child-width-auto uk-grid">
            <label><input class="uk-checkbox" @input="toggleSeparateBilling"
                          type="checkbox" :checked="!doesOrderHaveUniqueBillingAddressAssigned">
				<?= Text::_('COM_PROTOSTORE_ELM_CART_USER_BILLING_SAME_AS_SHIPPING'); ?></label>
        </div>
        <div v-show="doesOrderHaveUniqueBillingAddressAssigned">
            <h5><?= Text::_('COM_PROTOSTORE_ELM_CART_USER_CHOOSE_A_BILLING_ADDRESS'); ?></h5>


            <div v-cloak v-for="address in addresses" class="uk-width-1-1">
                <div @click="selectBillingAddress(address)"
                     class="uk-card uk-card-body uk-margin"
                     :class="address.isAssignedBilling ? 'uk-card-primary' : 'uk-card-default'">
                    <ul class="uk-iconnav uk-flex-right">
                        <input v-model="address.isAssignedBilling"
                               class="uk-checkbox yps_address_checkbox"
                               type="checkbox">
                    </ul>
                    <div class="uk-flex-left">
                        <h5>{{address.name}}</h5>
                        {{address.address_as_csv}}
                    </div>
                </div>
            </div>

        </div>
    </div>


    <h3 v-if="!addresses"><?= Text::_('COM_PROTOSTORE_ELM_CART_USER_PLEASE_ADD_AN_ADDRESS'); ?></h3>


</div>


<script>
    const <?= $id; ?> = {
        data() {
            return {
                baseUrl: '',
                address: {
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
                addresses: [],
                zones: '',
                formErrors: '',
                formErrorsList: '',
                loading: false,
                openAddAddressForm: false,
                doesOrderHaveUniqueBillingAddressAssigned: false,
                //language strings
                COM_PROTOSTORE_ELM_CART_USER_ALERT_ADDRESS_ADDED: '<?= Text::_('COM_PROTOSTORE_ELM_CART_USER_ALERT_ADDRESS_ADDED'); ?>',
                COM_PROTOSTORE_ELM_CART_USER_ALERT_ERROR_IN_ADDRESS_FORM: '<?= Text::_('COM_PROTOSTORE_ELM_CART_USER_ALERT_ERROR_IN_ADDRESS_FORM'); ?>',
                COM_PROTOSTORE_ELM_CART_USER_ALERT_ADDRESS_ASSIGNED: '<?= Text::_('COM_PROTOSTORE_ELM_CART_USER_ALERT_ADDRESS_ASSIGNED'); ?>'
            }
        },
        async beforeMount() {
            // set the data from the inline scripts
            const baseUrl = document.getElementById('yps-cartuser-baseUrl');
            this.baseUrl = baseUrl.innerText;
            baseUrl.remove();

            const addresses = document.getElementById('yps_logged_in-data-addresses');
            this.addresses = JSON.parse(addresses.innerText);
            addresses.remove();

        },
        methods: {
            async selectShippingAddress(e, address) {

                // if the current address is clicked... no nothing
                if (address.isAssignedShipping == true) {
                    address.isAssignedShipping = true;
                    e.preventDefault();
                    return;
                }

                // make sure to set all other addresses as false.
                this.addresses.forEach((thisaddress) => {
                    thisaddress.isAssignedShipping = false;
                });
                // set this address to true
                address.isAssignedShipping = true;


                // init params
                const params = {
                    'shipping_address_id': address.id,
                    'shipping_type': 'shipping'
                };

                const request = await fetch(this.baseUrl + "index.php?option=com_ajax&plugin=protostore_ajaxhelper&method=post&task=task&type=cart.assignaddress&format=raw", {
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
                if (response.data.status == 'ok') {
                    emitter.emit('yps_cart_update');
                    this.updateCustomerAddresses();
                    this.loading = false;
                    UIkit.notification({
                        message: this.COM_PROTOSTORE_ELM_CART_USER_ALERT_ADDRESS_ASSIGNED,
                        status: 'success',
                        pos: 'top-right',
                        timeout: 5000
                    });


                    if (!this.doesOrderHaveUniqueBillingAddressAssigned) {


                        const params = {
                            'shipping_address_id': address.id,
                            'shipping_type': 'billing'
                        };

                        const request = await fetch(this.baseUrl + "index.php?option=com_ajax&plugin=protostore_ajaxhelper&method=post&task=task&type=cart.assignaddress&format=raw", {
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
                        emitter.emit('yps_cart_update');
                    }

                }
            },
            async selectBillingAddress(address) {

                this.addresses.forEach((thisaddress) => {
                    thisaddress.isAssignedBilling = false;
                });
                address.isAssignedBilling = true;

                // init params
                const params = {
                    'shipping_address_id': address.id,
                    'shipping_type': 'billing'
                };

                const request = await fetch(this.baseUrl + "index.php?option=com_ajax&plugin=protostore_ajaxhelper&method=post&task=task&type=cart.assignaddress&format=raw", {
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
                if (response.data.status == 'ok') {
                    emitter.emit('yps_cart_update');
                    this.updateCustomerAddresses();
                    this.loading = false;
                    UIkit.notification({
                        message: this.COM_PROTOSTORE_ELM_CART_USER_ALERT_ADDRESS_ASSIGNED,
                        status: 'success',
                        pos: 'top-right',
                        timeout: 5000
                    });

                }

            },
            async toggleSeparateBilling(e) {
                if (e.target.checked) {
                    this.doesOrderHaveUniqueBillingAddressAssigned = false;
                    const request = await fetch(this.baseUrl + "index.php?option=com_ajax&plugin=protostore_ajaxhelper&method=post&task=task&type=cart.assignBillingAsShipping&format=raw", {
                        method: 'post'
                    });
                    const response = await request.json();
                    if (response.data.status == 'ok') {
                        this.loading = false;
                        this.updateCustomerAddresses();
                        emitter.emit('yps_cart_update');
                        UIkit.notification({
                            message: this.COM_PROTOSTORE_ELM_CART_USER_ALERT_ADDRESS_ASSIGNED,
                            status: 'success',
                            pos: 'top-right',
                            timeout: 5000
                        });

                    }

                } else {
                    this.updateCustomerAddresses();
                    this.doesOrderHaveUniqueBillingAddressAssigned = 'true';
                }
            },
            async submitAddressForm() {

                this.loading = true;

                const request = await fetch(this.baseUrl + "index.php?option=com_ajax&plugin=protostore_ajaxhelper&method=post&task=task&type=address.addAddress&format=raw", {
                    method: 'POST',
                    mode: 'cors',
                    cache: 'no-cache',
                    credentials: 'same-origin',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    redirect: 'follow',
                    referrerPolicy: 'no-referrer',
                    body: JSON.stringify(this.address)
                });


                const response = await request.json();

                if (response.success) {

                    if (response.data.status === 'ok') {
                        this.loading = false;
                        UIkit.notification({
                            message: '<span uk-icon=\'icon: check\'></span> ' + this.COM_PROTOSTORE_ELM_CART_USER_ALERT_ADDRESS_ADDED,
                            status: 'success',
                            pos: 'top-center'
                        });
                        this.updateCustomerAddresses();
                        this.openAddAddressForm = false;
                        this.doesOrderHaveUniqueBillingAddressAssigned = false;
                        emitter.emit("yps_cart_update");
                    } else {
                        this.loading = false;
                        UIkit.notification({
                            message: '<span uk-icon=\'icon: ban\'></span> ' + this.COM_PROTOSTORE_ELM_CART_USER_ALERT_ERROR_IN_ADDRESS_FORM,
                            status: 'warning',
                            pos: 'top-center'
                        });
                        this.formErrors = responseAddAddress.data.errors;
                        this.formErrorsList = responseAddAddress.data.errorsList;
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
                    customer_id: <?= CustomerFactory::get()->id; ?>
                }

                const request = await fetch(this.baseUrl + "index.php?option=com_ajax&plugin=protostore_ajaxhelper&method=post&task=task&type=address.getCustomerAddresses&format=raw", {
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
            async updateZones() {
                const params = {
                    country_id: this.address.country
                };

                const URLparams = this.serialize(params);

                const request = await fetch(this.baseUrl + "index.php?option=com_ajax&plugin=protostore_ajaxhelper&method=post&task=task&type=address.getZones&format=raw&" + URLparams);

                const response = await request.json();

                if (response.success) {
                    this.zones = response.data;
                }
            },
            serialize(obj) {
                var str = [];
                for (var p in obj)
                    if (obj.hasOwnProperty(p)) {
                        str.push(encodeURIComponent(p) + "=" + encodeURIComponent(obj[p]));
                    }
                return str.join("&");
            }
        }
    }

    Vue.createApp(<?= $id; ?>).mount('#<?= $id; ?>')

</script>
