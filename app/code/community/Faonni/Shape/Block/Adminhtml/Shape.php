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
class Faonni_Shape_Block_Adminhtml_Shape 
	extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    /**
     * Initialize invitation manage page
	 *
     * @return void
     */
    public function __construct()
    {
        $this->_blockGroup = 'faonni_shape';
        $this->_controller = 'adminhtml_shape';
        $this->_headerText = Mage::helper('faonni_shape')->__('Shape Products Rules');
        $this->_addButtonLabel = Mage::helper('faonni_shape')->__('Add New Rule');
        parent::__construct();
    }
}