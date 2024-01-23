<?php

namespace He426100\phpgetwindow\platforms\windows;

use FFI;
use wchar2string;

final class kernel32
{
    public const TH32CS_INHERIT = 0x80000000;
    public const TH32CS_SNAPHEAPLIST = 0x00000001;
    public const TH32CS_SNAPMODULE = 0x00000008;
    public const TH32CS_SNAPMODULE32 = 0x00000010;
    public const TH32CS_SNAPPROCESS = 0x00000002;
    public const TH32CS_SNAPTHREAD = 0x00000004;

    public const FORMAT_MESSAGE_ALLOCATE_BUFFER = 0x00000100;
    public const FORMAT_MESSAGE_FROM_SYSTEM = 0x00001000;
    public const FORMAT_MESSAGE_IGNORE_INSERTS = 0x00000200;

    public static $ffi = null;

    public function __construct()
    {
        if (is_null(self::$ffi)) {
            self::$ffi = FFI::cdef(file_get_contents(__DIR__ . '/kernel32.h'), 'kernel32.dll');
        }
    }

    public function getAllProcess()
    {
        $processObjs = [];
        // 获取系统中的进程快照
        $hProcessSnap = self::$ffi->CreateToolhelp32Snapshot(0x00000002, 0);
        if ($hProcessSnap == null) {
            throw new \Exception("CreateToolhelp32Snapshot failed");
        }

        // 设置 PROCESSENTRY32 结构的大小
        $pe32 = self::$ffi->new("PROCESSENTRY32");
        $pe32->dwSize = self::$ffi::sizeof($pe32);

        // 遍历进程快照，获取进程信息
        if (!self::$ffi->Process32First($hProcessSnap, FFI::addr($pe32))) {
            throw new \Exception("Process32First failed");
        }

        // 显示每个进程的信息
        do {
            $processObjs[] = new Process32(clone $pe32);
        } while (self::$ffi->Process32Next($hProcessSnap, FFI::addr($pe32)));

        // 关闭进程快照句柄
        self::$ffi->CloseHandle($hProcessSnap);

        return $processObjs;
    }

    public function getProcessWithTitle($title)
    {
        return array_filter($this->getAllProcess(), function ($process) use ($title) {
            return str_contains(strtoupper($process->getTitle()), strtoupper($title));
        });
    }

    public function getLastError()
    {
        return self::$ffi->GetLastError();
    }

    public function throwLastError()
    {
        throw new \Exception($this->formatMessage($this->getLastError()));
    }

    /**
     * A nice wrapper for FormatMessageW()
     * @link https://docs.microsoft.com/en-us/windows/desktop/api/winbase/nf-winbase-formatmessagew
     * @param int $errorCode 
     * @return string 
     */
    public function formatMessage($errorCode)
    {
        $lpBuffer = self::$ffi->new("LPWSTR");
        self::$ffi->FormatMessageW(
            self::FORMAT_MESSAGE_FROM_SYSTEM | self::FORMAT_MESSAGE_ALLOCATE_BUFFER | self::FORMAT_MESSAGE_IGNORE_INSERTS,
            null,
            $errorCode,
            0, // dwLanguageId
            self::$ffi->cast('LPWSTR', FFI::addr($lpBuffer)),
            0, // nSize
            null
        );
        $msg = rtrim(wchar2string($lpBuffer));
        self::$ffi->LocalFree($lpBuffer);
        return $msg;
    }
}
