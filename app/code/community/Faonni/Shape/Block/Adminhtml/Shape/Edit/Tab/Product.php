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
class Faonni_Shape_Block_Adminhtml_Shape_Edit_Tab_Product
	extends Mage_Adminhtml_Block_Widget_Grid
{
    /**
     * Intialize grid
	 *
	 * @return void
     */
    public function __construct()
    {
        parent::__construct();
		
        $this->setId('productGrid');
        $this->setUseAjax(true);
        $this->setDefaultSort('entity_id');
        $this->setDefaultDir('ASC');
    }
	
    /**
     * Return current store
	 *
	 * @return void
     */	
    protected function _getStore()
    {
        $storeId = (int) $this->getRequest()->getParam('store', 0);
        return Mage::app()->getStore($storeId);
    }
	
    /**
     * Instantiate and prepare collection
	 *
     * @return Faonni_Shape_Block_Adminhtml_Shape_Edit_Tab_Product
     */
    protected function _prepareCollection()
    {
        $rule = Mage::registry('current_shape_rule');
        if ($rule && $rule->getId()){
			$store = $this->_getStore();
			$collection = $rule->getProductCollection($store->getId());	
				
			if (Mage::helper('catalog')->isModuleEnabled('Mage_CatalogInventory')) {
				$collection->joinField('qty',
					'cataloginventory/stock_item',
					'qty',
					'product_id=entity_id',
					'{{table}}.stock_id=1',
					'left');
			}
			if ($store->getId()) {
				$adminStore = Mage_Core_Model_App::ADMIN_STORE_ID;
				$collection->addStoreFilter($store);
				$collection->joinAttribute(
					'name',
					'catalog_product/name',
					'entity_id',
					null,
					'inner',
					$adminStore
				);
				$collection->joinAttribute(
					'custom_name',
					'catalog_product/name',
					'entity_id',
					null,
					'inner',
					$store->getId()
				);
				$collection->joinAttribute(
					'status',
					'catalog_product/status',
					'entity_id',
					null,
					'inner',
					$store->getId()
				);
				$collection->joinAttribute(
					'visibility',
					'catalog_product/visibility',
					'entity_id',
					null,
					'inner',
					$store->getId()
				);
				$collection->joinAttribute(
					'price',
					'catalog_product/price',
					'entity_id',
					null,
					'left',
					$store->getId()
				);
			}
			else {
				$collection->addAttributeToSelect('price');
				$collection->joinAttribute('status', 'catalog_product/status', 'entity_id', null, 'inner');
				$collection->joinAttribute('visibility', 'catalog_product/visibility', 'entity_id', null, 'inner');
			}
			$this->setCollection($collection);
		}
        return parent::_prepareCollection();
    }

    /**
     * Prepare columns for grid
	 *
     * @return Faonni_Shape_Block_Adminhtml_Shape_Edit_Tab_Product
     */
    protected function _prepareColumns()
    {
        $this->addColumn('entity_id', array(
            'header'    => Mage::helper('faonni_shape')->__('ID'),
            'sortable'  => true,
            'width'     => '60',
            'index'     => 'entity_id'
        ));
		
        $this->addColumn('name', array(
            'header'    => Mage::helper('faonni_shape')->__('Name'),
            'index'     => 'name'
        ));
		
        $this->addColumn('type',
            array(
                'header'=> Mage::helper('faonni_shape')->__('Type'),
                'width' => '60px',
                'index' => 'type_id',
                'type'  => 'options',
                'options' => Mage::getSingleton('catalog/product_type')->getOptionArray(),
        ));	
		
        $sets = Mage::getResourceModel('eav/entity_attribute_set_collection')
            ->setEntityTypeFilter(Mage::getModel('catalog/product')->getResource()->getTypeId())
            ->load()
            ->toOptionHash();

        $this->addColumn('set_name',
            array(
                'header'=> Mage::helper('faonni_shape')->__('Attrib. Set Name'),
                'width' => '100px',
                'index' => 'attribute_set_id',
                'type'  => 'options',
                'options' => $sets,
        ));
		
        $this->addColumn('sku', array(
            'header'    => Mage::helper('faonni_shape')->__('SKU'),
            'width'     => '80',
            'index'     => 'sku'
        ));
		
        $this->addColumn('price', array(
            'header'    => Mage::helper('faonni_shape')->__('Price'),
            'type'      => 'currency',
            'width'     => '1',
            'currency_code' => (string) Mage::getStoreConfig(Mage_Directory_Model_Currency::XML_PATH_CURRENCY_BASE),
            'index'     => 'price'
        ));
		
	    if (Mage::helper('catalog')->isModuleEnabled('Mage_CatalogInventory')) {
            $this->addColumn('qty',
                array(
                    'header'=> Mage::helper('faonni_shape')->__('Qty'),
                    'width' => '100px',
                    'type'  => 'number',
                    'index' => 'qty',
            ));
        }

        $this->addColumn('visibility',
            array(
                'header'=> Mage::helper('faonni_shape')->__('Visibility'),
                'width' => '70px',
                'index' => 'visibility',
                'type'  => 'options',
                'options' => Mage::getModel('catalog/product_visibility')->getOptionArray(),
        ));

        $this->addColumn('status',
            array(
                'header'=> Mage::helper('faonni_shape')->__('Status'),
                'width' => '70px',
                'index' => 'status',
                'type'  => 'options',
                'options' => Mage::getSingleton('catalog/product_status')->getOptionArray(),
        ));	

        return parent::_prepareColumns();
    }
}
