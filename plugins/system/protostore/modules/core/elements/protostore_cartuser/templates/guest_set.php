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

use Protostore\Address\AddressFactory;
use Protostore\Language\LanguageFactory;

$id = uniqid('yps_guest');

LanguageFactory::load();

echo "{emailcloak=off}";


$shippingAddress = AddressFactory::getCurrentShippingAddress();

if (AddressFactory::doesOrderHaveUniqueBillingAddressAssigned())
{
	$boxChecked     = false;
	$billingAddress = AddressFactory::getCurrentBillingAddress();
}
else
{
	$billingAddress = [];
	$boxChecked     = true;
}

?>

<script id="yps_guest-data-shippingaddress" type="application/json"><?= json_encode($shippingAddress); ?></script>
<script id="yps_guest-data-billingaddress" type="application/json"><?= json_encode($billingAddress); ?></script>
<script id="yps_guest-data-isbillingset" type="application/json"></script>
<script id="yps_guest-data-billingSameAsShipping"
        type="application/json"><?= $boxChecked; ?></script>
<script id="yps_guest-data-alertstartover"
        type="application/json"><?= Text::_('COM_PROTOSTORE_ELM_CART_USER_ALERT_SURE_ABOUT_START_OVER'); ?></script>

<div id="<?= $id; ?>">

    <div class="uk-grid" uk-grid>
        <div class="uk-width-expand">
            <h3><?= Text::_('COM_PROTOSTORE_ELM_CART_USER_GUEST_CHECKOUT'); ?></h3>
        </div>
        <div class="uk-width-auto">
            <div class="uk-margin uk-text-right">
                <button @click="yps_guest_start_over"
                        class="uk-button uk-button-small "><?= Text::_('COM_PROTOSTORE_ELM_CART_USER_START_OVER'); ?></button>
            </div>
        </div>
    </div>


    <h5><?= Text::_('COM_PROTOSTORE_ELM_CART_SHIPPING_ADDRESS'); ?></h5>
    <div v-cloak class="uk-width-1-1">
        <div @click="" class="uk-card uk-card-body uk-card-primary uk-margin yps-shippingAddressBox">
            <div>
                <h5>{{shipping_address.name}}</h5>
                {{shipping_address.address_as_string}}
            </div>
        </div>
    </div>

    <div class="uk-margin uk-grid-small uk-child-width-auto uk-grid">
        <label><input class="uk-checkbox" @change="toggleSeparateBilling" v-model="billingSameAsShipping"
                      type="checkbox">
			<?= Text::_('COM_PROTOSTORE_ELM_CART_USER_BILLING_SAME_AS_SHIPPING'); ?></label>
    </div>

    <div v-show="billingSameAsShipping == false" class="uk-width-1-1">

        <!--        //NEW ADDRESS FORM HERE-->

		<?= $this->render("{$__dir}/forms/guest_billing", compact('props')) ?>


        <h5 v-show="billing_address"><?= Text::_('COM_PROTOSTORE_ELM_CART_BILLING_ADDRESS'); ?></h5>
        <div v-cloak v-show="billing_address"
             class="uk-card uk-card-body uk-card-primary uk-margin yps-shippingAddressBox">
            <div>
                <h5>{{billing_address.name}}</h5>
                {{billing_address.address_as_string}}
            </div>
        </div>
    </div>


</div>


<script>
    const <?= $id; ?> = {
        data() {
            return {
                baseUrl: '',
                shipping_address: [],
                billing_address: [],
                billing_address_form: {
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
                zones: '',
                formErrors: '',
                formErrorsList: '',
                loading: false,
                billingSameAsShipping: false,
                COM_PROTOSTORE_ELM_CART_USER_ALERT_SURE_ABOUT_START_OVER: ''
            }
        },
        async beforeMount() {
            // set the data from the inline scripts
            const billingSameAsShipping = document.getElementById('yps_guest-data-billingSameAsShipping');

            if (billingSameAsShipping.innerText == 1) {
                this.billingSameAsShipping = true;
            }
            billingSameAsShipping.remove();

            const baseUrl = document.getElementById('yps-cartuser-baseUrl');
            this.baseUrl = baseUrl.innerText;
            baseUrl.remove();

            const shipping_address = document.getElementById('yps_guest-data-shippingaddress');
            this.shipping_address = JSON.parse(shipping_address.innerText);
            shipping_address.remove();

            const billing_address = document.getElementById('yps_guest-data-billingaddress');
            this.billing_address = JSON.parse(billing_address.innerText);
            billing_address.remove();

            const alertstartover = document.getElementById('yps_guest-data-alertstartover');
            this.COM_PROTOSTORE_ELM_CART_USER_ALERT_SURE_ABOUT_START_OVER = alertstartover.innerText;
            alertstartover.remove();

        },
        methods: {

            async yps_guest_start_over() {

                await UIkit.modal.confirm('<h5>' + this.COM_PROTOSTORE_ELM_CART_USER_ALERT_SURE_ABOUT_START_OVER + '</h5>');

                const request = await fetch(this.baseUrl + "index.php?option=com_ajax&plugin=protostore_ajaxhelper&method=post&task=task&type=cart.startover&format=raw", {
                    method: 'POST',
                    mode: 'cors',
                    cache: 'no-cache',
                    credentials: 'same-origin',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    redirect: 'follow',
                    referrerPolicy: 'no-referrer',
                    body: ''
                });


                const response = await request.json();

                if (response.success) {

                    location.reload();

                } else {
                    UIkit.notification({
                        message: 'ERROR',
                        status: 'danger',
                        pos: 'top-center'
                    });
                }

            },
            async toggleSeparateBilling(e) {
                if (e.target.checked) {

                    const request = await fetch(this.baseUrl + "index.php?option=com_ajax&plugin=protostore_ajaxhelper&method=post&task=task&type=cart.setbillingasshipping&format=raw", {
                        method: 'POST',
                        mode: 'cors',
                        cache: 'no-cache',
                        credentials: 'same-origin',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        redirect: 'follow',
                        referrerPolicy: 'no-referrer',
                        body: ''
                    });
                    const response = await request.json();
                    if (response.success) {
                        location.reload();
                    }
                } else {
                    this.billing_address = false;
                }
            },
            async newguestbillingSubmit() {

                const request = await fetch(this.baseUrl + "index.php?option=com_ajax&plugin=protostore_ajaxhelper&method=post&task=task&type=address.saveguestbilling&format=raw", {
                    method: 'POST',
                    mode: 'cors',
                    cache: 'no-cache',
                    credentials: 'same-origin',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    redirect: 'follow',
                    referrerPolicy: 'no-referrer',
                    body: JSON.stringify(this.billing_address_form)
                });


                const response = await request.json();

                if (response.success) {

                    if (response.data.status === 'ok') {

                        this.loading = false;
                        UIkit.notification({
                            message: '<span uk-icon=\'icon: check\'>' + response.data.message + '</span>',
                            status: 'success',
                            pos: 'top-center'
                        });
                        location.reload();
                    } else {
                        this.loading = false;
                        this.formErrors = response.data.error;
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
            async updateZones() {
                const params = {
                    country_id: this.billing_address_form.country
                };

                const URLparams = this.serialize(params);

                const request = await fetch(this.base_url + "index.php?option=com_ajax&plugin=protostore_ajaxhelper&method=post&task=task&type=address.getZones&format=raw&" + URLparams);

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
