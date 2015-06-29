<?php

/**
* Código disponibilizado pelo próprio Bling
* @todo: Melhorar muito este código
*/

class Lema21_Nfe_Model_Lib_ConnectBling
{

    const URL_ORDER    = 'http://www.bling.com.br/recepcao.nfe.php';
    const URL_NFE_SEND = 'http://www.bling.com.br/recepcao.nfe.emissao.php';

    public function sendRequest($data, $optionalHeaders = null)
    {

        $params = array('http' => array(
          'method' => 'POST',
          'content' => $this->_getData($data)
          ));

        if ($optionalHeaders !== null) {
            $params['http']['header'] = $optionalHeaders;
        }
        $ctx = stream_context_create($params);
        $fp = @fopen(self::URL_ORDER, 'rb', false, $ctx);
        if (!$fp) {
            throw new Exception("Problema com $url");
        }
        $response = @stream_get_contents($fp);
        if ($response === false) {
            throw new Exception("Problema obtendo retorno de $url");
        }
        return $response;
    }


    private function _getData($data)
    {
        return "retornaNumeroNota=S&apiKey=" . 
        Mage::getStoreConfig(
            'nfe/general/chave_acesso_bing'
        ) . "&pedidoXML=" . $data;
    }

}