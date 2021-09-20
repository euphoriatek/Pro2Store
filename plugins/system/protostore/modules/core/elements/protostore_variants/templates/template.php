<?php

/**
 * @package   Pro2Store
 * @author    Ray Lawlor - pro2.store
 * @copyright Copyright (C) 2021 Ray Lawlor - pro2.store
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 *
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

/** @var array $props */
/** @var array $attrs */

?>



<?= $el($props, $attrs) // Output div tag                       ?>
<div id="<?= $id; ?>">
	<?= $content($props, $attrs) // Content div tag                       ?>


    <div class="uk-form-controls" v-for="variant in variants">
        <label class="uk-form-label">{{variant.name}}</label>
        <select class="uk-select yps_variant" @change="recalc">
            <template v-for="label in variant.labels">

                <option v-if="label.variant_id == variant.id" :value="label.id"
                        :selected="variantDefault.includes(label.id)">{{label.name}}
                </option>
            </template>
        </select>
    </div>


    <span v-show="unavailableMessage"><?= $props['unavailableMessage']; ?></span>
</div><!--Vue div tag-->
</div><!--Content div tag-->
</div><!--Output div tag-->
<script>

    const <?= $id; ?> = {
        data() {
            return {
                base_url: '0',
                joomla_item_id: 0,
                variants: [],
                variantDefault: [],
                selected: [],
                unavailableMessage: false,
            }
        },
        beforeMount() {

            // set the data from the inline scripts

            const base_url = document.getElementById('base_url_data');
            try {
                this.base_url = base_url.innerText;
                base_url.remove();
            } catch (err) {
            }
            const joomla_item_id = document.getElementById('yps_joomla_item_id_data');
            try {
                this.joomla_item_id = joomla_item_id.innerText;
                joomla_item_id.remove();
            } catch (err) {
            }
            const variants = document.getElementById('yps_variants_data');
            try {
                this.variants = JSON.parse(variants.innerText);
                variants.remove();
            } catch (err) {
            }
            const variantDefault = document.getElementById('yps_variantDefault_data');
            try {
                this.variantDefault = JSON.parse(variantDefault.innerText);
                variantDefault.remove();
            } catch (err) {
            }
            const variantLabels = document.getElementById('yps_variantLabels_data');
            try {
                this.variantLabels = JSON.parse(variantLabels.innerText);
                variantLabels.remove();
            } catch (err) {
            }


        },
        mounted() {

        },
        created() {
            document.addEventListener("DOMContentLoaded", () => {
                this.selected = this.variantDefault;
                emitter.emit('yps_variantsChange', this.selected);
            });

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
                    'joomla_item_id': this.joomla_item_id,
                    'selected': this.selected
                };

                /**
                 * use this to simply see if the current selection is active... leave the price calculations to the Total Element
                 */

                const request = await fetch(this.base_url + "index.php?option=com_ajax&plugin=protostore_ajaxhelper&method=post&task=task&type=product.checkVariantAvailability&format=raw", {
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
                    }
                }


                emitter.emit('yps_variantsChange', this.selected);
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
