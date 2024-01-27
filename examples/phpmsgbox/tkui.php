<?php

require __DIR__ . '/../../vendor/autoload.php';

use Tkui\DotEnv;
use Tkui\TclTk\TkAppFactory;
use Tkui\Windows\MainWindow;
use Tkui\Dialogs\MessageBox;
use Tkui\TclTk\TkWindowManager;

/**
 * 在本项目根目录下创建.env，写入以下内容：
 * WINDOWS_LIB_TCL=D:/ActiveTcl/bin/tcl86t.dll
 * WINDOWS_LIB_TK=D:/ActiveTcl/bin/tk86t.dll
 * 
 * 获取tcl/tk教程：
 * 1. 使用 skoro/tkui 项目tools内的tcltk-vs.zip，或者按BUILD-WIN编译
 * 2. 按 https://tkdocs.com/tutorial/install.html#installwin 安装 ActiveTcl
 */
$env = DotEnv::create(dirname(__DIR__, 2));
$factory = new TkAppFactory('tkui');
$app = $factory->createFromEnvironment($env);
$window = new MainWindow($app, 'tkui');
// 最小化，显示在任务栏
$window->state = TkWindowManager::STATE_ICONIC;

(new MessageBox($window, '温馨提示', 'php是最好的语言。', [
    'type' => MessageBox::TYPE_OK,
    'icon' => MessageBox::ICON_INFO,
]))->showModal();

(new MessageBox($window, '温馨提示', 'php是最好的语言。', [
    'type' => MessageBox::TYPE_OK_CANCEL,
    'icon' => MessageBox::ICON_QUESTION,
]))->showModal();

// $app->close();
// $app->run();
