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
class Faonni_Shape_Block_Adminhtml_Shape_Edit_Tab_Main
    extends Mage_Adminhtml_Block_Widget_Form
{
    /**
     * Prepare general properties form
	 *
     * @return Faonni_Shape_Block_Adminhtml_Shape_Edit_Tab_General
     */
    protected function _prepareForm()
    {
        $isEditable = ($this->getCanEditShapeRule() !== false) ? true : false;
        $form = new Varien_Data_Form();
        $model = Mage::registry('current_shape_rule');

        $fieldset = $form->addFieldset('base_fieldset', array(
            'legend'  => Mage::helper('faonni_shape')->__('General Information'),
        ));

        if ($model->getId()) {
            $fieldset->addField('rule_id', 'hidden', array(
                'name' => 'rule_id',
            ));
        }

        $fieldset->addField('rule_name', 'text', array(
            'name'     => 'rule_name',
            'label'    => Mage::helper('faonni_shape')->__('Rule Name'),
            'required' => true,
        ));

        $fieldset->addField('rule_description', 'textarea', array(
            'name'  => 'rule_description',
            'label' => Mage::helper('faonni_shape')->__('Description'),
            'style' => 'height: 100px;',
        ));

        $fieldset->addField('is_active', 'select', array(
            'label'    => Mage::helper('faonni_shape')->__('Status'),
            'name'     => 'is_active',
            'required' => true,
            'options'  => array(
                '1' => Mage::helper('faonni_shape')->__('Active'),
                '0' => Mage::helper('faonni_shape')->__('Inactive'),
            ),
        ));

        if (!$model->getId()) {
            $model->setData('is_active', '1');
        }

        $form->setValues($model->getData());
        $this->setForm($form);

        if (!$isEditable) {
            $this->getForm()->setReadonly(true, true);
        }
        return parent::_prepareForm();
    }
}