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
// init vars

$id = uniqid('p2s_dash');


?>
<div id="app">
	<p-timeline :value="events">
		<template #content="slotProps">
			{{slotProps.item.status}}
		</template>
	</p-timeline>
</div>

<script>
    const {createApp, ref} = Vue;

    const App = {
        data() {
            return {
                events: [
                    {
                        status: 'Ordered',
                        date: '15/10/2020 10:30',
                        icon: 'pi pi-shopping-cart',
                        color: '#9C27B0',
                        image: 'game-controller.jpg'
                    },
                    {
                        status: 'Processing',
                        date: '15/10/2020 14:00',
                        icon: 'pi pi-cog',
                        color: '#673AB7'
                    },
                    {
                        status: 'Shipped',
                        date: '15/10/2020 16:15',
                        icon: 'pi pi-shopping-cart',
                        color: '#FF9800'
                    },
                    {
                        status: 'Delivered',
                        date: '16/10/2020 10:00',
                        icon: 'pi pi-check',
                        color: '#607D8B'
                    }
                ],
                events2: [
                    "2020", "2021", "2022", "2023"
                ]
            }
        },
        components: {
            'p-timeline': primevue.timeline
        }
    };

    createApp(App).mount("#app");
</script>
