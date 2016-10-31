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
class Faonni_Shape_Block_Adminhtml_Shape_Edit 
	extends Mage_Adminhtml_Block_Widget_Form_Container
{
    /**
     * Initialize form
     * Add standard buttons
     * Add "Run Now" button
     * Add "Save and Continue" button
     */
    public function __construct()
    {
        $this->_objectId = 'id';
        $this->_blockGroup = 'faonni_shape';
        $this->_controller = 'adminhtml_shape';

        parent::__construct();

        /** @var $rule Faonni_Shape_Model_Rule */
        $rule = Mage::registry('current_shape_rule');
        if ($rule && $rule->getId()) {
            $confirm = Mage::helper('faonni_shape')->__('Are you sure you want to match this rule now?');

            $this->_addButton('apply', array(
                'label'   => Mage::helper('faonni_shape')->__('Apply'),
                'onclick' => "confirmSetLocation('{$confirm}', '{$this->getApplyUrl()}')"
            ), -1);
        }

        $this->_addButton('save_and_continue_edit', array(
            'class'   => 'save',
            'label'   => Mage::helper('faonni_shape')->__('Save and Continue Edit'),
            'onclick' => "saveAndContinueEdit('".$this->getSaveAndContinueUrl()."');",
        ), 3);
    }
	
    /**
     * Prepare layout
	 *
     * @return Mage_Core_Block_Abstract
     */
    protected function _prepareLayout()
    {

		$tabsBlockJsObject = 'faonni_shape_rule_tabsJsTabs';
		$tabsBlockPrefix   = 'faonni_shape_rule_tabs_';

        $this->_formScripts[] = "
            function saveAndContinueEdit(urlTemplate) {
                var tabsIdValue = " . $tabsBlockJsObject . ".activeTab.id;
                var tabsBlockPrefix = '" . $tabsBlockPrefix . "';
                if (tabsIdValue.startsWith(tabsBlockPrefix)) {
                    tabsIdValue = tabsIdValue.substr(tabsBlockPrefix.length)
                }
                var template = new Template(urlTemplate, /(^|.|\\r|\\n)({{(\w+)}})/);
                var url = template.evaluate({tab_id:tabsIdValue});
                editForm.submit(url);
            }
        ";
        return parent::_prepareLayout();
    }	
	
    /**
     * Getter for form header text
	 *
     * @return string
     */
    public function getHeaderText()
    {
        $rule = Mage::registry('current_shape_rule');
        if ($rule->getRuleId()) {
            return Mage::helper('faonni_shape')->__("Edit Rule '%s'", $this->escapeHtml($rule->getRuleName()));
        } else return Mage::helper('faonni_shape')->__('New Rule');
    }
	
    /**
     * Get url for save and continue process
	 *
     * @return string
     */	
	public function getSaveAndContinueUrl()
    {
        return $this->getUrl('*/*/save', array(
            '_current'   => true,
            'back'       => 'edit',
            'tab'        => '{{tab_id}}',
            'active_tab' => null
        ));
    }
	
    /**
     * Get url for immediate Apply sending process
	 *
     * @return string
     */
    public function getApplyUrl()
    {
        $rule = Mage::registry('current_shape_rule');
        return $this->getUrl('*/*/apply', array('id' => $rule->getRuleId()));
    }
}
