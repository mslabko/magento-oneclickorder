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

class Smasoft_Oneclickorder_Block_Success extends Smasoft_Oneclickorder_Block_Abstract
{

    /**
     * @return null|string
     */
    public function getOrderId()
    {
        $id = $this->_getData('order_id');
        return $id ? str_pad($id, 6, '0', STR_PAD_LEFT) : null;
    }

    /**
     * @return null|string
     */
    public function getPhoneNumber()
    {
        return $this->_getData('phone');
    }

    /**
     * @return bool
     */
    public function isShowMagentoOrderSuccess()
    {
        if (Mage::helper('smasoft_oneclickorder')->isSaveMagentoOrder() && $this->_getData('magento_order_id')) {
            return true;
        }
        return false;
    }

    protected function _prepareData()
    {
        $orderId = Mage::getSingleton('checkout/session')->getOneclickOrderId();
        if ($orderId) {
            /** @var $order Smasoft_Oneclickorder_Model_Order */
            $order = Mage::getModel('smasoft_oneclickorder/order')->load($orderId);
            if ($order->getId()) {
                $this->addData(array(
                    'order_id' => $orderId,
                    'phone' => $order->getFullPhoneNumber(),
                    'magento_order_id' => $order->getMagentoOrderId(),
                ));
            }
        }

    }

    protected function _beforeToHtml()
    {
        $this->_prepareData();
        parent::_beforeToHtml();
    }
}