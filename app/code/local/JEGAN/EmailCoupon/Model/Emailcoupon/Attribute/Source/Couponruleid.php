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
 * Admin source model for Coupon Name
 *
 * @category    JEGAN
 * @package     JEGAN_EmailCoupon
 * @author      Ultimate Module Creator
 */
class JEGAN_EmailCoupon_Model_Emailcoupon_Attribute_Source_Couponruleid
{
    /**
     * get possible values
     *
     * @access public
     * @param bool $withEmpty
     * @param bool $defaultValues
     * @return array
     * @author Ultimate Module Creator
     */
    public function getAllOptions($withEmpty = true, $defaultValues = false)
    {
        /*$source  = Mage::getModel('eav/config')->getAttribute('customer', 'coupon_id');
        return $source->getSource()->getAllOptions($withEmpty, $defaultValues);*/
        $rules = Mage::getResourceModel('salesrule/rule_collection')->load();
        $list = array(
            array('label' => Mage::helper('adminhtml')->__('Please choose rule'),
                'value' => '')
        );
        if ($rules) {
            $i = 1;
            foreach ($rules as $rule) {
                if ($rule->getCouponType() == 2) {
                    $list[$i]['value'] = $rule->getId();
                    $list[$i]['label'] = $rule->getName();
                    $i++;
                }
            }
        }
        return $list;
    }

    public function toOptionArray()
    {
        return $this->getAllOptions();
    }

    /**
     * get options as array
     *
     * @access public
     * @param bool $withEmpty
     * @return string
     * @author Ultimate Module Creator
     */
    public function getOptionsArray($withEmpty = true)
    {
        $options = array();
        foreach ($this->getAllOptions($withEmpty) as $option) {
            $options[$option['value']] = $option['label'];
        }
        return $options;
    }

    /**
     * get option text
     *
     * @access public
     * @param mixed $value
     * @return string
     * @author Ultimate Module Creator
     */
    public function getOptionText($value)
    {
        $options = $this->getOptionsArray();
        if (!is_array($value)) {
            $value = explode(',', $value);
        }
        $texts = array();
        foreach ($value as $v) {
            if (isset($options[$v])) {
                $texts[] = $options[$v];
            }
        }
        return implode(', ', $texts);
    }
}
