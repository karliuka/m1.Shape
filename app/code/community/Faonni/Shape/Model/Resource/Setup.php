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
class Faonni_Shape_Model_Resource_Setup 
	extends Mage_Eav_Model_Entity_Setup
{
    /**
     * Get Default Entities
	 *
     * @return array
     */
    public function getDefaultEntities() 
	{
        return array(
            Faonni_Shape_Model_Template::ENTITY => array(
                'entity_model'               => 'faonni_shape/template',
                'table'                      => 'faonni_shape/template',
				'additional_attribute_table' => 'faonni_shape/template_eav_attribute',
                'attribute_model'            => 'faonni_shape/resource_eav_attribute_template',
				'attributes'                 => array(									
					'h1' => array(
						'type'                       => 'varchar',
						'label'                      => 'tag H1',
						'input'                      => 'text',
						'required'                   => false,
						'sort_order'                 => 10,
						'global'                     => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE,
						'group'                      => 'General',						
					), 
					'name' => array(
						'type'                       => 'varchar',
						'label'                      => 'Name',
						'input'                      => 'text',
						'required'                   => false,
						'sort_order'                 => 20,
						'global'                     => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE,
						'group'                      => 'General',						
					), 	
                    'short_description' => array(
                        'type'                       => 'text',
                        'label'                      => 'Short Description',
                        'input'                      => 'textarea',
                        'required'                   => false,
                        'sort_order'                 => 30,
                        'global'                     => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE,
                        'group'                      => 'General',
                    ),
                    'description' => array(
                        'type'                       => 'varchar',
                        'label'                      => 'Description',
                        'input'                      => 'textarea',
                        'required'                   => false,
                        'sort_order'                 => 40,
                        'global'                     => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE,
                        'group'                      => 'General',
                    ),				
                    'meta_title' => array(
                        'type'                       => 'varchar',
                        'label'                      => 'Meta Title',
                        'input'                      => 'text',
                        'required'                   => false,
                        'sort_order'                 => 50,
                        'global'                     => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE,
                        'group'                      => 'General',
                    ),
                    'meta_keyword' => array(
                        'type'                       => 'text',
                        'label'                      => 'Meta Keywords',
                        'input'                      => 'textarea',
                        'required'                   => false,
                        'sort_order'                 => 60,
                        'global'                     => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE,
                        'group'                      => 'General',
                    ),
                    'meta_description' => array(
                        'type'                       => 'varchar',
                        'label'                      => 'Meta Description',
                        'input'                      => 'textarea',
                        'required'                   => false,
                        'note'                       => 'Maximum 255 chars',
                        'class'                      => 'validate-length maximum-length-255',
                        'sort_order'                 => 70,
                        'global'                     => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE,
                        'group'                      => 'General',
                    ),
                )
            )
        );
    }
}