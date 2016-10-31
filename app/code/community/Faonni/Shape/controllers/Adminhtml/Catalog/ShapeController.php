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
class Faonni_Shape_Adminhtml_Catalog_ShapeController 
	extends Mage_Adminhtml_Controller_Action
{
    /**
     * Init active menu and set breadcrumb
	 *
     * @return Faonni_Shape_Adminhtml_ShapeController
     */
    protected function _initAction()
    {
        $this->loadLayout()
            ->_setActiveMenu('catalog/shape')
            ->_addBreadcrumb(
                Mage::helper('faonni_shape')->__('Shape Rules'),
                Mage::helper('faonni_shape')->__('Shape Rules')
            );
        return $this;
    }

    /**
     * Initialize proper rule model
	 *
     * @param string $requestParam
     * @return Faonni_Shape_Model_Rule
     */
    protected function _initRule($requestParam = 'id')
    {
        $ruleId = $this->getRequest()->getParam($requestParam, 0);
        $rule = Mage::getModel('faonni_shape/rule');
        if ($ruleId) {
            $rule->load($ruleId);
            if (!$rule->getId()) {
                Mage::throwException($this->__('Wrong shape rule requested.'));
            }
        }
        Mage::register('current_shape_rule', $rule);
        return $rule;
    }

    /**
     * Rules list
	 *
     * @return void
     */
    public function indexAction()
    {
		$this->_title($this->__('Shape Rules'));
		$this->loadLayout();
		$this->_setActiveMenu('catalog/shape');
		$this->renderLayout();
    }

    /**
     * Create new rule
	 *
     * @return void	 
     */
    public function newAction()
    {
        $this->_forward('edit');
    }

    /**
     * Edit shape rule
	 *
     * @return void	 	 
     */
    public function editAction()
    {
        $this->_title($this->__('Shape Rules'));

        try {
            $rule = $this->_initRule();
        }
        catch (Mage_Core_Exception $e) {
            Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            $this->_redirect('*/*/');
            return;
        }

        $this->_title($rule->getId() ? $rule->getName() : $this->__('New Rule'));

        // set entered data if was error when we do save
        $data = Mage::getSingleton('adminhtml/session')->getPageData(true);
        if (!empty($data)) {
            $rule->addData($data);
        }

        $rule->getConditions()->setJsFormObject('rule_conditions_fieldset');
		
		$template = Mage::getModel('faonni_shape/template')
			->setStoreId(Mage::app()->getRequest()->getParam('store', null))
			->load($rule->getId());
	
		Mage::register('current_shape_rule_template', $template);
		
        $block =  $this->getLayout()->createBlock('faonni_shape/adminhtml_shape_edit',
            'adminhtml_shape_edit')->setData('form_action_url', $this->getUrl('*/*/save'));

        $this->_initAction();

        $this->getLayout()->getBlock('head')
            ->setCanLoadExtJs(true)
            ->setCanLoadRulesJs(true);

        $this->_addBreadcrumb(
                $rule->getId() ? $this->__('Edit Rule') : $this->__('New Rule'),
                $rule->getId() ? $this->__('Edit Rule') : $this->__('New Rule'))
            ->_addContent($block)
            ->_addLeft($this->getLayout()->createBlock('faonni_shape/adminhtml_shape_edit_tabs',
                'adminhtml_shape_edit_tabs'))->renderLayout();
    }

    /**
     * Add new condition
	 *
     * @return void	 	 
     */
    public function newConditionHtmlAction()
    {
        $id = $this->getRequest()->getParam('id');
        $typeArr = explode('|', str_replace('-', '/', $this->getRequest()->getParam('type')));
        $type = $typeArr[0];

        $rule = Mage::getModel($type)
            ->setId($id)
            ->setType($type)
            ->setRule(Mage::getModel('faonni_shape/rule'))
            ->setPrefix('conditions');
        if (!empty($typeArr[1])) {
            $rule->setAttribute($typeArr[1]);
        }

        if ($rule instanceof Mage_Rule_Model_Condition_Abstract) {
            $rule->setJsFormObject($this->getRequest()->getParam('form'));
            $html = $rule->asHtmlRecursive();
        } else {
            $html = '';
        }
        $this->getResponse()->setBody($html);
    }

    /**
     * Save shape rule
	 *
     * @return void	 	 
     */
    public function saveAction()
    {
        if ($data = $this->getRequest()->getPost()) {
            try {
                $redirectBack = $this->getRequest()->getParam('back', false);

                $rule = $this->_initRule('rule_id');
                $validateResult = $rule->validateData(new Varien_Object($data));
				
                if (true !== $validateResult) {
                    foreach ($validateResult as $errorMessage) {
                        $this->_getSession()->addError($errorMessage);
                    }
                    $this->_getSession()->setFormData($data);

                    $this->_redirect('*/*/edit', array('id' => $rule->getId()));
                    return;
                }

                $data['conditions'] = $data['rule']['conditions'];
                unset($data['rule']);

                $rule->loadPost($data);
                Mage::getSingleton('adminhtml/session')->setPageData($rule->getData());
                $rule->save();

                Mage::getSingleton('adminhtml/session')->addSuccess($this->__('The shape rule has been saved.'));
                Mage::getSingleton('adminhtml/session')->setPageData(false);

                if ($redirectBack) {
					$tabId = $this->getRequest()->getParam('tab', null);
                    $this->_redirect('*/*/edit', array(
                        'id'         => $rule->getId(),
                        '_current'   => true,
						'active_tab' => $tabId,
                    ));
                    return;
                }

            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setPageData($data);
                $this->_redirect('*/*/edit', array('id' => $rule->getId()));
                return;
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($this->__('Failed to save shape rule.'));
                Mage::logException($e);
            }
        }
        $this->_redirect('*/*/');
    }

    /**
     * Delete shape rule
	 *
     * @return void		 
     */
    public function deleteAction()
    {
        try {
            $rule = $this->_initRule();
            $rule->delete();
            Mage::getSingleton('adminhtml/session')->addSuccess($this->__('The shape rule has been deleted.'));
        }
        catch (Mage_Core_Exception $e) {
            Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            $this->_redirect('*/*/edit', array('id' => $rule->getId()));
            return;
        } catch (Exception $e) {
            Mage::getSingleton('adminhtml/session')->addError($this->__('Failed to delete shape rule.'));
            Mage::logException($e);
        }
        $this->_redirect('*/*/');
    }

    /**
     * Match shape rule for matched products
	 *
     * @return void		 
     */
    public function applyAction()
    {
        try {
            $rule = $this->_initRule();
			$rule->getResource()->updateRuleProductData($rule);
			$rule->getResource()->updateProductData($rule);
			
            Mage::getSingleton('adminhtml/session')->addSuccess($this->__('The shape rule has been matched.'));
			
        } catch (Mage_Core_Exception $e) {
            Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
        } catch (Exception $e) {
            Mage::getSingleton('adminhtml/session')->addException($e, $this->__('Shape rule matching error.'));
            Mage::logException($e);
        }
        $this->_redirect('*/*/edit', array('id' => $rule->getId(), 'active_tab' => 'matched_products_section'));
    }

    /**
     *  Product grid ajax action
	 *
     * @return void		 
     */
    public function productGridAction()
    {
        if ($this->_initRule('rule_id')) {
            $block = $this->getLayout()->createBlock('faonni_shape/adminhtml_shape_edit_tab_product');
            $this->getResponse()->setBody($block->toHtml());
        }
    }	
	
    /**
     * Check the permission to run it
	 *
     * @return boolean
     */
    protected function _isAllowed()
    {
        return Mage::getSingleton('admin/session')->isAllowed('faonni_shape/shape');
    }
}