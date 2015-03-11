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
 * Event Coupon admin controller
 *
 * @category    JEGAN
 * @package     JEGAN_EmailCoupon
 * @author      Ultimate Module Creator
 */
class JEGAN_EmailCoupon_Adminhtml_Emailcoupon_EventcouponController extends JEGAN_EmailCoupon_Controller_Adminhtml_EmailCoupon
{
    /**
     * init the event coupon
     *
     * @access protected
     * @return JEGAN_EmailCoupon_Model_Eventcoupon
     */
    protected function _initEventcoupon()
    {
        $eventcouponId = (int)$this->getRequest()->getParam('id');
        $eventcoupon = Mage::getModel('jegan_emailcoupon/eventcoupon');
        if ($eventcouponId) {
            $eventcoupon->load($eventcouponId);
        }
        Mage::register('current_eventcoupon', $eventcoupon);
        return $eventcoupon;
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
            ->_title(Mage::helper('jegan_emailcoupon')->__('Event Coupons'));
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
     * edit event coupon - action
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function editAction()
    {
        $eventcouponId = $this->getRequest()->getParam('id');
        $eventcoupon = $this->_initEventcoupon();
        if ($eventcouponId && !$eventcoupon->getId()) {
            $this->_getSession()->addError(
                Mage::helper('jegan_emailcoupon')->__('This event coupon no longer exists.')
            );
            $this->_redirect('*/*/');
            return;
        }
        $data = Mage::getSingleton('adminhtml/session')->getEventcouponData(true);
        if (!empty($data)) {
            $eventcoupon->setData($data);
        }
        Mage::register('eventcoupon_data', $eventcoupon);
        $this->loadLayout();
        $this->_title(Mage::helper('jegan_emailcoupon')->__('JEGAN Email Coupon'))
            ->_title(Mage::helper('jegan_emailcoupon')->__('Event Coupons'));
        if ($eventcoupon->getId()) {
            $this->_title($eventcoupon->getEventName());
        } else {
            $this->_title(Mage::helper('jegan_emailcoupon')->__('Add event coupon'));
        }
        if (Mage::getSingleton('cms/wysiwyg_config')->isEnabled()) {
            $this->getLayout()->getBlock('head')->setCanLoadTinyMce(true);
        }
        $this->renderLayout();
    }

    /**
     * new event coupon action
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
     * save event coupon - action
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function saveAction()
    {
        $helperData = new JEGAN_EmailCoupon_Helper_Data();

        if ($data = $this->getRequest()->getPost('eventcoupon')) {
            $customerId = $data['customerid'];
            unset($data['customerid']);
            try {
                $eventcoupon = $this->_initEventcoupon();
                $eventcoupon->addData($data);
                $eventcoupon->save();

                $helperData->sendCouponCodesToCustomer($customerId, $eventcoupon->getId(), $data['coupon_rule_id']);

                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('jegan_emailcoupon')->__('Event Coupon was successfully saved')
                );
                Mage::getSingleton('adminhtml/session')->setFormData(false);
                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', array('id' => $eventcoupon->getId()));
                    return;
                }
                $this->_redirect('*/*/');
                return;
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setEventcouponData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            } catch (Exception $e) {
                Mage::logException($e);
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('jegan_emailcoupon')->__('There was a problem saving the event coupon.')
                );
                Mage::getSingleton('adminhtml/session')->setEventcouponData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(
            Mage::helper('jegan_emailcoupon')->__('Unable to find event coupon to save.')
        );
        $this->_redirect('*/*/');
    }

    /**
     * delete event coupon - action
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function deleteAction()
    {
        if ($this->getRequest()->getParam('id') > 0) {
            try {
                $eventcoupon = Mage::getModel('jegan_emailcoupon/eventcoupon');
                $eventcoupon->setId($this->getRequest()->getParam('id'))->delete();
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('jegan_emailcoupon')->__('Event Coupon was successfully deleted.')
                );
                $this->_redirect('*/*/');
                return;
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('jegan_emailcoupon')->__('There was an error deleting event coupon.')
                );
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                Mage::logException($e);
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(
            Mage::helper('jegan_emailcoupon')->__('Could not find event coupon to delete.')
        );
        $this->_redirect('*/*/');
    }

    /**
     * mass delete event coupon - action
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function massDeleteAction()
    {
        $eventcouponIds = $this->getRequest()->getParam('eventcoupon');
        if (!is_array($eventcouponIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('jegan_emailcoupon')->__('Please select event coupons to delete.')
            );
        } else {
            try {
                foreach ($eventcouponIds as $eventcouponId) {
                    $eventcoupon = Mage::getModel('jegan_emailcoupon/eventcoupon');
                    $eventcoupon->setId($eventcouponId)->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('jegan_emailcoupon')->__('Total of %d event coupons were successfully deleted.', count($eventcouponIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('jegan_emailcoupon')->__('There was an error deleting event coupons.')
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
        $eventcouponIds = $this->getRequest()->getParam('eventcoupon');
        if (!is_array($eventcouponIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('jegan_emailcoupon')->__('Please select event coupons.')
            );
        } else {
            try {
                foreach ($eventcouponIds as $eventcouponId) {
                    $eventcoupon = Mage::getSingleton('jegan_emailcoupon/eventcoupon')->load($eventcouponId)
                        ->setStatus($this->getRequest()->getParam('status'))
                        ->setIsMassupdate(true)
                        ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d event coupons were successfully updated.', count($eventcouponIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('jegan_emailcoupon')->__('There was an error updating event coupons.')
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
        $fileName = 'eventcoupon.csv';
        $content = $this->getLayout()->createBlock('jegan_emailcoupon/adminhtml_eventcoupon_grid')
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
        $fileName = 'eventcoupon.xls';
        $content = $this->getLayout()->createBlock('jegan_emailcoupon/adminhtml_eventcoupon_grid')
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
        $fileName = 'eventcoupon.xml';
        $content = $this->getLayout()->createBlock('jegan_emailcoupon/adminhtml_eventcoupon_grid')
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
        return Mage::getSingleton('admin/session')->isAllowed('jegan_emailcoupon/eventcoupon');
    }
}
