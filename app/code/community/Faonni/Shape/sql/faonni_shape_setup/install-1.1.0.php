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
$installer = $this;
/** @var $installer Faonni_Shape_Model_Resource_Setup */
$installer->startSetup();
$setup = Mage::getResourceModel('catalog/eav_mysql4_setup', 'core_setup');

/**
 * Create table 'faonni_shape/rule'
*/
$table = $installer->getConnection()
    ->newTable($installer->getTable('faonni_shape/rule'))
	->addColumn('rule_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
		'identity'  => true,
		'unsigned'  => true,
		'nullable'  => false,
		'primary'   => true,
		), 'Rule Id')	
    ->addColumn('rule_name', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(
        'nullable'  => false,
        ), 'Name')
    ->addColumn('rule_description', Varien_Db_Ddl_Table::TYPE_TEXT, '64K', array(
        'nullable'  => false,
        ), 'Description')
    ->addColumn('conditions_serialized', Varien_Db_Ddl_Table::TYPE_TEXT, '2M', array(
        'nullable'  => false,
        ), 'Conditions Serialized')	
    ->addColumn('created_at', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array(
        'nullable'  => false,
        ), 'Creation Time')
    ->addColumn('updated_at', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array(
        'nullable'  => false,
        ), 'Updated Time')		
    ->addColumn('is_active', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
        'nullable'  => false,
		'unsigned'  => true,
		'default'   => '1',
        ), 'Rule Is Active');
		
$installer->getConnection()->createTable($table);

/**
 * Create table 'faonni_shape/rule_product'
*/	
$table = $installer->getConnection()
    ->newTable($installer->getTable('faonni_shape/rule_product'))
	->addColumn('rule_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
		'unsigned'  => true,
		'nullable'  => false,
		), 'Rule Id')	
    ->addColumn('store_id', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
        'nullable'  => false,
		'unsigned'  => true,
        ), 'Store Id')
	->addColumn('product_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
		'unsigned'  => true,
		'nullable'  => false,
		), 'Product Id')
		
	->addIndex($installer->getIdxName('faonni_shape/rule_product', array('rule_id')),
        array('rule_id'))	
	->addIndex($installer->getIdxName('faonni_shape/rule_product', array('store_id')),
        array('store_id'))	
	->addIndex($installer->getIdxName('faonni_shape/rule_product', array('product_id')),
        array('product_id'))
	->addIndex($installer->getIdxName('faonni_shape/rule_product', array('rule_id', 'store_id', 'product_id'), Varien_Db_Adapter_Interface::INDEX_TYPE_UNIQUE),
        array('rule_id', 'store_id', 'product_id'))	
		
    ->addForeignKey($installer->getFkName('faonni_shape/rule_product', 'rule_id', 'faonni_shape/rule', 'rule_id'),
        'rule_id', $installer->getTable('faonni_shape/rule'), 'rule_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_CASCADE)
    ->addForeignKey($installer->getFkName('faonni_shape/rule_product', 'store_id', 'core/store', 'store_id'),
        'store_id', $installer->getTable('core/store'), 'store_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_CASCADE)
    ->addForeignKey($installer->getFkName('faonni_shape/rule_product', 'product_id', 'catalog/product', 'entity_id'),
        'product_id', $installer->getTable('catalog/product'), 'entity_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_CASCADE);
		
$installer->getConnection()->createTable($table);			
		
/**
 * Create table 'faonni_shape/template'
*/
$table = $installer->getConnection()
    ->newTable($installer->getTable('faonni_shape/template'))
	->addColumn('entity_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
		'identity'  => true,
		'unsigned'  => true,
		'nullable'  => false,
		'primary'   => true,
		), 'Template Rule Id');	
		
$installer->getConnection()->createTable($table);			
		
/**
 * Create table 'faonni_shape/template_entity_varchar'
*/
$table = $installer->getConnection()
    ->newTable($installer->getTable('faonni_shape/template_entity_varchar'))
	->addColumn('value_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
		'identity'  => true,
		'unsigned'  => true,
		'nullable'  => false,
		'primary'   => true,
		), 'Value Id')
	->addColumn('entity_type_id', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
        'nullable'  => false,
		'unsigned'  => true,
        ), 'Entity Type Id')		
	->addColumn('attribute_id', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
        'nullable'  => false,
		'unsigned'  => true,
        ), 'Attribute Id')		
    ->addColumn('store_id', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
        'nullable'  => false,
		'unsigned'  => true,
        ), 'Store Id')		
	->addColumn('entity_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
		'unsigned'  => true,
		'nullable'  => false,
		), 'Entity Id')
    ->addColumn('value', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(
        'nullable'  => false,
        ), 'Value')

	->addIndex($installer->getIdxName('faonni_shape/template_entity_varchar', array('entity_id')),
        array('entity_id'))	
	->addIndex($installer->getIdxName('faonni_shape/template_entity_varchar', array('store_id')),
        array('store_id'))	
	->addIndex($installer->getIdxName('faonni_shape/template_entity_varchar', array('entity_type_id')),
        array('entity_type_id'))
	->addIndex($installer->getIdxName('faonni_shape/template_entity_varchar', array('attribute_id')),
        array('attribute_id'))		
	->addIndex($installer->getIdxName('faonni_shape/template_entity_varchar', array('entity_id', 'store_id', 'attribute_id'), Varien_Db_Adapter_Interface::INDEX_TYPE_UNIQUE),
        array('entity_id', 'store_id', 'attribute_id'))	

    ->addForeignKey($installer->getFkName('faonni_shape/template_entity_varchar', 'entity_id', 'faonni_shape/template', 'entity_id'),
        'entity_id', $installer->getTable('faonni_shape/template'), 'entity_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_CASCADE)
    ->addForeignKey($installer->getFkName('faonni_shape/template_entity_varchar', 'store_id', 'core/store', 'store_id'),
        'store_id', $installer->getTable('core/store'), 'store_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_CASCADE)
    ->addForeignKey($installer->getFkName('faonni_shape/template_entity_varchar', 'entity_type_id', 'eav/entity_type', 'entity_type_id'),
        'entity_type_id', $installer->getTable('eav/entity_type'), 'entity_type_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_CASCADE)
    ->addForeignKey($installer->getFkName('faonni_shape/template_entity_varchar', 'attribute_id', 'eav/attribute', 'attribute_id'),
        'attribute_id', $installer->getTable('eav/attribute'), 'attribute_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_CASCADE);

$installer->getConnection()->createTable($table);		
		
/**
 * Create table 'faonni_shape/template_entity_text'
*/
$table = $installer->getConnection()
    ->newTable($installer->getTable('faonni_shape/template_entity_text'))
	->addColumn('value_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
		'identity'  => true,
		'unsigned'  => true,
		'nullable'  => false,
		'primary'   => true,
		), 'Value Id')
	->addColumn('entity_type_id', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
        'nullable'  => false,
		'unsigned'  => true,
        ), 'Entity Type Id')		
	->addColumn('attribute_id', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
        'nullable'  => false,
		'unsigned'  => true,
        ), 'Attribute Id')		
    ->addColumn('store_id', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
        'nullable'  => false,
		'unsigned'  => true,
        ), 'Store Id')		
	->addColumn('entity_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
		'unsigned'  => true,
		'nullable'  => false,
		), 'Entity Id')
    ->addColumn('value', Varien_Db_Ddl_Table::TYPE_TEXT, '64K', array(
        'nullable'  => false,
        ), 'Value')

	->addIndex($installer->getIdxName('faonni_shape/template_entity_text', array('entity_id')),
        array('entity_id'))	
	->addIndex($installer->getIdxName('faonni_shape/template_entity_text', array('store_id')),
        array('store_id'))	
	->addIndex($installer->getIdxName('faonni_shape/template_entity_text', array('entity_type_id')),
        array('entity_type_id'))
	->addIndex($installer->getIdxName('faonni_shape/template_entity_text', array('attribute_id')),
        array('attribute_id'))		
	->addIndex($installer->getIdxName('faonni_shape/template_entity_text', array('entity_id', 'store_id', 'attribute_id'), Varien_Db_Adapter_Interface::INDEX_TYPE_UNIQUE),
        array('entity_id', 'store_id', 'attribute_id'))	

    ->addForeignKey($installer->getFkName('faonni_shape/template_entity_text', 'entity_id', 'faonni_shape/template', 'entity_id'),
        'entity_id', $installer->getTable('faonni_shape/template'), 'entity_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_CASCADE)
    ->addForeignKey($installer->getFkName('faonni_shape/template_entity_text', 'store_id', 'core/store', 'store_id'),
        'store_id', $installer->getTable('core/store'), 'store_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_CASCADE)
    ->addForeignKey($installer->getFkName('faonni_shape/template_entity_text', 'entity_type_id', 'eav/entity_type', 'entity_type_id'),
        'entity_type_id', $installer->getTable('eav/entity_type'), 'entity_type_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_CASCADE)
    ->addForeignKey($installer->getFkName('faonni_shape/template_entity_text', 'attribute_id', 'eav/attribute', 'attribute_id'),
        'attribute_id', $installer->getTable('eav/attribute'), 'attribute_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_CASCADE);

$installer->getConnection()->createTable($table);
		
/**
 * Create table 'faonni_shape/template_eav_attribute'
*/
$table = $installer->getConnection()
    ->newTable($installer->getTable('faonni_shape/template_eav_attribute'))
	->addColumn('attribute_id', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
		'unsigned'  => true,
		'nullable'  => false,
		'primary'   => true,
		), 'Attribute ID')
	->addColumn('is_global', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
        'nullable'  => false,
		'unsigned'  => true,
		'default'   => '1',
        ), 'Is Global')		
	->addColumn('position', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'nullable'  => false,
		'unsigned'  => true,
        ), 'Position')		

    ->addForeignKey($installer->getFkName('faonni_shape/template_eav_attribute', 'attribute_id', 'eav/attribute', 'attribute_id'),
        'attribute_id', $installer->getTable('eav/attribute'), 'attribute_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_CASCADE);

$installer->getConnection()->createTable($table);

/**
 * install templates Entities
*/
$installer->installEntities();

/**
 * Install product attribute h1
*/
$attributeId = $setup->getAttributeId(Mage_Catalog_Model_Product::ENTITY, 'h1');
if (false === $attributeId) {
    $setup->addAttribute(
		Mage_Catalog_Model_Product::ENTITY, 
		'h1', 
		array(
			'group'                => 'Meta Information',
			'sort_order'           => 10,
			'type'                 => 'varchar',
			'label'                => 'Tag H1',
			'input'                => 'text',
			'global'               => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE,
			'visible'              => true,
			'required'             => false,
			'user_defined'         => true,
			'visible_on_front'     => false,
			'unique'               => false,
			'is_configurable'      => false,
			'used_for_promo_rules' => false
		)
	);
}

$installer->endSetup();