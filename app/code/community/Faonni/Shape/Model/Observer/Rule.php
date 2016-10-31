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
class Faonni_Shape_Model_Observer_Rule
	extends Varien_Event_Observer
{
    /**
     * save template
	 *
     * @param Varien_Event_Observer $observer
     * @return Faonni_Shape_Model_Observer_Rule
     */
    public function saveTemplate($observer)
    {
		$rule = $observer->getEvent()->getRule();
		/*Faonni_Shape_Model_Rule*/
		if($rule->getId()){
			$template = $rule->getTemplateInstance()->load($rule->getId());
			$template
					->setId($rule->getId())
					->setStoreId(Mage::app()->getRequest()->getParam('store', null))
					->addData($rule->getTemplate());
					
			if ($useDefaults = Mage::app()->getRequest()->getPost('use_default')){
				foreach ($useDefaults as $attributeCode) {
					$template->setData($attributeCode, false);
				}
			}
			$template->save();		
		}
        return $this;
    }
	
    /**
     * delete template
	 *
     * @param Varien_Event_Observer $observer
     * @return Faonni_Shape_Model_Observer_Rule
     */
    public function deleteTemplate($observer)
    {
		$rule = $observer->getEvent()->getRule();
		/*Faonni_Shape_Model_Rule*/
		if($rule->getId()){
			$template = $rule->getTemplateInstance()->load($rule->getId());
			$template->delete();					
		}
        return $this;
    }	
}