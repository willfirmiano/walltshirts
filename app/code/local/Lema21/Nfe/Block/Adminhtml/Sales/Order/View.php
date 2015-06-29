<?php
class Lema21_Nfe_Block_Adminhtml_Sales_Order_View 
extends Mage_Adminhtml_Block_Sales_Order_View
{
    public function  __construct()
    { 
    	$states = explode(',',Mage::getStoreConfig('nfe/general/pedido_status'));
    	if( ( in_array( $this->getOrder()->getState(), $states)) || !is_array($states)  ){
    		$this->_addButton(
    		            'call_to_send_nfe', array( 
    		                'label'     => Mage::helper('Sales')->__('Emitir NF-e'), 
    		                'onclick'   => 'setLocation(\'' . $this->getUrlSend() . '\')', 
    		                'class'     => 'go' 
    		), 0, 100, 'header', 'header'
    		);
    	} else {
    		$this->_addButton(
    		                    'call_to_send_nfe', array(
    		                            'label'     => Mage::helper('Sales')->__('Emitir NF-e'),                            
    		                            'class'     => 'disabled'
    		), 0, 100, 'header', 'header'
    		);
    	}
        parent::__construct();  
    } 


    // route for send nfe
    // nfe/adminhtml_nfe/send 
    public function getUrlSend()
    {
        return $this->getUrl('nfe/adminhtml_nfe/send');
    }
} 
