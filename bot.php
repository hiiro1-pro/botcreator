<?php

// Bot criado por @empresajbhosts código original para o @criarsshJBH_bot

date_default_timezone_set ('America/Sao_Paulo'); // define timestamp padrão

// Incluindo arquivos nescessários
include __DIR__.'/Telegram.php';

if (!file_exists('dadosBot.ini')){

	echo "Faça a instalação do bot antes!";
	exit;

}

$textoMsg=json_decode (file_get_contents('textos.json'));
$iniParse=parse_ini_file('dadosBot.ini');

$ip=$iniParse ['ip'];
$token=$iniParse ['token'];
$limite=$iniParse ['limite'];

define ('TOKEN', $token); // token do bot criado no @botfather

// Instancia das classes
$tlg=new Telegram (TOKEN);
$redis=new Redis ();
$redis->connect ('localhost', 6379); //redis usando porta padrão

// BLOCO USADO EM LONG POLLING

while (true){

$updates=$tlg->getUpdates();

for ($i=0; $i < $tlg->UpdateCount(); $i++){

$tlg->serveUpdate($i);

switch ($tlg->Text ()){

	case '/start':

	$tlg->sendMessage ([
		'chat_id' => $tlg->ChatID (),
		'text' => $textoMsg->start,
		'parse_mode' => 'html',
		'reply_markup' => tlg->buildInlineKeyBoard(['🇨🇦 SSH Gratis BR 🇨🇦', null, '/sshgratis'
			$tlg->buildInlineKeyboardButton (['🚀 Payload & ssl 🚀', null, '/payload'
			$tlg->buildInlineKeyboardButton (['😎 Sobre Nos 😎', null, '/sobre'
           ]);
		]);
	]);

	break;
	case '/sobre':

	$tlg->sendMessage ([
		'chat_id' => $tlg->ChatID (),
		'text' => 'Canal original @gamerfree1 por @empresajbhosts'
	]);

	break;
	case '/payload':

	$tlg->sendMessage ([
		'chat_id' => $tlg->ChatID (),
		'text' => $textoMsg->payload,
		'parse_mode' => 'hmtl',
		'reply_markup' => $tlg->buildInlineKeyBoard(['✈️ Payload #VIVO ✈️', null, '/vivopay'
			$tlg->buildInlineKeyboardButton (['🛸 Ssl #Claro 🛸', null, '/sslclaro'
		]);
	]);

	break;
	case '/vivopay':

	$tlg->sendMessage ([
		'chat_id' => $tlg->ChatID (),
		'text' => 'teste payload'
	]);

	break;
	case '/sslclaro':

	$tlg->sendMessage ([
		'chat_id' => $tlg->ChatID (),
		'text' => 'teste'
	]);

	break;
	case '/total':

	$tlg->sendMessage ([
		'chat_id' => $tlg->ChatID (),
		'text' => 'Foram criadas <b>'.$redis->dbSize ().'</b> contas nas ultimas 24h',
		'parse_mode' => 'html'
	]);

	break;
	case '/sshgratis':

	$tlg->answerCallbackQuery ([
	'callback_query_id' => $tlg->Callback_ID()
	]);

	if ($redis->dbSize () == $limite){

		$textoSSH=$textoMsg->sshgratis->limite;

	} elseif ($redis->exists ($tlg->UserID ())){

		$textoSSH=$textoMsg->sshgratis->nao_criado;

	} else {

		$usuario=substr (str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, 6);
		$senha=mt_rand(11111, 999999);

		exec ('./gerarusuario.sh '.$usuario.' '.$senha.' 1 1');

		$textoSSH="🇨🇦Conta SSH criada ;)\r\n\r\n<b>Servidor:</b> <code>".$ip."</code>\r\n<b>Usuario:</b> <code>".$usuario."</code>\r\n<b>Senha:</b> <code>".$senha."</code>\r\n<b>Logins:</b> 1\r\n<b>Validade:</b>\r\n<b>Squid: 80</b>\r\n<b>SSL: 443</b>".date ('d/m', strtotime('+1 day'))."\r\n\r\n🤙 By: @empresajbhosts";

		$redis->setex ($tlg->UserID (), 43200, 'true'); //define registro para ser guardado por 12h

	}

	$tlg->sendMessage ([
		'chat_id' => $tlg->ChatID (),
		'text' => $textoSSH,
		'parse_mode' => 'html'
	]);

	break;

}

}}