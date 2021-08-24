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
use Joomla\CMS\Helper\ModuleHelper;

// init vars


?>
<div id="p2s_dashboard">
    <div class="uk-grid" uk-grid>
        <div class="uk-width-1-2@l uk-first-column">

        </div>
        <div class="uk-width-1-2@l uk-first-column">


            <canvas id="myChart" width="400" height="400"></canvas>


        </div>
    </div>
</div>
<script>
    var ctx = document.getElementById('myChart').getContext('2d');
    var myChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Red', 'Blue', 'Yellow', 'Green', 'Purple', 'Orange'],
            datasets: [{
                label: '# of Votes',
                data: [12, 19, 3, 5, 2, 3],
                backgroundColor: [
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(255, 206, 86, 0.2)',
                    'rgba(75, 192, 192, 0.2)',
                    'rgba(153, 102, 255, 0.2)',
                    'rgba(255, 159, 64, 0.2)'
                ],
                borderColor: [
                    'rgba(255, 99, 132, 1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(153, 102, 255, 1)',
                    'rgba(255, 159, 64, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>

<script>
    // const p2s_dashboard = {
    //     data() {
    //         return {
    //             ctx: ''
    //         }
    //     },
    //     created() {
    //
    //     },
    //     mounted() {
    //
    //     },
    //
    //     computed() {
    //     },
    //
    //     async beforeMount() {
    //         const ctx = document.getElementById('myChart');
    //         try {
    //             this.ctx = ctx;
    //             var myChart = new Chart(this.ctx, {
    //                 type: 'bar',
    //                 data: {
    //                     labels: ['Red', 'Blue', 'Yellow', 'Green', 'Purple', 'Orange'],
    //                     datasets: [{
    //                         label: '# of Votes',
    //                         data: [12, 19, 3, 5, 2, 3],
    //                         backgroundColor: [
    //                             'rgba(255, 99, 132, 0.2)',
    //                             'rgba(54, 162, 235, 0.2)',
    //                             'rgba(255, 206, 86, 0.2)',
    //                             'rgba(75, 192, 192, 0.2)',
    //                             'rgba(153, 102, 255, 0.2)',
    //                             'rgba(255, 159, 64, 0.2)'
    //                         ],
    //                         borderColor: [
    //                             'rgba(255, 99, 132, 1)',
    //                             'rgba(54, 162, 235, 1)',
    //                             'rgba(255, 206, 86, 1)',
    //                             'rgba(75, 192, 192, 1)',
    //                             'rgba(153, 102, 255, 1)',
    //                             'rgba(255, 159, 64, 1)'
    //                         ],
    //                         borderWidth: 1
    //                     }]
    //                 },
    //                 options: {
    //                     scales: {
    //                         y: {
    //                             beginAtZero: true
    //                         }
    //                     }
    //                 }
    //             });
    //         } catch (err) {
    //         }
    //     },
    //     methods: {
    //
    //     },
    //     components: {
    //
    //     }
    // }
    //
    // Vue.createApp(p2s_dashboard).mount('#p2s_dashboard')

</script>
