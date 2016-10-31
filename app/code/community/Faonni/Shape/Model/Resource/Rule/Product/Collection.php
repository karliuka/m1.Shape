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
class Faonni_Shape_Model_Resource_Rule_Product_Collection 
	extends Mage_Catalog_Model_Resource_Product_Collection
{
    /**
     * Joins shape rules to collection
	 *
     * @param int $ruleId
     * @param int $storeId
     * @return Faonni_Shape_Model_Resource_Rule_Product_Collection
     */
    public function addRuleIdFilter($ruleId, $storeId)
    {
        $this->setStoreId($storeId);
		$this->getSelect()
			->joinInner(
				array('rule' => $this->getResource()->getTable('faonni_shape/rule_product')),
				'rule.product_id = e.entity_id',
				array())
			->where('rule.rule_id = ?', $ruleId)->where('rule.store_id = ?', $storeId);
			
        return $this;
    }	
}