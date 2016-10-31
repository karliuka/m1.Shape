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
class Faonni_Shape_Model_Resource_Abstract 
	extends Mage_Catalog_Model_Resource_Abstract
{
    /**
     * Retrieve configuration for all attributes
	 *
     * @return Mage_Eav_Model_Entity_Attribute_Abstract
     */
    public function loadAllAttributes($object=null)
    {
        $attributeCodes = Mage::getSingleton('eav/config')
            ->getEntityAttributeCodes($this->getEntityType(), $object);

        foreach ($attributeCodes as $attributeCode) {
            $attributeIndex = array_search($attributeCode, $attributeCodes);
            if ($attributeIndex !== false) {
                $this->getAttribute($attributeCodes[$attributeIndex]);
                unset($attributeCodes[$attributeIndex]);
            } else {
                $this->addAttribute($this->_getDefaultAttribute($attributeCode));
            }
        }
        foreach ($attributeCodes as $code) {
            $this->getAttribute($code);
        }
        return $this;
    }
	
    /**
     * Retrieve attributes ids by group id
	 *
     * @return array
     */	
	public function getAttributesIdsByGroup($groupId)
	{
		$bind = array(':attribute_group_id' => $groupId);
		$select = $this->_getReadAdapter()->select()
			->from($this->getTable('eav_entity_attribute'), array('attribute_id'))
			->where('attribute_group_id = :attribute_group_id');
		return $this->_getReadAdapter()->fetchCol($select, $bind);
	}
	
    /**
     * Redeclare attribute model
	 *
     * @return string
     */
    protected function _getDefaultAttributeModel()
    {
		return 'faonni_shape/resource_eav_attribute';
    }
}