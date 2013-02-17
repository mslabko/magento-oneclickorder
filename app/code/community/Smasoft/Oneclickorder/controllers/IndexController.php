<?php
/**
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 *
 * @category    Smasoft
 * @package     Smasoft_Oneclikorder
 * @copyright   Copyright (c) 2013 Slabko Michail. <l.nagash@gmail.com>
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */


/**
 * IndexController.php model
 *
 */
class Smasoft_Oneclickorder_IndexController extends Mage_Core_Controller_Front_Action
{

    protected $_errors = array();


    /**
     * @return Mage_Checkout_OnepageController
     */
    public function preDispatch()
    {
        parent::preDispatch();
        /** @var $helper Smasoft_Oneclickorder_Helper_Data */
        $helper = Mage::helper('smasoft_oneclickorder');
        if (!$helper->isEnabled()) {
            return $this->_ajaxEnd(array(
                'error' => $helper->__('Please, use standard checkout for order product.')
            ));
        }

    }

    /**
     * Save order in Magento
     * @return bool
     */
    protected function _saveMagentoOrder()
    {
        /** @var $onepage Mage_Checkout_Model_Type_Onepage */
        $onepage = $this->getOnepage();
        try {
            $onepage->savePayment(array(
                'method' => 'smasoft_oneclickorder',
            ));
            $onepage->getQuote()->collectTotals()->save();
            Mage::register('oneclickorder_ignore_quote_validation', true, true);
            $onepage->saveOrder();
            Mage::unregister('oneclickorder_ignore_quote_validation');
            return true;
        } catch (Exception $e) {
            $this->_errors[] = $message = $e->getMessage();
        }
        return false;
    }

    /**
     * @param $data
     * @return Smasoft_Oneclickorder_Model_Order
     */
    protected function _saveOrderInfo($data)
    {
        /** @var $model Smasoft_Oneclickorder_Model_Order */
        $model = Mage::getModel('smasoft_oneclickorder/order');
        $model->setData($data);
        $model->setCustomerId($this->_getCustomer()->getId());
        $model->setQuoteId($this->getOnepage()->getQuote()->getId());
        $model->setStoreId(Mage::app()->getStore()->getId());
        $model->setCreateDate(date('Y-m-d h:i:s'));
        $model->save();
        Mage::register('oneclickorder_order_instance', $model, true);
// save for Guest
        if (isset($data['email'])) {
            $newBillingAddress = Mage::getModel('sales/quote_address');
            $newBillingAddress->setEmail($data['email']);
            $this->getOnepage()->getQuote()->setBillingAddress($newBillingAddress)->save();
        }
        return $model;
    }

    /**
     * @return Mage_Customer_Model_Customer
     */
    protected function _getCustomer()
    {
        return Mage::getSingleton('customer/session')->getCustomer();
    }

    /**
     * Is current customer guest
     * @return bool
     */
    protected function _isGuest()
    {
        return !(bool)Mage::getSingleton('customer/session')->getCustomerId();
    }

    protected function _ajaxEnd($data)
    {
        return $this->getResponse()->setBody(
            Mage::helper('core')->jsonEncode($data)
        );
    }

    /**
     * @return string
     */
    protected function _getErrors()
    {
        return implode("\n", $this->_errors);
    }

    /**
     * @param $data
     * @return bool
     */
    protected function _validateData($data)
    {
        /** @var $helper Smasoft_Oneclickorder_Helper_Data */
        $helper = Mage::helper('smasoft_oneclickorder');

        $this->_filterInput($data);
        if (!$data['phone'] || !$data['country'] || ($this->_isGuest() && $helper->isSaveMagentoOrder() && !$data['email'])) {
            $this->_errors[] = $helper->__('Please, fill required fields');
        }
        if (isset($data['email']) && !Zend_Validate::is($data['email'], 'Zend_Validate_EmailAddress')) {
            $this->_errors[] = $helper->__('Please enter a valid email address.');
        }
        return !(bool)count($this->_errors);
    }

    protected function _filterInput($data)
    {
        foreach ($data as &$v) {
            $v = trim(strip_tags($v));
        }
    }

    /**
     * Get one page checkout model
     *
     * @return Mage_Checkout_Model_Type_Onepage
     */
    public function getOnepage()
    {
        return Mage::getSingleton('checkout/type_onepage');
    }


    public function saveOrderAction()
    {
        Mage::getSingleton('checkout/session')->unsOneclickOrderId();

        /** @var $helper Smasoft_Oneclickorder_Helper_Data */
        $helper = Mage::helper('smasoft_oneclickorder');

        $checkoutSessionQuote = Mage::getSingleton('checkout/session')->getQuote();
        if ($checkoutSessionQuote->getIsMultiShipping()) {
            $checkoutSessionQuote->setIsMultiShipping(false);
            $checkoutSessionQuote->removeAllAddresses();
        }

        if (!$this->getOnepage()->getQuote()->hasItems() || $this->getOnepage()->getQuote()->getHasError()) {
            return $this->_ajaxEnd(array(
                'error' => $helper->__('Please, add item to Cart before order.')
            ));
        }
        $data = $this->getRequest()->getPost('oneclickorder');
        if (!$this->_validateData($data)) {
            return $this->_ajaxEnd(array(
                'error' => $this->_getErrors()
            ));
        }

        $order = $this->_saveOrderInfo($data);
//add comment to customer phone... save order if id
        if ($helper->isSaveMagentoOrder()) {
            $this->_saveMagentoOrder();
        }

        if ($this->_getErrors()) {
            if ($order->getId()) {
                $order->delete();
            }
            return $this->_ajaxEnd(array(
                'error' => $this->_getErrors()
            ));
        }

        $this->getOnepage()->getQuote()->setIsActive(0)->save();
        if ($helper->isSendEmail()) {
            Mage::helper('smasoft_oneclickorder/email')->sendOrderEmailToAdmin($order);
        }

        Mage::getSingleton('checkout/session')->setOneclickOrderId($order->getId());
        return $this->_ajaxEnd(array(
            'success' => true,
            'redirect' => Mage::getUrl('smasoft_oneclickorder/index/success')
        ));
    }

    public function successAction()
    {
        if (!Mage::getSingleton('checkout/session')->getOneclickOrderId()) {
            return $this->_redirect('checkout/cart/index');
        }
        $this->loadLayout();

        $this->_initLayoutMessages('checkout/session');
        $this->renderLayout();
    }
}
