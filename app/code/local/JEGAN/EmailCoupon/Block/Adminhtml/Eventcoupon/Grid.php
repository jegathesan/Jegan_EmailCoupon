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
class JEGAN_EmailCoupon_Block_Adminhtml_Eventcoupon_Grid extends Mage_Adminhtml_Block_Widget_Grid
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
        $this->setId('eventcouponGrid');
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
        $collection = Mage::getModel('jegan_emailcoupon/eventcoupon')
            ->getCollection();
        $collection->getSelect()->join(array('a'=>'salesrule'),'main_table.coupon_rule_id = a.rule_id');
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
            'event_name',
            array(
                'header'    => Mage::helper('jegan_emailcoupon')->__('Event name'),
                'align'     => 'left',
                'index'     => 'event_name',
            )
        );

        $this->addColumn(
            'coupon_rule_id',
            array(
                'header' => Mage::helper('jegan_emailcoupon')->__('Coupon Rule Name'),
                'index'  => 'coupon_rule_id',
                'type'  => 'options',
                'options' => Mage::helper('jegan_emailcoupon')->convertOptions(
                    Mage::getModel('jegan_emailcoupon/emailcoupon_attribute_source_couponruleid')->getAllOptions(false)
                )

            )
        );
        
        $this->addColumn(
            'status',
            array(
                'header'  => Mage::helper('jegan_emailcoupon')->__('Status'),
                'index'   => 'status',
                'type'    => 'options',
                'options' => array(
                    '1' => Mage::helper('jegan_emailcoupon')->__('Enabled'),
                    '0' => Mage::helper('jegan_emailcoupon')->__('Disabled'),
                )
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
        $this->addColumn(
            'updated_at',
            array(
                'header'    => Mage::helper('jegan_emailcoupon')->__('Updated at'),
                'index'     => 'updated_at',
                'width'     => '120px',
                'type'      => 'datetime',
            )
        );
        $this->addColumn(
            'action',
            array(
                'header'  =>  Mage::helper('jegan_emailcoupon')->__('Action'),
                'width'   => '100',
                'type'    => 'action',
                'getter'  => 'getId',
                'actions' => array(
                    array(
                        'caption' => Mage::helper('jegan_emailcoupon')->__('Edit'),
                        'url'     => array('base'=> '*/*/edit'),
                        'field'   => 'id'
                    )
                ),
                'filter'    => false,
                'is_system' => true,
                'sortable'  => false,
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
    protected function _preparingMassaction()
    {
        $this->setMassactionIdField('entity_id');
        $this->getMassactionBlock()->setFormFieldName('eventcoupon');
        $this->getMassactionBlock()->addItem(
            'delete',
            array(
                'label'=> Mage::helper('jegan_emailcoupon')->__('Delete'),
                'url'  => $this->getUrl('*/*/massDelete'),
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
     * get the row url
     *
     * @access public
     * @param JEGAN_EmailCoupon_Model_Eventcoupon
     * @return string
     * @author Ultimate Module Creator
     */
    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/edit', array('id' => $row->getId()));
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
        return $this->getUrl('*/*/grid', array('_current'=>true));
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
