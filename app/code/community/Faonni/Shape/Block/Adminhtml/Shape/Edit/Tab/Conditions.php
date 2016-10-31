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
class Faonni_Shape_Block_Adminhtml_Shape_Edit_Tab_Conditions
    extends Mage_Adminhtml_Block_Widget_Form
{
    /**
     * Prepare conditions form
	 *
     * @return Faonni_Shape_Block_Adminhtml_Shape_Edit_Tab_Conditions
     */
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $model = Mage::registry('current_shape_rule');

        $renderer = Mage::getBlockSingleton('adminhtml/widget_form_renderer_fieldset')
            ->setTemplate('promo/fieldset.phtml')
            ->setNewChildUrl($this->getUrl('*/catalog_shape/newConditionHtml/form/rule_conditions_fieldset'));
        $fieldset = $form->addFieldset('rule_conditions_fieldset', array(
            'legend'  => Mage::helper('faonni_shape')->__('Conditions'),
            'comment' => Mage::helper('faonni_shape')->__('Rule will work only if at least one condition is specified.'),
        ))->setRenderer($renderer);

        $fieldset->addField('conditions', 'text', array(
            'name' => 'conditions',
            'required' => true,
        ))->setRule($model)->setRenderer(Mage::getBlockSingleton('rule/conditions'));

        $form->setValues($model->getData());
        $this->setForm($form);

        return parent::_prepareForm();
    }
}