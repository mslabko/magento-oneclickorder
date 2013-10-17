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


/**
 * Dummy oneclick order payment method model
 */
class Smasoft_Oneclickorder_Model_Payment_Method_Oneclick extends Mage_Payment_Model_Method_Abstract
{
    protected $_canUseCheckout = false;

    /**
     * Payment method code
     *
     * @var string
     */
    protected $_code = 'smasoft_oneclickorder';

    /**
     * @inheritdoc
     */
    public function canUseForCountry($country)
    {
        return true;
    }

    /**
     * @inheritdoc
     */
    public function isAvailable($quote = null)
    {
        return true;
    }

    /**
     * @inheritdoc
     */
    public function isApplicableToQuote($quote, $checksBitMask)
    {
        return true;
    }
}
