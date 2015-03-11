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
 * EmailCoupon default helper
 *
 * @category    JEGAN
 * @package     JEGAN_EmailCoupon
 * @author      Ultimate Module Creator
 */
class JEGAN_EmailCoupon_Helper_Data extends Mage_Core_Helper_Abstract
{
    /**
     * convert array to options
     *
     * @access public
     * @param $options
     * @return array
     * @author Ultimate Module Creator
     */
    public function convertOptions($options)
    {
        $converted = array();
        foreach ($options as $option) {
            if (isset($option['value']) && !is_array($option['value']) &&
                isset($option['label']) && !is_array($option['label'])
            ) {
                $converted[$option['value']] = $option['label'];
            }
        }
        return $converted;
    }

    public function generateNewCouponCode($storeConfigModel, $customerId, $reason, $ruleId = false)
    {

        //1.Get sales rule model
        $model = Mage::getModel('salesrule/rule');

        //2.Get store config values for new user coupon model
        $configValues = Mage::getStoreConfig($storeConfigModel);

        if (!$ruleId)
            $ruleId = $configValues['couponruleid'];

        //3.Load the rule coupon rule with its id
        $model->load($ruleId);

        $massGenerator = $model->getCouponMassGenerator();
        $session = Mage::getSingleton('core/session');



        //4.Set coupon settings to generate new coupon code
        try {
            $massGenerator->setData(array(
                'rule_id' => $ruleId,
                'qty' => 1,
                'length' => $configValues['length'],
                'format' => $configValues['format'],
                'prefix' => $configValues['prefix'],
                'suffix' => $configValues['suffix'],
                'dash' => $configValues['dash'],
                'uses_per_coupon' => 1,
                'uses_per_customer' => 1
            ));
            $massGenerator->generatePool();
            $latestCoupon = max($model->getCoupons());
        } catch (Mage_Core_Exception $e) {
            $session->addException($e, $this->__('There was a problem with coupon: %s', $e->getMessage()));
        }
        catch (Exception $e) {
            $session->addException($e, $this->__('There was a problem with coupon.'));
        }
        $this->_saveRecord($customerId, $latestCoupon->getCouponId(), $ruleId, $reason);
        return $latestCoupon->getCode();
    }

    public function _saveRecord($cusId, $couponId, $ruleId, $reason)
    {
        $emailModel = Mage::getModel('jegan_emailcoupon/emailcoupon');
        $emailModel->setCustomerId($cusId);
        $emailModel->setCouponId($couponId);
        $emailModel->setReason($reason);
        $emailModel->setCouponRuleId($ruleId);
        $emailModel->setStatus(1);
        $emailModel->save();
        $emailModel->setEmailcouponId($emailModel->getId());
        $emailModel->setStoreId(Mage::app()->getStore()->getStoreId());
        $emailModel->save();
    }

    public function sendCouponCodesToCustomer($customers, $eventId, $ruleId)
    {
        foreach ($customers as $key => $value) {
            $customerModel = Mage::getModel('customer/customer')->load($value);
            if ($customerModel->getEmail()) {

                $translate = Mage::getSingleton('core/translate');
                /* @var $translate Mage_Core_Model_Translate */
                $translate->setTranslateInline(false);

                $email = Mage::getModel('core/email_template');

                $email->sendTransactional(
                    Mage::getStoreConfig('jeganemailtabmenu/jeganeventcoupon/emailtemplate'),
                    Mage::getStoreConfig('jeganemailtabmenu/jeganeventcoupon/emailsender'),
                    $customerModel->getEmail(),
                    $customerModel->getName(),
                    array('couponcode' => $this->generateNewCouponCode('jeganemailtabmenu/jeganeventcoupon', $value, $eventId, $ruleId))
                );

                $translate->setTranslateInline(true);
            }
        }
    }
}
