<?php
/**
 * Faonni
 *  
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 *
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade module to newer
 * versions in the future.
 * 
 * @package     Faonni_Shape
 * @copyright   Copyright (c) 2014 Faonni Vitalii(faonni.vitalii@gmail.com) 
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class Faonni_Shape_Block_Adminhtml_Shape_Grid 
	extends Mage_Adminhtml_Block_Widget_Grid
{
    /**
     * Initialize grid
	 *
     * Set sort settings
     */
    public function __construct()
    {
        parent::__construct();
        $this->setId('shapeGrid');
        $this->setDefaultSort('rule_id');
        $this->setDefaultDir('ASC');
    }

    /**
     * Add websites to shape rules collection
     * Set collection
	 *
     * @return Faonni_Shape_Block_Adminhtml_Shape_Grid
     */
    protected function _prepareCollection()
    {
        /** @var $collection Faonni_Shape_Model_Resource_Rule_Collection */
        $collection = Mage::getModel('faonni_shape/rule')->getCollection();
        $this->setCollection($collection);

        parent::_prepareCollection();
        return $this;
    }

    /**
     * Add grid columns
	 *
     * @return Faonni_Shape_Block_Adminhtml_Shape_Grid
     */
    protected function _prepareColumns()
    {
        $this->addColumn('rule_id', array(
            'header'    => Mage::helper('faonni_shape')->__('ID'),
            'align'     => 'right',
            'width'     => '50px',
            'index'     => 'rule_id',
        ));

        $this->addColumn('rule_name', array(
            'header'    => Mage::helper('faonni_shape')->__('Rule Name'),
            'align'     => 'left',
            'index'     => 'rule_name',
        ));
		
        $this->addColumn('rule_description', array(
            'header'    => Mage::helper('faonni_shape')->__('Rule Description'),
            'align'     => 'left',
            'index'     => 'rule_description',
        ));
		
        $this->addColumn('is_active', array(
            'header'    => Mage::helper('faonni_shape')->__('Status'),
            'align'     => 'left',
            'width'     => '80px',
            'index'     => 'is_active',
            'type'      => 'options',
            'options'   => array(
                1 => Mage::helper('faonni_shape')->__('Active'),
                0 => Mage::helper('faonni_shape')->__('Inactive'),
            ),
        ));

        parent::_prepareColumns();
        return $this;
    }

    /**
     * Retrieve row click URL
	 *
     * @param Varien_Object $row
     * @return string
     */
    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/edit', array('id' => $row->getRuleId()));
    }

}
