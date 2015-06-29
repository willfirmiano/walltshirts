<?php

class Lema21_Nfe_Block_Adminhtml_Sales_Order_View_Tab_Nfe 
    extends Mage_Adminhtml_Block_Template implements Mage_Adminhtml_Block_Widget_Tab_Interface {

    protected function _construct() {
        parent::_construct();
        $this->setTemplate('nfe/list.phtml');
    }

   

    /**
     * Retrieve order model instance
     *
     * @return Mage_Sales_Model_Order
     */
    public function getOrder() {
        return Mage::registry('current_order');
    }

    /**
     * Retrieve source model instance
     *
     * @return Mage_Sales_Model_Order
     */
    public function getSource() {
        return $this->getOrder();
    }

    /**
     * ######################## TAB settings #################################
     */
    public function getTabLabel() {
        return Mage::helper('sales')->__('Nota Fiscal');
    }

    public function getTabTitle() {
        return Mage::helper('sales')->__('Nota Fiscal');
    }

    public function canShowTab() {
        return true;
    }

    public function isHidden() {
        return false;
    }

}


