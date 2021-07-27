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

$data  = $displayData;
$order = $data['item'];

?>


<div class="uk-card uk-card-<?= $data['cardStyle']; ?> uk-margin-bottom">
    <div class="uk-card-header">
        <div class="uk-grid uk-grid-small">
            <div class="uk-width-expand">
                <h3>
					<?= Text::_($data['cardTitle']); ?>
                </h3>
            </div>
            <div class="uk-width-auto">

            </div>

        </div>
    </div>

    <div class="uk-card-body">
        <table class="uk-table uk-table-striped">
            <thead>
            <tr>
                <th><?= Text::_('COM_PROTOSTORE_ORDER_PRODUCTS_ORDERED_TABLE_NAME'); ?></th>
                <th><?= Text::_('COM_PROTOSTORE_ORDER_PRODUCTS_ORDERED_TABLE_OPTIONS'); ?></th>
                <th><?= Text::_('COM_PROTOSTORE_ORDER_PRODUCTS_ORDERED_TABLE_PRICEATSALE'); ?></th>
                <th><?= Text::_('COM_PROTOSTORE_ORDER_PRODUCTS_ORDERED_TABLE_AMOUNT'); ?></th>
                <th><?= Text::_('COM_PROTOSTORE_ORDER_PRODUCTS_ORDERED_TABLE_SUBTOTAL'); ?></th>
            </tr>
            </thead>
            <tbody>
			<?php foreach ($order->ordered_products as $product) : ?>
                <tr>
                    <td>
                        <a href="index.php?option=com_protostore&view=product&id=<?= $product->j_item; ?>"><?= $product->j_item_name; ?></a>
                    </td>
                    <td>

						<?php foreach ($product->item_options_array as $option) : ?>
                            <div>
								<?= $option->optiontypename; ?>: <?= $option->optionname; ?>
                            </div>
						<?php endforeach; ?>
                    </td>
                    <td><?= $product->price_at_sale_formatted; ?></td>
                    <td><?= $product->amount; ?></td>
                    <td><?= $product->subtotal_formatted; ?></td>

                </tr>
			<?php endforeach; ?>
            </tbody>
        </table>

    </div>


    <div class="uk-card-footer"></div>
</div>
