<?php

require_once "../PagSeguroLibrary/PagSeguroLibrary.php";
require_once "../examples/NotificationListener.php";
require_once "../examples/searchTransactionByCode.php";
require_once '../mail/MailSender.php';
require_once 'include/pedidos.php';

/**
 * 
 * Classe que notifica os clientes suas respectivas transações
 * @author Will
 *
 */
class NotificationPartners {
	/**
	 * 
	 * Método main da classe
	 */
	public static function main() {
		if(isset($_GET['notificationCode']) && isset($_GET['notificationType'])) {
			$code = (isset($_GET['notificationCode']) && trim($_GET['notificationCode']) !== ""  ? trim($_GET['notificationCode']) : null);
			$type = (isset($_GET['notificationType']) && trim($_GET['notificationType']) !== ""  ? trim($_GET['notificationType']) : null);
		} else {
			$code = (isset($_POST['notificationCode']) && trim($_POST['notificationCode']) !== ""  ? trim($_POST['notificationCode']) : null);
			$type = (isset($_POST['notificationType']) && trim($_POST['notificationType']) !== ""  ? trim($_POST['notificationType']) : null);
		}
		
		LogPagSeguro::info('Notification Code:');
		LogPagSeguro::info($code);
		LogPagSeguro::info('Tipo de Transação:');
		LogPagSeguro::info($type);
		
		$transaction = NotificationListener::ValidaTransaction($code, $type);
		$transaction_code = $transaction->getCode();

		$transactionData = SearchTransactionByCode::searchTransaction($transaction_code);
		
		$intReference = $transactionData->getReference();

		$arrTransactionInfo = array();
		$arrTransactionInfo['status'] = $transactionData->getStatus()->getTypeFromValue();
		
		if ($transactionData->getItems()) {
			if (is_array($transaction->getItems())) {
				foreach($transaction->getItems() as $key => $item) {
					$strId = $item->getId();
					$arrTransactionInfo[$strId] = array(
													'quantidade' => $item->getQuantity(),
													'descricao' => $item->getDescription()
														);
				}
			}		
		}
		
		
		//INГЌCIO - TRANSACTION FAKE
		/*
		$transaction_code = 'C9CE60D1-CAE4-474D-97C5-20C374E8C7B7';
		$intReference = 100696969;
		$arrTransactionInfo = array(
			'status' => 'WAITING_PAYMENT',
			'577_1' => array(
				'quantidade' => 1,
				'descricao' => 'DL SKULL MASK'
			),
			'918_2' => array(
				'quantidade' => 1,
				'descricao' => 'Quem e o Melhor?'
			)		
		);
		*/
		// FIM - TRANSACTION FAKE
		echo 'PRINT RETORNO PAGSEGURO<pre>'; print_r($arrTransactionInfo);
		
		$arrProductsMarcosCastro = array('521', '558', '518', '726', '898', '909');
		$arrProductsDesceALetra = array('612', '605', '577', '619');
		$arrProductsLaFenix = array('647', '641', '849', '864');
		$arrProductsBebidaLiberada = array();
		$arrProductsBianca = array('694', '682', '471');
		$arrProductsTolkien = array('451');
		$arrProductsDoberro = array('805', '800', '794');
		$arrProductsBrancoala = array('763', '762');
		$arrProductsLOLGames = array('756', '750', '743', '737', '929');
		$arrProductsOverOne = array('630');
		$arrProductsOQND = array('827', '817', '812');
		$arrProductsLeo = array('918', '1030');
		$arrProductsNostalgia = array('982');
		$arrProductsDenissnider = array('971', '991');
		$arrProductsJesusManero = array('1021', '1006');
		$arrProductsWall = array('1041');
		
		foreach($arrTransactionInfo as $id => $value) {
			if($id != 'status') {
				$arrPieces = explode('_', $id);
				unset($arrTransactionInfo[$id]);
				$arrTransactionInfo[$arrPieces[0]] = $value;
			}	
		}
		echo 'PRINT TRATADO<pre>'; print_r($arrTransactionInfo);
		// ARMAZEDANDO PEDIDOS NO BANCO
		
		$objPedidos = new Pedidos();
		$objPedidos->inserirPedido($arrTransactionInfo, $intReference, $transaction_code);
		
		foreach($arrTransactionInfo as $id => $value) {
			if($id == 'status') {
				$strStatus = $value;
			
				switch($strStatus) {
					case 'WAITING_PAYMENT':
						$strStatus = MailSender::WAITING_PAYMENT;
						break;
					case 'PAID':
						$strStatus = MailSender::PAID;
						break;
					default:
						return false;						
				}
			} else {
				$intProduct = $id;
				
				switch($intProduct) {
					case in_array($intProduct, $arrProductsMarcosCastro):
						$arrMarcosCastroMail[] = $value;
						break;
					case in_array($intProduct, $arrProductsDesceALetra):
						$arrDesceALetraMail[] = $value;
						break;
					case in_array($intProduct, $arrProductsLaFenix):
						$arrLaFenixMail[] = $value;
						break;
					case in_array($intProduct, $arrProductsBianca):
						$arrBiancaMail[] = $value;
						break;
					case in_array($intProduct, $arrProductsDoberro):
						$arrDoberroMail[] = $value;
						break;
					case in_array($intProduct, $arrProductsBrancoala):
						$arrBrancoalaMail[] = $value;
						break;
					case in_array($intProduct, $arrProductsLOLGames):
						$arrLOLGamesMail[] = $value;
						break;
					case in_array($intProduct, $arrProductsTolkien):
						$arrTolkienMail[] = $value;
						break;
					case in_array($intProduct, $arrProductsOverOne):
						$arrOverOneMail[] = $value;
						break;
					case in_array($intProduct, $arrProductsOQND):
						$arrOQNDMail[] = $value;
						break;
					case in_array($intProduct, $arrProductsLeo):
						$arrLeoMail[] = $value;
						break;
					case in_array($intProduct, $arrProductsNostalgia):
						$arrNostalgiaMail[] = $value;
						break;
					case in_array($intProduct, $arrProductsDenissnider):
						$arrDenissniderMail[] = $value;
						break;
					case in_array($intProduct, $arrProductsJesusManero):
						$arrJesusManeroMail[] = $value;
						break;
					case in_array($intProduct, $arrProductsWall):
						$arrWallMail[] = $value;
						break;
				}
			}

		}
		
		//Envia e-mail para o parceiro		
		if(isset($arrMarcosCastroMail)) {
			MailSender::sendMailToPartner('camisas@marcoscastro.com.br', $arrMarcosCastroMail, $strStatus, MailSender::MARCOS_CASTRO, $intReference);
		} 
		if(isset($arrLaFenixMail)) {
			MailSender::sendMailToPartner('vivalafenix@gmail.com', $arrLaFenixMail, $strStatus, MailSender::LA_FENIX, $intReference);
		}
		if(isset($arrDesceALetraMail)) {
			MailSender::sendMailToPartner('william.firmiano@walltshirts.com', $arrDesceALetraMail, $strStatus, MailSender::CAUE, $intReference);
		}
		if(isset($arrBiancaMail)) {
			MailSender::sendMailToPartner('biancalucchesi@walltshirts.com', $arrBiancaMail, $strStatus, MailSender::BIANCA, $intReference);
		}
		if(isset($arrDoberroMail)) {
			MailSender::sendMailToPartner('cacadoberro@hotmail.com', $arrDoberroMail, $strStatus, MailSender::DOBERRO, $intReference);
		}
		if(isset($arrBrancoalaMail)) {
			MailSender::sendMailToPartner('brancoala@gmail.com', $arrBrancoalaMail, $strStatus, MailSender::BRANCOALA, $intReference);
		}
		if(isset($arrLOLGamesMail)) {
			MailSender::sendMailToPartner('contato@lolgamesonline.com', $arrLOLGamesMail, $strStatus, MailSender::LOLGAMES, $intReference);
		}
		if(isset($arrTolkienMail)) {
			MailSender::sendMailToPartner('rodrigo@agenciaoca.com.br, rodromani@walltshirts.com', $arrTolkienMail, $strStatus, MailSender::TOLKIEN, $intReference);
		}
		if(isset($arrOverOneMail)) {
			MailSender::sendMailToPartner('tiago.magnus@underdogs.com.br', $arrOverOneMail, $strStatus, MailSender::TIAGO, $intReference);
		}
		if(isset($arrOQNDMail)) {
			MailSender::sendMailToPartner('watson_w.pvh@hotmail.com', $arrOQNDMail, $strStatus, MailSender::OQND, $intReference);
		}
		if(isset($arrLeoMail)) {
			MailSender::sendMailToPartner('leonardo.ieiri@walltshirts.com', $arrLeoMail, $strStatus, MailSender::LEONARDO, $intReference);
		}
		if(isset($arrNostalgiaMail)) {
			MailSender::sendMailToPartner('camisetas.nostalgia@gmail.com', $arrNostalgiaMail, $strStatus, MailSender::NOSTALGIA, $intReference);
		}
		if(isset($arrDenissniderMail)) {
			MailSender::sendMailToPartner('denis@dsplay.net', $arrDenissniderMail, $strStatus, MailSender::DENISSNIDER, $intReference);
		}
		if(isset($arrJesusManeroMail)) {
			MailSender::sendMailToPartner('loja@jesusmanero.com', $arrJesusManeroMail, $strStatus, MailSender::JESUSMANERO, $intReference);
		}
		if(isset($arrWallMail)) {
			MailSender::sendMailToPartner('atendimento@walltshirts.com', $arrWallMail, $strStatus, MailSender::WALL, $intReference);
		}
	}
}

NotificationPartners::main();

?>