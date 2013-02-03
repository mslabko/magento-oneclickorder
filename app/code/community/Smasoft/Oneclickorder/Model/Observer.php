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

    public function test(Varien_Event_Observer $observer)
    {

//                $codesCollection = Mage::helper('smasoft_oneclickorder')->getPhoneCodes();
////                count($codesCollection);
//                echo '<pre>';
//                print_r($codesCollection->toOptionArray());
//                echo '</pre>';die;
        //Mage::dispatchEvent('admin_session_user_login_success', array('user'=>$user));
        //$user = $observer->getEvent()->getUser();
        //$user->doSomething();
    }

    /**
     * @dispatch checkout_type_onepage_save_order_after
     * @param Varien_Event_Observer $observer
     */
    public function saveMagentoOrderId(Varien_Event_Observer $observer)
    {
        if (Mage::helper('smasoft_oneclickorder')->isSaveMagentoOrder()) {
            $model = Mage::registry('oneclickorder_order_instance');
            if ($model && $model instanceof Smasoft_Oneclickorder_Model_Order && $model->getId()) {
                $order = $observer->getEvent()->getOrder();
                $model->setMagentoOrderId($order->getId())->save();
            }
            Mage::unregister('oneclickorder_order_instance');
        }
        return $this;
    }
}
