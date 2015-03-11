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
 * Event Coupon edit form tab
 *
 * @category    JEGAN
 * @package     JEGAN_EmailCoupon
 * @author      Ultimate Module Creator
 */
class JEGAN_EmailCoupon_Block_Adminhtml_Eventcoupon_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
    /**
     * prepare the form
     *
     * @access protected
     * @return JEGAN_EmailCoupon_Block_Adminhtml_Eventcoupon_Edit_Tab_Form
     * @author Ultimate Module Creator
     */
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $form->setHtmlIdPrefix('eventcoupon_');
        $form->setFieldNameSuffix('eventcoupon');
        $this->setForm($form);
        $fieldset = $form->addFieldset(
            'eventcoupon_form',
            array('legend' => Mage::helper('jegan_emailcoupon')->__('Event Coupon'))
        );

        $fieldset->addField(
            'event_name',
            'text',
            array(
                'label' => Mage::helper('jegan_emailcoupon')->__('Event name'),
                'name'  => 'event_name',
            'required'  => true,
            'class' => 'required-entry',

           )
        );

        $fieldset->addField(
            'coupon_rule_id',
            'select',
            array(
                'label' => 'Coupon Rule Name',
                'name' => 'coupon_rule_id',
                'required' => true,
                'class' => 'required-entry',
                'values'=> Mage::getModel('jegan_emailcoupon/emailcoupon_attribute_source_couponruleid')->getAllOptions(true)
            )
        );

        $fieldset->addField(
            'customerid',
            'multiselect',
            array(
                'label' => 'Choose users',
                'name' => 'customerid',
                'required' => true,
                'class' => 'required-entry',
                'values'=> Mage::getModel('jegan_emailcoupon/emailcoupon_attribute_source_customerid')->getAllOptions(true)
            )
        );


        $fieldset->addField(
            'status',
            'hidden',
            array(
                'label'  => Mage::helper('jegan_emailcoupon')->__('Status'),
                'name'   => 'status',
                'value' => 1 ,
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
            Mage::registry('current_eventcoupon')->setStoreId(Mage::app()->getStore(true)->getId());
        }
        $formValues = Mage::registry('current_eventcoupon')->getDefaultValues();
        if (!is_array($formValues)) {
            $formValues = array();
        }
        if (Mage::getSingleton('adminhtml/session')->getEventcouponData()) {
            $formValues = array_merge($formValues, Mage::getSingleton('adminhtml/session')->getEventcouponData());
            Mage::getSingleton('adminhtml/session')->setEventcouponData(null);
        } elseif (Mage::registry('current_eventcoupon')) {
            $formValues = array_merge($formValues, Mage::registry('current_eventcoupon')->getData());
        }
        $form->setValues($formValues);
        return parent::_prepareForm();
    }
}
