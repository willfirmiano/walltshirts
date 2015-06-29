<?php
class Lema21_Nfe_Model_TransformToXML
{

	private $_orderModel        = null;
	private $_xml               = "";
	private $_stringFinalXML    = "";
	private $_path              = "";
	const PRODUCT_OR_SERVICE    = "P";
	const SECURITY_AMOUNT       = 0;
	const DISCOUNT_DEFAULT      = 0;
	const ATTR_OPERATION_UNIT   = 'operation_unit';
	const ATTR_OPERATION_NAME   = 'operation_name';
	const ATTR_CODIGO_ORIGEM    = 'codigo_origem';
	const ATTR_CODIGO_NCM       = 'codigo_ncm';
	
	

	// Valores para código de origem
	// 0 = nacional
	// 1 = Estrangeira - Importação direta
	// 2 = Estrangeira - Adquirida no mercado interno

	/**
	 * Construction function
	 * Load order and path to write XML
	 *
	 * @return void
	 */
	public function __construct($params)
	{
		$order = $params["order"];
		$path  = $params["path"];

		// set order module
		$this->_orderModel = $order;

		// set path
		$this->_path = $path;
	}



	/**
	 * Write xml nfe in folder
	 *
	 * @return true
	 */
	public function write()
	{

		// load template array, fill data and convert to XML
		$this->_init()->_addCustomer()
		->_addItens()->_addOtherInfo()->_toXML();

		$io = new Varien_Io_File();
		$io->setAllowCreateFolders(true);
		$io->createDestinationDir($this->_path);

		$return = $io->write(
		$this->_path . $this->_getFileName(),
		$this->_stringFinalXML
		);

		return true;
	}

	/**
	 * Read nfe
	 *
	 * @return string
	 */
	public function read()
	{
		$io = new Varien_Io_File();
		return $io->read($this->_path . $this->_getFileName());
	}



	private function _init()
	{
		$this->_xml = $this->_template();
		return $this;
	}

	private function _toXML()
	{

		$xml = Lema21_Nfe_Model_Lib_Array2XML::createXML('pedido', $this->_xml);
		$this->_stringFinalXML = $xml->saveXML();

		return $this;
	}

	private function _addCustomer()
	{

		// nome
		$this->_xml["cliente"]["nome"] = $this->_orderModel
		->getCustomerFirstname() . " " . $this->_orderModel
		->getCustomerLastname();

		$this->_xml["cliente"]["email"] = $this->_orderModel
		->getCustomerEmail();
		// cpf
		$this->_xml["cliente"]["cpf_cnpj"] = $this->_orderModel
		->getCustomerTaxvat();

		$billingAddress = $this->_orderModel->getBillingAddress();

		// endereco - cep
		$this->_xml["cliente"]["cep"] = $billingAddress
		->getPostcode();

		// endereco - cidade
		$this->_xml["cliente"]["cidade"] = $billingAddress
		->getCity();

		// endereco - uf
		$this->_xml["cliente"]["uf"] = $billingAddress
		->getRegion();

		// endereco - telefone
		$this->_xml["cliente"]["fone"] = $billingAddress
		->getTelephone();

		// endereco - rua
		$this->_xml["cliente"]["endereco"] = $billingAddress
		->getStreet(1);

		// endereco - bairro
		$this->_xml["cliente"]["numero"] = $billingAddress
		->getStreet(2);

		// endereco - bairro
		$this->_xml["cliente"]["bairro"] = $billingAddress
		->getStreet(3);

		// endereco - bairro
		$this->_xml["cliente"]["complemento"] = $billingAddress
		->getStreet(4);

		return $this;
	}

	private function _addItens()
	{

		$this->_xml["itens"] = array();
		$this->_xml["itens"]["item"] = array();

		$cont=0;
		foreach ($this->_orderModel->getItemsCollection() as $itemId => $item) {

			$_product = Mage::getModel('catalog/product')->load( $item->product_id );
			if(!$this->checkProductAttributesRequired($_product)){
				throw new Exception("O produto no pedido ".$this->_orderModel->getIncrementId().", selecionado para emissão de Nf-e não possui os atributos requeridos");				
			}
			if ( $_product->getResource()->getAttribute( Lema21_Nfe_Model_TransformToXML::ATTR_CODIGO_ORIGEM )->getFrontend()->getValue( $_product )){
				$res = $_product->getResource()->getAttribute( Lema21_Nfe_Model_TransformToXML::ATTR_CODIGO_ORIGEM )->getFrontend()->getValue( $_product );
			}

			Mage::log(" origem > " .
			$_product->getResource()->getAttribute( Lema21_Nfe_Model_TransformToXML::ATTR_CODIGO_ORIGEM )->getFrontend()->getValue( $_product )
			);
			Mage::log(" origem 2 > " . $_product->getData( Lema21_Nfe_Model_TransformToXML::ATTR_CODIGO_ORIGEM ));
			$this->_xml["itens"]["item"][$cont] = array(
                "codigo" => $item->getSku(),
                "descricao" => $_product->getData(Lema21_Nfe_Model_TransformToXML::ATTR_OPERATION_NAME),
                "un"    => $_product->getData(Lema21_Nfe_Model_TransformToXML::ATTR_OPERATION_UNIT),
                "qtde"  => $item->getData("qty_ordered"),
                "vlr_unit" => $item->getPrice(),
                "tipo" => self::PRODUCT_OR_SERVICE,
                "peso_bruto" => $item->getWeight(),
                "peso_liq"  => $item->getWeight(),
                "class_fiscal" => $_product->getData(Lema21_Nfe_Model_TransformToXML::ATTR_CODIGO_NCM),
                "origem"    => $_product->getData( Lema21_Nfe_Model_TransformToXML::ATTR_CODIGO_ORIGEM )
			);

			$cont++;
		}

		return $this;
	}

	private function _addOtherInfo()
	{

		$this->_xml["vlr_frete"]     = $this->_orderModel->getShippingAmount();;
		$this->_xml["vlr_seguro"]    = self::SECURITY_AMOUNT;
		$this->_xml["vlr_desconto"]  = self::DISCOUNT_DEFAULT;
		$this->_xml["obs"]           = "PEDIDO " . $this->_orderModel
		->getData("increment_id");

		return $this;
	}

	private function _getFileName()
	{
		return $this->_orderModel->getId() . ".xml";
	}
	private function _template()
	{
		$template = array(
            "cliente" => array(
                "nome"        => null,
                "tipoPessoa"  => "F",
                "email"       => null,
                "cpf_cnpj"    => null,
                "ie_rg"       => "",
                "endereco"    => null,
                "numero"      => null,
                "complemento" => null,
                "bairro"      => null,
                "cep"         => null,
                "cidade"      => null,
                "uf"          => null,
                "fone"        => null                  
		),
            "vlr_frete"       => null,
            "vlr_seguro"      => null,
            "vlr_despesas"    => null,
            "vlr_desconto"    => null,
            "obs"             => null,
            "obs_internas"    => null
		);

		return $template;
	}
	
	protected function checkProductAttributesRequired( Mage_Catalog_Model_Product $product ){
		$haystack = $product->getAttributes();
		$target = array(Lema21_Nfe_Model_TransformToXML::ATTR_CODIGO_NCM, 
						Lema21_Nfe_Model_TransformToXML::ATTR_CODIGO_ORIGEM,
						Lema21_Nfe_Model_TransformToXML::ATTR_OPERATION_NAME,
						Lema21_Nfe_Model_TransformToXML::ATTR_OPERATION_UNIT);
		if(count(array_intersect($haystack, $target)) == count($target)){
			return true;
		}
		return false;
	}

	/*
	 <pedido>
	<cliente>
	<nome>João e Maria 123456  44444</nome>
	<tipoPessoa>J</tipoPessoa>
	<cpf_cnpj>00000000000000</cpf_cnpj>
	<ie_rg>3067663000</ie_rg>
	<endereco>Rua Visconde de São Gabriel</endereco>
	<numero>392</numero>
	<complemento>Sala 54</complemento>
	<bairro>Cidade Alta</bairro>
	<cep>95.700-000</cep>
	<cidade>Bento Gonçalves</cidade>
	<uf>RS</uf>
	<fone>54 8115-3376</fone>
	</cliente>
	<itens>
	<item>
	<codigo>001</codigo>
	<descricao>Caneta 001</descricao>
	<un>Pç</un>
	<qtde>10</qtde>
	<vlr_unit>1.68</vlr_unit>
	<tipo>P</tipo>
	<peso_bruto>0.2</peso_bruto>
	<peso_liq>0.18</peso_liq>
	<class_fiscal>1000.00.10</class_fiscal>
	<origem>0</origem>
	</item>
	<item>
	<codigo>002</codigo>
	<descricao>Caderno 002</descricao>
	<un>Un</un>
	<qtde>3</qtde>
	<vlr_unit>3.75</vlr_unit>
	<tipo>P</tipo>
	<peso_bruto>0.75</peso_bruto>
	<peso_liq>0.7</peso_liq>
	<class_fiscal>1000.00.10</class_fiscal>
	<origem>0</origem>
	</item>
	<item>
	<codigo>003</codigo>
	<descricao>Teclado 003</descricao>
	<un>Cx</un>
	<qtde>7</qtde>
	<vlr_unit>18.65</vlr_unit>
	<tipo>P</tipo>
	<peso_bruto>0.65</peso_bruto>
	<peso_liq>0.52</peso_liq>
	<class_fiscal>1000.00.10</class_fiscal>
	<origem>0</origem>
	</item>
	</itens>
	<parcelas>
	<parcela>
	<dias>10</dias>
	<data>01/09/2009</data>
	<vlr>100</vlr>
	<obs>Teste obs 1</obs>
	</parcela>
	<parcela>
	<dias>15</dias>
	<data>06/09/2009</data>
	<vlr>50</vlr>
	<obs></obs>
	</parcela>
	<parcela>
	<dias>20</dias>
	<data>11/09/2009</data>
	<vlr>50</vlr>
	<obs>Teste obs 3</obs>
	</parcela>
	</parcelas>
	<vlr_frete>15</vlr_frete>
	<vlr_seguro>7</vlr_seguro>
	<vlr_despesas>2.5</vlr_despesas>
	<vlr_desconto>10</vlr_desconto>
	<obs>Testando o campo observações do pedido</obs>
	<obs_internas>Observações internas</obs_internas>
	</pedido>

	*/
}