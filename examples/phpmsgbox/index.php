<?php

require __DIR__ . '/../../vendor/autoload.php';

use He426100\phpmsgbox\phpmsgbox;
use He426100\phpmsgbox\platforms\windows\windows;

$msg = new phpmsgbox(new windows);
$msg->alert('php是最好的语言。', '温馨提示');
echo $msg->confirm('php是最好的语言。', '温馨提示'), PHP_EOL;
