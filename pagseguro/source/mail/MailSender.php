<?php

class MailSender {

	const PARTNER = 'Teste';
	const DESIGNER = 'Teste';
	const BIANCA = 'Bianca Luchesi';
	const MARCOS_CASTRO = 'Marcos Castro';
	const WAITING_PAYMENT = 'Aguardando Pagamento';
	const PAID = 'Pagamento Aprovado';
	const DOBERRO = 'DOBERRO';
	const BRANCOALA = 'Brancoala';
	const LOLGAMES = 'LOL Games';
	const TOLKIEN = 'Tolkien';
	const CAUE = 'Cau� Moura';
	const TIAGO = 'Tiago';
	const OQND = 'O Que N�o Dizer';
	const LA_FENIX = 'La F�nix';
	const LEONARDO = 'Leo';
	const NOSTALGIA = 'Felipe';
	const DENISSNIDER = 'Denis';
	const JESUSMANERO = 'Jesus Manero';
	const WALL = 'Wall';
	
	/**
	 * 
	 * M�todo que envia o e-mail ao Parceiro
	 * @param String $strMailPartner
	 * @param array $arrTransactionInfo
	 */
	public static function sendMailToPartner($strDestinatario, $arrProducts, $strStatus, $strType, $intReference) {
		
		echo 'PRINT ENVIO DE E_MAIL<pre>'; print_r($arrProducts);
		
		$reply = "no-reply@walltshirts.com";
		if($strStatus == self::WAITING_PAYMENT) {
			$assunto = "Novo Pedido | Wall T-Shirts";
			$assuntoCorpo = 'Uma inten��o de pagamento foi gerada atrav�s da Wall T-Shirts.';
		}
		else {
			$assunto = "Pagamento Aprovado | Wall T-Shirts";
			$assuntoCorpo = 'O pagamento do pedido abaixo foi aprovado.';
		}
		$corpo = '
				<html>
				<head>
				<title></title>
				</head>
				<body style="font-family:\"Arial Narrow">
				<img src="https://walltshirt.lojablindada.com/skin/frontend/default/default/logo.png"/>
				<ul>
				<span style="color:#7C0000; font-weight:bold; font-size:13pt">Ol� '.$strType.',<br/></span>
				'.$assuntoCorpo.'<br/><br/>
				<ul>
				<b>STATUS:</b> '.$strStatus.'<br/>
				<b>Reference:</b> '.$intReference.'<br/><br/>
					<table border="0" cellpadding="3" cellspacing="0">
						<tr style="color:#7C0000; font-weight:bold; font-size:10pt">
							<td width="200">
							ITENS DO PEDIDO
							</td>
							<td width="100" align="center">
							QUANTIDADE
							</td>
						</tr>';
		
		if(isset($arrProducts['0'])) {
			//FOREACH caso $arrProducts seja Matriz
			foreach ($arrProducts as $arrProduct) {
				$corpo .= "<tr><td>".$arrProduct['descricao']."</td>";
				$corpo .= '<td align="center">'.$arrProduct['quantidade']."</td></tr>";
			}
		} else {
			//FOREACH caso $arrProducts seja Array
			$corpo .= "<tr><td>".$arrProducts['descricao']."</td>";
			$corpo .= '<td align="center">'.$arrProducts['quantidade']."</td></tr>";
		}
		
		$corpo .= '
				</table>
				</ul>
				</ul>
				<br/><br/><br/>
				<span style="font-size:8pt">
				Este e-mail foi gerado automaticamente pelo <b>System Notification by E-mail Beta</b>, por gentileza n�o responda.
				</span>
				</body>
				</html>
				';
		
		// Este sempre dever� existir para garantir a exibi��o correta dos caracteres
		$headers = "MIME-Version: 1.1\n";
		
		// Para enviar o e-mail em formato texto com codifica��o de caracteres Europeu Ocidental (usado no Brasil)
		//$headers .= "Content-type: text/plain; charset=iso-8859-1\n";
		
		// Para enviar o e-mail em formato HTML com codifica��o de caracteres Europeu Ocidental (usado no Brasil)
		$headers .= "Content-type: text/html; charset=iso-8859-1\n";
		
		// E-mail que receber� a resposta quando se clicar no "Responder" de seu leitor de e-mails
		$headers .= "Reply-To: ".$reply." \n";
		
		//E-mail o qual ser� copiado ocultamente
		$headers .= "Bcc: william.firmiano@walltshirts.com" . "\n";
		
		// E-mail que aparecer� como remetente.
		$headers .= "From: no-reply@walltshirts.com" . "\n";
		
		try {
			mail($strDestinatario, $assunto, $corpo, $headers);
			echo 'enviou!';
		} catch(Exception $e) {echo 'nao enviou';
			echo $e;
		}		
	} 	
}

?>