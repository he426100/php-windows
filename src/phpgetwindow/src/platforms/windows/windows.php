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

    private ?FFI $ffi = null;

    public function __construct()
    {
        $this->ffi = FFI::cdef(file_get_contents(__DIR__ . '/windows.h'), 'user32.dll');
    }

    public function getActiveWindow()
    {
        $hWnd = $this->ffi->GetForegroundWindow();
        if ($hWnd == 0) {
            return null;
        }
        return new Win32Window($hWnd);
    }

    public function getActiveWindowTitle()
    {
        $activeWindowTitle = '';
        $activeWindowHwnd = $this->ffi->GetForegroundWindow();
        if ($activeWindowHwnd == 0) {
            return null;
        }
        $this->ffi->EnumWindows(function ($hWnd, $lParam) use ($activeWindowHwnd, &$activeWindowTitle) {
            if ($hWnd == $activeWindowHwnd) {
                $length = $this->ffi->GetWindowTextLengthW($hWnd);
                $buffer = FFI::new("unsigned short[" . ($length + 1) . "]", false);
                $this->ffi->GetWindowTextW($hWnd, $buffer, $length + 1);
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
            [$left, $top, $right, $bottom] = $window->getWindowRect();
            [$width, $height] = [$right - $left, $bottom - $top];
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
        $this->ffi->EnumWindows(function ($hWnd, $lParam) use (&$windowObjs) {
            if ($this->ffi->IsWindowVisible($hWnd)) {
                $windowObjs[] = new Win32Window($hWnd);
            }
            return true;
        }, 0);

        return $windowObjs;
    }

    public function listAllTitles()
    {
        $titles = [];
        $this->ffi->EnumWindows(function ($hWnd, $lParam) use (&$titles) {
            if ($this->ffi->IsWindowVisible($hWnd)) {
                $length = $this->ffi->GetWindowTextLengthW($hWnd);
                $titleBuffer = FFI::new("unsigned short[" . ($length + 1) . "]", false);
                $this->ffi->GetWindowTextW($hWnd, $titleBuffer, $length + 1);
                $titles[] = [$hWnd, wchar2string($titleBuffer)];
            }
            return true;
        }, 0);

        return $titles;
    }

    public function cursor()
    {
        $point = $this->ffi->new("POINT");
        $this->ffi->GetCursorPos(FFI::addr($point));

        return [$point->x, $point->y];
    }

    public function resolution()
    {
        return [
            $this->ffi->GetSystemMetrics(0),
            $this->ffi->GetSystemMetrics(1)
        ];
    }
}
