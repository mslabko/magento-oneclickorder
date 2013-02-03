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


class Smasoft_Oneclickorder_Helper_Email extends Mage_Core_Helper_Abstract
{
    const XML_PATH_ADMIN_EMAIL_TEMPLATE = 'smasoft_oneclickorder/general/template_admin';

    /**
     * @param Smasoft_Oneclickorder_Model_Order $order
     * @return Smasoft_Oneclickorder_Helper_Email
     */
    public function sendOrderEmailToAdmin($order)
    {
        /** @var $helper Smasoft_Oneclickorder_Helper_Data */
        $helper = Mage::helper('smasoft_oneclickorder');
        $translate = Mage::getSingleton('core/translate');
        /* @var $translate Mage_Core_Model_Translate */
        $translate->setTranslateInline(false);

        $mailTemplate = Mage::getModel('core/email_template');
        /* @var $mailTemplate Mage_Core_Model_Email_Template */

        $template = Mage::getStoreConfig(self::XML_PATH_ADMIN_EMAIL_TEMPLATE);
        $recipientEmail = $helper->getOrderNotificationEmail();
        $recipientName = Mage::getStoreConfig(Mage_Core_Model_Store::XML_PATH_STORE_STORE_NAME);
        $customer = $order->getCustomer();

        $mailTemplate->setDesignConfig(array('area' => 'frontend'))
            ->sendTransactional(
            $template,
            'general',
            $recipientEmail,
            $recipientName,
            array(
                'customer' => $customer,
                'order' => $order,
                'invoice' => $order->getQuote()
            )
        );

        $translate->setTranslateInline(true);
        return $this;
    }
}