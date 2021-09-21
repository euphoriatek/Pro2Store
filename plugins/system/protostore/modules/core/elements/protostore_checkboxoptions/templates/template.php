<?php

/**
 * @package   Pro2Store
 * @author    Ray Lawlor - pro2.store
 * @copyright Copyright (C) 2021 Ray Lawlor - pro2.store
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 *
 */

$id = uniqid('yps_checkboxoptions');

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


?>


<?= $el($props, $attrs) ?>
<div id="<?= $id; ?>">
	<?= $content($props, $attrs) ?>

    <div class="uk-grid" uk-grid="" v-for="(option, index) in options">
        <div class="uk-width-expand">{{option.option_name}}</div>
        <div class="uk-width-auto">
            {{option.modifier_value_translated}} <input @change="recalc" type="checkbox" v-model="option.selected" class="yps_option">
        </div>
    </div>


	<?= $content->end() ?>
</div>
<?= $el->end() ?>


<script>


    const <?= $id; ?> = {
        data() {
            return {
                options: [],
            }
        },
        beforeMount() {

            // set the data from the inline scripts
            const options = document.getElementById('yps_options_data');
            if (options != null) {
                try {
                    this.options = JSON.parse(options.innerText);
                    options.remove();
                } catch (err) {
                }
            }


        },
        methods: {


            recalc() {
                //update price etc. do it via an emitter
                emitter.emit('yps_optionsChange', this.options);
            }
        }
    }

    Vue.createApp(<?= $id; ?>).mount('#<?= $id; ?>')


</script>
