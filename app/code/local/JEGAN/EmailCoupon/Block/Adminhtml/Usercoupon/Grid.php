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
 * Event Coupon admin grid block
 *
 * @category    JEGAN
 * @package     JEGAN_EmailCoupon
 * @author      Ultimate Module Creator
 */
class JEGAN_EmailCoupon_Block_Adminhtml_Usercoupon_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    /**
     * constructor
     *
     * @access public
     * @author Ultimate Module Creator
     */
    public function __construct()
    {
        parent::__construct();
        $this->setId('usercouponGrid');
        $this->setDefaultSort('entity_id');
        $this->setDefaultDir('ASC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);
    }

    /**
     * prepare collection
     *
     * @access protected
     * @return JEGAN_EmailCoupon_Block_Adminhtml_Eventcoupon_Grid
     * @author Ultimate Module Creator
     */
    protected function _prepareCollection()
    {

        $reason = 0;
        if($this->getRequest()->getParam('id') != null){
            $reason =  $this->getRequest()->getParam('id');
        }

        $collection = Mage::getModel('jegan_emailcoupon/emailcoupon')
            ->getCollection();
        $collection->getSelect()
            ->join(array('a' => 'salesrule_coupon'), 'main_table.coupon_id = a.coupon_id')
            ->where("main_table.reason not in (1,2,3) and main_table.reason = " . $reason);

        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    /**
     * prepare grid collection
     *
     * @access protected
     * @return JEGAN_EmailCoupon_Block_Adminhtml_Eventcoupon_Grid
     * @author Ultimate Module Creator
     */
    protected function _prepareColumns()
    {
        $this->addColumn(
            'entity_id',
            array(
                'header' => Mage::helper('jegan_emailcoupon')->__('Id'),
                'index'  => 'entity_id',
                'type'   => 'number'
            )
        );

        $this->addColumn(
            'customerid',
            array(
                'header' => Mage::helper('jegan_emailcoupon')->__('Customer Name'),
                'index'  => 'customer_id',
                'type'  => 'options',
                'options' => Mage::helper('jegan_emailcoupon')->convertOptions(
                    Mage::getModel('jegan_emailcoupon/emailcoupon_attribute_source_customerid')->getAllOptions(false)
                )

            )
        );

        $this->addColumn(
            'coupon_id',
            array(
                'header' => Mage::helper('jegan_emailcoupon')->__('Coupon Code'),
                'index'  => 'code',
                'type'  => 'string'
            )
        );

        if (!Mage::app()->isSingleStoreMode() && !$this->_isExport) {
            $this->addColumn(
                'store_id',
                array(
                    'header'     => Mage::helper('jegan_emailcoupon')->__('Store Views'),
                    'index'      => 'store_id',
                    'type'       => 'store',
                    'store_all'  => true,
                    'store_view' => true,
                    'sortable'   => false,
                    'filter_condition_callback'=> array($this, '_filterStoreCondition'),
                )
            );
        }
        $this->addColumn(
            'created_at',
            array(
                'header' => Mage::helper('jegan_emailcoupon')->__('Created at'),
                'index'  => 'created_at',
                'width'  => '120px',
                'type'   => 'datetime',
            )
        );

        $this->addExportType('*/*/exportCsv', Mage::helper('jegan_emailcoupon')->__('CSV'));
        $this->addExportType('*/*/exportExcel', Mage::helper('jegan_emailcoupon')->__('Excel'));
        $this->addExportType('*/*/exportXml', Mage::helper('jegan_emailcoupon')->__('XML'));
        return parent::_prepareColumns();
    }

    /**
     * prepare mass action
     *
     * @access protected
     * @return JEGAN_EmailCoupon_Block_Adminhtml_Eventcoupon_Grid
     * @author Ultimate Module Creator
     */
    protected function _prepareingMassaction()
    {
        $this->setMassactionIdField('entity_id');
        $this->getMassactionBlock()->setFormFieldName('eventcoupon');
        $this->getMassactionBlock()->addItem(
            'delete',
            array(
                'label'=> Mage::helper('jegan_emailcoupon')->__('Delete'),
                'url'  => $this->getUrl('*/emailcoupon_usercoupon/massDelete'),
                'confirm'  => Mage::helper('jegan_emailcoupon')->__('Are you sure?')
            )
        );
        $this->getMassactionBlock()->addItem(
            'status',
            array(
                'label'      => Mage::helper('jegan_emailcoupon')->__('Change status'),
                'url'        => $this->getUrl('*/*/massStatus', array('_current'=>true)),
                'additional' => array(
                    'status' => array(
                        'name'   => 'status',
                        'type'   => 'select',
                        'class'  => 'required-entry',
                        'label'  => Mage::helper('jegan_emailcoupon')->__('Status'),
                        'values' => array(
                            '1' => Mage::helper('jegan_emailcoupon')->__('Enabled'),
                            '0' => Mage::helper('jegan_emailcoupon')->__('Disabled'),
                        )
                    )
                )
            )
        );
        return $this;
    }



    /**
     * get the grid url
     *
     * @access public
     * @return string
     * @author Ultimate Module Creator
     */
    public function getGridUrl()
    {
        return $this->getUrl('*/emailcoupon_usercoupon/grid', array('_current'=>true));
    }

    /**
     * after collection load
     *
     * @access protected
     * @return JEGAN_EmailCoupon_Block_Adminhtml_Eventcoupon_Grid
     * @author Ultimate Module Creator
     */
    protected function _afterLoadCollection()
    {
        $this->getCollection()->walk('afterLoad');
        parent::_afterLoadCollection();
    }

    /**
     * filter store column
     *
     * @access protected
     * @param JEGAN_EmailCoupon_Model_Resource_Eventcoupon_Collection $collection
     * @param Mage_Adminhtml_Block_Widget_Grid_Column $column
     * @return JEGAN_EmailCoupon_Block_Adminhtml_Eventcoupon_Grid
     * @author Ultimate Module Creator
     */
    protected function _filterStoreCondition($collection, $column)
    {

        if (!$value = $column->getFilter()->getValue()) {
            return;
        }
        $collection->addStoreFilter($value);
        return $this;
    }
}
