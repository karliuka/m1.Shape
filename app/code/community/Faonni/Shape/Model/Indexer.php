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
class Faonni_Shape_Model_Indexer 
	extends Mage_Index_Model_Indexer_Abstract
{
    /**
     * Data key for matching result to be saved in
	 *
     * @var string	 
     */
    const EVENT_MATCH_RESULT_KEY = 'faonni_shape_match_result';
 
    /**
     * Matched Entities instruction array
	 *
     * @var array
     */
    protected $_matchedEntities = array(
        Mage_Catalog_Model_Category::ENTITY => array(
            Mage_Index_Model_Event::TYPE_SAVE,
			Mage_Index_Model_Event::TYPE_DELETE
        )		
    );
	
    /**
     * Retrieve Category attribute list has an effect on product
	 *
     * @return array
     */
    protected function _getDependentCategoryAttributes()
    {
        return array(
            'use_parent_category_templates',
            'meta_title_template',
            'meta_keywords_template',
            'meta_description_template'
        );
    }	
 
    /**
     * Retrieve Indexer name
	 *
     * @return string
     */
    public function getName()
    {
        return 'Shape tags products';
    }
 
    /**
     * Retrieve Indexer description
	 *
     * @return string
     */
    public function getDescription()
    {
        return 'Rebuild the shape tags of products';
    }
  
    /**
     * match whether the reindexing should be fired
	 *
	 * @param object $event Mage_Index_Model_Event
     * @return bool
     */
    public function matchEvent(Mage_Index_Model_Event $event)
    {
        return Mage::getModel('catalog/category_indexer_product')
					->matchEvent($event);
    }
	
    /**
     * Register data required by process in event object
	 *
	 * @param object $event Mage_Index_Model_Event
	 * @return void	 
     */
    protected function _registerEvent(Mage_Index_Model_Event $event)
    {
		switch($event->getEntity()) {
			case Mage_Catalog_Model_Category::ENTITY:
				$this->_registerCategoryEvent($event);
				break;				
		}
    }

	/**
	 * Register event data during category save process
	 *
	 * @param object $event Mage_Index_Model_Event 
	 * @return void 
	 */
	protected function _registerCategoryEvent(Mage_Index_Model_Event $event)
	{
		if ($this->_hasCategoryDataChanges($event->getDataObject())) {
			$process = $event->getProcess();
			$event->addNewData('category_id', $event->getDataObject()->getData('entity_id'));
			$process->changeStatus(Mage_Index_Model_Process::STATUS_REQUIRE_REINDEX);
		}
	}
	
	/**
	 * Register event data during category save process
	 *
	 * @param object $category Mage_Index_Model_Event
	 * @return bool
	 */
	protected function _hasCategoryDataChanges(Mage_Catalog_Model_Category $category)
	{
		if($category->hasDataChanges()){
			foreach($this->_getDependentCategoryAttributes() as $attribute){
				if($category->dataHasChangedFor($attribute)) return true;
			}
		}
		return false;
	}
	
    /**
     * Process event
	 *
	 * @param object $event Mage_Index_Model_Event 
	 * @return void
     */
    protected function _processEvent(Mage_Index_Model_Event $event)
    {
		$data = $event->getNewData();
		if (!empty($data['faonni_shape_reindex_all'])) {
			$this->reindexAll();
		}

		if (!empty($data['category_id'])) {
            //Mage::log($data['category_id'], null, 'reindex.log');
        }		
		$object = $event->getDataObject();
    }
 
    /**
     * Rebuild all index data 
	 *
	 * @return void	 
     */
    public function reindexAll()
    {
		$collection = Mage::getModel('faonni_shape/rule')->getCollection();
		foreach($collection as $rule){
			$rule->getResource()->updateRuleProductData($rule);
			$rule->getResource()->updateProductData($rule);
		}
    }
}