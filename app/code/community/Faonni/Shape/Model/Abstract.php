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
class Faonni_Shape_Model_Abstract
	extends Mage_Core_Model_Abstract
{
    /**
     * Identifuer of default store
	 *
     * used for loading default data for entity
     */
    const DEFAULT_STORE_ID = 0;
	
    /**
     * The cache tag name.
     */		
	const CACHE_TAG = 'shape_abstract';
	
    /**
     * Attribute default values
     * This array contain default values for attributes which was redefine value for store
	 *
     * @var array
     */
    protected $_defaultValues = array();

    /**
     * This array contains codes of attributes which have value in current store
	 *
     * @var array
     */
    protected $_storeValuesFlags = array();

    /**
     * Locked attributes
	 *
     * @var array
     */
    protected $_lockedAttributes = array();

    /**
     * Is model deleteable
	 *
     * @var boolean
     */
    protected $_isDeleteable = true;

    /**
     * Is model readonly
	 *
     * @var boolean
     */
    protected $_isReadonly = false;


    /**
     * Lock attribute
	 *
     * @param string $attributeCode
     * @return Faonni_Content_Model_Abstract
     */
    public function lockAttribute($attributeCode)
    {
        $this->_lockedAttributes[$attributeCode] = true;
        return $this;
    }

    /**
     * Unlock attribute
	 *
     * @param string $attributeCode
     * @return Faonni_Content_Model_Abstract
     */
    public function unlockAttribute($attributeCode)
    {
        if ($this->isLockedAttribute($attributeCode)) {
            unset($this->_lockedAttributes[$attributeCode]);
        }

        return $this;
    }

    /**
     * Unlock all attributes
	 *
     * @return Faonni_Content_Model_Abstract
     */
    public function unlockAttributes()
    {
        $this->_lockedAttributes = array();
        return $this;
    }

    /**
     * Retrieve locked attributes
	 *
     * @return array
     */
    public function getLockedAttributes()
    {
        return array_keys($this->_lockedAttributes);
    }

    /**
     * Checks that model have locked attributes
	 *
     * @return boolean
     */
    public function hasLockedAttributes()
    {
        return !empty($this->_lockedAttributes);
    }

    /**
     * Retrieve locked attributes
	 *
     * @return boolean
     */
    public function isLockedAttribute($attributeCode)
    {
        return isset($this->_lockedAttributes[$attributeCode]);
    }

    /**
     * Overwrite data in the object.
	 *
     * $key can be string or array.
     * If $key is string, the attribute value will be overwritten by $value
     * If $key is an array, it will overwrite all the data in the object.
     * $isChanged will specify if the object needs to be saved after an update.
     * @param string|array $key
     * @param mixed $value
     * @param boolean $isChanged
     * @return Varien_Object
     */
    public function setData($key, $value = null)
    {
        if ($this->hasLockedAttributes()) {
            if (is_array($key)) {
                 foreach ($this->getLockedAttributes() as $attribute) {
                     if (isset($key[$attribute])) {
                         unset($key[$attribute]);
                     }
                 }
            } elseif ($this->isLockedAttribute($key)) {
                return $this;
            }
        } elseif ($this->isReadonly()) {
            return $this;
        }

        return parent::setData($key, $value);
    }

    /**
     * Unset data from the object.
	 *
     * $key can be a string only. Array will be ignored.
     * $isChanged will specify if the object needs to be saved after an update.
     * @param string $key
     * @param boolean $isChanged
     * @return Faonni_Content_Model_Abstract
     */
    public function unsetData($key = null)
    {
        if ((!is_null($key) && $this->isLockedAttribute($key)) ||
            $this->isReadonly()) {
            return $this;
        }

        return parent::unsetData($key);
    }

    /**
     * Load entity by attribute
	 *
     * @param Mage_Eav_Model_Entity_Attribute_Interface|integer|string|array $attribute
     * @param null|string|array $value
     * @param string $additionalAttributes
     * @return bool|Faonni_Content_Model_Abstract
     */
    public function loadByAttribute($attribute, $value, $additionalAttributes = '*')
    {
        $collection = $this->getResourceCollection()
            ->addAttributeToSelect($additionalAttributes)
            ->addAttributeToFilter($attribute, $value)
            ->setPage(1,1);

        foreach ($collection as $object) {
            return $object;
        }
        return false;
    }

    /**
     * Retrieve sore object
	 *
     * @return Mage_Core_Model_Store
     */
    public function getStore()
    {
        return Mage::app()->getStore($this->getStoreId());
    }

    /**
     * Retrieve all store ids of object current website
	 *
     * @return array
     */
    public function getWebsiteStoreIds()
    {
        return $this->getStore()->getWebsite()->getStoreIds(true);
    }

    /**
     * Adding attribute code and value to default value registry
     * Default value existing is flag for using store value in data
	 *
     * @param   string $attributeCode
     * @value   mixed  $value
     * @return  Faonni_Content_Model_Abstract
     */
    public function setAttributeDefaultValue($attributeCode, $value)
    {
        $this->_defaultValues[$attributeCode] = $value;
        return $this;
    }

    /**
     * Retrieve default value for attribute code
	 *
     * @param   string $attributeCode
     * @return  array|boolean
     */
    public function getAttributeDefaultValue($attributeCode)
    {
        return array_key_exists($attributeCode, $this->_defaultValues) ? $this->_defaultValues[$attributeCode] : false;
    }

    /**
     * Set attribute code flag if attribute has value in current store and does not use
     * value of default store as value
	 *
     * @param   string $attributeCode
     * @return  Faonni_Content_Model_Abstract
     */
    public function setExistsStoreValueFlag($attributeCode)
    {
        $this->_storeValuesFlags[$attributeCode] = true;
        return $this;
    }

    /**
     * Check if object attribute has value in current store
	 *
     * @param   string $attributeCode
     * @return  bool
     */
    public function getExistsStoreValueFlag($attributeCode)
    {
        return array_key_exists($attributeCode, $this->_storeValuesFlags);
    }

    /**
     * Before save unlock attributes
	 *
     * @return Faonni_Content_Model_Abstract
     */
    protected function _beforeSave()
    {
        $this->unlockAttributes();
        return parent::_beforeSave();
    }

    /**
     * Checks model is deletable
	 *
     * @return boolean
     */
    public function isDeleteable()
    {
        return $this->_isDeleteable;
    }

    /**
     * Set is deletable flag
	 *
     * @param boolean $value
     * @return Faonni_Content_Model_Abstract
     */
    public function setIsDeleteable($value)
    {
        $this->_isDeleteable = (bool) $value;
        return $this;
    }

    /**
     * Checks model is deletable
	 *
     * @return boolean
     */
    public function isReadonly()
    {
        return $this->_isReadonly;
    }

    /**
     * Set is deletable flag
	 *
     * @param boolean $value
     * @return Faonni_Content_Model_Abstract
     */
    public function setIsReadonly($value)
    {
        $this->_isReadonly = (bool)$value;
        return $this;
    }
	
	/**
     * Retrieve product attributes
	 *
     * if $groupId is null - retrieve all product attributes
     * @param int  $groupId   Retrieve attributes of the specified group
     * @param bool $skipSuper Not used
     * @return array
     */
    public function getAttributes($groupId = null, $skipSuper = false)
    {
		$sectionAttributes = $this->getResource()->loadAllAttributes($this)->getAttributesByCode();
        if ($groupId){
			$attributes = array();
            foreach ($sectionAttributes as $attribute){
				if (in_array($attribute->getAttributeId(), $this->getResource()->getAttributesIdsByGroup($groupId))){
                    $attributes[] = $attribute;
                }
            }
			return $attributes;
        }
		return $sectionAttributes;
    }
	
    /**
     * Retrieve Store Id
	 *
     * @return int
     */
    public function getStoreId()
    {
        if ($this->hasData('store_id')) {
            return $this->getData('store_id');
        }
        return Mage::app()->getStore()->getId();
    }
	
    /**
     * Get collection instance
	 *
     * @return object
     */
    public function getResourceCollection()
    {
        if (empty($this->_resourceCollectionName)) {
            Mage::throwException(Mage::helper('catalog')->__('The model collection resource name is not defined.'));
        }
        $collection = Mage::getResourceModel($this->_resourceCollectionName);
        $collection->setStoreId($this->getStoreId());
        return $collection;
    }
}