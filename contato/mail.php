<?php

class Mail {
	
	/**
	 * 
	 * Método que envia o e-mail
	 *
	 * 
	 */
	public static function sendMail() {
	
		echo '<pre>'; print_r($_POST);
		/*
		$from = $_POST['email'];
		$message = $_POST['message'];
		$assunto = $_POST['subject'];
		$name = $_POST['name'];
		$npedido = $_POST['npedido'];
		*/
		
		$from = 'teste@teste.com.br';
		$message = 'essa eh minha mensagem';
		$assunto = 'duvida';
		$name = 'meu nnome';
		$npedido = '69696969';
		
		switch($assunto) {
			case 'site':
				$strDestinatario = 'atendimento@walltshirts.net';
				$assunto = '[Problema no Site]';
				break;
			case 'parceria':
				$strDestinatario = 'atendimento@walltshirts.net';
				$assunto = '[Parceria]';
				break;
			case 'duvida':
				$strDestinatario = 'atendimento@walltshirts.net';
				$assunto = '[Dúvida]';
				break;
			case 'pedido':
				$strDestinatario = 'atendimento@walltshirts.net';
				$assunto = '[Meu Pedido]';
				break;
			case 'reclelog':
				$strDestinatario = 'atendimento@walltshirts.net';
				$assunto = '[Reclamação/Elogio]';
				break;
			case 'outro':
				$strDestinatario = 'atendimento@walltshirts.net';
				$assunto = '[Outros]';
				break;
		}
		
		$assunto.=' Formulário de Contato';
		
		$corpo = '<b>Nome:</b> '.$name.'<br/><b>Nº Pedido:</b> '.$npedido.'<br/>
				<br/><b>Assunto:</b> '.$assunto.'<br/><br/>'.$message;				
		
		
		// Este sempre deverá existir para garantir a exibição correta dos caracteres
		$headers = "MIME-Version: 1.1\n";
		
		// Para enviar o e-mail em formato texto com codificação de caracteres Europeu Ocidental (usado no Brasil)
		$headers .= "Content-type: text/plain; charset=iso-8859-1\r\n";
		
		// Para enviar o e-mail em formato HTML com codificação de caracteres Europeu Ocidental (usado no Brasil)
		//$headers .= "Content-type: text/html; charset=utf-8\n";
		
		// E-mail que receberá a resposta quando se clicar no "Responder" de seu leitor de e-mails
		//$headers .= "Reply-To: ".$from." \n";
		
		//E-mail o qual será copiado ocultamente
		$headers .= "Bcc: atendimento@walltshirts.net" . "\n";
		
		// E-mail que aparecerá como remetente.
		$headers .= "From: atendimento@walltshirts.net\r\n"; // remetente
		
		$headers .= "Return-Path: atendimento@walltshirts.net\r\n"; // return-path
		
		try {
			mail($strDestinatario, $assunto, $corpo, $headers);
			print_r($headers);
			echo 'enviou!';
		} catch(Exception $e) {
			echo 'nao enviou';
			echo $e;
		}		
	}
}

Mail::sendMail();

?>