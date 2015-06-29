<?php

require_once 'include/conexaoMysql.php';

class Pedidos extends DB {
	
	const WAITING_PAYMENT = 1;
	const PAID = 2;
	
	public function inserirPedido($arrTransactionInfo, $intReference, $strTransactionCode) {		
		if($arrTransactionInfo['status'] == 'WAITING_PAYMENT')
			$intStatus = self::WAITING_PAYMENT;
		elseif($arrTransactionInfo['status'] == 'PAID')
			$intStatus = self::PAID;
		unset($arrTransactionInfo['status']);
		
		//debug::x($arrTransactionInfo);
		foreach($arrTransactionInfo as $id => $value) {
			$strSQL = '
				INSERT INTO pedidos (
					NUM_PEDIDO, 
					COD_PRODUTO, 
					COD_STATUS_PEDIDO, 
					DAT_ATUALIZACAO,
					QUANTIDADE,
					COD_TRANSACAO
					)
				VALUES
					('.$this->escape($intReference).',
					'.$this->escape($id).',
					'.$this->escape($intStatus).',
					NOW(),
					'.$this->escape($arrTransactionInfo[$id]['quantidade']).',
					'.$this->escape($strTransactionCode).'
					)';
				/*
				ON DUPLICATE KEY UPDATE
					COD_STATUS_PEDIDO = '.$this->escape($intStatus).',
					DAT_ATUALIZACAO = NOW();';
				*/	
			$this->executarSQL($strSQL, true);
		}
		$this->desconectarDB();
		//debug::x($strSQL);
	} 
	
}

?>