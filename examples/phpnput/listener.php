<?php
/**
 * 先按 ESC，再按 CTRL+C 可退出脚本
 * 这个脚本还没写完，原版pynput用了thread，不知道怎么模拟
 */

require __DIR__ . '/../../vendor/autoload.php';

use He426100\phpautogui\platforms\windows\windows;

$windows = new windows;

$ffi = FFI::cdef("
    // wtypesbase.h
    typedef void                *PVOID;
    typedef void                *LPVOID;
    typedef float               FLOAT;

    // intsafe.h

    typedef int64_t             INT_PTR;
    typedef uint64_t            UINT_PTR;
    typedef int64_t             LONG_PTR;
    typedef uint64_t            ULONG_PTR;

    typedef long                BOOL;
    typedef char                CHAR;
    typedef signed char         INT8;
    typedef unsigned char       UCHAR;
    typedef unsigned char       UINT8;
    typedef unsigned char       BYTE;
    typedef short               SHORT;
    typedef signed short        INT16;
    typedef unsigned short      USHORT;
    typedef unsigned short      UINT16;
    typedef unsigned short      WORD;
    typedef int                 INT;
    typedef signed int          INT32;
    typedef unsigned int        UINT;
    typedef unsigned int        UINT32;
    typedef long                LONG;
    typedef unsigned long       ULONG;
    typedef unsigned long       DWORD;
    typedef int64_t             LONGLONG;
    typedef int64_t             LONG64;
    typedef int64_t             INT64;
    typedef uint64_t            ULONGLONG;
    typedef uint64_t            DWORDLONG;
    typedef uint64_t            ULONG64;
    typedef uint64_t            DWORD64;
    typedef uint64_t            UINT64;

    typedef UINT                *PUINT;

    // wtypes.h

    typedef char                CHAR;
    typedef CHAR                *LPSTR;
    typedef const CHAR          *LPCSTR;

    typedef unsigned short      WCHAR;
    typedef WCHAR               TCHAR;

    typedef WCHAR               *LPWSTR;
    typedef TCHAR               *LPTSTR;
    typedef const WCHAR         *LPCWSTR;
    typedef const TCHAR         *LPCTSTR;

    typedef UINT_PTR            WPARAM;
    typedef LONG_PTR            LPARAM;
    typedef LONG_PTR            LRESULT;

    typedef void*               HANDLE;

    typedef intptr_t            HWND;

    typedef UINT_PTR            HMENU;
    typedef HANDLE              HACCEL;
    typedef HANDLE              HBRUSH;
    typedef HANDLE              HFONT;
    typedef HANDLE              HDC;
    typedef HANDLE              HICON;
    typedef HANDLE              HRGN;
    typedef HANDLE              HMONITOR;
    typedef HANDLE              HMODULE;
    typedef HANDLE              HINSTANCE;
    typedef HANDLE              HTASK;
    typedef HANDLE              HKEY;
    typedef HANDLE              HDESK;
    typedef HANDLE              HMF;
    typedef HANDLE              HEMF;
    typedef HANDLE              HPEN;
    typedef HANDLE              HRSRC;
    typedef HANDLE              HSTR;
    typedef HANDLE              HWINSTA;
    typedef HANDLE              HKL;
    typedef HANDLE              HGDIOBJ;
    typedef HANDLE              HDWP;
    typedef HICON               HCURSOR;

    typedef LONG                HRESULT;

    // dimm.h

    typedef WORD ATOM;
    
    // https://learn.microsoft.com/en-us/windows/win32/winprog/windows-data-types
    typedef HANDLE HHOOK;

    typedef struct tagPOINT {
        LONG x;
        LONG y;
    } POINT, *PPOINT, *NPPOINT, *LPPOINT;

    typedef struct tagMSG {
        HWND hwnd;
        UINT message;
        WPARAM wParam;
        LPARAM lParam;
        DWORD time;
        POINT pt;
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
    
    BOOL GetMessageW(LPMSG lpMsg, HWND hWnd, UINT wMsgFilterMin, UINT wMsgFilterMax);
    
    BOOL TranslateMessage(const MSG *lpMsg);

    LRESULT DispatchMessageA(const MSG *lpMsg);
    LRESULT DispatchMessageW(const MSG *lpMsg);
", "user32.dll");

$ole32 = FFI::cdef("
    typedef void *LPVOID;
    typedef unsigned long DWORD;
    typedef long LONG;
    typedef LONG HRESULT;

    HRESULT CoInitializeEx(LPVOID pvReserved, DWORD dwCoInit);
    void CoUninitialize(void);
", "Ole32.dll");

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

const COINIT_APARTMENTTHREADED = 0x02;

$ole32->CoInitializeEx(null, COINIT_APARTMENTTHREADED);

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

$keyboardHook = $ffi->SetWindowsHookExW(WH_KEYBOARD_LL, function ($nCode, $wParam, $lParam) use (&$keyboardHook, &$mouseHook, &$listen, $windows) {
    global $ffi;

    if ($nCode == 0 && $wParam == WM_KEYDOWN) {
        $kbdStruct = $ffi->cast('PKBDLLHOOKSTRUCT', $lParam);
        $keyCode = $kbdStruct->vkCode;

        if ($keyCode == windows::VK_ESCAPE) { // Esc键
            $listen = false;
            $ffi->UnhookWindowsHookEx($mouseHook);
            $ffi->UnhookWindowsHookEx($keyboardHook);
        } else {
            echo 'pressed: ', $windows->getKeyName($keyCode), PHP_EOL;
        }
    }

    return $ffi->CallNextHookEx(null, $nCode, $wParam, $lParam);
}, null, 0);

$msg = FFI::addr($ffi->new("MSG"));
while ($listen) {
    if ($ffi->GetMessageW($msg, null, 0, 0)) {
        $ffi->TranslateMessage($msg);
        $ffi->DispatchMessageW($msg);
    }
    \usleep(1);
}

$ffi->UnhookWindowsHookEx($mouseHook);
$ffi->UnhookWindowsHookEx($keyboardHook);
$ole32->CoUninitialize();
