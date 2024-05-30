<?php

require __DIR__ . '/../../vendor/autoload.php';

use He426100\phpgetwindow\phpgetwindow;
use He426100\phpgetwindow\platforms\windows\kernel32;
use He426100\phpgetwindow\platforms\windows\windows;
use He426100\phpgetwindow\platforms\windows\Win32Window;
use Local\Driver\Win32\Lib\Kernel32 as Kernel32Driver;

// 示例：使用函数
$native = new windows();
$window = new phpgetwindow($native);
echo "活动窗口: " . $window->getActiveWindowTitle() . PHP_EOL, PHP_EOL;
echo "所有窗口:", PHP_EOL;
$windows = $native->getAllWindows();
echo implode(PHP_EOL, $windows), PHP_EOL, PHP_EOL;
echo "所有进程:", PHP_EOL, PHP_EOL;
echo implode(PHP_EOL, (new kernel32(new Kernel32Driver))->getAllProcess());
