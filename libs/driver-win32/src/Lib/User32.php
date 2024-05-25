<?php

declare(strict_types=1);

namespace Local\Driver\Win32\Lib;

use Local\Driver\Win32\Library;

/**
 * https://github.com/SerafimArts/Boson/
 * @package Local\Driver\Win32\Lib
 */
final class User32 extends Library
{
    public function __construct()
    {
        parent::__construct('user32.dll');
    }

    public static function getHeader(): string
    {
        return (string) \file_get_contents(__FILE__, offset: __COMPILER_HALT_OFFSET__);
    }
}

__halt_compiler();

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

// add by heyiming, for phpnput
// https://learn.microsoft.com/en-us/windows/win32/winprog/windows-data-types
typedef HANDLE HHOOK;

// ---------------------------------------------------------------------------------------------------------------------
//  Prototypes
// ---------------------------------------------------------------------------------------------------------------------

typedef LRESULT ( *WNDPROC)(HWND,UINT,WPARAM,LPARAM);

// ---------------------------------------------------------------------------------------------------------------------
//  STRUCTURES
// ---------------------------------------------------------------------------------------------------------------------

typedef struct tagWNDCLASSA {
    UINT style;
    WNDPROC lpfnWndProc;
    int cbClsExtra;
    int cbWndExtra;
    HINSTANCE hInstance;
    HICON hIcon;
    HCURSOR hCursor;
    HBRUSH hbrBackground;
    LPCSTR lpszMenuName;
    LPCSTR lpszClassName;
} WNDCLASSA, *PWNDCLASSA, *NPWNDCLASSA, *LPWNDCLASSA;

typedef struct tagWNDCLASSW {
    UINT style;
    WNDPROC lpfnWndProc;
    int cbClsExtra;
    int cbWndExtra;
    HINSTANCE hInstance;
    HICON hIcon;
    HCURSOR hCursor;
    HBRUSH hbrBackground;
    LPCWSTR lpszMenuName;
    LPCWSTR lpszClassName;
} WNDCLASSW, *PWNDCLASSW, *NPWNDCLASSW, *LPWNDCLASSW;

typedef struct tagPOINT {
    LONG x;
    LONG y;
} POINT, *PPOINT, *NPPOINT, *LPPOINT;

typedef struct _POINTL {
    LONG x;
    LONG y;
} POINTL, *PPOINTL;

typedef struct tagMSG {
    HWND hwnd;
    UINT message;
    WPARAM wParam;
    LPARAM lParam;
    DWORD time;
    POINT pt;
} MSG, *PMSG, *NPMSG, *LPMSG;

typedef struct tagRECT {
    LONG left;
    LONG top;
    LONG right;
    LONG bottom;
} RECT, *PRECT, *NPRECT, *LPRECT;

typedef struct tagMONITORINFO {
    DWORD cbSize;
    RECT rcMonitor;
    RECT rcWork;
    DWORD dwFlags;
} MONITORINFO, *LPMONITORINFO;

typedef struct _devicemodeA {
    BYTE dmDeviceName[32];
    WORD dmSpecVersion;
    WORD dmDriverVersion;
    WORD dmSize;
    WORD dmDriverExtra;
    DWORD dmFields;
    __extension__ union {
        __extension__ struct {
            short dmOrientation;
            short dmPaperSize;
            short dmPaperLength;
            short dmPaperWidth;
            short dmScale;
            short dmCopies;
            short dmDefaultSource;
            short dmPrintQuality;
        };
        struct {
            POINTL dmPosition;
            DWORD dmDisplayOrientation;
            DWORD dmDisplayFixedOutput;
        };
    };
    short dmColor;
    short dmDuplex;
    short dmYResolution;
    short dmTTOption;
    short dmCollate;
    BYTE dmFormName[32];
    WORD dmLogPixels;
    DWORD dmBitsPerPel;
    DWORD dmPelsWidth;
    DWORD dmPelsHeight;
    __extension__ union {
        DWORD dmDisplayFlags;
        DWORD dmNup;
    };
    DWORD dmDisplayFrequency;
    DWORD dmICMMethod;
    DWORD dmICMIntent;
    DWORD dmMediaType;
    DWORD dmDitherType;
    DWORD dmReserved1;
    DWORD dmReserved2;
    DWORD dmPanningWidth;
    DWORD dmPanningHeight;
} DEVMODEA, *PDEVMODEA, *NPDEVMODEA, *LPDEVMODEA;

typedef struct _devicemodeW {
    WCHAR dmDeviceName[32];
    WORD dmSpecVersion;
    WORD dmDriverVersion;
    WORD dmSize;
    WORD dmDriverExtra;
    DWORD dmFields;
    __extension__ union {
        __extension__ struct {
            short dmOrientation;
            short dmPaperSize;
            short dmPaperLength;
            short dmPaperWidth;
            short dmScale;
            short dmCopies;
            short dmDefaultSource;
            short dmPrintQuality;
        };
        __extension__ struct {
            POINTL dmPosition;
            DWORD dmDisplayOrientation;
            DWORD dmDisplayFixedOutput;
        };
    };
    short dmColor;
    short dmDuplex;
    short dmYResolution;
    short dmTTOption;
    short dmCollate;
    WCHAR dmFormName[32];
    WORD dmLogPixels;
    DWORD dmBitsPerPel;
    DWORD dmPelsWidth;
    DWORD dmPelsHeight;
    __extension__ union {
        DWORD dmDisplayFlags;
        DWORD dmNup;
    };
    DWORD dmDisplayFrequency;
    DWORD dmICMMethod;
    DWORD dmICMIntent;
    DWORD dmMediaType;
    DWORD dmDitherType;
    DWORD dmReserved1;
    DWORD dmReserved2;
    DWORD dmPanningWidth;
    DWORD dmPanningHeight;
} DEVMODEW, *PDEVMODEW, *NPDEVMODEW, *LPDEVMODEW;

typedef struct tagWINDOWPLACEMENT {
    UINT length;
    UINT flags;
    UINT showCmd;
    POINT ptMinPosition;
    POINT ptMaxPosition;
    RECT rcNormalPosition;
} WINDOWPLACEMENT;

typedef WINDOWPLACEMENT *PWINDOWPLACEMENT,*LPWINDOWPLACEMENT;

typedef struct tagMINMAXINFO {
    POINT ptReserved;
    POINT ptMaxSize;
    POINT ptMaxPosition;
    POINT ptMinTrackSize;
    POINT ptMaxTrackSize;
} MINMAXINFO, *PMINMAXINFO, *LPMINMAXINFO;

typedef struct tagLVITEMA
{
    UINT mask;
    int iItem;
    int iSubItem;
    UINT state;
    UINT stateMask;
    LPSTR pszText;
    int cchTextMax;
    int iImage;
    LPARAM lParam;
    int iIndent;
    int iGroupId;
    UINT cColumns; // tile view columns
    PUINT puColumns;
    int* piColFmt;
    int iGroup; // readonly. only valid for owner data.
} LVITEMA, *LPLVITEMA;

typedef struct tagLVITEMW
{
    UINT mask;
    int iItem;
    int iSubItem;
    UINT state;
    UINT stateMask;
    LPWSTR pszText;
    int cchTextMax;
    int iImage;
    LPARAM lParam;
    int iIndent;
    int iGroupId;
    UINT cColumns; // tile view columns
    PUINT puColumns;
    int* piColFmt;
    int iGroup; // readonly. only valid for owner data.
} LVITEMW, *LPLVITEMW;

typedef struct tagLVCOLUMNA
{
    UINT mask;
    int fmt;
    int cx;
    LPSTR pszText;
    int cchTextMax;
    int iSubItem;
    int iImage;
    int iOrder;
    int cxMin;       // min snap point
    int cxDefault;   // default snap point
    int cxIdeal;     // read only. ideal may not eqaul current width if auto sized (LVS_EX_AUTOSIZECOLUMNS) to a lesser width.
} LVCOLUMNA, *LPLVCOLUMNA;

typedef struct tagLVCOLUMNW
{
    UINT mask;
    int fmt;
    int cx;
    LPWSTR pszText;
    int cchTextMax;
    int iSubItem;
    int iImage;
    int iOrder;
    int cxMin;       // min snap point
    int cxDefault;   // default snap point
    int cxIdeal;     // read only. ideal may not eqaul current width if auto sized (LVS_EX_AUTOSIZECOLUMNS) to a lesser width.
} LVCOLUMNW, *LPLVCOLUMNW;

// ---------------------------------------------------------------------------------------------------------------------
//  API
// ---------------------------------------------------------------------------------------------------------------------

HDC GetDC(HWND hWnd);
int ReleaseDC(HWND hWnd, HDC hDC);

LRESULT DefWindowProcA(HWND hWnd, UINT Msg, WPARAM wParam, LPARAM lParam);
LRESULT DefWindowProcW(HWND hWnd, UINT Msg, WPARAM wParam, LPARAM lParam);

DWORD SetClassLongA(HWND hWnd, int nIndex, LONG dwNewLong);
DWORD SetClassLongW(HWND hWnd, int nIndex, LONG dwNewLong);

ATOM RegisterClassA(const WNDCLASSA *lpWndClass);
ATOM RegisterClassW(const WNDCLASSW *lpWndClass);

BOOL UnregisterClassA(LPCSTR lpClassName, HINSTANCE hInstance);
BOOL UnregisterClassW(LPCWSTR lpClassName, HINSTANCE hInstance);

HMONITOR MonitorFromWindow(HWND hwnd,DWORD dwFlags);

BOOL GetMonitorInfoA(HMONITOR hMonitor, LPMONITORINFO lpmi);
BOOL GetMonitorInfoW(HMONITOR hMonitor, LPMONITORINFO lpmi);

BOOL EnumDisplaySettingsA(LPCSTR lpszDeviceName, DWORD iModeNum, LPDEVMODEA lpDevMode);
BOOL EnumDisplaySettingsW(LPCWSTR lpszDeviceName, DWORD iModeNum, LPDEVMODEW lpDevMode);

HICON LoadIconA(HINSTANCE hInstance, LPCSTR lpIconName);
HICON LoadIconW(HINSTANCE hInstance, LPCWSTR lpIconName);

BOOL DrawIcon(HDC hDC, int X, int Y, HICON hIcon);

BOOL SetWindowTextA(HWND hWnd, LPCSTR lpString);
BOOL SetWindowTextW(HWND hWnd, LPCWSTR lpString);

int GetWindowTextA(HWND hWnd, LPCSTR lpString, int nMaxCount);
int GetWindowTextW(HWND hWnd, LPWSTR lpString, int nMaxCount);

HANDLE LoadImageA(HINSTANCE hInst, LPCSTR name, UINT type, int cx, int cy, UINT fuLoad);
HANDLE LoadImageW(HINSTANCE hInst, LPCWSTR name, UINT type, int cx, int cy, UINT fuLoad);

HWND CreateWindowExA(DWORD dwExStyle, LPCSTR lpClassName, LPCSTR lpWindowName, DWORD dwStyle, int X, int Y, int nWidth, int nHeight, HWND hWndParent, HMENU hMenu, HINSTANCE hInstance, LPVOID lpParam);
HWND CreateWindowExW(DWORD dwExStyle, LPCWSTR lpClassName, LPCWSTR lpWindowName, DWORD dwStyle, int X, int Y, int nWidth, int nHeight, HWND hWndParent, HMENU hMenu, HINSTANCE hInstance, LPVOID lpParam);

int GetSystemMetrics(int nIndex);

BOOL GetClientRect(HWND hWnd, LPRECT lpRect);
BOOL GetWindowRect(HWND hWnd, LPRECT lpRect);
BOOL SetWindowPos(HWND hWnd, HWND hWndInsertAfter, int X, int Y, int cx, int cy, UINT uFlags);
BOOL ShowWindow(HWND hWnd, int nCmdShow);
BOOL GetWindowPlacement (HWND hWnd, WINDOWPLACEMENT *lpwndpl);

LONG GetWindowLongA(HWND hWnd, int nIndex);
LONG GetWindowLongW(HWND hWnd, int nIndex);

LONG SetWindowLongA(HWND hWnd, int nIndex, LONG dwNewLong);
LONG SetWindowLongW(HWND hWnd, int nIndex, LONG dwNewLong);

BOOL UpdateWindow(HWND hWnd);
BOOL DestroyWindow(HWND hWnd);
BOOL DestroyIcon(HICON hIcon);

BOOL PeekMessageA(LPMSG lpMsg, HWND hWnd, UINT wMsgFilterMin, UINT wMsgFilterMax, UINT wRemoveMsg);
BOOL PeekMessageW(LPMSG lpMsg, HWND hWnd, UINT wMsgFilterMin, UINT wMsgFilterMax, UINT wRemoveMsg);

BOOL GetMessageW(LPMSG lpMsg, HWND hWnd, UINT wMsgFilterMin, UINT wMsgFilterMax);
BOOL GetMessageA(LPMSG lpMsg, HWND hWnd, UINT wMsgFilterMin, UINT wMsgFilterMax);

LRESULT DispatchMessageA(const MSG *lpMsg);
LRESULT DispatchMessageW(const MSG *lpMsg);

LRESULT SendMessageA(HWND hWnd, UINT Msg, WPARAM wParam, LPARAM lParam);
LRESULT SendMessageW(HWND hWnd, UINT Msg, WPARAM wParam, LPARAM lParam);

HCURSOR LoadCursorA(HINSTANCE hInstance, LPCSTR lpCursorName);
HCURSOR LoadCursorW(HINSTANCE hInstance, LPCWSTR lpCursorName);

HBRUSH GetSysColorBrush(int nIndex);

BOOL TranslateMessage(const MSG *lpMsg);

// add by heyiming, for phpmsgbox, winuser.h
int MessageBoxA(HWND hWnd, LPCSTR lpText, LPCSTR lpCaption, UINT uType);
int MessageBoxW(HWND hWnd, LPCWSTR lpText, LPCWSTR lpCaption, UINT uType);

// add by heyiming, for phpnput, winuser.h
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

BOOL UnhookWindowsHook(int nCode, void (*)(int, WPARAM, LPARAM));
HHOOK SetWindowsHookExA(int idHook, void (*)(int, WPARAM, LPARAM), HINSTANCE hmod, DWORD dwThreadId);
HHOOK SetWindowsHookExW(int idHook, void (*)(int, WPARAM, LPARAM), HINSTANCE hmod, DWORD dwThreadId);
BOOL UnhookWindowsHookEx(HANDLE hhk);
LRESULT CallNextHookEx(HHOOK hhk, int nCode, WPARAM wParam, LPARAM lParam);

// add by heyiming, for phpautogui, winuser.h
HWND GetDesktopWindow();
BOOL GetCursorPos(POINT *lpPoint);
BOOL SetCursorPos(int X, int Y);
SHORT VkKeyScanA(CHAR ch);

void keybd_event(BYTE bVk, BYTE bScan, DWORD dwFlags, ULONG_PTR dwExtraInfo);
void mouse_event(DWORD dwFlags, DWORD dx, DWORD dy, DWORD dwData, ULONG_PTR dwExtraInfo);

// add by heyiming, for phpgetwindow, winuser.h
int GetWindowTextLengthA(HWND hWnd);
int GetWindowTextLengthW(HWND hWnd);
BOOL PostMessageA(HWND hWnd, UINT Msg, WPARAM wParam, LPARAM lParam);
BOOL PostMessageW(HWND hWnd, UINT Msg, WPARAM wParam, LPARAM lParam);
HWND GetForegroundWindow();
BOOL SetForegroundWindow(HWND hWnd);
BOOL AllowSetForegroundWindow(DWORD dwProcessId);
BOOL LockSetForegroundWindow(UINT uLockCode);
BOOL EnumWindows(void (*)(HWND, LPARAM), LPARAM);
BOOL IsIconic(HWND hWnd);
BOOL IsZoomed(HWND hWnd);
BOOL IsWindowVisible(HWND hWnd);

// add by heyiming, for phpwindow
typedef struct tagPAINTSTRUCT {
    HDC  hdc;
    BOOL fErase;
    RECT rcPaint;
    BOOL fRestore;
    BOOL fIncUpdate;
    BYTE rgbReserved[32];
} PAINTSTRUCT, *PPAINTSTRUCT, *NPPAINTSTRUCT, *LPPAINTSTRUCT;

HDC BeginPaint(
    HWND          hWnd,
    LPPAINTSTRUCT lpPaint
);

int DrawTextW(
    HDC     hdc,
    LPCWSTR lpchText,
    int     cchText,
    LPRECT  lprc,
    UINT    format
);

BOOL EndPaint(
    HWND              hWnd,
    const PAINTSTRUCT *lpPaint
);
