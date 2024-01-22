<?php

require __DIR__ . '/../../vendor/autoload.php';

use He426100\phpautogui\phpautogui;
use He426100\phpautogui\platforms\windows\windows;

$auto = new phpautogui(new windows);
$size = $auto->size();
$position = $auto->position();
echo 'currentX: ' . $position[0], ', currentY: ' . $position[1], ', width: ' . $size[0], ', height: ' . $size[1], PHP_EOL;

$s = microtime(true);
$central = [$size[0] / 2, $size[1] / 2];
$auto->moveTo($central[0], $central[1], 0.5);
assert($auto->position() == $central);
echo 'moved to (', $central[0], ', ', $central[1], '), used ' . (microtime(true) - $s) . ' s', PHP_EOL;
