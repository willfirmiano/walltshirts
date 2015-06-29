<?php 

class Mycode_Function_Helper_Data extends Mage_Core_Helper_Abstract
{
	/*Aparecer Quantidade de Itens e o Texto Itens do Carrinho de Compras*/
	
	function ItensCarrinho(){
	
		if(Mage::helper('checkout/cart')->getSummaryCount() != 0){
			 $qtd = Mage::helper('checkout/cart')->getSummaryCount();
			 
			 if(Mage::helper('checkout/cart')->getSummaryCount() == 1){ 
			  $texto = '';
			 }
			  else{ 
			  $texto = '';
			 }
		 }
		 else{
			$qtd = ''; 
			$texto = '';
		 }
 
		 $item = $qtd . $texto;
	 
		echo $item;
	}	



/************************************************************************************************************************************************************************/

/************************************************************************************************************************************************************************/

	/* Entrar e Sair do Top Links*/

	function Entrar_Sair(){
		if ( Mage::getSingleton('customer/session')->isLoggedIn() == 1 ) {
			 $texto = "Sair"; 
			 $link = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_LINK) . "customer/account/logout";
		} else {
			 $texto = "Entrar"; 
			 $link = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_LINK) . "customer/account/login";
		}
				
		echo '
				<li>
                	<a title="' . $texto . '" href="' . $link . '">' . $texto . '</a>
                </li>
			 ';
	}


/************************************************************************************************************************************************************************/

/************************************************************************************************************************************************************************/

	/* Primeiro nome do usuario logado */
	
	function Nome_Usuario(){
		if ( Mage::getSingleton('customer/session')->isLoggedIn() == 1 ) {
				 
			 $nome = $this->_data['welcome'] = $this->__('Welcome, %s!', Mage::getSingleton('customer/session')->getCustomer()->getFirstname()); 
			 
			 echo $nome;

		}

	} 


/************************************************************************************************************************************************************************/

/************************************************************************************************************************************************************************/





}	

?> 