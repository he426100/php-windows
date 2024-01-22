<?php

namespace He426100\phpgetwindow\platforms\windows;

use FFI;
use string2wchar;
use wchar2string;
use point_in_rect;
use He426100\phpgetwindow\phpgetwindow;
use He426100\phpgetwindow\platforms\platform;

final class windows implements platform
{
    public const FORMAT_MESSAGE_ALLOCATE_BUFFER = 0x00000100;
    public const FORMAT_MESSAGE_FROM_SYSTEM = 0x00001000;
    public const FORMAT_MESSAGE_IGNORE_INSERTS = 0x00000200;

    public static $ffi = null;

    public function __construct()
    {
        if (is_null(self::$ffi)) {
            // 不知道为啥用load不行
            self::$ffi = FFI::cdef(file_get_contents(__DIR__ . '/windows.h'), 'user32.dll');
        }
    }

    public function getActiveWindow()
    {
        $hWnd = self::$ffi->GetForegroundWindow();
        // if (FFI::cast('int*', $hWnd)[0] == 0) {
        //     return null;
        // }
        return new Win32Window($hWnd);
    }

    public function getActiveWindowTitle()
    {
        $activeWindowTitle = '';
        $activeWindowHwnd = self::$ffi->GetForegroundWindow();
        // if (FFI::cast('int*', $activeWindowHwnd)[0] == 0) {
        //     return null;
        // }
        self::$ffi->EnumWindows(function ($hWnd, $lParam) use ($activeWindowHwnd, &$activeWindowTitle) {
            if ($hWnd == $activeWindowHwnd) {
                $length = self::$ffi->GetWindowTextLengthW($hWnd);
                $buffer = FFI::new("unsigned short[" . ($length + 1) . "]", false);
                self::$ffi->GetWindowTextW($hWnd, $buffer, $length + 1);
                $activeWindowTitle = wchar2string($buffer);
            }
            return true;
        }, 0);
        return $activeWindowTitle;
    }

    public function getWindowsAt($x, $y)
    {
        $windowsAtXY = [];
        foreach ($this->getAllWindows() as $window) {
            [$left, $top] = $window->getRect();
            [$width, $height] = $window->getSize();
            if (point_in_rect($x, $y, $left, $top, $width, $height)) {
                $windowsAtXY[] = $window;
            }
        }
        return $windowsAtXY;
    }

    public function getWindowsWithTitle($title)
    {
        $hWndsAndTitles = $this->listAllTitles();
        $windowObjs = [];
        foreach ($hWndsAndTitles as [$hWnd, $winTitle]) {
            if (str_contains(strtoupper($winTitle), strtoupper($title))) {
                $windowObjs[] = new Win32Window($hWnd);
            }
        }
        return $windowObjs;
    }

    public function getAllTitles()
    {
        return array_map(fn ($e) => $e->getTitle(), $this->getAllWindows());
    }

    public function getAllWindows()
    {
        $windowObjs = [];
        self::$ffi->EnumWindows(function ($hWnd, $lParam) use (&$windowObjs) {
            if (self::$ffi->IsWindowVisible($hWnd)) {
                $windowObjs[] = new Win32Window($hWnd);
            }
            return true;
        }, 0);

        return $windowObjs;
    }

    public function listAllTitles()
    {
        $titles = [];
        self::$ffi->EnumWindows(function ($hWnd, $lParam) use (&$titles) {
            if (self::$ffi->IsWindowVisible($hWnd)) {
                $length = self::$ffi->GetWindowTextLengthW($hWnd);
                $titleBuffer = FFI::new("unsigned short[" . ($length + 1) . "]", false);
                self::$ffi->GetWindowTextW($hWnd, $titleBuffer, $length + 1);
                $titles[] = [$hWnd, wchar2string($titleBuffer)];
            }
            return true;
        }, 0);

        return $titles;
    }

    public function cursor()
    {
        $point = self::$ffi->new("POINT");
        self::$ffi->GetCursorPos(FFI::addr($point));

        return [$point->x, $point->y];
    }

    public function resolution()
    {
        return [
            self::$ffi->GetSystemMetrics(0),
            self::$ffi->GetSystemMetrics(1)
        ];
    }
}
