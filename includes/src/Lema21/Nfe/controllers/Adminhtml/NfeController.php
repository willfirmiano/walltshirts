<?php
class Lema21_Nfe_Adminhtml_NfeController
extends Mage_Adminhtml_Controller_Action
{

    function sendAction()
    {

        try {
            $orderId = $this->getRequest()->getParam('order_id');
            $this->responseMessage( array( $orderId ) );
        } catch (Exception $e) {
            Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
        }
        $this->_redirectReferer();
    }

    /**
     * @todo criar msg sucesso para pedidos ok, avisar pedidos que deram problema
     *           colocar opcao no admin para escolher o status para emissao da Nf-e
     * Enter description here ...
     */
    function sendMassAction()
    {

        try {
            $orderIds = $this->getRequest()->getParam('order_ids');
        	$this->responseMessage( $orderIds );   
        } catch (Exception $e) {
            Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
        }

        $this->_redirectReferer();
    }
    
    protected function responseMessage( array $orderIds ){
    	if(!is_array($orderIds)) {
    		Mage::getSingleton('adminhtml/session')->addError(Mage::helper('tax')->__('Please select Order(s).'));
    	}
    	$errorMessage   = false;
    	$successMessage = false;
    	foreach ( $orderIds as $orderId ) {
    		$rsMessage = Mage::getModel('nfe/service_send', $orderId)->call();
    		switch ( key($rsMessage) ) {
    			case Lema21_Nfe_Helper_Data::ERROR_MESSAGE:
    				$errorMessage   .= $rsMessage[ key($rsMessage) ];
    				break;
    			case Lema21_Nfe_Helper_Data::SUCCESS_MESSAGE:
    				$successMessage .= $rsMessage[ key($rsMessage) ];
    				break;
    		}
    	}
    	
    	if($successMessage){
    		Mage::getSingleton('adminhtml/session')->addSuccess( $successMessage );
    	}
    	if($errorMessage){
    		Mage::getSingleton('adminhtml/session')->addError( $errorMessage );
    	}
    }
}