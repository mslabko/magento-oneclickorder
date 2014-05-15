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

class Smasoft_Oneclickorder_Model_Observer
{
    /**
     * @return Smasoft_Oneclickorder_Helper_Data
     */
    protected function _helper()
    {
        return Mage::helper('smasoft_oneclickorder');
    }

    /**
     * @dispatch checkout_type_onepage_save_order_after
     * @param Varien_Event_Observer $observer
     * @return $this
     */
    public function saveMagentoOrderId(Varien_Event_Observer $observer)
    {
        if ($this->_helper()->isSaveMagentoOrder()) {
            $model = Mage::registry('oneclickorder_order_instance');
            if ($model && $model instanceof Smasoft_Oneclickorder_Model_Order && $model->getId()) {
                $order = $observer->getEvent()->getOrder();
                $model->setMagentoOrderId($order->getId())->save();
            }
            Mage::unregister('oneclickorder_order_instance');
        }
        return $this;
    }

    /**
     * Change standard OnePage checkout with One Click Order checkout
     * @param Varien_Event_Observer $observer
     * @return $this
     */
    public function changeOnepageCheckout(Varien_Event_Observer $observer)
    {
        switch ($observer->getEvent()->getName()) {
            case 'controller_action_layout_generate_blocks_after':
                /** @var $action Mage_Core_Controller_Varien_Action */
                $action = $observer->getEvent()->getAction();
                /** @var $layout Mage_Core_Model_Layout */
                $layout = $observer->getEvent()->getLayout();
                if ($action->getFullActionName() == 'checkout_cart_index' && $this->_helper()->isEnabled() && $this->_helper()->isChangeOnepageCheckout()) {
                    $block = $layout->getBlock('checkout.cart.methods');
                    if ($block && $block instanceof Mage_Core_Block_Abstract) {
                        $block->unsetChild('checkout.cart.methods.onepage');
                    }
                    $block = $layout->getBlock('checkout.cart.top_methods');
                    if ($block && $block instanceof Mage_Core_Block_Abstract) {
                        $block->unsetChild('checkout.cart.methods.onepage');
                    }
                }

                break;
            case 'controller_action_predispatch_checkout_onepage_index':
                if ($this->_helper()->isEnabled() && $this->_helper()->isChangeOnepageCheckout()) {
                    /** @var $action Mage_Core_Controller_Varien_Action */
                    $action =  $observer->getEvent()->getControllerAction();
                    if ($action) {
                        $action->getResponse()->setRedirect(Mage::getUrl('checkout/cart/index'));
                    }
                }

                break;
        }
        return $this;
    }

    /**
     * @param Varien_Event_Observer $observer
     * @return $this
     */
    public function addPhoneColumnToSalesCollection(Varien_Event_Observer $observer)
    {
        if ($this->_helper()->isDisplayPhoneInSalesOrders()) {
            $collection = $observer->getEvent()->getOrderGridCollection();
            $collection->getSelect()->joinLeft(
                array('smasoft_orders' => $collection->getTable('smasoft_oneclickorder/order')),
                'smasoft_orders.magento_order_id=main_table.entity_id',
                'phone'
            );
        }
        return $this;
    }

    /**
     * @param Varien_Event_Observer $observer
     * @return $this
     */
    public function addPhoneColumnToSalesGrid(Varien_Event_Observer $observer)
    {
        /** @var Mage_Adminhtml_Block_Widget_Grid $block */
        $block = $observer->getEvent()->getBlock();
        if (!isset($block)) {
            return $this;
        }

        if ($block->getType() == 'adminhtml/sales_order_grid' && $this->_helper()->isDisplayPhoneInSalesOrders()) {
            $block->addColumnAfter('customer_phone',
                array(
                    'header' => $this->_helper()->__('Customer Phone'),
                    'type' => 'text',
                    'index' => 'phone',
                    'filter_index' => 'smasoft_orders.phone'
                ),
                'shipping_name'
            );
            $block->sortColumnsByOrder();
        }
        return $this;
    }
}
