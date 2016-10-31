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
class Faonni_Shape_Model_Rule_Condition_Combine
    extends Mage_Rule_Model_Condition_Combine
{
    /**
     * Intialize model
	 *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->setType('faonni_shape/rule_condition_combine');
    }

    /**
     * Get inherited conditions selectors
	 *
     * @return array
     */
    public function getNewChildSelectOptions()
    {
        $productCondition = Mage::getModel('faonni_shape/rule_condition_product');
        $productAttributes = $productCondition->loadAttributeOptions()->getAttributeOption();
        $attributes = array();
        foreach ($productAttributes as $code=>$label) {
            $attributes[] = array(
				'label' => $label,
				'value' => 'faonni_shape/rule_condition_product|' . $code
			);
        }
        $conditions = array(
            array(
				'label' => Mage::helper('catalogrule')->__('Product Attribute'), 
				'value' => $attributes
			),		
        );
        $conditions = array_merge_recursive(parent::getNewChildSelectOptions(), $conditions);
        return $conditions;
    }
	
    /**
     * Validate attributes of product collection
	 *
	 * @param $productCollection Mage_Catalog_Model_Resource_Eav_Mysql4_Product_Collection
     * @return Faonni_Shape_Model_Rule_Condition_Combine
     */	
    public function collectValidatedAttributes($productCollection)
    {
        foreach ($this->getConditions() as $condition) {
            $condition->collectValidatedAttributes($productCollection);
        }
        return $this;
    }	
}