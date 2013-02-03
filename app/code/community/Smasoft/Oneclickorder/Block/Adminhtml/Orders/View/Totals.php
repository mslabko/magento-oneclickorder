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


class Smasoft_Oneclickorder_Block_Adminhtml_Orders_View_Totals extends Mage_Adminhtml_Block_Sales_Totals //Mage_Adminhtml_Block_Sales_Order_Abstract
{


    /**
     * Retrieve order model object
     *
     * @return Smasoft_Oneclickorder_Model_Order
     */
    public function getOrder()
    {
        return Mage::registry('order');
    }

    /**
     * @return Mage_Sales_Model_Quote
     */
    public function getSource()
    {
        return $this->getOrder()->getQuote();
    }

    /**
     * Initialize order totals array
     *
     * @return Mage_Sales_Block_Order_Totals
     */
    protected function _initTotals()
    {
        parent::_initTotals();
        $this->_totals['count'] = new Varien_Object(array(
            'code' => 'count',
            'strong' => true,
            'is_formated' => true,
            'value' => $this->getSource()->getItemsCount(),
            'base_value' => $this->getSource()->getItemsCount(),
            'label' => $this->helper('smasoft_oneclickorder')->__('Total items'),
            'area' => 'footer'
        ));

        $magentoOrder = $this->getOrder()->getMagentoOrder();

        if ($magentoOrder) {
            $this->_totals['paid'] = new Varien_Object(array(
                'code' => 'paid',
                'strong' => true,
                'value' => $magentoOrder->getTotalPaid(),
                'base_value' => $magentoOrder->getBaseTotalPaid(),
                'label' => $this->helper('sales')->__('Total Paid'),
                'area' => 'footer'
            ));
        }
        return $this;
    }
}
