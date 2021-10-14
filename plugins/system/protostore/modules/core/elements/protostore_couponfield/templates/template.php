<?php
/**
 * @package   Pro2Store
 * @author    Ray Lawlor - pro2.store
 * @copyright Copyright (C) 2021 Ray Lawlor - pro2.store
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 *
 */

use Joomla\CMS\Uri\Uri;

defined('_JEXEC') or die;

$id = uniqid('yps_couponfield');

$el = $this->el('div', [

	'class' => [
		'uk-panel {@!style}',
		'uk-card uk-card-body uk-{style}'
	]

]);


?>


<?= $el($props) ?>
<div id="<?= $id; ?>">

    <div v-if="isCouponApplied">
        <div class="uk-margin yps-coupon-applied">
            <h5>{{couponapplied}}: {{appliedcouponcode}}</h5>
            <button class="uk-button uk-button-small" @click="removeCoupon">{{removebuttontext}}</button>
        </div>
    </div>

    <div v-if="!isCouponApplied">
        <div class="uk-margin" uk-margin>
            <div class="uk-grid uk-grid-small" uk-grid>
                <div class="uk-width-expand">
                    <input :placeholder="entercouponcode" class="uk-input uk-width-1-1" type="text"
                           v-model="couponCode">
                </div>
                <div>
                    <button @click="applyCoupon" class="uk-button uk-button-default">{{buttontext}}</button>
                </div>
            </div>
        </div>
    </div>


</div>

</div>

<script>
    const <?= $id; ?> = {
        data() {
            return {
                baseUrl: '',
                isCouponApplied: false,
                appliedcouponcode: '',
                buttontext: '',
                removebuttontext: '',
                couponapplied: '',
                entercouponcode: '',
                couponremoved: '',
                couponCode: ''
            }
        },
        beforeMount() {

            // set the data from the inline script

            const isCouponApplied = document.getElementById('yps-coupon-field-isCouponApplied');
            try {
                this.isCouponApplied = (isCouponApplied.innerText == 'true' ? true : false);
                isCouponApplied.remove();

            } catch (err) {
            }

            const buttontext = document.getElementById('yps-coupon-field-buttontext');
            try {
                this.buttontext = buttontext.innerText;
                buttontext.remove();

            } catch (err) {
            }

            const appliedcouponcode = document.getElementById('yps-coupon-field-appliedcouponcode');
            try {
                this.appliedcouponcode = appliedcouponcode.innerText;
                appliedcouponcode.remove();
            } catch (err) {
            }

            const removebuttontext = document.getElementById('yps-coupon-field-removebuttontext');
            try {
                this.removebuttontext = removebuttontext.innerText;
                removebuttontext.remove();
            } catch (err) {
            }

            const couponapplied = document.getElementById('yps-coupon-field-couponapplied');
            try {
                this.couponapplied = couponapplied.innerText;
                couponapplied.remove();
            } catch (err) {
            }

            const entercouponcode = document.getElementById('yps-coupon-field-entercouponcode');
            try {
                this.entercouponcode = entercouponcode.innerText;
                entercouponcode.remove();
            } catch (err) {
            }

            const couponremoved = document.getElementById('yps-coupon-field-couponremoved');
            try {
                this.couponremoved = couponremoved.innerText;
                couponremoved.remove();
            } catch (err) {
            }



        },
        methods: {
            async removeCoupon() {

                const params = {};

                const request = await fetch("<?= Uri::base(); ?>index.php?option=com_ajax&plugin=protostore_ajaxhelper&method=post&task=task&type=coupon.remove&format=raw", {
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
                    if (response.data) {
                        UIkit.notification({
                            message: '<span uk-icon=\'icon: check\'></span>' + this.couponremoved,
                            status: 'success',
                            pos: 'top-center'
                        });
                        this.isCouponApplied = false;
                        emitter.emit("yps_cart_update")
                    }

                } else {
                    UIkit.notification({
                        message: 'There was an error.',
                        status: 'danger',
                        pos: 'top-center',
                        timeout: 5000
                    });
                }

            },
            async applyCoupon() {

                const params = {
                    'couponCode': this.couponCode,
                };

                const URLparams = this.serialize(params);

                const request = await fetch('<?= Uri::base(); ?>index.php?option=com_ajax&plugin=protostore_ajaxhelper&method=post&task=task&type=coupon.apply&format=raw&' + URLparams, {method: 'post'});


                const response = await request.json();

                if (response.success) {
                    if (response.data.applied) {
                        UIkit.notification({
                            message: '<span uk-icon=\'icon: check\'></span>' + this.couponapplied,
                            status: 'success',
                            pos: 'top-center'
                        });
                        this.isCouponApplied = true;
                        this.appliedcouponcode = this.couponCode;
                        emitter.emit("yps_cart_update")
                    } else {
                        UIkit.notification({
                            message: response.message,
                            status: 'danger',
                            pos: 'top-center',
                            timeout: 5000
                        });
                    }

                } else {
                    UIkit.notification({
                        message: response.message,
                        status: 'danger',
                        pos: 'top-center',
                        timeout: 5000
                    });
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
    };

    Vue.createApp(<?= $id; ?>).mount('#<?= $id; ?>')

</script>
