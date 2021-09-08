<?php

/**
 * @package     Pro2Store - Total
 *
 * @copyright   Copyright (C) 2021 Ray Lawlor - Pro2Store. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */


$id = uniqid('yps_total');


// Create div tag
$el = $this->el($props['title_element'], [

	'class' => [
		'uk-{title_style}',
		'uk-heading-{title_decoration}',
		'uk-font-{title_font_family}',
		'uk-text-{title_color} {@!title_color: background}',
		'uk-margin-remove {position: absolute}',
	]

]);

?>
<script id="yps-total-baseUrl" type="application/json"><?= $props['baseUrl']; ?></script>
<script id="yps_total_item_id_data" type="application/json"><?= $props['item_id']; ?></script>
<?= $el($props, $attrs) ?>
<div id="<?= $id; ?>">
    <div v-show="loading" id="yps-total-spinner" uk-spinner></div>
    <span v-show="!loading" style="display: none">{{total}}</span>
</div>
<?= $el->end(); ?>

<script>

    window.addEventListener('load', function () {
        const <?= $id; ?> = {
            data() {
                return {
                    baseUrl: '',
                    total: 0,
                    loading: false,
                    itemid: 0,
                    ioptions: [],
                    multiplier: 1,
                    selected: [],
                }
            },
            created() {
                emitter.on("yps_amountChange", this.setMultiplier)
                emitter.on("yps_product_update", this.recalculateTotal)
                emitter.on("yps_optionsChange", this.recalculateTotal)
            },
            async beforeMount() {
                // set the itemid from the inline scripts
                const itemId = document.getElementById('yps_total_item_id_data');
                try {
                    this.itemid = itemId.innerText;
                } catch (err) {
                    throw new Error('Data is missing...')
                }

                if (!this.itemid) {
                    throw new Error('Data is missing...')
                }
                itemId.remove();


                const baseUrl = document.getElementById('yps-total-baseUrl');
                this.baseUrl = baseUrl.innerText;
                baseUrl.remove();


            },
            async mounted() {

                // var selectElements = document.getElementsByClassName('yps_option');
                // for (let item of selectElements) {
                //     var selected = item.options[item.selectedIndex];
                //     var thisoption = {
                //         optionid: selected.getAttribute('data-optionid'),
                //     };
                //     this.ioptions.push(thisoption);
                // }
                //
                // var checkboxElements = document.getElementsByClassName('yps_option_checkbox');
                // for (let item of checkboxElements) {
                //     if (item.checked) {
                //
                //         var thisoption = {
                //             optionid: item.getAttribute('data-optionid'),
                //         };
                //         this.ioptions.push(thisoption);
                //
                //     }
                //
                // }
                // var radioElements = document.getElementsByClassName('yps_option_radio');
                // for (let item of radioElements) {
                //     if (item.checked) {
                //
                //         var thisoption = {
                //             optionid: item.getAttribute('data-optionid'),
                //         };
                //         this.ioptions.push(thisoption);
                //
                //     }
                //
                // }
                //
                //
                // var params = {
                //     'contentitemid': this.itemid,
                //     'itemoptions': JSON.stringify(this.ioptions),
                //     'amount': this.amount
                // };
                //
                // const URLparams = new URLSearchParams(Object.entries(params));
                // URLparams.toString();
                //
                //
                // this.loading = true;
                // const requestTotal = await fetch(this.baseUrl + "index.php?option=com_ajax&plugin=protostore_ajaxhelper&method=post&task=task&type=product.calculatePrice&format=raw&" + URLparams, {
                //     method: 'get',
                // });
                //
                // const responseTotal = await requestTotal.json();
                //
                // if (responseTotal.success) {
                //     this.total = responseTotal.data;
                //     this.loading = false;
                // } else {
                //     UIkit.notification({
                //         message: 'There was an error.',
                //         status: 'danger',
                //         pos: 'top-center',
                //         timeout: 5000
                //     });
                // }

                await this.recalculateTotal();
            },
            methods: {

               async recalculateTotal() {

                    console.log(this.selected);
                    this.loading = true;

                    // var ioptions = [];
                    //
                    // var selectElements = document.getElementsByClassName('yps_option');
                    // for (let item of selectElements) {
                    //     var selected = item.options[item.selectedIndex];
                    //     var thisoption = {
                    //         optionid: selected.getAttribute('data-optionid'),
                    //     };
                    //     ioptions.push(thisoption);
                    // }
                    //
                    // var checkboxElements = document.getElementsByClassName('yps_option_checkbox');
                    // for (let item of checkboxElements) {
                    //     if (item.checked) {
                    //
                    //         var thisoption = {
                    //             optionid: item.getAttribute('data-optionid'),
                    //         };
                    //         ioptions.push(thisoption);
                    //
                    //     }
                    //
                    // }
                    // var radioElements = document.getElementsByClassName('yps_option_radio');
                    // for (let item of radioElements) {
                    //     if (item.checked) {
                    //
                    //         var thisoption = {
                    //             optionid: item.getAttribute('data-optionid'),
                    //         };
                    //         ioptions.push(thisoption);
                    //
                    //     }
                    //
                    // }


                    let selected = [];
                    const selectedVariants = document.getElementsByClassName('yps_variant');
                    Array.prototype.forEach.call(selectedVariants, function (el) {
                        selected.push(el.value);
                        console.log(el.value)
                    });

                    this.selected = selected;

                    console.log(this.selected)

                    var params = {
                        'joomla_item_id': this.itemid,
                        'selectedVariants': this.selected,
                        'multiplier': this.multiplier
                    };


                    const request = await fetch(this.base_url + "index.php?option=com_ajax&plugin=protostore_ajaxhelper&method=post&task=task&type=product.calculatePrice&format=raw", {
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
                        this.total = response.data.string;
                        this.loading = false;
                    } else {
                        UIkit.notification({
                            message: 'There was an error.',
                            status: 'danger',
                            pos: 'top-center',
                            timeout: 5000
                        });
                    }


                },
                setMultiplier(multiplier) {
                    //set the amount
                    this.multiplier = multiplier;
                }

            }
        }

        Vue.createApp(<?= $id; ?>).mount('#<?= $id; ?>')
    });
</script>
