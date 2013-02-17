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


class Smasoft_Oneclickorder_Model_System_Config_Source_Country
{
    protected $_options;

    public function toOptionArray()
    {
        if (!$this->_options) {
            /** @var $codesCollection Smasoft_Oneclickorder_Model_System_Config_Source_Country */
//          comp for < 1.6
            $alias = '`directory/country`';
            $codesCollection = Mage::getResourceModel('smasoft_oneclickorder/country_collection');
            $codesCollection->join('directory/country', "$alias.country_id = main_table.country_code");
            $codesCollection->setOrder('main_table.order', Varien_Data_Collection::SORT_ORDER_ASC);
            $this->_options = $codesCollection->toOptionArray();
        }
        $options = $this->_options;
        return $options;
    }
}
