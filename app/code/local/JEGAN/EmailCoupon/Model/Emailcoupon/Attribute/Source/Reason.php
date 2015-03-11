<?php
/**
 * JEGAN_EmailCoupon extension
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the MIT License
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/mit-license.php
 *
 * @category       JEGAN
 * @package        JEGAN_EmailCoupon
 * @copyright      Copyright (c) 2015
 * @license        http://opensource.org/licenses/mit-license.php MIT License
 */
/**
 * EmailCoupon setup
 *
 * @category    JEGAN
 * @package     JEGAN_EmailCoupon
 * @author      Ultimate Module Creator
 */
class JEGAN_EmailCoupon_Model_Emailcoupon_Attribute_Source_Reason{
    /**
     * Utility to get int values
     * @return array
     */
    public function getAllOptions() {

        return array(
            array('value' => '1', 'label'=>Mage::helper('jegan_emailcoupon')->__('Newsletter subscribe')),
            array('value' => '2', 'label'=>Mage::helper('jegan_emailcoupon')->__('First order placed')),
            array('value' => '3', 'label'=>Mage::helper('jegan_emailcoupon')->__('Become a new user')),
        );
    }
}