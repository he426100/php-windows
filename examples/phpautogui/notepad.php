<?php

require __DIR__ . '/../../vendor/autoload.php';

use He426100\phpautogui\phpautogui;
use He426100\phpautogui\platforms\windows\windows;

$auto = new phpautogui(new windows);
$auto->press('win');
usleep(0.2 * 1000_000);

$auto->typewrite('notepad.exe', 0.1);
usleep(0.2 * 1000_000);

$auto->press('enter');
usleep(2 * 1000_000);

$auto->typewrite('PHP is the best language.', 0.1);
