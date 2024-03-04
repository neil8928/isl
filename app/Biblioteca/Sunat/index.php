<?php

//use sunatsol\Sunat\Bot\Bot;
use sunatsol\Sunat\Bot\Menu;
//use sunatsol\Sunat\Bot\Model\ClaveSol;
include("Bot\Model\ClaveSol.php");
include("Bot\Bot.php");

$user = new ClaveSol();
$user->ruc = '20479729141';
$user->user = 'SYST1NDU';
$user->password = '1ndu4m3r1c@';




$bot = new Bot($user);

print_r($bot);
exit();

$bot->login();
$bot->navigate([Menu::CONSULTA_SOL_FACTURA]);
$xml = $bot->getSeeXml($user->ruc, 'E001', '38689');

file_put_contents('factura.xml', $xml);



