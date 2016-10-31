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
class Faonni_Shape_Model_Resource_Rule 
	extends Mage_Rule_Model_Resource_Abstract
{
    /**
     * Initialize main table and table id field
	 *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('faonni_shape/rule', 'rule_id');
    }
	
    /**
     * Update temp table
	 *
     * @param Faonni_Shape_Model_Rule
     * @return Faonni_Shape_Model_Resource_Rule 
     */
    public function updateRuleProductData(Faonni_Shape_Model_Rule $rule)
    {
        $ruleId = $rule->getId();
        $write = $this->_getWriteAdapter();
        $write->beginTransaction();
        $write->delete($this->getTable('faonni_shape/rule_product'), $write->quoteInto('rule_id=?', $ruleId));

        if (!$rule->getIsActive()) {
            $write->commit();
            return $this;
        }
        $rows = array();
        try {
            foreach ($rule->getMatchingProductIds() as $productId => $productStores) {
                foreach ($productStores as $storeId => $match) {
					if($match){
                        $rows[] = array(
                            'rule_id' => $ruleId,
                            'store_id' => $storeId,
                            'product_id' => $productId,
                        );
                        if (count($rows) == 1000) {
                            $write->insertMultiple($this->getTable('faonni_shape/rule_product'), $rows);
                            $rows = array();
                        }
					}
                }
            }
            if (!empty($rows)) {
               $write->insertMultiple($this->getTable('faonni_shape/rule_product'), $rows);
            }
            $write->commit();
        } catch (Exception $e) {
            $write->rollback();
            throw $e;
        }
        return $this;
    }
	
    /**
     * Update products which are matched for rule
	 *
     * @param Faonni_Shape_Model_Rule
     * @return Faonni_Shape_Model_Resource_Rule 
     */	
    public function updateProductData(Faonni_Shape_Model_Rule $rule)
    {
        $ruleId = $rule->getId();
        $write = $this->_getWriteAdapter();
        $write->beginTransaction();
		try {
			foreach($rule->getTemplateAttributes() as $storeId => $attributes){
				$collection = $rule->getProductCollection($storeId);
				foreach($attributes as $code => $value){	
					$attribute = Mage::getSingleton('eav/config')
								->getAttribute(Mage_Catalog_Model_Product::ENTITY, $code);
					if (!$attribute || !$attribute->getAttributeId() || empty($value)) {
						continue;
					}
					foreach($collection as $product){
						$new = $value;
						$productAttributes = array_keys($product->getData());
						foreach($productAttributes as $productAttribute)
						{
							if(!is_string($productAttribute)) continue;
							$attributeValue = $product->getData($productAttribute);
							if(!is_string($attributeValue)) continue;
							
							$new = str_replace("{{$productAttribute}}", $attributeValue, $new);
						}
						$rule->getTemplateInstance()->getResource()->updateAttributeForStore($product, $attribute, $new, $storeId);
					}
				}
			}
			$write->commit();
        } catch (Exception $e) {
            $write->rollback();
            throw $e;
        }		
        return $this;
    }
}