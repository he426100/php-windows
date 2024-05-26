<?php

namespace He426100\phpgetwindow\platforms\windows;

use FFI;
use Local\Com\WideString;

class Process32
{
    public function __construct(protected $pe32)
    {
    }

    public function getTitle()
    {
        return FFI::string($this->pe32->szExeFile);
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
