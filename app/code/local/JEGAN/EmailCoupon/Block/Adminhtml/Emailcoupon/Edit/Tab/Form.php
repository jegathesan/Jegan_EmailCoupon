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
 * Email Coupon edit form tab
 *
 * @category    JEGAN
 * @package     JEGAN_EmailCoupon
 * @author      Ultimate Module Creator
 */
class JEGAN_EmailCoupon_Block_Adminhtml_Emailcoupon_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
    /**
     * prepare the form
     *
     * @access protected
     * @return JEGAN_EmailCoupon_Block_Adminhtml_Emailcoupon_Edit_Tab_Form
     * @author Ultimate Module Creator
     */
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $form->setHtmlIdPrefix('emailcoupon_');
        $form->setFieldNameSuffix('emailcoupon');
        $this->setForm($form);
        $fieldset = $form->addFieldset(
            'emailcoupon_form',
            array('legend' => Mage::helper('jegan_emailcoupon')->__('Email Coupon'))
        );

        $fieldset->addField(
            'customerid',
            'select',
            array(
                'label' => Mage::helper('jegan_emailcoupon')->__('Customer Name'),
                'name'  => 'customerid',
                'required'  => true,
                'class' => 'required-entry',

                'values'=> Mage::getModel('jegan_emailcoupon/emailcoupon_attribute_source_customerid')->getAllOptions(true),
            )
        );

        $fieldset->addField(
            'couponRuleId',
            'select',
            array(
                'label' => Mage::helper('jegan_emailcoupon')->__('Coupon Rule Name'),
                'name'  => 'couponRuleId',
                'required'  => true,
                'class' => 'required-entry',
                'values'=> Mage::getModel('jegan_emailcoupon/emailcoupon_attribute_source_couponruleid')->getAllOptions(true),
            )
        );

        $fieldset->addField(
            'reason',
            'select',
            array(
                'label' => Mage::helper('jegan_emailcoupon')->__('Reason'),
                'name'  => 'reason',
                'required'  => true,
                'class' => 'required-entry',
                'values'=> Mage::getModel('jegan_emailcoupon/emailcoupon_attribute_source_reason')->getAllOptions(true),
            )
        );

        $fieldset->addField(
            'used',
            'select',
            array(
                'label' => Mage::helper('jegan_emailcoupon')->__('Used'),
                'name'  => 'used',
                'required'  => true,
                'class' => 'required-entry',

                'values'=> array(
                    array(
                        'value' => 1,
                        'label' => Mage::helper('jegan_emailcoupon')->__('Yes'),
                    ),
                    array(
                        'value' => 0,
                        'label' => Mage::helper('jegan_emailcoupon')->__('No'),
                    ),
                ),
            )
        );
        $fieldset->addField(
            'status',
            'select',
            array(
                'label'  => Mage::helper('jegan_emailcoupon')->__('Status'),
                'name'   => 'status',
                'values' => array(
                    array(
                        'value' => 1,
                        'label' => Mage::helper('jegan_emailcoupon')->__('Enabled'),
                    ),
                    array(
                        'value' => 0,
                        'label' => Mage::helper('jegan_emailcoupon')->__('Disabled'),
                    ),
                ),
            )
        );
        if (Mage::app()->isSingleStoreMode()) {
            $fieldset->addField(
                'store_id',
                'hidden',
                array(
                    'name'      => 'stores[]',
                    'value'     => Mage::app()->getStore(true)->getId()
                )
            );
            Mage::registry('current_emailcoupon')->setStoreId(Mage::app()->getStore(true)->getId());
        }
        $formValues = Mage::registry('current_emailcoupon')->getDefaultValues();
        if (!is_array($formValues)) {
            $formValues = array();
        }
        if (Mage::getSingleton('adminhtml/session')->getEmailcouponData()) {
            $formValues = array_merge($formValues, Mage::getSingleton('adminhtml/session')->getEmailcouponData());
            Mage::getSingleton('adminhtml/session')->setEmailcouponData(null);
        } elseif (Mage::registry('current_emailcoupon')) {
            $formValues = array_merge($formValues, Mage::registry('current_emailcoupon')->getData());
        }
        $form->setValues($formValues);
        return parent::_prepareForm();
    }
}
