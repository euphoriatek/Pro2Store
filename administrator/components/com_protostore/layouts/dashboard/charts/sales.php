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
				<h4><?= Text::_('COM_PROTOSTORE_SALES_CHART'); ?></h4>
			</div>
			<div class="uk-width-expand uk-text-right panel-icons">
			</div>
		</div>
	</div>
	<div class="uk-card-body">
		<div>
			<div class="chartjs-size-monitor">
				<div class="chartjs-size-monitor-expand">
					<div class=""></div>
				</div>
				<div class="chartjs-size-monitor-shrink">
					<div class=""></div>
				</div>
			</div>
			<canvas id="product_sales_chart" width="400" height="200"></canvas>
		</div>
	</div>
</div>

<script>

    var product_sales_chart = document.getElementById('product_sales_chart').getContext('2d');
    var myProduct_sales_chart = new Chart(product_sales_chart, {
        type: 'line',
        data: {
            labels: <?= json_encode($data['months']); ?>,
            datasets: [
                {
                    label: "<?= Text::sprintf('COM_PROTOSTORE_TOTAL_SALES_VALUE', $data['currencysymbol']); ?>",
                    data: <?= json_encode($data['monthsSales']); ?>,
                    borderColor: '#84d182',
                    tension: 0.5
                }
            ]
        },
        options: {
            scales: {
                y: {
                    ticks: {
                        // Include a dollar sign in the ticks
                        callback: function(value, index, values) {
                            return '<?= $data['currencysymbol']; ?>' + Intl.NumberFormat().format((value));
                        }
                    }
                }
            }
        }
    });

</script>
