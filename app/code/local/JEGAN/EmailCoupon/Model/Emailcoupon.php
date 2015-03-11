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
 * Email Coupon model
 *
 * @category    JEGAN
 * @package     JEGAN_EmailCoupon
 * @author      Ultimate Module Creator
 */
class JEGAN_EmailCoupon_Model_Emailcoupon extends Mage_Core_Model_Abstract
{
    /**
     * Entity code.
     * Can be used as part of method name for entity processing
     */
    const ENTITY    = 'jegan_emailcoupon_emailcoupon';
    const CACHE_TAG = 'jegan_emailcoupon_emailcoupon';

    /**
     * Prefix of model events names
     *
     * @var string
     */
    protected $_eventPrefix = 'jegan_emailcoupon_emailcoupon';

    /**
     * Parameter name in event
     *
     * @var string
     */
    protected $_eventObject = 'emailcoupon';

    /**
     * constructor
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function _construct()
    {
        parent::_construct();
        $this->_init('jegan_emailcoupon/emailcoupon');
    }

    /**
     * before save email coupon
     *
     * @access protected
     * @return JEGAN_EmailCoupon_Model_Emailcoupon
     * @author Ultimate Module Creator
     */
    protected function _beforeSave()
    {
        parent::_beforeSave();
        $now = Mage::getSingleton('core/date')->gmtDate();
        if ($this->isObjectNew()) {
            $this->setCreatedAt($now);
        }
        $this->setUpdatedAt($now);
        return $this;
    }

    /**
     * save email coupon relation
     *
     * @access public
     * @return JEGAN_EmailCoupon_Model_Emailcoupon
     * @author Ultimate Module Creator
     */
    protected function _afterSave()
    {
        return parent::_afterSave();
    }

    /**
     * get default values
     *
     * @access public
     * @return array
     * @author Ultimate Module Creator
     */
    public function getDefaultValues()
    {
        $values = array();
        $values['status'] = 1;
        return $values;
    }
    
}
