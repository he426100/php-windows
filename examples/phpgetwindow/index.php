<?php

require __DIR__ . '/../../vendor/autoload.php';

use He426100\phpgetwindow\phpgetwindow;
use He426100\phpgetwindow\platforms\windows\windows;

// 示例：使用函数
$native = new windows;
$window = new phpgetwindow($native);
echo "活动窗口标题: " . $window->getActiveWindowTitle() . "\n";
echo "所有窗口标题:\n";
$hWndsAndTitles = $native->listAllTitles();
print_r(array_column($hWndsAndTitles, 1, 0));
