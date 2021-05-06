<?php
/**
 * @package   Pro2Store - Helper
 * @author    Ray Lawlor - pro2.store
 * @copyright Copyright (C) 2020 Ray Lawlor - pro2.store
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

// no direct access
namespace Protostore\Config;
defined('_JEXEC') or die('Restricted access');


use Joomla\CMS\Factory;

class Config
{
    public $config;
    public $cancellationItemid;
    public $confirmationItemid;
    public $termsConditionsItemid;
    public $checkoutItemid;
    public $shoplogo;

    public function __construct()
    {

        $this->setConfig();
        $this->setCancellationItemid();
        $this->setConfirmationItemid();
        $this->setTermsConditionsItemid();
        $this->setCheckoutItemid();
        $this->setShoplogo();
    }


    public function getConfirmationItemid()
    {
        return $this->config->get('confirmation_page_url');
    }

    public function getTermsItemid()
    {
        return $this->config->get('terms_and_conditions_url');
    }


    public function getCheckoutItemid()
    {
        return $this->config->get('checkout_page_url');
    }


    public function getEmprtCartRedirectItemid()
    {
        return $this->config->get('empty_cart_redirect_url');
    }

    /**
     * @param mixed $config
     */
    public function setConfig()
    {
        $app = Factory::getApplication();
        $this->config = $app->getParams('com_protostore');
    }


    /**
     * @param mixed $cancellationItemid
     */
    public function setCancellationItemid()
    {
        $this->cancellationItemid = $this->config->get('cancellation_page_url');
    }

    /**
     * @param mixed $confirmationItemid
     */
    public function setConfirmationItemid()
    {
        $this->confirmationItemid = $this->config->get('confirmation_page_url');
    }

    /**
     *
     */

    public function setTermsConditionsItemid()
    {
        $this->termsConditionsItemid = $this->config->get('terms_and_conditions_url');
    }

    /**
     *
     */

    public function setShoplogo()
    {
        $this->shoplogo = $this->config->get('shop_logo');
    }
  /**
     *
     */

    public function setCheckoutItemid()
    {
        $this->checkoutItemid = $this->config->get('checkout_page_url');
    }

}
