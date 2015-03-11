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
 * Event Coupon admin edit tabs
 *
 * @category    JEGAN
 * @package     JEGAN_EmailCoupon
 * @author      Ultimate Module Creator
 */
class JEGAN_EmailCoupon_Block_Adminhtml_Eventcoupon_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{
    /**
     * Initialize Tabs
     *
     * @access public
     * @author Ultimate Module Creator
     */
    public function __construct()
    {
        parent::__construct();
        $this->setId('eventcoupon_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('jegan_emailcoupon')->__('Event Coupon'));
    }

    /**
     * before render html
     *
     * @access protected
     * @return JEGAN_EmailCoupon_Block_Adminhtml_Eventcoupon_Edit_Tabs
     * @author Ultimate Module Creator
     */
    protected function _beforeToHtml()
    {
        $this->addTab(
            'form_eventcoupon',
            array(
                'label'   => Mage::helper('jegan_emailcoupon')->__('Event Coupon'),
                'title'   => Mage::helper('jegan_emailcoupon')->__('Event Coupon'),
                'content' => $this->getLayout()->createBlock(
                    'jegan_emailcoupon/adminhtml_eventcoupon_edit_tab_form'
                )
                ->toHtml(),
            )
        );

        $this->addTab("Manage Users", array(
            "label" => Mage::helper("jegan_emailcoupon")->__("Manage Users"),
            "title" => Mage::helper("jegan_emailcoupon")->__("Manage Users"),
            'content' => $this->getLayout()->createBlock('jegan_emailcoupon/adminhtml_usercoupon_grid', 'custom-tab-content')->toHtml()

        ));

        if (!Mage::app()->isSingleStoreMode()) {
            $this->addTab(
                'form_store_eventcoupon',
                array(
                    'label'   => Mage::helper('jegan_emailcoupon')->__('Store views'),
                    'title'   => Mage::helper('jegan_emailcoupon')->__('Store views'),
                    'content' => $this->getLayout()->createBlock(
                        'jegan_emailcoupon/adminhtml_eventcoupon_edit_tab_stores'
                    )
                    ->toHtml(),
                )
            );
        }
        return parent::_beforeToHtml();
    }

    /**
     * Retrieve event coupon entity
     *
     * @access public
     * @return JEGAN_EmailCoupon_Model_Eventcoupon
     * @author Ultimate Module Creator
     */
    public function getEventcoupon()
    {
        return Mage::registry('current_eventcoupon');
    }
}
