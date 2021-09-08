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

$data = $displayData;


?>


<div class="uk-card uk-card-default uk-card-small uk-card-hover">
	<div class="uk-card-header">
		<div class="uk-grid uk-grid-small">
			<div class="uk-width-auto">
				<h4> <?= Text::_('COM_PROTOSTORE_BESTS_SELLERS'); ?></h4>
			</div>
			<div class="uk-width-expand uk-text-right panel-icons">

			</div>
		</div>
	</div>
	<div class="uk-card-body">
		<table class="uk-table uk-table-striped">
			<thead>
			<tr>
				<th><?= Text::_('COM_PROTOSTORE_PRODUCT'); ?></th>
				<th><?= Text::_('COM_PROTOSTORE_SOLD'); ?></th>
			</tr>
			</thead>
			<tbody>
			<?php foreach ($data['bestSellers'] as $bestseller) :?>
				<tr>
					<td><a href="index.php?option=com_protostore&view=product&id=<?= $bestseller->id; ?>"><?= $bestseller->title; ?></a> </td>
					<td><?= $bestseller->salestotal; ?></td>
				</tr>
			<?php endforeach; ?>
			</tbody>
		</table>
	</div>
</div>
