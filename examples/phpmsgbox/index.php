<?php

require __DIR__ . '/../../vendor/autoload.php';

use He426100\phpmsgbox\phpmsgbox;
use He426100\phpmsgbox\platforms\windows\windows;

$msg = new phpmsgbox(new windows);
$msg->alert('php is the best language.', mb_convert_encoding('温馨提示', 'GBK', 'UTF-8'));
echo $msg->confirm('php is the best language.', mb_convert_encoding('温馨提示', 'GBK', 'UTF-8')), PHP_EOL;
