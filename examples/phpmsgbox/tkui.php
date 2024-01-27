<?php

require __DIR__ . '/../../vendor/autoload.php';

use Tkui\Application;
use Tkui\DotEnv;
use Tkui\TclTk\TkAppFactory;
use Tkui\Windows\MainWindow;
use Tkui\Dialogs\MessageBox;
use Tkui\TclTk\TkWindowManager;

final class tkui extends MainWindow
{
    const APP_NAME = 'tkui';

    protected Application $app;
    private MessageBox $dialog;

    public function __construct($env = null)
    {
        $factory = new TkAppFactory(self::APP_NAME);
        $this->app = $factory->createFromEnvironment($env ?: DotEnv::create(dirname(__DIR__)));
        parent::__construct($this->app, 'MessageBox');

        // 最小化，显示在任务栏
        $this->state = TkWindowManager::STATE_ICONIC;
        // 隐藏
        // $this->state = TkWindowManager::STATE_WITHDRAWN;
    }

    public function alert($text, $title, $button = MessageBox::TYPE_OK, $icon = MessageBox::ICON_INFO)
    {
        $this->dialog = new MessageBox($this, $title, $text, [
            'type' => $button,
            'icon' => $icon,
        ]);
        return $this->dialog->showModal();
    }

    public function confirm($text, $title, $buttons = MessageBox::TYPE_OK_CANCEL, $icon = MessageBox::ICON_QUESTION)
    {
        $this->dialog = new MessageBox($this, $title, $text, [
            'type' => $buttons,
            'icon' => $icon,
        ]);
        return $this->dialog->showModal();
    }

    public function run(): void
    {
        $this->app->run();
    }
}

/**
 * 在本项目根目录下创建.env，写入以下内容：
 * WINDOWS_LIB_TCL=D:/ActiveTcl/bin/tcl86t.dll
 * WINDOWS_LIB_TK=D:/ActiveTcl/bin/tk86t.dll
 * 
 * 获取tcl/tk教程：
 * 1. 使用 skoro/tkui 项目tools内的tcltk-vs.zip，或者按BUILD-WIN编译
 * 2. 按 https://tkdocs.com/tutorial/install.html#installwin 安装 ActiveTcl
 */
$ui = new tkui(DotEnv::create(dirname(__DIR__, 2)));
$ui->alert('php是最好的语言。', '温馨提示');
echo $ui->confirm('php是最好的语言。', '温馨提示'), PHP_EOL;
// 下面两行可忽略：不写 run 就不需要 close，没有事件循环它会自然结束
$ui->close();
$ui->run();
