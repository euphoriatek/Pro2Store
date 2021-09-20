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


$data = $displayData;


?>

<div class="uk-card uk-card-default uk-card-small uk-card-hover">
	<div class="uk-card-header">
		<div class="uk-grid uk-grid-small">
			<div class="uk-width-auto">
				<h4><?= Text::_('COM_PROTOSTORE_SALES_IN_PRODUCT_CATEGORIES'); ?></h4>
			</div>
			<div class="uk-width-expand uk-text-right panel-icons">

			</div>
		</div>
	</div>
	<div class="uk-card-body">
		<canvas id="product_categories_chart" width="400" height="400"></canvas>
	</div>
</div>

<script>

    var product_categories_chart = document.getElementById('product_categories_chart').getContext('2d');
    var myProduct_categories_chart = new Chart(product_categories_chart, {
        type: 'doughnut',
        data: {
            labels: <?= json_encode($data['categories']); ?>,
            datasets: [
                {
                    backgroundColor: <?= json_encode($data['colours']); ?>,
                    data: <?= json_encode($data['categorySales']); ?>
                }
            ]
        }
    });

</script>
