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



class Smasoft_Oneclickorder_Block_Adminhtml_Orders_View_Tab_Cart
    extends Mage_Adminhtml_Block_Widget_Grid
    implements Mage_Adminhtml_Block_Widget_Tab_Interface
{


    public function __construct($attributes = array())
    {
        parent::__construct($attributes);
        $this->setUseAjax(true);
        $this->setFilterVisibility(false);
        $this->_parentTemplate = $this->getTemplate();
        $this->setTemplate('customer/tab/cart.phtml');
    }

    /**
     * Retrieve order model instance
     *
     * @return Smasoft_Oneclickorder_Model_Order
     */
    public function getOrder()
    {
        return Mage::registry('order');
    }

    /**
     * Prepare grid
     *
     * @return void
     */
    protected function _prepareGrid()
    {
        $this->setId('customer_cart_grid');
        parent::_prepareGrid();
    }


    protected function _prepareCollection()
    {
        $quote = $this->getOrder()->getQuote();
        if ($quote) {
            $collection = $quote->getItemsCollection(false);
        } else {
            $collection = new Varien_Data_Collection();
        }
        $collection->addFieldToFilter('parent_item_id', array('null' => true));
        $this->setCollection($collection);

        return parent::_prepareCollection();
    }

    /**
     * Gets customer assigned to this block
     *
     * @return Mage_Customer_Model_Customer
     */
    public function getCustomer()
    {
        return $this->getOrder()->getCustomer();
    }


    protected function _prepareColumns()
    {
        $this->addColumn('product_id', array(
            'header' => Mage::helper('catalog')->__('Product ID'),
            'index' => 'product_id',
            'width' => '100px',
        ));

        $this->addColumn('name', array(
            'header' => Mage::helper('catalog')->__('Product Name'),
            'index' => 'name',
            'renderer' => 'adminhtml/customer_edit_tab_view_grid_renderer_item'
        ));

        $this->addColumn('sku', array(
            'header' => Mage::helper('catalog')->__('SKU'),
            'index' => 'sku',
            'width' => '100px',
        ));

        $this->addColumn('qty', array(
            'header' => Mage::helper('catalog')->__('Qty'),
            'index' => 'qty',
            'type' => 'number',
            'width' => '60px',
        ));

        $this->addColumn('price', array(
            'header' => Mage::helper('catalog')->__('Price'),
            'index' => 'price',
            'type' => 'currency',
            'currency_code' => (string)Mage::getStoreConfig(Mage_Directory_Model_Currency::XML_PATH_CURRENCY_BASE),
        ));

        $this->addColumn('total', array(
            'header' => Mage::helper('sales')->__('Total'),
            'index' => 'row_total',
            'type' => 'currency',
            'currency_code' => (string)Mage::getStoreConfig(Mage_Directory_Model_Currency::XML_PATH_CURRENCY_BASE),
        ));

        $this->addColumn('action', array(
            'header' => Mage::helper('customer')->__('Action'),
            'index' => 'quote_item_id',
            'renderer' => 'adminhtml/customer_grid_renderer_multiaction',
            'filter' => false,
            'sortable' => false,
            'actions' => array(
                array(
                    'caption' => Mage::helper('customer')->__('View'),
                    'url' => $this->getUrl('*/catalog_product/edit', array('id' => '$product_id')),
                    'field' => '$product_id'
                ),

            )
        ));

        return parent::_prepareColumns();
    }


    public function getGridParentHtml()
    {
        $templateName = Mage::getDesign()->getTemplateFilename($this->_parentTemplate, array('_relative' => true));
        return $this->fetchView($templateName);
    }

    public function getRowUrl($row)
    {
        return $this->getUrl('*/catalog_product/edit', array('id' => $row->getProductId()));
    }


    /**
     * Grid url getter
     *
     * @return string current grid url
     */
    public function getGridUrl()
    {
        return $this->getUrl('adminhtml/oneclickorder/cartgrid', array('_current' => true));
    }

    /**
     * ######################## TAB settings #################################
     */
    public function getTabLabel()
    {
        return Mage::helper('customer')->__('Shopping Cart');
    }

    public function getTabTitle()
    {
        return Mage::helper('sales')->__('Order Information');
    }

    public function canShowTab()
    {
        return true;
    }

    public function isHidden()
    {
        return false;
    }
}