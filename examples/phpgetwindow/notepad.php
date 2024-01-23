<?php

require __DIR__ . '/../../vendor/autoload.php';

use He426100\phpautogui\phpautogui;
use He426100\phpautogui\platforms\windows\windows as phpautogui_windows;
use He426100\phpgetwindow\phpgetwindow;
use He426100\phpgetwindow\platforms\windows\windows as phpgetwindow_windows;

$auto = new phpautogui(new phpautogui_windows);
$auto->press('win');
usleep(0.2 * 1000_000);

$auto->typewrite('notepad.exe', 0.1);
usleep(0.2 * 1000_000);

$auto->press('enter');
usleep(2 * 1000_000);

$native = new phpgetwindow_windows;
$window = new phpgetwindow($native);
$noteWin = $window->getWindowsWithTitle('无标题 - Notepad');
assert(count($noteWin) == 1);

$resolution = $native->resolution();
$noteWin[0]->resizeTo((int)($resolution[0] / 2), (int)($resolution[1] / 2));
$noteWin[0]->activate();

// 移动窗口到屏幕中心
$noteWin[0]->moveTo((int)($resolution[0] / 4), (int)($resolution[1] / 4));

$auto->typewrite('PHP is the best language.', 0.1);
