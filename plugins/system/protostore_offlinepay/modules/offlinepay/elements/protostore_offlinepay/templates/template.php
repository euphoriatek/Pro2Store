<?php
/**
 * @package   Pro2Store
 * @author    Ray Lawlor - pro2.store
 * @copyright Copyright (C) 2021 Ray Lawlor - pro2.store
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 *
 */

defined('_JEXEC') or die;


$id = uniqid('yps_offlinepay');


$button = '<button @click="completePurchase" class="uk-button uk-button-' . $props['button_style'] . ' uk-button-' . $props['button_size'] . ' ' . ($props['fullwidth'] ? "uk-width-1-1" : "") . '" :disabled="!readyStatus">';


$el = $this->el('div');

?>

<?= $el($props, $attrs) ?>
<script id="yps_offlinepay_data-baseUrl" type="application/json"><?= $props['baseUrl']; ?></script>
<script id="yps_offlinepay_data-buttonenabled" type="application/json"><?= $props['buttonEnabled']; ?></script>
<script id="yps_offlinepay_data-buttontext" type="application/json"><?= $props['button_text']; ?></script>
<script id="yps_offlinepay_data-buttonprocessingtext" type="application/json"><?= $props['button_processing_text']; ?></script>
<script id="yps_offlinepay_data-buttoncompletetext"  type="application/json"><?= $props['button_complete_text']; ?></script>
<script id="yps_offlinepay_data-showcompleteicon"  type="application/json"><?= $props['complete_icon']; ?></script>
<script id="yps_offlinepay_data-completionurl"  type="application/json"><?=  $props['completionurl']; ?></script>


<div v-cloak id="<?= $id; ?>">
    <?= $button; ?>
    <?php if ($props['icon']) : ?>
        <?php if ($props['icon_align'] == 'left') : ?>
            <span v-show="set" uk-icon="<?= $props['icon'] ?>"></span>
        <?php endif; ?>
    <?php endif; ?>
    <span>{{buttontext}}</span>

    <?php if ($props['icon']) : ?>
        <?php if ($props['icon_align'] == 'right') : ?>
            <span v-show="set" uk-icon="<?= $props['icon'] ?>"></span>
        <?php endif; ?>
    <?php endif; ?>
    <?php if ($props['complete_icon']) : ?>
        <span v-show="complete" uk-icon="check"></span>
    <?php endif; ?>
    <div v-show="loading" id="yps-offline-payment-submit-spinner" uk-spinner></div>
    </button>
</div>
</div>


<script>
    const <?= $id; ?> = {
        data() {
            return {
                base_url: '',
                paymentType: 'Offlinepay',
                buttontext: '',
                buttonprocessingtext: '',
                buttoncompletetext: '',
                set: true,
                loading: false,
                complete: false,
                readyStatus: false,
                showcompleteicon: false,
                completionurl: '',
            }
        },
        created() {
            emitter.on("yps_cart_update", this.check)
        },
        async beforeMount() {
            // set the data from the inline scripts
            const base_url = document.getElementById('yps_offlinepay_data-baseUrl');
            this.base_url = base_url.innerText;
            base_url.remove();

            const buttonenabled = document.getElementById('yps_offlinepay_data-buttonenabled');
            this.readyStatus = buttonenabled.innerText;
            buttonenabled.remove();

            const buttontext = document.getElementById('yps_offlinepay_data-buttontext');
            this.buttontext = buttontext.innerText;
            buttontext.remove();

            const buttonprocessingtext = document.getElementById('yps_offlinepay_data-buttonprocessingtext');
            this.buttonprocessingtext = buttonprocessingtext.innerText;
            buttonprocessingtext.remove();

            const buttoncompletetext = document.getElementById('yps_offlinepay_data-buttoncompletetext');
            this.buttoncompletetext = buttoncompletetext.innerText;
            buttoncompletetext.remove();

            const showcompleteicon = document.getElementById('yps_offlinepay_data-showcompleteicon');
            this.showcompleteicon = showcompleteicon.innerText;
            showcompleteicon.remove();

            const completionurl = document.getElementById('yps_offlinepay_data-completionurl');
            this.completionurl = completionurl.innerText;
            // completionurl.remove();

        },
        methods: {
            async check() {

                const request = await fetch(this.base_url + "index.php?option=com_ajax&plugin=protostore_ajaxhelper&method=post&task=task&type=checkout.status&format=raw", { method: 'post'});

                const response = await request.json();

                if (response.data === true) {
                    this.readyStatus = true;
                } else {
                    this.readyStatus = false;
                }

            },
            async completePurchase() {

                this.loading = true;
                this.set = false;
                this.buttontext = this.buttonprocessingtext;

                const params = {
                    'paymentType':  this.paymentType
                };


                const request = await fetch(this.base_url + "index.php?option=com_ajax&plugin=protostore_ajaxhelper&task=task&type=payment.initPayment&format=raw", {
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
                    this.buttontext = this.buttoncompletetext;
                    this.loading = false;
                    if (this.showcompleteicon) {
                        this.complete = true;
                    }

                    UIkit.notification({
                        message: 'Checkout Success',
                        status: 'success',
                        pos: 'top-right',
                        timeout: 5000
                    });

                    if (response.data.id) {
                        window.location.href = this.completionurl + '&confirmation=' + response.data.hash;
                    }



                } else {

                }


            }
        }
    }


    Vue.createApp(<?= $id; ?>).mount('#<?= $id; ?>');

</script>

