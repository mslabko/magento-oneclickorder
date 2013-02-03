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


class Smasoft_Oneclickorder_Adminhtml_OneclickorderController extends Mage_Adminhtml_Controller_Action
{

    protected function _isActionAllowed($action)
    {
        return Mage::getSingleton('admin/session')->isAllowed('sales/oneclick_order/' . $action);
    }

    public function indexAction()
    {
        $this->loadLayout();
        $this->_initLayoutMessages('adminhtml/session');
        $this->_setActiveMenu('sales/oneclickorder');
        $this->renderLayout();
    }

    /**
     * grid
     */
    public function gridAction()
    {
        $block = $this->getLayout()->createBlock('smasoft_oneclickorder/adminhtml_orders_grid');
        $this->getResponse()->setBody($block->toHtml());
    }

    /**
     * grid
     */
    public function cartgridAction()
    {
        $order = Mage::getModel('smasoft_oneclickorder/order')->load(
            $this->getRequest()->getParam('id')
        );
        Mage::register('order', $order);
        $block = $this->getLayout()->createBlock('smasoft_oneclickorder/adminhtml_orders_view_tab_cart');
        $this->getResponse()->setBody($block->toHtml());
    }

    public function viewAction()
    {
        $order = Mage::getModel('smasoft_oneclickorder/order')->load(
            $this->getRequest()->getParam('id')
        );
        Mage::register('order', $order);
        if (!$order->getId()) {
            return $this->_redirect('*/*/index');
        }
        $this->loadLayout();
        $this->_initLayoutMessages('adminhtml/session');
        $this->_setActiveMenu('sales/oneclickorder');


        $this->renderLayout();
    }

    /**
     * check permissions
     * @return bool
     */
    protected function _isAllowed()
    {
        switch ($this->getRequest()->getActionName()) {
            case 'index':
            case 'grid':
            case 'cartgrid':
                return $this->_isActionAllowed('grid');
                break;
            case 'view':
                return $this->_isActionAllowed('view');
                break;
            case 'delete':
                return $this->_isActionAllowed('delete');
                break;
        }
        return false;
    }
}