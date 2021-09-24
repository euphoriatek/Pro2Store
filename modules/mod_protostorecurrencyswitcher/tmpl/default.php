<?php
/**
 * @package     Pro2Store - Currency Switcher
 *
 * @copyright   Copyright (C) 2020 Ray Lawlor - Pro2Store. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

use Joomla\CMS\Uri\Uri;

use Protostore\Currency\Currency;
use Protostore\Currency\CurrencyFactory;

$uid = uniqid();

?>


<select id="yps_currencyselect<?= $uid; ?>" class="uk-select">

	<?php
    /** @var Currency $currency */
	foreach (CurrencyFactory::getList(0, 0, true) as $currency) :
        ?>
        <option value="<?= $currency->id; ?>" <?php echo(CurrencyFactory::getCurrent()->id == $currency->id ? 'selected' : ''); ?>><?= $currency->currencysymbol; ?></option>
	<?php endforeach; ?>
</select>


<script>


    var yps_currencyselect<?= $uid; ?> = document.getElementById("yps_currencyselect<?= $uid; ?>");
    yps_currencyselect<?= $uid; ?>.addEventListener("change", function () {
        var selected = yps_currencyselect<?= $uid; ?>.value;

        fetch('<?php echo Uri::root(); ?>index.php?option=com_ajax&plugin=protostore_ajaxhelper&method=post&task=task&type=currency.switch&format=raw&currency_id=' + selected, {
            method: 'post',
        }).then(function (res) {
            return res.json();
        }).then(function (response) {
            if (response.success) {
                if (response.data) {
                    UIkit.notification({
                        message: 'Currency Switched', // todo - translate!
                        status: 'success',
                        pos: 'top-center',
                        timeout: 5000
                    });
                    location.reload();
                } else {
                    UIkit.notification({
                        message: 'Error Switching Currency',
                        status: 'danger',
                        pos: 'top-center',
                        timeout: 5000
                    });
                }
            }
        });

    });


</script>
