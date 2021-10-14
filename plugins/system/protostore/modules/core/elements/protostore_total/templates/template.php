<?php

/**
 * @package   Pro2Store
 * @author    Ray Lawlor - pro2.store
 * @copyright Copyright (C) 2021 Ray Lawlor - pro2.store
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 *
 */


$id = uniqid('yps_total');
/** @var array $attrs */
/** @var array $props */
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
                    base_url: '<?= \Joomla\CMS\Uri\Uri::base(); ?>',
                    total: 0,
                    loading: false,
                    itemid: <?= $props['item_id']; ?>,
                    multiplier: 1,
                    selected: [],
                    options: [],
                }
            },
            created() {
                emitter.on("yps_amountChange", this.setMultiplier)
                emitter.on("yps_product_update", this.recalculateTotal)
                emitter.on("yps_optionsChange", this.recalculateTotal)
                emitter.on("yps_variantsChange", this.recalculateTotal)
            },

            async mounted() {

                await this.recalculateTotal();
            },
            methods: {

                async recalculateTotal(options) {


                    this.loading = true;

                    let selected = [];
                    const selectedVariants = document.getElementsByClassName('yps_variant');
                    Array.prototype.forEach.call(selectedVariants, function (el) {
                        selected.push(el.value);
                    });

                    this.selected = selected;

                    if(options) {
                        selected = [];
                        Array.prototype.forEach.call(options, function (option) {
                            if(option.selected) {
                                selected.push(option);
                            }
                        });

                        this.options = selected;
                    }



                    var params = {
                        'joomla_item_id': this.itemid,
                        'selectedVariants': this.selected,
                        'selectedOptions': this.options,
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
