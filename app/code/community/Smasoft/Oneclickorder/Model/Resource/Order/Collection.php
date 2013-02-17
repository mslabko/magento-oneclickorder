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


class Smasoft_Oneclickorder_Model_Resource_Order_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    /**
     * Define resource model
     *
     */
    protected function _construct()
    {
        $this->_init('smasoft_oneclickorder/order');
    }


    /**
     * Join Eav table
     * @param $attributeCode
     * @return Smasoft_Oneclickorder_Model_Resource_Order_Collection
     */
    public function joinCustomerAttribute($attributeCode)
    {
        //join that attribute table using attribute id and customer id
        $attribute = Mage::getSingleton('eav/config')->getAttribute('customer', $attributeCode);
        if ($attribute) {
            $entityType = Mage::getModel('eav/entity_type')->loadByCode('customer');
            $entityTable = $this->getTable($entityType->getEntityTable()); //customer_entity
            if ($attribute->getBackendType() == 'static') {
                $table = $entityTable;
            } else {
                $table = $entityTable . '_' . $attribute->getBackendType(); //customer_entity_varchar
            }
            $tableAlias = "$table";
            $this->getSelect()->joinLeft($table,
                'main_table.customer_id = ' . $tableAlias . '.entity_id and ' . $tableAlias . '.attribute_id = ' . $attribute->getAttributeId(),
                array($attributeCode => $tableAlias . '.value')
            );
        }
        return $this;
    }
}