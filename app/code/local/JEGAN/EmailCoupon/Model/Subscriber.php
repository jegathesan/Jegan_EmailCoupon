<?php

class JEGAN_EmailCoupon_Model_Subscriber extends Mage_Newsletter_Model_Subscriber
{

    const XML_PATH_NEWS_SUBSCRIBE_CONFIRMED_EMAIL_TEMPLATE_WITH_COUPON = 'jeganemailtabmenu/jegannewsletteremailcoupon/emailtemplate';
    const XML_PATH_NEWS_SUBSCRIBE_CONFIRMED_EMAIL_SENDER_FOR_TEMPLATE_WITH_COUPON = 'jeganemailtabmenu/jegannewsletteremailcoupon/emailsender';

    /**
     * Sends out confirmation success email
     *
     * @return Mage_Newsletter_Model_Subscriber
     */
    public function sendConfirmationSuccessEmail()
    {
        if ($this->getImportMode()) {
            return $this;
        }

        // Read JEGAN email coupon configuration
        $configValue = Mage::getStoreConfig('jeganemailtabmenu/jegannewsletteremailcoupon');
        $defaultEmailIdentity = self::XML_PATH_SUCCESS_EMAIL_IDENTITY;
        $defaultEmailTemplate = self::XML_PATH_SUCCESS_EMAIL_TEMPLATE;

        //Check JEGAN Email coupon is enabled or not
        if ($configValue['enable']) {
            $defaultEmailIdentity = self::XML_PATH_NEWS_SUBSCRIBE_CONFIRMED_EMAIL_SENDER_FOR_TEMPLATE_WITH_COUPON;
            $defaultEmailTemplate = self::XML_PATH_NEWS_SUBSCRIBE_CONFIRMED_EMAIL_TEMPLATE_WITH_COUPON;
        }


        if (!Mage::getStoreConfig($defaultEmailTemplate)
            || !Mage::getStoreConfig($defaultEmailIdentity)
        ) {
            return $this;
        }

        $translate = Mage::getSingleton('core/translate');
        /* @var $translate Mage_Core_Model_Translate */
        $translate->setTranslateInline(false);

        $email = Mage::getModel('core/email_template');

        $email->sendTransactional(
            Mage::getStoreConfig($defaultEmailTemplate),
            Mage::getStoreConfig($defaultEmailIdentity),
            $this->getEmail(),
            $this->getName(),
            array('subscriber' => $this)
        );

        $translate->setTranslateInline(true);

        return $this;
    }

    public function  getCouponCode()
    {
        $helperData = new JEGAN_EmailCoupon_Helper_Data();
        return $helperData->generateNewCouponCode('jeganemailtabmenu/jegannewsletteremailcoupon', $this->getCustomerId(), 1);
    }

}