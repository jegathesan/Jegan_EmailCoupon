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
 * Event Coupon admin edit form
 *
 * @category    JEGAN
 * @package     JEGAN_EmailCoupon
 * @author      Ultimate Module Creator
 */
class JEGAN_EmailCoupon_Block_Adminhtml_Eventcoupon_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    /**
     * constructor
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function __construct()
    {
        parent::__construct();
        $this->_blockGroup = 'jegan_emailcoupon';
        $this->_controller = 'adminhtml_eventcoupon';
        $this->_updateButton(
            'save',
            'label',
            Mage::helper('jegan_emailcoupon')->__('Save Event Coupon')
        );
        $this->_removeButton(
            'delete'
        );
        $this->_addButton(
            'saveandcontinue',
            array(
                'label'   => Mage::helper('jegan_emailcoupon')->__('Save And Continue Edit'),
                'onclick' => 'saveAndContinueEdit()',
                'class'   => 'save',
            ),
            -100
        );
        $this->_formScripts[] = "
            function saveAndContinueEdit() {
                editForm.submit($('edit_form').action+'back/edit/');
            }
        ";
    }

    /**
     * get the edit form header
     *
     * @access public
     * @return string
     * @author Ultimate Module Creator
     */
    public function getHeaderText()
    {
        if (Mage::registry('current_eventcoupon') && Mage::registry('current_eventcoupon')->getId()) {
            return Mage::helper('jegan_emailcoupon')->__(
                "Edit Event Coupon '%s'",
                $this->escapeHtml(Mage::registry('current_eventcoupon')->getEventName())
            );
        } else {
            return Mage::helper('jegan_emailcoupon')->__('Add Event Coupon');
        }
    }
}
