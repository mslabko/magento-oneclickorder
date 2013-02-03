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
 * @method int getQuoteId
 * @method int getCustomerId
 * @method int getMagentoOrderId
 * @method int getStoreId
 * @method string getPhone
 * @method string getComment
 * @method string getCountry
 * @method string getCreateDate

 */
class Smasoft_Oneclickorder_Model_Order extends Mage_Core_Model_Abstract
{

    /**
     * Initialize resources
     */
    protected function _construct()
    {
        $this->_init('smasoft_oneclickorder/order');
    }

    /**
     * @return string
     */
    public function getFullPhoneNumber()
    {
        if ($this->getPhoneCode()) {
            return '+' . $this->getPhoneCode() . ' ' . $this->getPhone();
        }
        return '+' . $this->getCountryModel()->getPhoneCode() . ' ' . $this->getPhone();
    }

    /**
     * get customer for order
     *
     * @return Mage_Customer_Model_Customer
     */
    public function getCustomer()
    {
        $customer = Mage::getModel('customer/customer')->load($this->getCustomerId());
        if (!$customer->getId()) {
            $customer->setIsGuest(true);
        }
        return $customer;
    }

    /**
     * @return Mage_Sales_Model_Quote
     */
    public function getQuote()
    {
        return Mage::getModel('sales/quote')->getCollection()
            ->addFieldToFilter('entity_id', $this->getQuoteId())
            ->getFirstItem();
    }


    /**
     * @return Mage_Sales_Model_Quote
     */
    public function getMagentoOrder()
    {
        return $this->getMagentoOrderId() ?
            Mage::getModel('sales/order')->load($this->getMagentoOrderId())
            : null;
    }

    /**
     * @return Smasoft_Oneclickorder_Model_Country
     */
    public function getCountryModel()
    {
        return Mage::getModel('smasoft_oneclickorder/country')->load($this->getCountry(), 'country_code');
    }

    /**
     * Get object created at date affected with object store timezone
     *
     * @return Zend_Date
     */
    public function getCreatedAtStoreDate()
    {
        return Mage::app()->getLocale()->storeDate(
            $this->getStoreId(),
            Varien_Date::toTimestamp($this->getCreateDate()),
            true
        );
    }


}