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

$installer = $this;
/* @var $installer Mage_Core_Model_Resource_Setup */

$installer->startSetup();

/**
 * Create table 'phone codes'
 */
$table = $installer->getConnection()
    ->newTable($installer->getTable('smasoft_oneclickorder/country'))
    ->addColumn('entity_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
    'identity' => true,
    'unsigned' => true,
    'nullable' => false,
    'primary' => true,
), 'Entity ID')
    ->addColumn('phone_code', Varien_Db_Ddl_Table::TYPE_VARCHAR, 9, array(
    'nullable' => false,
    'default' => null,
), 'Phone Code')

    ->addColumn('country_code', Varien_Db_Ddl_Table::TYPE_VARCHAR, 4, array(
    'nullable' => false,
    'default' => null,
), 'Country Code')

    ->addColumn('order', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
    'nullable' => false,
    'default' => 0,
), 'Order');

$installer->getConnection()->createTable($table);


$insertData = array(
    array(
        'phone_code' => '380',
        'country_code' => 'UA',
        'order' => 1
    ),
    array(
        'phone_code' => '7',
        'country_code' => 'RU',
        'order' => 2
    ),
    array(
        'phone_code' => '1',
        'country_code' => 'US',
        'order' => 3
    ),
    array(
        'phone_code' => '44',
        'country_code' => 'GB',
        'order' => 4
    ),

);

foreach ($insertData as $data) {
    $installer->getConnection()->insert($installer->getTable('smasoft_oneclickorder/country'), $data);
}


/**
 * Create table 'order'
 */
$table = $installer->getConnection()
    ->newTable($installer->getTable('smasoft_oneclickorder/order'))
    ->addColumn('entity_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
    'identity' => true,
    'unsigned' => true,
    'nullable' => false,
    'primary' => true,
), 'Entity ID')

    ->addColumn('magento_order_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
    'unsigned' => true,
    'nullable' => true,
), 'Magento Order Id')

    ->addColumn('customer_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
    'unsigned' => true,
    'nullable' => true,
), 'Customer Id')

    ->addColumn('quote_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
    'unsigned' => true,
    'nullable' => true,
), 'Quote Id')

    ->addColumn('store_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
    'unsigned' => true,
    'nullable' => false,
), 'Store Id')

    ->addColumn('phone', Varien_Db_Ddl_Table::TYPE_VARCHAR, 40, array(
    'nullable' => false,
), 'Phone number')


    ->addColumn('country', Varien_Db_Ddl_Table::TYPE_VARCHAR, 4, array(
    'nullable' => false,
), 'Country code')

    ->addColumn('comment', Varien_Db_Ddl_Table::TYPE_TEXT, null, array(
    'nullable' => true,
), 'Comment')

    ->addColumn('create_date', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array(
    'default' => 'CURRENT_TIMESTAMP',
    'nullable' => false,
), 'Create date');
$installer->getConnection()->createTable($table);


$installer->run($sql);

$installer->endSetup();
