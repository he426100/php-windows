<?php
/**
 * 先按 ESC，再按 CTRL+C 可退出脚本
 * 神奇，必须要下面这行require，否则移动鼠标时坐标会变但鼠标是不动的
 * 神奇，开启swow扩展的php.exe执行这个脚本，鼠标移动时坐标会变但鼠标是不动的
 * 神奇，静态php带swow扩展不需要下面这行require
 * 这个脚本还没写完，原版pynput用了thread，不知道怎么模拟
 */
require __DIR__ . '/../../vendor/autoload.php';

$ffi = FFI::cdef("
    typedef long LONG_PTR;
    typedef int INT;
    typedef unsigned int UINT;
    typedef unsigned int DWORD;
    typedef unsigned long ULONG_PTR;
    typedef void* HANDLE;
    typedef LONG_PTR LRESULT;
    typedef HANDLE HHOOK;
    typedef HANDLE HINSTANCE;
    typedef unsigned long WPARAM;
    typedef void* LPARAM;
    typedef int BOOL;
    typedef void* HWND;
    typedef void* HOOKPROC;
    
    typedef struct tagPOINT
    {
        long x;
        long y;
    } POINT, *PPOINT;

    typedef struct tagMSG {
        HWND   hwnd;
        UINT   message;
        WPARAM wParam;
        LPARAM lParam;
        DWORD  time;
        POINT  pt;
        DWORD  lPrivate;
    } MSG, *PMSG, *NPMSG, *LPMSG;

    typedef struct tagMOUSEHOOKSTRUCT {
        POINT     pt;
        HWND      hwnd;
        UINT      wHitTestCode;
        ULONG_PTR dwExtraInfo;
    } MOUSEHOOKSTRUCT, *LPMOUSEHOOKSTRUCT, *PMOUSEHOOKSTRUCT;

    typedef struct tagMSLLHOOKSTRUCT {
        POINT     pt;
        DWORD     mouseData;
        DWORD     flags;
        DWORD     time;
        ULONG_PTR dwExtraInfo;
    } MSLLHOOKSTRUCT, *LPMSLLHOOKSTRUCT, *PMSLLHOOKSTRUCT;

    typedef struct tagKBDLLHOOKSTRUCT {
        DWORD     vkCode;
        DWORD     scanCode;
        DWORD     flags;
        DWORD     time;
        ULONG_PTR dwExtraInfo;
    } KBDLLHOOKSTRUCT, *LPKBDLLHOOKSTRUCT, *PKBDLLHOOKSTRUCT;
    
    HHOOK SetWindowsHookExW(
        int       idHook,
        void (*)(int, WPARAM, LPARAM),
        HINSTANCE hmod,
        DWORD     dwThreadId
    );
    
    LRESULT CallNextHookEx(
        HHOOK  hhk,
        int    nCode,
        WPARAM wParam,
        LPARAM lParam
    );
    
    BOOL UnhookWindowsHookEx(
        HANDLE hhk
    );
    
    BOOL GetMessageW(
        LPMSG lpMsg,
        HWND  hWnd,
        UINT  wMsgFilterMin,
        UINT  wMsgFilterMax
    );
    
    BOOL PeekMessageW(
        LPMSG lpMsg,
        HWND  hWnd,
        UINT  wMsgFilterMin,
        UINT  wMsgFilterMax,
        UINT  wRemoveMsg
    );
", "user32.dll");

// 定义全局变量来存储钩子句柄
$mouseHook = null;
$keyboardHook = null;
$listen = true;

const WH_MOUSE_LL = 14;
const WH_KEYBOARD_LL = 13;
const HC_ACTION = 0;
const WM_KEYDOWN = 0x0100;

const WM_LBUTTONDOWN = 0x0201;
const WM_LBUTTONUP = 0x0202;
const WM_MBUTTONDOWN = 0x0207;
const WM_MBUTTONUP = 0x0208;
const WM_MOUSEMOVE = 0x0200;
const WM_MOUSEWHEEL = 0x020A;
const WM_MOUSEHWHEEL = 0x020E;
const WM_RBUTTONDOWN = 0x0204;
const WM_RBUTTONUP = 0x0205;
const WM_XBUTTONDOWN = 0x20B;
const WM_XBUTTONUP = 0x20C;

const MK_XBUTTON1 = 0x0020;
const MK_XBUTTON2 = 0x0040;

const XBUTTON1 = 1;
const XBUTTON2 = 2;

// 安装鼠标钩子
$mouseHook = $ffi->SetWindowsHookExW(WH_MOUSE_LL, function ($nCode, $wParam, $lParam) {
    global $ffi;

    // 如果鼠标事件是WM_MOUSEMOVE或WM_LBUTTONDOWN，进行相应处理
    if ($nCode >= 0 && ($wParam == 0x200 || $wParam == 0x201)) {
        $mouseStruct = $ffi->cast("PMOUSEHOOKSTRUCT", $lParam);
        $x = $mouseStruct->pt->x;
        $y = $mouseStruct->pt->y;

        if ($wParam == 0x200) {
            echo "鼠标移动到坐标：{$x}, {$y}\n";
        } elseif ($wParam == 0x201) {
            echo "鼠标点击坐标：{$x}, {$y}\n";
        }
    }

    // 调用下一个钩子或默认过程
    return $ffi->CallNextHookEx(null, $nCode, $wParam, $lParam);
}, null, 0);

$keyboardHook = $ffi->SetWindowsHookExW(WH_KEYBOARD_LL, function ($nCode, $wParam, $lParam) use (&$keyboardHook, &$mouseHook, &$listen) {
    global $ffi;

    if ($nCode == 0 && $wParam == WM_KEYDOWN) {
        $kbdStruct = $ffi->cast('PKBDLLHOOKSTRUCT', $lParam);
        $keyCode = $kbdStruct->vkCode;

        if ($keyCode == 27) { // Esc键
            $listen = false;
            $ffi->UnhookWindowsHookEx($mouseHook);
            $ffi->UnhookWindowsHookEx($keyboardHook);
        } else {
            echo 'pressed: ', $keyCode, PHP_EOL;
        }
    }

    return $ffi->CallNextHookEx(null, $nCode, $wParam, $lParam);
}, null, 0);

while ($listen) {
    $msg = $ffi->new("MSG");
    while ($ffi->GetMessageW(FFI::addr($msg), null, 0, 0) !== 0) {
        echo '我执行了1, ', $listen, PHP_EOL;
        $ffi->PeekMessageW($msg);
        echo '我执行了2, ', $listen, PHP_EOL;
    }
    echo '我执行了3, ', $listen, PHP_EOL;
}

$ffi->UnhookWindowsHookEx($mouseHook);
$ffi->UnhookWindowsHookEx($keyboardHook);
