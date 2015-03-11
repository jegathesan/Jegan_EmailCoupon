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
 * Email Coupon admin controller
 *
 * @category    JEGAN
 * @package     JEGAN_EmailCoupon
 * @author      Ultimate Module Creator
 */
class JEGAN_EmailCoupon_Adminhtml_Emailcoupon_EmailcouponController extends JEGAN_EmailCoupon_Controller_Adminhtml_EmailCoupon
{
    /**
     * init the email coupon
     *
     * @access protected
     * @return JEGAN_EmailCoupon_Model_Emailcoupon
     */
    protected function _initEmailcoupon()
    {
        $emailcouponId  = (int) $this->getRequest()->getParam('id');
        $emailcoupon    = Mage::getModel('jegan_emailcoupon/emailcoupon');
        if ($emailcouponId) {
            $emailcoupon->load($emailcouponId);
        }
        Mage::register('current_emailcoupon', $emailcoupon);
        return $emailcoupon;
    }

    /**
     * default action
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function indexAction()
    {
        $this->loadLayout();
        $this->_title(Mage::helper('jegan_emailcoupon')->__('JEGAN Email Coupon'))
             ->_title(Mage::helper('jegan_emailcoupon')->__('Email Coupons'));
        $this->renderLayout();
    }

    /**
     * grid action
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function gridAction()
    {
        $this->loadLayout()->renderLayout();
    }

    /**
     * edit email coupon - action
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function editAction()
    {
        $emailcouponId    = $this->getRequest()->getParam('id');
        $emailcoupon      = $this->_initEmailcoupon();
        if ($emailcouponId && !$emailcoupon->getId()) {
            $this->_getSession()->addError(
                Mage::helper('jegan_emailcoupon')->__('This email coupon no longer exists.')
            );
            $this->_redirect('*/*/');
            return;
        }
        $data = Mage::getSingleton('adminhtml/session')->getEmailcouponData(true);
        if (!empty($data)) {
            $emailcoupon->setData($data);
        }
        Mage::register('emailcoupon_data', $emailcoupon);
        $this->loadLayout();
        $this->_title(Mage::helper('jegan_emailcoupon')->__('JEGAN Email Coupon'))
             ->_title(Mage::helper('jegan_emailcoupon')->__('Email Coupons'));
        if ($emailcoupon->getId()) {
            $this->_title($emailcoupon->getCouponId());
        } else {
            $this->_title(Mage::helper('jegan_emailcoupon')->__('Add email coupon'));
        }
        if (Mage::getSingleton('cms/wysiwyg_config')->isEnabled()) {
            $this->getLayout()->getBlock('head')->setCanLoadTinyMce(true);
        }
        $this->renderLayout();
    }

    /**
     * new email coupon action
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function newAction()
    {
        $this->_forward('edit');
    }

    /**
     * save email coupon - action
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function saveAction()
    {
        if ($data = $this->getRequest()->getPost('emailcoupon')) {
            try {
                $emailcoupon = $this->_initEmailcoupon();
                $emailcoupon->addData($data);
                $emailcoupon->save();
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('jegan_emailcoupon')->__('Email Coupon was successfully saved')
                );
                Mage::getSingleton('adminhtml/session')->setFormData(false);
                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', array('id' => $emailcoupon->getId()));
                    return;
                }
                $this->_redirect('*/*/');
                return;
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setEmailcouponData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            } catch (Exception $e) {
                Mage::logException($e);
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('jegan_emailcoupon')->__('There was a problem saving the email coupon.')
                );
                Mage::getSingleton('adminhtml/session')->setEmailcouponData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(
            Mage::helper('jegan_emailcoupon')->__('Unable to find email coupon to save.')
        );
        $this->_redirect('*/*/');
    }

    /**
     * delete email coupon - action
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function deleteAction()
    {
        if ( $this->getRequest()->getParam('id') > 0) {
            try {
                $emailcoupon = Mage::getModel('jegan_emailcoupon/emailcoupon');
                $emailcoupon->setId($this->getRequest()->getParam('id'))->delete();
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('jegan_emailcoupon')->__('Email Coupon was successfully deleted.')
                );
                $this->_redirect('*/*/');
                return;
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('jegan_emailcoupon')->__('There was an error deleting email coupon.')
                );
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                Mage::logException($e);
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(
            Mage::helper('jegan_emailcoupon')->__('Could not find email coupon to delete.')
        );
        $this->_redirect('*/*/');
    }

    /**
     * mass delete email coupon - action
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function massDeleteAction()
    {
        $emailcouponIds = $this->getRequest()->getParam('emailcoupon');
        if (!is_array($emailcouponIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('jegan_emailcoupon')->__('Please select email coupons to delete.')
            );
        } else {
            try {
                foreach ($emailcouponIds as $emailcouponId) {
                    $emailcoupon = Mage::getModel('jegan_emailcoupon/emailcoupon');
                    $emailcoupon->setId($emailcouponId)->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('jegan_emailcoupon')->__('Total of %d email coupons were successfully deleted.', count($emailcouponIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('jegan_emailcoupon')->__('There was an error deleting email coupons.')
                );
                Mage::logException($e);
            }
        }
        $this->_redirect('*/*/index');
    }

    /**
     * mass status change - action
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function massStatusAction()
    {
        $emailcouponIds = $this->getRequest()->getParam('emailcoupon');
        if (!is_array($emailcouponIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('jegan_emailcoupon')->__('Please select email coupons.')
            );
        } else {
            try {
                foreach ($emailcouponIds as $emailcouponId) {
                $emailcoupon = Mage::getSingleton('jegan_emailcoupon/emailcoupon')->load($emailcouponId)
                            ->setStatus($this->getRequest()->getParam('status'))
                            ->setIsMassupdate(true)
                            ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d email coupons were successfully updated.', count($emailcouponIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('jegan_emailcoupon')->__('There was an error updating email coupons.')
                );
                Mage::logException($e);
            }
        }
        $this->_redirect('*/*/index');
    }

    /**
     * mass Customer Name change - action
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function massCustomerIdAction()
    {
        $emailcouponIds = $this->getRequest()->getParam('emailcoupon');
        if (!is_array($emailcouponIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('jegan_emailcoupon')->__('Please select email coupons.')
            );
        } else {
            try {
                foreach ($emailcouponIds as $emailcouponId) {
                $emailcoupon = Mage::getSingleton('jegan_emailcoupon/emailcoupon')->load($emailcouponId)
                    ->setCustomerId($this->getRequest()->getParam('flag_customer_id'))
                    ->setIsMassupdate(true)
                    ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d email coupons were successfully updated.', count($emailcouponIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('jegan_emailcoupon')->__('There was an error updating email coupons.')
                );
                Mage::logException($e);
            }
        }
        $this->_redirect('*/*/index');
    }

    /**
     * mass Coupon Rule Name change - action
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function massCouponRuleIdAction()
    {
        $emailcouponIds = $this->getRequest()->getParam('emailcoupon');
        if (!is_array($emailcouponIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('jegan_emailcoupon')->__('Please select email coupons.')
            );
        } else {
            try {
                foreach ($emailcouponIds as $emailcouponId) {
                $emailcoupon = Mage::getSingleton('jegan_emailcoupon/emailcoupon')->load($emailcouponId)
                    ->setCouponRuleId($this->getRequest()->getParam('flag_coupon_rule_id'))
                    ->setIsMassupdate(true)
                    ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d email coupons were successfully updated.', count($emailcouponIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('jegan_emailcoupon')->__('There was an error updating email coupons.')
                );
                Mage::logException($e);
            }
        }
        $this->_redirect('*/*/index');
    }

    /**
     * export as csv - action
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function exportCsvAction()
    {
        $fileName   = 'emailcoupon.csv';
        $content    = $this->getLayout()->createBlock('jegan_emailcoupon/adminhtml_emailcoupon_grid')
            ->getCsv();
        $this->_prepareDownloadResponse($fileName, $content);
    }

    /**
     * export as MsExcel - action
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function exportExcelAction()
    {
        $fileName   = 'emailcoupon.xls';
        $content    = $this->getLayout()->createBlock('jegan_emailcoupon/adminhtml_emailcoupon_grid')
            ->getExcelFile();
        $this->_prepareDownloadResponse($fileName, $content);
    }

    /**
     * export as xml - action
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function exportXmlAction()
    {
        $fileName   = 'emailcoupon.xml';
        $content    = $this->getLayout()->createBlock('jegan_emailcoupon/adminhtml_emailcoupon_grid')
            ->getXml();
        $this->_prepareDownloadResponse($fileName, $content);
    }

    /**
     * Check if admin has permissions to visit related pages
     *
     * @access protected
     * @return boolean
     * @author Ultimate Module Creator
     */
    protected function _isAllowed()
    {
        return Mage::getSingleton('admin/session')->isAllowed('jegan_emailcoupon/emailcoupon');
    }
}
