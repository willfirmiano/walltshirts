<?php
class Lema21_Nfe_Model_Service_Send
{
    /**
     *
     * @TODO
     * 
     * 1 - colocar lista de produtos dinamicamente - done
     * 2 - passar o bairro
     * 3 - passar o codigo da cidade
     * 4 - exibir numero da nota fiscal no pedido - done
     * (adicionar uma tab - nota fiscal com os campos do pedido)
     * 5 - colocar numero da nota fiscal no grid
     * 6 - Colocar o botao emitir NF-e apenas quando o pedido estiver faturado
     *
     */
    private $_orderModel    = null ;
    private $_orderId       = null;
    private $_path          = 'nfes';
    private $_states        = null;

    function __construct($orderId)
    {
        $this->_orderId = $orderId;
        $this->_loadOrder();
        $this->_states = explode(',',Mage::getStoreConfig('nfe/general/pedido_status'));
    }

    public function call()
    {
    	if( ( is_array($this->_states) && !in_array( $this->_orderModel->getState(), $this->_states)) ){
    		return array( Lema21_Nfe_Helper_Data::ERROR_MESSAGE  => "Erro ao persistir nota {$this->_orderModel->getData('increment_id')} [Não se encontra com status liberado para emissão de Nf-e]"."<br/>" );
    	}
    	
        $response = $this->_transformToXML()
            ->_sendData();

        $resultMessage = $this->_saveNfe($response);
        return $resultMessage; 
    }


    private function _saveNfe($response)
    {
        if ( strlen( $response ) > 30 ) {
            return array( Lema21_Nfe_Helper_Data::ERROR_MESSAGE  => "Erro ao persistir nota {$this->_orderModel->getData('increment_id')} [$response]"."<br/>" );
        } else {
            // save nfe_number in order
            $this->_orderModel->setNfeNumber($response)->save();
            return array( Lema21_Nfe_Helper_Data::SUCCESS_MESSAGE => "Nf-e enviada com sucesso: ".$this->_orderModel->getData('increment_id')."<br/>" );
        }
    }

    /**
     * Call transform order in XML and write in file
     *
     * @return this
     */
    private function _transformToXML()
    {

        Mage::getModel(
            "nfe/transformToXML", 
            array(
                "order" => $this->_orderModel,
                "path"  => $this->_getPathToFileStore()
            )
        )->write();

        return $this;
    }

    /**
     * Call action of render the XML order, after send xml to bling!
     * 
     * @return string response bling
     */
    private function _sendData()
    {
        $content = Mage::getModel(
            "nfe/transformToXML", 
            array(
                "order" => $this->_orderModel,
                "path"  =>  $this->_getPathToFileStore()
            )
        )->read();

        $response = Mage::getModel(
            "nfe/lib_connectBling"
        )->sendRequest($content);

        return $response;
    }

    /**
     * Return path to store XML data
     *
     * @return string
     */
    private function _getPathToFileStore()
    {
        return Mage::getBaseDir('var') . DS . $this->_path . DS;
    }

    private function _loadOrder()
    {

        if ($this->_orderModel = Mage::getModel('sales/order')
            ->load($this->_orderId)) {
            return $this;
        } else {
            throw new Exception(
                "Error in load order $this->_orderId",
                1
            );            
        }
    }

}