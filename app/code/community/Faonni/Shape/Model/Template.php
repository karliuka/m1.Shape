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
class Faonni_Shape_Model_Template
	extends Faonni_Shape_Model_Abstract
{
    /**
     * Maps to the array key from Setup.php::getDefaultEntities()
	 *
     * @var string	 
     */
    const ENTITY = 'shape_template';
	
    /**
     * The event prefix name.
	 *
     * @var string	 
     */    
    protected $_eventPrefix = 'shape_template';
	
    /**
     * The event Object name.
	 *
     * @var string
     */	
    protected $_eventObject = 'template';
	
    /**
     * Set resource names
	 *
     * @return void
     */
    protected function _construct()
	{
       $this->_init('faonni_shape/template');
    }	
}