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
class Faonni_Shape_Block_Adminhtml_Shape_Edit_Tabs
    extends Mage_Adminhtml_Block_Widget_Tabs
{
    /**
     * attribute Tab Block
	 *
     * @var string
     */
	protected $_attributeTabBlock = 'faonni_shape/adminhtml_shape_edit_tab_template';
	
    /**
     * Intialize form
	 *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->setId('faonni_shape_rule_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('faonni_shape')->__('Shape Rule'));
    }
	
    /**
     * Add tab sections
	 *
     * @return Faonni_Shape_Block_Adminhtml_Shape_Edit_Tabs
     */
    protected function _beforeToHtml()
    {
        $this->addTab('general_section', array(
            'label'   => Mage::helper('faonni_shape')->__('General'),
            'content' => $this->getLayout()->createBlock('faonni_shape/adminhtml_shape_edit_tab_main',
                'adminhtml_shape_edit_tab_general')->toHtml(),
        ));

        $this->addTab('conditions_section', array(
            'label'   => Mage::helper('faonni_shape')->__('Conditions'),
            'content' => $this->getLayout()->createBlock('faonni_shape/adminhtml_shape_edit_tab_conditions',
                'adminhtml_shape_edit_tab_conditions')->toHtml()
        ));
		
	   $model = Mage::registry("current_shape_rule_template");

		$entityTypeId = Mage::getModel('eav/entity')->setType(Faonni_Shape_Model_Template::ENTITY)->getTypeId(); 
		$collection = Mage::getModel("eav/entity_attribute_set")->getCollection()->addFieldToFilter("entity_type_id", $entityTypeId);

        $groupCollection = Mage::getResourceModel('eav/entity_attribute_group_collection')
			->setAttributeSetFilter($collection->getFirstItem()->getId())
			->setSortOrder()
			->load();
		
        foreach ($groupCollection as $group){	
			$attributes = $model->getAttributes($group->getId());
			if (0 == count($attributes)) continue;
			
			$this->addTab('group_'.$group->getId(), array(
				'label'   => Mage::helper('catalog')->__('Templates'),
				'content' => $this->_translateHtml($this->getLayout()->createBlock($this->getAttributeTabBlock(),
					'faonni_shape.adminhtml.shape.edit.tab.template')->setGroup($group)
						->setGroupAttributes($attributes)
						->toHtml()),
			));
        }
		
        $rule = Mage::registry('current_shape_rule');
        if ($rule && $rule->getId()) {
            $this->addTab('matched_products_section', array(
                'label' => Mage::helper('faonni_shape')->__('Matched Products'),
                'url'   => $this->getUrl('*/*/productGrid', array('rule_id' => $rule->getId())),
                'class' => 'ajax'
            ));
        }		
		
        return parent::_beforeToHtml();
    }	
	
    /**
     * Getting attribute block name for tabs
	 *
     * @return string
     */
    public function getAttributeTabBlock()
    {
        return $this->_attributeTabBlock;
    }
	
    /**
     * Set attribute block name for tabs
	 *
     * @return Faonni_Shape_Block_Adminhtml_Shape_Edit_Tabs
     */
    public function setAttributeTabBlock($attributeTabBlock)
    {
        $this->_attributeTabBlock = $attributeTabBlock;
        return $this;
    }
	
    /**
     * Translate html content
	 *
     * @param string $html
     * @return string
     */
    protected function _translateHtml($html)
    {
        Mage::getSingleton('core/translate_inline')->processResponseBody($html);
        return $html;
    }
}