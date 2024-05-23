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

HMODULE GetModuleHandleA(LPCSTR lpModuleName);
HMODULE GetModuleHandleW(LPCWSTR lpModuleName);
