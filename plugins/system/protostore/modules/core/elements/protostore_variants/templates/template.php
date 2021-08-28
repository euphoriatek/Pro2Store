<?php

/**
 * @package     Pro2Store - Variants
 *
 * @copyright   Copyright (C) 2021 Ray Lawlor - Pro2Store. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

$id = uniqid('yps_productvariants');

// Create div tag
$el = $this->el('div', [

	'class' => [
		'uk-panel {@!panel_style}',
		'uk-card uk-{panel_style} [uk-card-{panel_size}]',
		'uk-card-hover {@!panel_style: |card-hover}',
		'uk-card-body {@panel_style}',
	],

]);

// Content
$content = $this->el('div', [

	'class' => [
		'uk-card-body uk-margin-remove-first-child {@panel_style}',
		'uk-padding[-{!panel_content_padding: |default}] uk-margin-remove-first-child {@!panel_style} {@has_panel_content_padding}',
	],

]);

//echo json_encode($props['variantViewObject']);

?>

<script id="yps_product_id_data" type="application/json"><?= $props['product_id']; ?></script>
<script id="yps_variants_data" type="application/json"><?= $props['variants']; ?></script>
<script id="yps_variantLabels_data" type="application/json"><?= $props['variantLabels']; ?></script>


<?= $el($props, $attrs) // Output div tag                    ?>
<div id="<?= $id; ?>">
	<?= $content($props, $attrs) // Content div tag                    ?>


	<?php $variants = json_decode($props['variants']); ?>
	<?php $variantLabels = json_decode($props['variantLabels']); ?>



	<?php foreach ($variants as $key => $variant) : ?>


        <div class="uk-form-controls">
            <label class="uk-form-label"><?= $variant; ?></label>
            <select class="uk-select yps_variant" @change="recalc" >

				<?php foreach ($variantLabels[$key] as $variantLabel) : ?>
                    <option <?= ($props['variantDefault'][$key] == $variantLabel ? 'selected' : ''); ?>
                            data-optionid="<?= $variant; ?>.<?= $variantLabel; ?>"><?= $variantLabel; ?>
                    </option>
				<?php endforeach; ?>

            </select>
        </div>


	<?php endforeach; ?>
    <span v-show="unavailableMessage"><?= $props['unavailableMessage']; ?></span>
</div><!--Vue div tag-->
</div><!--Content div tag-->
</div><!--Output div tag-->
<script>

    const <?= $id; ?> = {
        data() {
            return {
                product_id: 0,
                variants: [],
                variantLabels: [],
                selected: [],
                unavailableMessage: false,
            }
        },
        beforeMount() {

            // set the data from the inline scripts

            const product_id = document.getElementById('yps_product_id_data');
            try {
                this.product_id = product_id.innerText;
                // product_id.remove();
            } catch (err) {
            }
            const variants = document.getElementById('yps_variants_data');
            try {
                this.variants = JSON.parse(variants.innerText);
                // variants.remove();
            } catch (err) {
            }
            const variantLabels = document.getElementById('yps_variantLabels_data');
            try {
                this.variantLabels = JSON.parse(variantLabels.innerText);
                // variantLabels.remove();
            } catch (err) {
            }


        },
        mounted() {

        },
        created() {

        },
        methods: {
            async recalc() {

                let selected = [];
                const selectedVariants = document.getElementsByClassName('yps_variant');
                Array.prototype.forEach.call(selectedVariants, function (el) {
                    selected.push(el.value);
                });

                this.selected = selected;

                const params = {
                    'product_id': this.product_id,
                    'selected': this.selected

                };

                const request = await fetch(this.base_url + "index.php?option=com_ajax&plugin=protostore_ajaxhelper&method=post&task=task&type=product.processVariants&format=raw", {
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

                    if (response.data.active) {
                        this.unavailableMessage = false;
                    } else {

                        this.unavailableMessage = true;

                        // UIkit.notification({
                        //     message: 'There was an error.',
                        //     status: 'danger',
                        //     pos: 'top-center',
                        //     timeout: 5000
                        // });
                    }
                }


                emitter.emit('yps_optionsChange');
            },
            checkIfAvailable(variant) {
                console.log(variant);
                return false;
            },
            arraysMatch(arr1, arr2) {
                // check if 2 arrays are the same
                if (arr1.length !== arr2.length) return false;

                for (var i = 0; i < arr1.length; i++) {
                    if (arr1[i] !== arr2[i]) return false;
                }

                return true;

            }
        }
    }

    Vue.createApp(<?= $id; ?>).mount('#<?= $id; ?>')


</script>
