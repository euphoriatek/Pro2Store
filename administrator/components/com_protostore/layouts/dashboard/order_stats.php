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
use Protostore\Order\Order;

$data = $displayData;


?>

<div class="uk-card uk-card-default uk-card-small uk-card-hover">
	<div class="uk-card-header">
		<div class="uk-grid uk-grid-small">
			<div class="uk-width-auto">
				<h4> <?= Text::_('COM_PROTOSTORE_ORDER_STATS'); ?> <i class="fad fa-shopping-cart"></i></h4>
			</div>
			<div class="uk-width-expand uk-text-right panel-icons">
			</div>
		</div>
	</div>
	<div class="uk-card-body">
		<div class="uk-grid uk-child-width-1-4@m uk-grid-small" uk-grid>
			<?php foreach ($data['orderStats'] as $orderStat): ?>
				<div>
					<div class="uk-text-center colour-panel colour-panel-<?= $orderStat['status']; ?>">
						<h5><?= $orderStat['title']; ?></h5>
						<h2><?= $orderStat['count']; ?></h2>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</div>
