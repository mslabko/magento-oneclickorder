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

class Smasoft_Oneclickorder_Helper_Data extends Mage_Core_Helper_Abstract
{
    const XML_PATH_ENABLED = 'smasoft_oneclickorder/general/enabled';
    const XML_PATH_CREATE_MAGENTO_ORDER = 'smasoft_oneclickorder/general/create_magento_order';
    const XML_PATH_SEND_EMAIL = 'smasoft_oneclickorder/general/send_email';
    const XML_PATH_EMAIL = 'smasoft_oneclickorder/general/email';
    const XML_PATH_CHANGE_ONEPAGE_CHECKOUT = 'smasoft_oneclickorder/checkout/change_onepage';
    const XML_PATH_ALLOW_COUNTRIES = 'smasoft_oneclickorder/general/allow_countries';
    const XML_PATH_DISPLAY_PHONE_IN_SALES_ORDERS = 'smasoft_oneclickorder/general/display_phone_in_sales_orders';

    /**
     * Is OneClick Order functionality enabled
     *
     * @return bool
     */
    public function isEnabled()
    {
        return Mage::getStoreConfigFlag(self::XML_PATH_ENABLED);
    }

    /**
     * Change onepage checkout with OneClick Order
     *
     * @return bool
     */
    public function isChangeOnepageCheckout()
    {
        return Mage::getStoreConfigFlag(self::XML_PATH_CHANGE_ONEPAGE_CHECKOUT);
    }

    /**
     * Save OneClick Order in Magento
     *
     * @return bool
     */
    public function isSaveMagentoOrder()
    {
        return Mage::getStoreConfigFlag(self::XML_PATH_CREATE_MAGENTO_ORDER);
    }

    /**
     * Send email address to admin
     *
     * @return bool
     */
    public function isSendEmail()
    {
        return Mage::getStoreConfigFlag(self::XML_PATH_SEND_EMAIL);
    }

    /**
     * get admin email address for send
     *
     * @return bool|string
     */
    public function getOrderNotificationEmail()
    {
        $email = false;
        if ($this->isSendEmail()) {
            $email = Mage::getStoreConfig(self::XML_PATH_EMAIL);
            if (!$email) {
                $email = Mage::getStoreConfig('trans_email/ident_general/email');
            }
        }
        return $email;
    }

    /**
     * @return array
     */
    public function getAllowCountries()
    {
        $codes = Mage::getStoreConfig(self::XML_PATH_ALLOW_COUNTRIES);
        return explode(',', $codes);
    }

    /**
     * @return Smasoft_Oneclickorder_Model_Resource_Country_Collection
     */
    public function getPhoneCodes()
    {
        /** @var $collection Smasoft_Oneclickorder_Model_Resource_Country_Collection */
        $collection = Mage::getResourceModel('smasoft_oneclickorder/country_collection');
        $collection->addFieldToFilter('country_code', array('in' => $this->getAllowCountries()));
        $collection->setOrder('main_table.order', Varien_Data_Collection::SORT_ORDER_ASC);
        return $collection;
    }

    /**
     * Is display phone number in Magento Sales Orders grid Order
     *
     * @return bool
     */
    public function isDisplayPhoneInSalesOrders()
    {
        return Mage::getStoreConfigFlag(self::XML_PATH_DISPLAY_PHONE_IN_SALES_ORDERS);
    }
}
