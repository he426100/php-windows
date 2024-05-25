<?php

/**
 * 先按 ESC，再按 CTRL+C 可退出脚本
 * 这个脚本还没写完，原版pynput用了thread，不知道怎么模拟
 */

require __DIR__ . '/../../vendor/autoload.php';

use FFI\CData;
use Local\Driver\Win32\Lib\Ole32;
use Local\Driver\Win32\Lib\User32;
use He426100\phpautogui\platforms\windows\windows;

final class Application
{
    private const WH_MOUSE_LL = 14;
    private const WH_KEYBOARD_LL = 13;
    private const HC_ACTION = 0;
    private const WM_KEYDOWN = 0x0100;

    private const WM_LBUTTONDOWN = 0x0201;
    private const WM_LBUTTONUP = 0x0202;
    private const WM_MBUTTONDOWN = 0x0207;
    private const WM_MBUTTONUP = 0x0208;
    private const WM_MOUSEMOVE = 0x0200;
    private const WM_MOUSEWHEEL = 0x020A;
    private const WM_MOUSEHWHEEL = 0x020E;
    private const WM_RBUTTONDOWN = 0x0204;
    private const WM_RBUTTONUP = 0x0205;
    private const WM_XBUTTONDOWN = 0x20B;
    private const WM_XBUTTONUP = 0x20C;

    private const MK_XBUTTON1 = 0x0020;
    private const MK_XBUTTON2 = 0x0040;

    private const XBUTTON1 = 1;
    private const XBUTTON2 = 2;
    private const COINIT_APARTMENTTHREADED = 0x02;
    private bool $isRunning = false;
    private bool $isListening = false;
    private CData $message;
    private CData $mouseHook;
    private CData $keyboardHook;
    private windows $windows;
    private Ole32 $ole32;
    private User32 $user32;

    public function __construct()
    {
        $this->windows = new windows;
        $this->ole32 = new Ole32();
        $this->user32 = new User32();
        $this->message = $this->createMessage();
        $this->ole32->CoInitializeEx(null, self::COINIT_APARTMENTTHREADED);
    }

    public function run(): void
    {
        if ($this->isRunning === true) {
            return;
        }

        $this->isRunning = true;

        if (!$this->isListening) {
            $this->isListening = true;

            $this->listenMouse();
            // echo '开始监听鼠标事件...', PHP_EOL;

            $this->listenKeyboard();
            // echo '开始监听键盘事件...', PHP_EOL;
        }

        // @phpstan-ignore-next-line
        while ($this->isRunning) {
            if ($this->user32->GetMessageW($this->message, null, 0, 0)) {
                $this->user32->TranslateMessage($this->message);
                $this->user32->DispatchMessageW($this->message);
            }
            \usleep(1);
        }
    }

    public function isRunning(): bool
    {
        return $this->isRunning;
    }

    public function isListening(): bool
    {
        return $this->isListening;
    }

    private function listenMouse(): void
    {
        // 安装鼠标钩子
        $this->mouseHook = $this->user32->SetWindowsHookExW(self::WH_MOUSE_LL, function ($nCode, $wParam, $lParam) {
            // 如果鼠标事件是WM_MOUSEMOVE或WM_LBUTTONDOWN，进行相应处理
            if ($nCode >= 0 && ($wParam == self::WM_MOUSEMOVE || $wParam == self::WM_LBUTTONDOWN)) {
                $mouseStruct = $this->user32->cast("PMOUSEHOOKSTRUCT", $lParam);
                $x = $mouseStruct->pt->x;
                $y = $mouseStruct->pt->y;

                if ($wParam == self::WM_MOUSEMOVE) {
                    echo "鼠标移动到坐标：{$x}, {$y}\n";
                } elseif ($wParam == self::WM_LBUTTONDOWN) {
                    echo "鼠标点击坐标：{$x}, {$y}\n";
                }
            }

            // 调用下一个钩子或默认过程
            return $this->user32->CallNextHookEx(null, $nCode, $wParam, $lParam);
        }, null, 0);
    }

    private function listenKeyboard(): void
    {
        $this->keyboardHook = $this->user32->SetWindowsHookExW(self::WH_KEYBOARD_LL, function ($nCode, $wParam, $lParam) {
            if ($nCode == 0 && $wParam == self::WM_KEYDOWN) {
                $kbdStruct = $this->user32->cast('PKBDLLHOOKSTRUCT', $lParam);
                $keyCode = $kbdStruct->vkCode;
        
                if ($keyCode == windows::VK_ESCAPE) { // Esc键
                    $this->stopListen();
                } else {
                    echo 'pressed: ', $this->windows->getKeyName($keyCode), PHP_EOL;
                }
            }
        
            return $this->user32->CallNextHookEx(null, $nCode, $wParam, $lParam);
        }, null, 0);
    }

    private function createMessage(): CData
    {
        // @phpstan-ignore-next-line
        return \FFI::addr($this->user32->new('MSG'));
    }

    private function stopListen(): void
    {
        $this->user32->UnhookWindowsHookEx($this->mouseHook);
        $this->user32->UnhookWindowsHookEx($this->keyboardHook);
        $this->isListening = false;
    }

    public function stop(): void
    {
        if ($this->isRunning === false) {
            return;
        }
        if ($this->isListening) {
            $this->stopListen();
        }
        $this->isRunning = false;
    }

    public function __destruct()
    {
        $this->ole32->CoUninitialize();
    }
}

(new Application)->run();
