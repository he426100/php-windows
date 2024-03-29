<?php

namespace He426100\phpgetwindow\platforms\windows;

use FFI;
use wchar2string;

class Process32
{
    private ?FFI $ffi = null;

    public function __construct(protected $pe32)
    {
        $this->ffi = FFI::cdef(file_get_contents(__DIR__ . '/kernel32.h'), 'kernel32.dll');
    }

    public function getTitle()
    {
        return wchar2string($this->pe32->szExeFile);
    }

    public function __toString()
    {
        return sprintf('<%s pid="%s", ppid="%s", title="%s">',
            self::class,
            $this->pe32->th32ProcessID,
            $this->pe32->th32ParentProcessID,
            $this->getTitle(),
        );
    }
}
