<?php

class JEGAN_EmailCoupon_Model_Order extends Mage_Sales_Model_Order
{

    const XML_PATH_NEW_ORDER_CONFIRMED_EMAIL_TEMPLATE_WITH_COUPON = 'jeganemailtabmenu/jeganfirstorderemailcoupon/emailtemplate';
    const XML_PATH_NEW_ORDER_EMAIL_SENDER_FOR_TEMPLATE_WITH_COUPON = 'jeganemailtabmenu/jeganfirstorderemailcoupon/emailsender';

    /**
     * Send email with order data
     *
     * @return Mage_Sales_Model_Order
     * @throws Exception
     */
    public function sendNewOrderEmail()
    {
        $storeId = $this->getStore()->getId();

        if (!Mage::helper('sales')->canSendNewOrderEmail($storeId)) {
            return $this;
        }

        $emailSentAttributeValue = $this->hasEmailSent()
            ? $this->getEmailSent()
            : Mage::getModel('sales/order')->load($this->getId())->getData('email_sent');
        $this->setEmailSent((bool)$emailSentAttributeValue);
        if ($this->getEmailSent()) {
            return $this;
        }

        // Get the destination email addresses to send copies to
        $copyTo = $this->_getEmails(self::XML_PATH_EMAIL_COPY_TO);
        $copyMethod = Mage::getStoreConfig(self::XML_PATH_EMAIL_COPY_METHOD, $storeId);

        // Start store emulation process
        $appEmulation = Mage::getSingleton('core/app_emulation');
        $initialEnvironmentInfo = $appEmulation->startEnvironmentEmulation($storeId);

        try {
            // Retrieve specified view block from appropriate design package (depends on emulated store)
            $paymentBlock = Mage::helper('payment')->getInfoBlock($this->getPayment())
                ->setIsSecureMode(true);
            $paymentBlock->getMethod()->setStore($storeId);
            $paymentBlockHtml = $paymentBlock->toHtml();
        } catch (Exception $exception) {
            // Stop store emulation process
            $appEmulation->stopEnvironmentEmulation($initialEnvironmentInfo);
            throw $exception;
        }

        // Stop store emulation process
        $appEmulation->stopEnvironmentEmulation($initialEnvironmentInfo);

        // Retrieve corresponding email template id and customer name
        if ($this->getCustomerIsGuest()) {
            $templateId = Mage::getStoreConfig(self::XML_PATH_EMAIL_GUEST_TEMPLATE, $storeId);
            $customerName = $this->getBillingAddress()->getName();
        } else {
            $templateId = Mage::getStoreConfig(self::XML_PATH_EMAIL_TEMPLATE, $storeId);
            $customerName = $this->getCustomerName();
        }


        $mailer = Mage::getModel('core/email_template_mailer');
        $emailInfo = Mage::getModel('core/email_info');
        $emailInfo->addTo($this->getCustomerEmail(), $customerName);
        if ($copyTo && $copyMethod == 'bcc') {
            // Add bcc to customer email
            foreach ($copyTo as $email) {
                $emailInfo->addBcc($email);
            }
        }
        $mailer->addEmailInfo($emailInfo);

        // Email copies are sent as separated emails if their copy method is 'copy'
        if ($copyTo && $copyMethod == 'copy') {
            foreach ($copyTo as $email) {
                $emailInfo = Mage::getModel('core/email_info');
                $emailInfo->addTo($email);
                $mailer->addEmailInfo($emailInfo);
            }
        }


        $emailIdentity = Mage::getStoreConfig(self::XML_PATH_EMAIL_IDENTITY, $storeId);
        $templateParams = array(
            'order'        => $this,
            'billing'      => $this->getBillingAddress(),
            'payment_html' => $paymentBlockHtml
        );

        //1.Check for first order coupon email is enabled
        $config = Mage::getStoreConfig('jeganemailtabmenu/jeganfirstorderemailcoupon', $storeId);
        if($config['enable']){
            //2.Check for customer order count
            $ordersCount = Mage::getResourceModel('sales/order_collection')
                ->addFieldToSelect('*')
                ->addFieldToFilter('customer_id', $this->getCustomerId())
                ->count();
            if($ordersCount == 0){
                $templateId = Mage::getStoreConfig(self::XML_PATH_NEW_ORDER_CONFIRMED_EMAIL_TEMPLATE_WITH_COUPON, $storeId);
                $emailIdentity = Mage::getStoreConfig(self::XML_PATH_NEW_ORDER_EMAIL_SENDER_FOR_TEMPLATE_WITH_COUPON, $storeId);
                $helperData = new JEGAN_EmailCoupon_Helper_Data();
                $couponCode = $helperData->generateNewCouponCode('jeganemailtabmenu/jeganfirstorderemailcoupon', $this->getCustomerId(), 2);
                $templateParams['couponCode'] = $couponCode;
            }
        }


        // Set all required params and send emails
        $mailer->setSender($emailIdentity);
        $mailer->setStoreId($storeId);
        $mailer->setTemplateId($templateId);
        $mailer->setTemplateParams($templateParams);
        $mailer->send();

        $this->setEmailSent(true);
        $this->_getResource()->saveAttribute($this, 'email_sent');

        return $this;
    }


}