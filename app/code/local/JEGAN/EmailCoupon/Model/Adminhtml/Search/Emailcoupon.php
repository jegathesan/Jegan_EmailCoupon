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
 * Admin search model
 *
 * @category    JEGAN
 * @package     JEGAN_EmailCoupon
 * @author      Ultimate Module Creator
 */
class JEGAN_EmailCoupon_Model_Adminhtml_Search_Emailcoupon extends Varien_Object
{
    /**
     * Load search results
     *
     * @access public
     * @return JEGAN_EmailCoupon_Model_Adminhtml_Search_Emailcoupon
     * @author Ultimate Module Creator
     */
    public function load()
    {
        $arr = array();
        if (!$this->hasStart() || !$this->hasLimit() || !$this->hasQuery()) {
            $this->setResults($arr);
            return $this;
        }
        $collection = Mage::getResourceModel('jegan_emailcoupon/emailcoupon_collection')
            ->addFieldToFilter('coupon_id', array('like' => $this->getQuery().'%'))
            ->setCurPage($this->getStart())
            ->setPageSize($this->getLimit())
            ->load();
        foreach ($collection->getItems() as $emailcoupon) {
            $arr[] = array(
                'id'          => 'emailcoupon/1/'.$emailcoupon->getId(),
                'type'        => Mage::helper('jegan_emailcoupon')->__('Email Coupon'),
                'name'        => $emailcoupon->getCouponId(),
                'description' => $emailcoupon->getCouponId(),
                'url' => Mage::helper('adminhtml')->getUrl(
                    '*/emailcoupon_emailcoupon/edit',
                    array('id'=>$emailcoupon->getId())
                ),
            );
        }
        $this->setResults($arr);
        return $this;
    }
}
