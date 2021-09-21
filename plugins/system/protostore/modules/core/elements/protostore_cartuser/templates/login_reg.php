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

$id = uniqid('yps_loginreg');

$app    = Factory::getApplication();
$config = $app->getParams('com_protostore');

$shownCount = count($props['shown']);

$language = Factory::getLanguage();
$language->load('com_protostore', JPATH_ADMINISTRATOR);

?>

<script id="yps_login_reg-data-addedsuccessfully"
        type="application/json"><?= Text::_('COM_PROTOSTORE_ELM_CART_USER_ALERT_ADDRESS_ADDED'); ?></script>
<script id="yps_login_reg-data-errorinaddressform"
        type="application/json"><?= Text::_('COM_PROTOSTORE_ELM_CART_USER_ALERT_ERROR_IN_ADDRESS_FORM'); ?></script>

<div id="<?= $id; ?>">

	<?php if ($shownCount !== 2) : ?>

        <ul class="uk-subnav uk-subnav-pill" uk-switcher="animation: uk-animation-fade; toggle: > *">
			<?php if (!$props['hideregister']) : ?>
                <li><a href="#"><?= Text::_('COM_PROTOSTORE_ELM_CART_USER_NEW_CUSTOMER'); ?></a></li>
			<?php endif; ?>
			<?php if (!$props['hidelogin']) : ?>
                <li><a href="#"><?= Text::_('COM_PROTOSTORE_ELM_CART_USER_RETURNING_CUSTOMER'); ?></a></li>
			<?php endif; ?>
			<?php if (!$props['hideguest']) : ?>
                <li><a href="#"><?= Text::_('COM_PROTOSTORE_ELM_CART_USER_GUEST_CHECKOUT'); ?></a></li>
			<?php endif; ?>
        </ul>
	<?php endif; ?>
    <div v-cloak class="uk-alert-danger" uk-alert v-show="formErrors">
        <a class="uk-alert-close" uk-close></a>
        <b><?= Text::_('COM_PROTOSTORE_ELM_CART_USER_ALERT_ERROR_IN_ADDRESS_FORM'); ?></b>
        <ul>
            <li v-for="error in formErrorsList">{{ error }}</li>
        </ul>
    </div>
    <ul class="<?php if ($shownCount !== 2) : ?>uk-switcher <?php else : ?>uk-list<?php endif; ?> uk-margin">
		<?php if (!$props['hideregister']) : ?>
            <li>
				<?= $this->render("{$__dir}/forms/register", compact('props')) ?>
            </li>
		<?php endif; ?>
		<?php if (!$props['hidelogin']) : ?>
            <li>
				<?= $this->render("{$__dir}/forms/login", compact('props')) ?>
            </li>
		<?php endif; ?>
		<?php if (!$props['hideguest']) : ?>
            <li>

                <div>
					<?= $this->render("{$__dir}/forms/guest", compact('props')) ?>
                </div>
            </li>
		<?php endif; ?>
    </ul>


</div>


<script>
    const <?= $id; ?> = {
        data() {
            return {
                debug: true,
                baseUrl: '',
                guest_address: {
                    name: '',
                    address1: '',
                    address2: '',
                    address3: '',
                    town: '',
                    country: '',
                    zone: '',
                    postcode: '',
                    mobile_phone: '',
                    phone: '',
                    email: '',
                },
                reg_form: {
                    username: '',
                    password: '',
                    password2: '',
                    name: '',
                    email: '',
                },
                login_form: {
                    username: '',
                    password: ''
                },
                zones: '',
                formErrors: '',
                formErrorsList: '',
                loading: false,
                //language strings
                COM_PROTOSTORE_ELM_CART_USER_ALERT_ADDRESS_ADDED: '',
                COM_PROTOSTORE_ELM_CART_USER_ALERT_ERROR_IN_ADDRESS_FORM: ''
            }
        },
        mounted() {
            if (this.debug) {
                this.reg_form = {
                    username: 'test',
                    password: 'testtest',
                    password2: 'testtest',
                    name: 'test',
                    email: 'test@test.com',
                }
            }

        },

        async beforeMount() {
            // set the data from the inline scripts

            const baseUrl = document.getElementById('yps-cartuser-baseUrl');
            this.baseUrl = baseUrl.innerText;
            baseUrl.remove();

            const addressAdded = document.getElementById('yps_login_reg-data-addedsuccessfully');
            this.COM_PROTOSTORE_ELM_CART_USER_ALERT_ADDRESS_ADDED = addressAdded.innerText;
            addressAdded.remove();

            const errorInAddressForm = document.getElementById('yps_login_reg-data-errorinaddressform');
            this.COM_PROTOSTORE_ELM_CART_USER_ALERT_ERROR_IN_ADDRESS_FORM = errorInAddressForm.innerText;
            errorInAddressForm.remove();

        },
        methods: {

            async submitGuestAddressForm() {

                this.loading = true;


                const request = await fetch(this.baseUrl + "index.php?option=com_ajax&plugin=protostore_ajaxhelper&method=post&task=task&type=address.saveguestshipping&format=raw", {
                    method: 'POST',
                    mode: 'cors',
                    cache: 'no-cache',
                    credentials: 'same-origin',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    redirect: 'follow',
                    referrerPolicy: 'no-referrer',
                    body: JSON.stringify(this.guest_address)
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
            async submitRegisterForm() {

                this.loading = true;

                const request = await fetch(this.baseUrl + "index.php?option=com_ajax&plugin=protostore_ajaxhelper&method=post&task=task&type=user.register&format=raw", {
                    method: 'POST',
                    mode: 'cors',
                    cache: 'no-cache',
                    credentials: 'same-origin',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    redirect: 'follow',
                    referrerPolicy: 'no-referrer',
                    body: JSON.stringify(this.reg_form)
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
            async submitLoginForm() {

                const request = await fetch(this.baseUrl + "index.php?option=com_ajax&plugin=protostore_ajaxhelper&method=post&task=task&type=user.login&format=raw", {
                    method: 'POST',
                    mode: 'cors',
                    cache: 'no-cache',
                    credentials: 'same-origin',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    redirect: 'follow',
                    referrerPolicy: 'no-referrer',
                    body: JSON.stringify(this.login_form)
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
                    country_id: this.guest_address.country
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
