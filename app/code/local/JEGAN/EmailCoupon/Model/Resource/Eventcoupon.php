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
 * Event Coupon resource model
 *
 * @category    JEGAN
 * @package     JEGAN_EmailCoupon
 * @author      Ultimate Module Creator
 */
class JEGAN_EmailCoupon_Model_Resource_Eventcoupon extends Mage_Core_Model_Resource_Db_Abstract
{

    /**
     * constructor
     *
     * @access public
     * @author Ultimate Module Creator
     */
    public function _construct()
    {
        $this->_init('jegan_emailcoupon/eventcoupon', 'entity_id');
    }

    /**
     * Get store ids to which specified item is assigned
     *
     * @access public
     * @param int $eventcouponId
     * @return array
     * @author Ultimate Module Creator
     */
    public function lookupStoreIds($eventcouponId)
    {
        $adapter = $this->_getReadAdapter();
        $select  = $adapter->select()
            ->from($this->getTable('jegan_emailcoupon/eventcoupon_store'), 'store_id')
            ->where('eventcoupon_id = ?', (int)$eventcouponId);
        return $adapter->fetchCol($select);
    }

    /**
     * Perform operations after object load
     *
     * @access public
     * @param Mage_Core_Model_Abstract $object
     * @return JEGAN_EmailCoupon_Model_Resource_Eventcoupon
     * @author Ultimate Module Creator
     */
    protected function _afterLoad(Mage_Core_Model_Abstract $object)
    {
        if ($object->getId()) {
            $stores = $this->lookupStoreIds($object->getId());
            $object->setData('store_id', $stores);
        }
        return parent::_afterLoad($object);
    }

    /**
     * Retrieve select object for load object data
     *
     * @param string $field
     * @param mixed $value
     * @param JEGAN_EmailCoupon_Model_Eventcoupon $object
     * @return Zend_Db_Select
     */
    protected function _getLoadSelect($field, $value, $object)
    {
        $select = parent::_getLoadSelect($field, $value, $object);
        if ($object->getStoreId()) {
            $storeIds = array(Mage_Core_Model_App::ADMIN_STORE_ID, (int)$object->getStoreId());
            $select->join(
                array('emailcoupon_eventcoupon_store' => $this->getTable('jegan_emailcoupon/eventcoupon_store')),
                $this->getMainTable() . '.entity_id = emailcoupon_eventcoupon_store.eventcoupon_id',
                array()
            )
            ->where('emailcoupon_eventcoupon_store.store_id IN (?)', $storeIds)
            ->order('emailcoupon_eventcoupon_store.store_id DESC')
            ->limit(1);
        }
        return $select;
    }

    /**
     * Assign event coupon to store views
     *
     * @access protected
     * @param Mage_Core_Model_Abstract $object
     * @return JEGAN_EmailCoupon_Model_Resource_Eventcoupon
     * @author Ultimate Module Creator
     */
    protected function _afterSave(Mage_Core_Model_Abstract $object)
    {
        $oldStores = $this->lookupStoreIds($object->getId());
        $newStores = (array)$object->getStores();
        if (empty($newStores)) {
            $newStores = (array)$object->getStoreId();
        }
        $table  = $this->getTable('jegan_emailcoupon/eventcoupon_store');
        $insert = array_diff($newStores, $oldStores);
        $delete = array_diff($oldStores, $newStores);
        if ($delete) {
            $where = array(
                'eventcoupon_id = ?' => (int) $object->getId(),
                'store_id IN (?)' => $delete
            );
            $this->_getWriteAdapter()->delete($table, $where);
        }
        if ($insert) {
            $data = array();
            foreach ($insert as $storeId) {
                $data[] = array(
                    'eventcoupon_id'  => (int) $object->getId(),
                    'store_id' => (int) $storeId
                );
            }
            $this->_getWriteAdapter()->insertMultiple($table, $data);
        }
        return parent::_afterSave($object);
    }}
