<?php

declare(strict_types=1);

namespace Local\Driver\Win32\Lib;

use Local\Driver\Win32\Library;

/**
 * @link https://github.com/SerafimArts/Boson/
 * @package Local\Driver\Win32\Lib
 */
final class Kernel32 extends Library
{
    public function __construct()
    {
        parent::__construct('kernel32.dll');
    }

    public static function getHeader(): string
    {
        return (string) \file_get_contents(__FILE__, offset: __COMPILER_HALT_OFFSET__);
    }
}

__halt_compiler();

typedef char CHAR;
typedef unsigned short WCHAR;
typedef const CHAR *LPCSTR;
typedef const WCHAR *LPCWSTR;
typedef void *HINSTANCE;
typedef HINSTANCE HMODULE;
typedef WCHAR *LPWSTR;
typedef unsigned long DWORD;
typedef unsigned long long  ULONG_PTR;
typedef long  LONG;
typedef void* PVOID;
typedef PVOID HANDLE;
typedef char TCHAR;
typedef int BOOL;
typedef const void *LPCVOID;
typedef void* va_list;
typedef HANDLE HLOCAL;

typedef struct _PROCESSENTRY32 {
    DWORD     dwSize;
    DWORD     cntUsage;
    DWORD     th32ProcessID;
    ULONG_PTR th32DefaultHeapID;
    DWORD     th32ModuleID;
    DWORD     cntThreads;
    DWORD     th32ParentProcessID;
    LONG      pcPriClassBase;
    DWORD     dwFlags;
    TCHAR     szExeFile[260];
} PROCESSENTRY32;

HMODULE GetModuleHandleA(LPCSTR lpModuleName);
HMODULE GetModuleHandleW(LPCWSTR lpModuleName);

HANDLE CreateToolhelp32Snapshot(DWORD dwFlags, DWORD th32ProcessID);
BOOL Process32First(HANDLE hSnapshot, PROCESSENTRY32 *lppe);
BOOL Process32Next(HANDLE hSnapshot, PROCESSENTRY32 *lppe);
BOOL CloseHandle(HANDLE hObject);
DWORD GetLastError();

DWORD FormatMessageW(
  DWORD   dwFlags,
  LPCVOID lpSource,
  DWORD   dwMessageId,
  DWORD   dwLanguageId,
  LPWSTR  lpBuffer,
  DWORD   nSize,
  va_list *Arguments
);
void LocalFree(HLOCAL hMem);
