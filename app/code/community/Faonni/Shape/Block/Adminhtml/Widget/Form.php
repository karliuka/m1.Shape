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
class Faonni_Shape_Block_Adminhtml_Widget_Form 
	extends Mage_Adminhtml_Block_Widget_Form
{
	/**
     * Field Name Suffix
	 *
     * @var string
     */
	protected $fieldNameSuffix;
	
	/**
     * Prepare layout
	 *
     * @return Faonni_Shape_Block_Adminhtml_Widget_Form 
     */	
	protected function _prepareLayout()
    {
        Varien_Data_Form::setElementRenderer(
            $this->getLayout()->createBlock('adminhtml/widget_form_renderer_element')
        );
        Varien_Data_Form::setFieldsetRenderer(
            $this->getLayout()->createBlock('adminhtml/widget_form_renderer_fieldset')
        );
        Varien_Data_Form::setFieldsetElementRenderer(
            $this->getLayout()->createBlock('faonni_shape/adminhtml_widget_form_renderer_fieldset_element')
        );
		return $this;
    }
	
	/**
     * Prepare attributes form
	 *
     * @return void
     */
    protected function _prepareForm()
    {
        $group = $this->getGroup();
        if ($group) {
            $form = new Varien_Data_Form();

            $fieldset = $form->addFieldset('group_fields' . $group->getId(), array(
                'legend' => Mage::helper('catalog')->__($group->getAttributeGroupName()),
                'class' => 'fieldset-wide'
            ));

            $attributes = $this->getGroupAttributes();

			$this->_setFieldset($attributes, $fieldset, false);

            $values = Mage::registry($this->_dataName)->getData();

            // Set default attribute values for new section_data
            if (!Mage::registry($this->_dataName)->getId()) {
                foreach ($attributes as $attribute) {
                    if (!isset($values[$attribute->getAttributeCode()])) {
                        $values[$attribute->getAttributeCode()] = $attribute->getDefaultValue();
                    }
                }
            }

            if (Mage::registry($this->_dataName)->hasLockedAttributes()) {
                foreach (Mage::registry($this->_dataName)->getLockedAttributes() as $attribute) {
                    $element = $form->getElement($attribute);
                    if ($element) {
                        $element->setReadonly(true, true);
                    }
                }
            }
            $form->addValues($values);
			//Initialize product object as form property to use it during elements generation
			$form->setDataObject(Mage::registry($this->_dataName));
			if ($this->fieldNameSuffix) $form->setFieldNameSuffix($this->fieldNameSuffix);
            $this->setForm($form);
        }
    }
	
    /**
     * Set Fieldset to Form
	 *
     * @param array $attributes attributes that are to be added
     * @param Varien_Data_Form_Element_Fieldset $fieldset
     * @param array $exclude attributes that should be skipped
     * @return void	 
     */
    protected function _setFieldset($attributes, $fieldset, $exclude=array())
    {
        $this->_addElementTypes($fieldset);
        foreach ($attributes as $attribute) {
            if (!$attribute || ($attribute->hasIsVisible() && !$attribute->getIsVisible())) {
                continue;
            }
			if (($inputType = $attribute->getFrontend()->getInputType())
					// fix Warning: in_array() Wrong datatype for second argument in ...
					&& !(is_array($exclude) && in_array($attribute->getAttributeCode(), $exclude))){

                $fieldType     = $inputType;
                $rendererClass = $attribute->getFrontend()->getInputRendererClass();
				
                if (!empty($rendererClass)){
                    $fieldType = $inputType . '_' . $attribute->getAttributeCode();
                    $fieldset->addType($fieldType, $rendererClass);
                }

                $element = $fieldset->addField($attribute->getAttributeCode(), $fieldType,
                    array(
                        'name'     => $attribute->getAttributeCode(),
                        'label'    => $attribute->getFrontend()->getLabel(),
                        'class'    => $attribute->getFrontend()->getClass(),
                        'required' => $attribute->getIsRequired(),
                        'note'     => $attribute->getNote(),
                    )
                )->setEntityAttribute($attribute);

                $element->setAfterElementHtml($this->_getAdditionalElementHtml($element));

                if ($inputType == 'select') {
                    $element->setValues($attribute->getSource()->getAllOptions(true, true));
                } else if ($inputType == 'multiselect') {
                    $element->setValues($attribute->getSource()->getAllOptions(false, true));
                    $element->setCanBeEmpty(true);
                } else if ($inputType == 'date') {
					$showTime = (1 == $attribute->getTime()) ? true : false;
					$element->setTime($showTime);
					$format = ($showTime) 
								? Varien_Date::DATETIME_INTERNAL_FORMAT 
								: Varien_Date::DATE_INTERNAL_FORMAT;
					
                    $element->setImage($this->getSkinUrl('images/grid-cal.gif'));
                    $element->setFormat($format);
                } else if ($inputType == 'multiline') {
                    $element->setLineCount($attribute->getMultilineCount());
                }
            }
        }
    }			
}