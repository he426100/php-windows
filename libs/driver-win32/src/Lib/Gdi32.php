<?php

declare(strict_types=1);

namespace Local\Driver\Win32\Lib;

use Local\Driver\Win32\Library;

/**
 * https://github.com/SerafimArts/Boson/
 * @package Local\Driver\Win32\Lib
 */
final class Gdi32 extends Library
{
    public function __construct()
    {
        parent::__construct('gdi32.dll');
    }

    public static function getHeader(): string
    {
        return (string) \file_get_contents(__FILE__, offset: __COMPILER_HALT_OFFSET__);
    }
}

__halt_compiler();

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

typedef char                CHAR;
typedef CHAR                *LPSTR;
typedef const CHAR          *LPCSTR;

typedef unsigned short      WCHAR;
typedef WCHAR               TCHAR;

typedef WCHAR               *LPWSTR;
typedef TCHAR               *LPTSTR;
typedef const WCHAR         *LPCWSTR;
typedef const TCHAR         *LPCTSTR;

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

typedef DWORD COLORREF;
typedef DWORD* LPCOLORREF;

typedef struct tagRECT {
    LONG left;
    LONG top;
    LONG right;
    LONG bottom;
} RECT, *PRECT, *NPRECT, *LPRECT;

HFONT CreateFontW(
    int     cHeight,
    int     cWidth,
    int     cEscapement,
    int     cOrientation,
    int     cWeight,
    DWORD   bItalic,
    DWORD   bUnderline,
    DWORD   bStrikeOut,
    DWORD   iCharSet,
    DWORD   iOutPrecision,
    DWORD   iClipPrecision,
    DWORD   iQuality,
    DWORD   iPitchAndFamily,
    LPCWSTR pszFaceName
);

COLORREF SetTextColor(
    HDC      hdc,
    COLORREF color
);

int SetBkMode(
    HDC hdc,
    int mode
);

HGDIOBJ SelectObject(
    HDC     hdc,
    HGDIOBJ h
);

BOOL DeleteObject(
    HGDIOBJ ho
);