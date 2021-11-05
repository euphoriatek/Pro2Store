<?php
/**
 * @package   Pro2Store
 * @author    Ray Lawlor - pro2.store
 * @copyright Copyright (C) 2021 Ray Lawlor - pro2.store
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 *
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

use Joomla\CMS\Language\Text;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Layout\LayoutHelper;

use Joomla\CMS\Uri\Uri;
use Joomla\CMS\Version;

if (Version::MAJOR_VERSION === 3)
{
	HTMLHelper::_('behavior.keepalive');
	HTMLHelper::_('behavior.formvalidator');
}
/** @var array $vars */
/** @var Protostore\Product\Product $item */
$item = $vars['item'];


?>


<div id="p2s_product_form">
    <form @submit.prevent="saveItem" v-cloak>
        <div class="uk-margin-left">
            <div class="uk-grid" uk-grid="">
                <div class="uk-width-1-1">

                    <div uk-sticky="sel-target: .uk-navbar-container; cls-active: uk-navbar-sticky; offset: 31">

                        <nav class="uk-navbar-container uk-padding-small" uk-navbar style="border-radius: 8px">

                            <div class="uk-navbar-left">

                                <span class="uk-navbar-item uk-logo" v-cloak>
                                    <span v-show="form.itemid"> <?= Text::_('COM_PROTOSTORE_ADD_DISCOUNTS_MODAL_EDITING'); ?>  {{form.jform_title}}</span>
                                    <span v-show="!form.itemid"> <?= Text::_('COM_PROTOSTORE_ADD_PRODUCT_TITLE'); ?>:  {{form.jform_title}}</span>

                                </span>

                            </div>

                            <div class="uk-navbar-right">

<!--                                <button type="button"-->
<!--                                        class="uk-button uk-button-primary uk-button-small uk-margin-right"-->
<!--                                        @click="logIt">LogIt-->
<!--                                </button>-->
                                <button type="submit" @click="andClose = false"
                                        class="uk-button uk-button-default button-success uk-button-small uk-margin-right">
									<?= Text::_('JTOOLBAR_APPLY'); ?>
                                </button>
                                <button type="submit" @click="andClose = true"
                                        class="uk-button uk-button-default button-success uk-button-small uk-margin-right">
									<?= Text::_('JTOOLBAR_SAVE'); ?>
                                </button>
                                <a class="uk-button uk-button-default uk-button-small uk-margin-right"
                                   href="index.php?option=com_protostore&view=products"><?= Text::_('JTOOLBAR_CANCEL'); ?></a>
<!--                                <button type="button" uk-toggle="target: #advancedOptions"-->
<!--                                        class="uk-button uk-button-primary uk-button-small uk-margin-right">-->
<!--									--><?//= Text::_('COM_PROTOSTORE_ADD_PRODUCT_ADVANCED_OPTIONS'); ?>
<!--                                    <span uk-icon="icon: settings"></span>-->
<!--                                </button>-->

                            </div>

                        </nav>
                    </div>

                </div>

                <div class="uk-width-2-3">

					<?= LayoutHelper::render('product/card_details', array(
						'form'      => $vars['form'],
						'cardStyle' => 'default',
						'cardTitle' => 'COM_PROTOSTORE_ADD_PRODUCT_PRODUCT_DETAILS',
						'cardId'    => 'details',
						'fields'    => array('title', 'short_description', 'long_description')
					)); ?>

					<?= LayoutHelper::render('card', array(
						'form'             => $vars['form'],
						'cardTitle'        => 'COM_PROTOSTORE_ADD_PRODUCT_IMAGES',
						'cardStyle'        => 'default',
						'cardId'           => 'images',
						'fields'           => array('teaserimage', 'fullimage'),
						'field_grid_width' => '1-2',
					)); ?>
                    <span v-show="form.jform_product_type == 2">
						<?= LayoutHelper::render('product/card_digital', array(
							'form'      => $vars['form'],
							'cardTitle' => 'Digital Details',
							'cardStyle' => 'default',
							'cardId'    => 'digital',
						)); ?>
					</span>
					<?= LayoutHelper::render('product/card_variant', array(
						'form'             => $vars['form'],
						'cardTitle'        => 'COM_PROTOSTORE_ADD_PRODUCT_VARIANTS',
						'cardStyle'        => 'default',
						'cardId'           => 'variants',
						'fields'           => array('variants'),
						'field_grid_width' => '1-1',
					)); ?>

					<?php echo LayoutHelper::render('product/card_options', array(
						'form'             => $vars['form'],
						'cardTitle'        => 'COM_PROTOSTORE_ADD_PRODUCT_OPTIONS',
						'cardStyle'        => 'default',
						'cardId'           => 'options',
						'fields'           => array('options'),
						'field_grid_width' => '1-1',
					)); ?>


					<?= LayoutHelper::render('product/card_custom_fields', array(
						'form'             => $vars['form'],
						'custom_fields'    => $vars['custom_fields'],
						'cardTitle'        => 'COM_PROTOSTORE_ADD_PRODUCT_JOOMLA_CUSTOM_FIELDS',
						'cardStyle'        => 'default',
						'cardId'           => 'custom_fields',
						'field_grid_width' => '1-1',
					)); ?>


                </div>


                <div class="uk-width-1-3">


					<?= LayoutHelper::render('card', array(
						'form'      => $vars['form'],
						'cardTitle' => 'COM_PROTOSTORE_ADD_PRODUCT_PRODUCT_ACCESS',
						'cardStyle' => 'default',
						'cardId'    => 'access',
						'fields'    => array('state', 'access', 'publish_up_date')
					)); ?>
					<?= LayoutHelper::render('card', array(
						'form'      => $vars['form'],
						'cardTitle' => 'COM_PROTOSTORE_ADD_PRODUCT_ORGANISATION',
						'cardStyle' => 'default',
						'cardId'    => 'organisation',
						'fields'    => array('category', 'featured', 'tags')
					)); ?>
					<?= LayoutHelper::render('card', array(
						'form'      => $vars['form'],
						'cardTitle' => 'COM_PROTOSTORE_ADD_PRODUCT_PRODUCT_PRICING',
						'cardStyle' => 'default',
						'cardId'    => 'pricing',
						'fields'    => array('base_price', 'taxable', 'show_discount', 'discount')
					)); ?>

					<?= LayoutHelper::render('card', array(
						'form'      => $vars['form'],
						'cardTitle' => 'COM_PROTOSTORE_ADD_PRODUCT_INVENTORY',
						'cardStyle' => 'default',
						'cardId'    => 'inventory',
						'fields'    => array('sku', 'manage_stock', 'stock')
					)); ?>



					<?= LayoutHelper::render('card', array(
						'form'      => $vars['form'],
						'cardTitle' => 'COM_PROTOSTORE_ADD_PRODUCT_SHIPPING',
						'cardStyle' => 'default',
						'cardId'    => 'shipping',
						'fields'    => array('shipping_mode', 'flatfee')
					)); ?>


                </div>
            </div>

        </div>
    </form>

	<?= LayoutHelper::render('product/modals/advancedOptions'); ?>

</div>

<!--REMOVE ALL EMPTY LABELS-->
<script>Array.from(document.getElementsByClassName("control-label")).forEach(function(e){""===e.innerHTML&&e.remove()});</script>

<script>





    var bar = document.getElementById('js-progressbar');

    UIkit.upload('.p2s_file_upload', {

        url: '',
        multiple: false,
        beforeAll: function () {
            this.url = '<?= Uri::base(); ?>index.php?option=com_ajax&plugin=protostore_ajaxhelper&method=post&task=task&type=file.upload&format=raw';
            console.log('beforeAll', arguments);
        },

        loadStart: function (e) {

            bar.removeAttribute('hidden');
            bar.max = e.total;
            bar.value = e.loaded;
        },

        progress: function (e) {

            bar.max = e.total;
            bar.value = e.loaded;
        },

        loadEnd: function (e) {

            bar.max = e.total;
            bar.value = e.loaded;
        },

        completeAll: function () {


            const response = JSON.parse(arguments[0].response);

            if (response.success) {
                setTimeout(function () {
                    bar.setAttribute('hidden', 'hidden');
                }, 1000);
                emitter.emit('p2s_product_file_upload', response.data);
                UIkit.notification({
                    message: 'Uploaded',
                    status: 'success',
                    pos: 'top-right',
                    timeout: 5000
                });
            } else {
                UIkit.notification({
                    message: 'There was an error',
                    status: 'danger',
                    pos: 'top-right',
                    timeout: 5000
                });
            }
        }


    });

</script>
