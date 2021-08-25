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

use Joomla\CMS\Language\Text;
use Joomla\CMS\Layout\LayoutHelper;
use Joomla\CMS\Uri\Uri;


$form = $vars['form'];


?>

<div id="p2s_io">
    <div class="uk-margin-left">
        <div class="uk-grid" uk-grid="">
            <div class="uk-width-3-4">
                <div class="uk-grid uk-margin-bottom" uk-grid="">
                    <div class="uk-width-expand">
                        <h1>Import/Export</h1>
                    </div>
                    <div class="uk-width-auto">

                    </div>
                </div>


            </div>
            <div class="uk-width-1-4">
                <div>

                </div>
            </div>
        </div>

    </div>
</div>


<script>
    const p2s_io = {
        data() {
            return {
                base_url: '',
                form: {
                    orders: 0,
                    customers: 0,
                    products: 0,
                    category: 0
                },
                loading: false

            }

        },
        mounted() {

        },
        computed: {},
        async beforeMount() {

            const base_url = document.getElementById('base_url');
            this.base_url = base_url.innerText;
            base_url.remove();


        },
        methods: {

            randomise() {
                this.form.products = this.getRandomInt(15);
                this.form.customers = this.getRandomInt(15);
                this.form.orders = this.getRandomInt(15);

                const select  = document.getElementById('category');

                // fetch all options within the dropdown
                const options = select.children;

                // generate a random number between 0 and the total amount of options
                // the number will always be an index within the bounds of the array (options)
                const random  = Math.floor(Math.random() * options.length);

                // set the value of the dropdown to a random option
                this.form.category = options[random].value;

            },

            async generate() {

                console.log(this.form);

                this.loading = true;

                if (this.form.products > 15) {
                    this.form.products = 15;
                }
                if (this.form.orders > 15) {
                    this.form.orders = 15;
                }
                if (this.form.customers > 15) {
                    this.form.customers = 15;
                }

                const request = await fetch(this.base_url + "index.php?option=com_ajax&plugin=p2sfaker&group=protostore_extended&format=raw", {
                    method: 'POST',
                    mode: 'cors',
                    cache: 'no-cache',
                    credentials: 'same-origin',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    redirect: 'follow',
                    referrerPolicy: 'no-referrer',
                    body: JSON.stringify(this.form)
                });


                const response = await request.json();


                if (response.success) {
                    this.loading = false;
                    UIkit.notification({
                        message: 'Generated!',
                        status: 'success',
                        pos: 'top-center',
                        timeout: 5000
                    });
                } else {
                    UIkit.notification({
                        message: 'There was an error.',
                        status: 'danger',
                        pos: 'top-center',
                        timeout: 5000
                    });
                }


            },
            getRandomInt(max) {
                return Math.floor(Math.random() * max);
            },

        },
        components: {
            'p-inputswitch': primevue.inputswitch
        }
    }
    Vue.createApp(p2s_faker).mount('#p2s_faker');


</script>

