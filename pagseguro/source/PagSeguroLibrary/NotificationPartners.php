<?php

require_once "../PagSeguroLibrary/PagSeguroLibrary.php";
require_once "../examples/NotificationListener.php";
require_once "../examples/searchTransactionByCode.php";
require_once '../mail/MailSender.php';

/**
 * 
 * Classe que notifica os clientes suas respectivas transa��es
 * @author Will
 *
 */
class NotificationPartners {
	/**
	 * 
	 * M�todo main da classe
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
		LogPagSeguro::info('Tipo de Transa��o:');
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
		
		echo 'PRINT RETORNO PAGSEGURO<pre>'; print_r($arrTransactionInfo);
		
		$arrProductsMarcosCastro = array('521', '558', '518');
		$arrProductsDesceALetra = array('612', '605', '577', '619');
		$arrProductsLaFenix = array('647', '641');
		$arrProductsBebidaLiberada = array();
		$arrProductsBianca = array('694', '682', '471');
		$arrProductsTolkien = array();
		
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
			}
			$arrPieces = explode('_', $id);
			$intProduct = $arrPieces[0];
	
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
			}

		}

		//Envia e-mail para o parceiro		
		if(isset($arrMarcosCastroMail)) {
			MailSender::sendMailToPartner('joystickviolao@walltshirts.com', $arrMarcosCastroMail, $strStatus, MailSender::PARTNER, $intReference);
		} 
		if(isset($arrLaFenixMail)) {
			MailSender::sendMailToPartner('william.firmiano@walltshirts.com', $arrLaFenixMail, $strStatus, MailSender::PARTNER, $intReference);
		}
		if(isset($arrDesceALetraMail)) {
			MailSender::sendMailToPartner('descealetra@walltshirts.com', $arrDesceALetraMail, $strStatus, MailSender::PARTNER, $intReference);
		}
		if(isset($arrBiancaMail)) {
			MailSender::sendMailToPartner('biancalucchesi@walltshirts.com', $arrBiancaMail, $strStatus, MailSender::BIANCA, $intReference);
		}	
	}
}

NotificationPartners::main();

?>