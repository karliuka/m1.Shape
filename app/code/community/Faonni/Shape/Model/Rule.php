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
class Faonni_Shape_Model_Rule 
	extends Mage_Rule_Model_Abstract
{
    /**
     * Prefix of model events names
	 *
     * @var string
     */
    protected $_eventPrefix = 'shape_rule';
	
    /**
     * Parameter name in event
     * In observe method you can use $observer->getEvent()->getLink() in this case
	 *
     * @var string
     */
    protected $_eventObject = 'rule';
	
    /**
     * Store matched product Ids
	 *
     * @var array
     */
    protected $_productIds;
	
    /**
     * Stores Ids cache
	 *
     * @var array
     */
    protected $_stores;
	
    /**
     * Rule Template
	 *
     * @var Faonni_Shape_Model_Template
     */
    protected $_template;
	
    /**
     * Init resource model
	 *
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->_init('faonni_shape/rule');
    }
	
    /**
     * Getter for rule combine conditions instance
	 *
     * @return Faonni_Shape_Model_Rule_Condition_Combine
     */
    public function getConditionsInstance()
    {
        return Mage::getModel('faonni_shape/rule_condition_combine');
    }

    /**
     * Getter for rule actions collection instance
	 *
     * @return Mage_Rule_Model_Action_Collection
     */
    public function getActionsInstance()
    {
        return Mage::getModel('rule/action_collection');
    }
	
    /**
     * Retrieve rule products collection
	 *
     * @param int $storeId	 
     * @return Faonni_Shape_Model_Resource_Rule_Product_Collection
     */
    public function getProductCollection($storeId=null)
    {
        $collection = Mage::getResourceModel('faonni_shape/rule_product_collection')
					->addRuleIdFilter($this->getId(), $storeId)
					->addAttributeToSelect('*');
		return $collection;
    }
	
    /**
     * Getter for rule template instance
	 *
     * @return Faonni_Shape_Model_Template
     */
    public function getTemplateInstance()
    {
        return Mage::getModel('faonni_shape/template');
    }

    /**
     * Retrieve template attributes collection
	 *
     * @param int $storeId
     * @return array
     */
    public function getTemplateAttributes()
    {
		$result = array();
		$default = array();
		$template = $this->getTemplateInstance();
		foreach ($this->getStoreIds() as $storeId)
		{
			$template->setStoreId($storeId)->load($this->getId());
			$attributes = $template->getAttributes();

			foreach ($attributes as $attribute) {
				$code = $attribute->getAttributeCode();
				if(0 == $storeId){
					$default[$code] = $template->getData($code);
					$result[$storeId][$code] = $default[$code];
				} elseif($default[$code] != $template->getData($code)){
					$result[$storeId][$code] = $template->getData($code);
				}
			}			
		}
		return $result;
    }

    /**
     * Get array of product ids which are matched by rule
	 *
     * @return array
     */
    public function getMatchingProductIds()
    {
        if (null === $this->_productIds) {
            $this->_productIds = array();
            $this->setCollectedAttributes(array());
			$collection = Mage::getResourceModel('catalog/product_collection');
			$this->getConditions()->collectValidatedAttributes($collection);

			Mage::getSingleton('core/resource_iterator')->walk(
				$collection->getSelect(),
				array(array($this, 'callbackValidateProduct')),
				array(
					'attributes' => $this->getCollectedAttributes(),
					'product' => Mage::getModel('catalog/product'),
				)
			);
        }
        return $this->_productIds;
    }

    /**
     * Callback function for product matching
	 *
     * @param $args
     * @return void
     */
    public function callbackValidateProduct($args)
    {
        $product = clone $args['product'];
        $product->setData($args['row']);

        $results = array();
        foreach ($this->getStoreIds() as $storeId) {
            $product->setStoreId($storeId);
            $results[$storeId] = (int)$this->getConditions()->validate($product);
        }
        $this->_productIds[$product->getId()] = $results;
    }

    /**
     * Retrieve stores Ids array
	 *
     * @return array
     */
    public function getStoreIds()
    {
        if(null === $this->_stores){
			$this->_stores = array(0);
			foreach (Mage::app()->getStores() as $storeId => $store) {
                $this->_stores[$storeId] = $storeId;
            }
        }		
        return $this->_stores;
    }
}