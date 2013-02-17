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

    $installer->run("
    DROP TABLE IF EXISTS  `{$installer->getTable('smasoft_oneclickorder/country')}`;
    CREATE TABLE `{$installer->getTable('smasoft_oneclickorder/country')}` (
       `entity_id` int(10) unsigned NOT NULL auto_increment,
       `phone_code` varchar(9) NOT NULL default '',
       `country_code` varchar(9) NOT NULL default '',
       `order` tinyint(4) default '0',
       PRIMARY KEY  (`entity_id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;

    INSERT INTO  `{$installer->getTable('smasoft_oneclickorder/country')}` (`phone_code`, `country_code`,`order`)
        VALUES ('380', 'UA', 1);
    INSERT INTO  `{$installer->getTable('smasoft_oneclickorder/country')}` (`phone_code`, `country_code`, `order`)
        VALUES ('7', 'RU', 2);
    INSERT INTO  `{$installer->getTable('smasoft_oneclickorder/country')}` (`phone_code`, `country_code`, `order`)
        VALUES ('1', 'US', 3);
    INSERT INTO  `{$installer->getTable('smasoft_oneclickorder/country')}` (`phone_code`, `country_code`,`order`)
        VALUES ('44', 'GB', 4);

    DROP TABLE IF EXISTS  `{$installer->getTable('smasoft_oneclickorder/order')}`;
    CREATE TABLE `{$installer->getTable('smasoft_oneclickorder/order')}` (
       `entity_id` int(10) unsigned NOT NULL auto_increment,
       `magento_order_id` int(10) unsigned,
       `customer_id` int(10) unsigned,
       `quote_id` int(10) unsigned,
       `store_id` int(10) unsigned  NOT NULL,
       `phone` varchar(40) NOT NULL default '',
       `country` varchar(4) NOT NULL default '',
       `comment` text,
       `create_date` datetime NOT NULL,
       PRIMARY KEY  (`entity_id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
");

$installer->endSetup();
