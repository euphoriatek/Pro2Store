<?php
/**
 * @package     Pro2Store - Content Plugin
 * @subpackage  com_protostore
 *
 * @copyright   Copyright (C) 2020 Ray Lawlor - pro2store - https://pro2.store. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Protostore\Utilities\Utilities;

class plgContentProtostore_fields extends JPlugin
{
    /**
     * Load the language file on instantiation.
     * Note this is only available in Joomla 3.1 and higher.
     * If you want to support 3.0 series you must override the constructor
     *
     * @var boolean
     * @since <your version>
     */
    protected $autoloadLanguage = true;

    /**
     * Prepare form and add my field.
     *
     * @param JForm $form The form to be altered.
     * @param mixed $data The associated data for the form.
     *
     * @return  boolean
     *
     * @since   <your version>
     */
    function onContentPrepareForm($form, $data)
    {
        $app = Factory::getApplication();
        $option = $app->input->get('option');

        switch ($option) {
            case 'com_content' :
                if ($app->isClient('administrator')) {

                    $articleid = Factory::getApplication()->input->get('id');

                    $value = ($this->checkIfProductExists($articleid) ? 1 : 0);

                    if($data) {
                        $data->attribs['ispro2storeproduct'] = $value;
                    }



//                    Factory::getApplication()->enqueueMessage(json_encode($data), 'info');

                    $form->load('<form><fields name="attribs"><fieldset name="protostore" label="Pro2Store Activation"><field name="ispro2storeproduct" type="radio" default="' . $value . '" label="Set as Pro2Store Product" description="" class="btn-group btn-group-yesno"><option value="1">JYES</option><option value="0">JNO</option></field></fieldset></fields></form>');

                }

                return true;
        }

        return true;
    }

    function onContentAfterSave($context, $article, $isNew)
    {


        // make sure we're in Articles
        if ($context == 'com_content.article') {

            $db = Factory::getDbo();

            // check if this article is already a Pro2Store Product
            // $dbProduct is the product object if true or simply false if not.
            $dbProduct = $this->checkIfProductExists($article->id);


            // get the article attribs
            $attribs = json_decode($article->attribs);


            // check if the article attribs are set to 1
            if ($attribs->ispro2storeproduct == 1) {

                if ($dbProduct) {
                    //if so: update it
                    //Do we need to update the product record from here?
                    $product = new stdClass();
                    $product->id = $dbProduct->id;
                    $db->updateObject('#__protostore_product', $product, 'id');

                } else {
                    //if not: created it
                    $product = new stdClass();
                    $product->id = 0;
                    $product->joomla_item_id = $article->id;
                    $product->base_price = 0;

                    $insert = $db->insertObject('#__protostore_product', $product);

                    if ($insert) {
                        Factory::getApplication()->enqueueMessage('PRODUCT ADDED TO PRO2STORE', 'info');
                    }
                }


                // if "ispro2storeproduct" is set to 0
            } else {

                // if there was a Pro2Store product associated, delete it.
                if ($dbProduct) {
                    $query = $db->getQuery(true);
                    $conditions = array(
                        $db->quoteName('joomla_item_id') . ' = ' . $db->quote($article->id)
                    );
                    $query->delete($db->quoteName('#__protostore_product'));
                    $query->where($conditions);
                    $db->setQuery($query);
                    $delete = $db->execute();

                    // if successful raise a message
                    if ($delete) {
                        Factory::getApplication()->enqueueMessage('PRODUCT DELETED FROM PRO2STORE', 'info');
                    }
                }

                // now make sure to clear all cart of this product
//                $query = $db->getQuery(true);
//                $conditions = array(
//                    $db->quoteName('joomla_item_id') . ' = ' . $db->quote($article->id)
//                );
//                $query->delete($db->quoteName('#__protostore_carts'));
//                $query->where($conditions);
//                $db->setQuery($query);
//                $db->execute();

            }

        }


    }



    function onContentAfterDelete($context, $data)
    {

        // Delete Pro2Store item if product is deleted in the content manager.

        $db = Factory::getDbo();

        $query = $db->getQuery(true);
        $conditions = array($db->quoteName('joomla_item_id') . ' = ' . $db->quote($data->id));
        $query->delete($db->quoteName('#__protostore_product'));
        $query->where($conditions);
        $db->setQuery($query);
        $db->execute();

        //also clear any items that are in carts

//        $query = $db->getQuery(true);
//        $conditions = array($db->quoteName('joomla_item_id') . ' = ' . $db->quote($data->id));
//        $query->delete($db->quoteName('#__protostore_carts'));
//        $query->where($conditions);
//        $db->setQuery($query);
//        $db->execute();


        return true;
    }

    private function checkIfProductExists($id)
    {
        $db = Factory::getDbo();
        //check that product exists in pro2store
        $query = $db->getQuery(true);

        $query->select('*');
        $query->from($db->quoteName('#__protostore_product'));
        $query->where($db->quoteName('joomla_item_id') . ' = ' . $db->quote($id));

        $db->setQuery($query);

        return $db->loadObject();

    }


}
