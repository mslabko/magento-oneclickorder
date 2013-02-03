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



class Smasoft_Oneclickorder_Block_Abstract extends Mage_Core_Block_Template
{

    /**
     * @return Smasoft_Oneclickorder_Helper_Data
     */
    public function getModuleHelper()
    {
        return Mage::helper('smasoft_oneclickorder');
    }

    protected function _toHtml()
    {
        if (!$this->getModuleHelper()->isEnabled()) {
            return '';
        }
        return parent::_toHtml();
    }

    public function isGuest()
    {
        return !(bool)Mage::getSingleton('customer/session')->getCustomerId();
    }

}