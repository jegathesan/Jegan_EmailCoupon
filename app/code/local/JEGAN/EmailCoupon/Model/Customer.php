<?php

class JEGAN_EmailCoupon_Model_Customer extends Mage_Customer_Model_Customer
{


    const XML_PATH_CONFIRMED_EMAIL_TEMPLATE_WITH_COUPON = 'jeganemailtabmenu/jegannewuseremailcoupon/emailtemplate';
    const XML_PATH_CONFIRMED_EMAIL_SENDER_FOR_TEMPLATE_WITH_COUPON = 'jeganemailtabmenu/jegannewuseremailcoupon/emailsender';


    /**
     * Send email with new account related information
     *
     * @param string $type
     * @param string $backUrl
     * @param string $storeId
     * @throws Mage_Core_Exception
     * @return Mage_Customer_Model_Customer
     */

    public function sendNewAccountEmail($type = 'registered', $backUrl = '', $storeId = '0')
    {
        //Get store id
        if (!$storeId) {
            $storeId = $this->_getWebsiteStoreId($this->getSendemailStoreId());
        }

        // Read JEGAN email coupon configuration
        $configValue = Mage::getStoreConfig('jeganemailtabmenu/jegannewuseremailcoupon');

        //1.Check JEGAN Email coupon is enabled or not
        if ($configValue['enable']) {

            $this->_sendEmailTemplate(self::XML_PATH_CONFIRMED_EMAIL_TEMPLATE_WITH_COUPON, self::XML_PATH_CONFIRMED_EMAIL_SENDER_FOR_TEMPLATE_WITH_COUPON,
                array('customer' => $this, 'back_url' => $backUrl), $storeId);
        } else {
            //Magento core customer code starts
            $types = array(
                'registered' => self::XML_PATH_REGISTER_EMAIL_TEMPLATE, // welcome email, when confirmation is disabled
                'confirmed' => self::XML_PATH_CONFIRMED_EMAIL_TEMPLATE, // welcome email, when confirmation is enabled
                'confirmation' => self::XML_PATH_CONFIRM_EMAIL_TEMPLATE, // email with confirmation link
            );
            if (!isset($types[$type])) {
                Mage::throwException(Mage::helper('customer')->__('Wrong transactional account email type'));
            }

            $this->_sendEmailTemplate($types[$type], self::XML_PATH_REGISTER_EMAIL_IDENTITY,
                array('customer' => $this, 'back_url' => $backUrl), $storeId);
            //Magento core customer code ends
        }
        return $this;
    }

    public function  getCouponCode()
    {
        $helperData = new JEGAN_EmailCoupon_Helper_Data();
        return $helperData->generateNewCouponCode('jeganemailtabmenu/jegannewuseremailcoupon', $this->getId(), 3);
    }
}