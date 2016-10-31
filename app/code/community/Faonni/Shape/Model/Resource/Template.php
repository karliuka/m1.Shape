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
class Faonni_Shape_Model_Resource_Template
	extends Faonni_Shape_Model_Resource_Abstract
{
	/**
     * The construct. Init Resource
	 *
     * @return void	 
     */
    public function __construct()
    {
        $this->_isPkAutoIncrement = false;
		$resource = Mage::getSingleton('core/resource');
		
        $this->setType(Faonni_Shape_Model_Template::ENTITY); 
        $this->setConnection(
            $resource->getConnection('content_read'),
            $resource->getConnection('content_write')
        );
    }
	
    /**
     * Update attribute value for specific store
	 *
     * @param Mage_Catalog_Model_Abstract $object
     * @param object $attribute
     * @param mixed $value
     * @param int $storeId
     * @return Faonni_Shape_Model_Resource_Template
     */	
	public function updateAttributeForStore($object, $attribute, $value, $storeId)
    {
		return $this->_updateAttributeForStore($object, $attribute, $value, $storeId);
	}
	
    /**
     * Update attribute value for specific store
	 *
     * @param Mage_Catalog_Model_Abstract $object
     * @param object $attribute
     * @param mixed $value
     * @param int $storeId
     * @return Faonni_Shape_Model_Resource_Template
     */
    protected function _updateAttributeForStore($object, $attribute, $value, $storeId)
    {
        $adapter = $this->_getWriteAdapter();
        $table   = $attribute->getBackend()->getTable();
        $entityIdField = $attribute->getBackend()->getEntityIdField();
        $select  = $adapter->select()
            ->from($table, 'value_id')
            ->where('entity_type_id = :entity_type_id')
            ->where("$entityIdField = :entity_field_id")
            ->where('store_id = :store_id')
            ->where('attribute_id = :attribute_id');
        $bind = array(
            'entity_type_id'  => $object->getEntityTypeId(),
            'entity_field_id' => $object->getId(),
            'store_id'        => $storeId,
            'attribute_id'    => $attribute->getId()
        );
        $valueId = $adapter->fetchOne($select, $bind);
        /**
         * When value for store exist
        */
        if ($valueId) {
            $bind  = array('value' => $this->_prepareValueForSave($value, $attribute));
            $where = array('value_id = ?' => (int)$valueId);

            $adapter->update($table, $bind, $where);
        } else {
            $bind  = array(
                $entityIdField      => (int)$object->getId(),
                'entity_type_id'    => (int)$object->getEntityTypeId(),
                'attribute_id'      => (int)$attribute->getId(),
                'value'             => $this->_prepareValueForSave($value, $attribute),
                'store_id'          => (int)$storeId
            );

            $adapter->insert($table, $bind);
        }
        return $this;
    }
	
    /**
     * Save object collected data
	 *
     * @param   array $saveData array('newObject', 'entityRow', 'insert', 'update', 'delete')
     * @return  Mage_Eav_Model_Entity_Abstract
     */
    protected function _processSaveData($saveData)
    {
        extract($saveData);
        /**
         * Import variables into the current symbol table from save data array
         *
         * @see Mage_Eav_Model_Entity_Attribute_Abstract::_collectSaveData()
         *
         * @var array $entityRow
         * @var Mage_Core_Model_Abstract $newObject
         * @var array $insert
         * @var array $update
         * @var array $delete
         */
        $adapter        = $this->_getWriteAdapter();
        $insertEntity   = true;
        $entityTable    = $this->getEntityTable();
        $entityIdField  = $this->getEntityIdField();
        $entityId       = $newObject->getId();

        unset($entityRow[$entityIdField]);
        if (!empty($entityId) && is_numeric($entityId)) {
            $bind   = array('entity_id' => $entityId);
            $select = $adapter->select()
                ->from($entityTable, $entityIdField)
                ->where("{$entityIdField} = :entity_id");
            $result = $adapter->fetchOne($select, $bind);
            if ($result) {
                $insertEntity = false;
            }
        } else {
            $entityId = null;
        }
        /**
         * Process base row
         */
        $entityObject = new Varien_Object($entityRow);
        $entityRow    = $this->_prepareDataForTable($entityObject, $entityTable);
        if ($insertEntity) {
            if (!empty($entityId)) {
                $entityRow[$entityIdField] = $entityId;
                $adapter->insertForce($entityTable, $entityRow);
            } else {
                $adapter->insert($entityTable, $entityRow);
                $entityId = $adapter->lastInsertId($entityTable);
            }
            $newObject->setId($entityId);
        } elseif(0 < count($entityRow)) {
            $where = sprintf('%s=%d', $adapter->quoteIdentifier($entityIdField), $entityId);
            $adapter->update($entityTable, $entityRow, $where);
        }
        /**
         * insert attribute values
         */
        if (!empty($insert)) {
            foreach ($insert as $attributeId => $value) {
                $attribute = $this->getAttribute($attributeId);
                $this->_insertAttribute($newObject, $attribute, $value);
            }
        }
        /**
         * update attribute values
         */
        if (!empty($update)) {
            foreach ($update as $attributeId => $v) {
                $attribute = $this->getAttribute($attributeId);
                $this->_updateAttribute($newObject, $attribute, $v['value_id'], $v['value']);
            }
        }
        /**
         * delete empty attribute values
         */
        if (!empty($delete)) {
            foreach ($delete as $table => $values) {
                $this->_deleteAttributes($newObject, $table, $values);
            }
        }

        $this->_processAttributeValues();

        $newObject->isObjectNew(false);

        return $this;
    }	
}