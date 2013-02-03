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


class Smasoft_Oneclickorder_Block_Form extends Smasoft_Oneclickorder_Block_Abstract
{
    public function getPhoneCodeHtml($name, $class = '', $withCountryName = true, $emptyLabel = 'Code')
    {
        $codesCollection = $this->getModuleHelper()->getPhoneCodes();
        $codes = $codesCollection->toOptionArray($withCountryName);

        if (count($codes) > 1) {
            $options = array_merge(
                array(array(
                    'value' => '',
                    'label' => $emptyLabel,
                )),
                $codes
            );

            $selectedValue = '';

            $html = $this->getLayout()->createBlock('core/html_select')
                ->setName($name)
                ->setId('oneclickorder-phone-code')
                ->setClass($class)
                ->setValue($selectedValue)
                ->setOptions($options)
                ->getHtml();
        } else {
            $item = $codesCollection->getFirstItem();
            $html = "<input type=\"hidden\" name=\"$name\" value=\"{$item->getCountryCode()}\" /><span class=\"$class\">+{$item->getPhoneCode()}</span> ";
        }
        return $html;
    }

    /**
     * need o render email field. Only for guest and if magento order is enabled
     */
    public function isShowEmailField()
    {
        return $this->getModuleHelper()->isSaveMagentoOrder() && $this->isGuest();
    }
}