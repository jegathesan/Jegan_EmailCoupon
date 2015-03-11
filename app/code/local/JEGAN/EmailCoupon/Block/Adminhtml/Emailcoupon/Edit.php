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
 * Email Coupon admin edit form
 *
 * @category    JEGAN
 * @package     JEGAN_EmailCoupon
 * @author      Ultimate Module Creator
 */
class JEGAN_EmailCoupon_Block_Adminhtml_Emailcoupon_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
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
        $this->_controller = 'adminhtml_emailcoupon';
        $this->_updateButton(
            'save',
            'label',
            Mage::helper('jegan_emailcoupon')->__('Save Email Coupon')
        );
        $this->_updateButton(
            'delete',
            'label',
            Mage::helper('jegan_emailcoupon')->__('Delete Email Coupon')
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
        if (Mage::registry('current_emailcoupon') && Mage::registry('current_emailcoupon')->getId()) {
            return Mage::helper('jegan_emailcoupon')->__(
                "Edit Email Coupon '%s'",
                $this->escapeHtml(Mage::registry('current_emailcoupon')->getCouponId())
            );
        } else {
            return Mage::helper('jegan_emailcoupon')->__('Add Email Coupon');
        }
    }
}
