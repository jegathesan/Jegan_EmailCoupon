<?php

class JEGAN_EmailCoupon_Model_Emailcoupon_Attribute_Source_Couponcode
{
    public function toOptionArray()
    {
        return array(
            array('value' => 'alphanum', 'label'=>Mage::helper('adminhtml')->__('Alphanumeric')),
            array('value' => 'alpha', 'label'=>Mage::helper('adminhtml')->__('Alphabetical')),
            array('value' => 'num', 'label'=>Mage::helper('adminhtml')->__('Numeric')),
        );
    }
}