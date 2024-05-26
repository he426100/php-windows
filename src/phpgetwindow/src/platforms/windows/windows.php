<?php

namespace He426100\phpgetwindow\platforms\windows;

use FFI;
use Local\Com\WideString;
use Local\Driver\Win32\Lib\User32;
use He426100\phpgetwindow\platforms\platform;

use function point_in_rect;

final class windows implements platform
{
    public const FORMAT_MESSAGE_ALLOCATE_BUFFER = 0x00000100;
    public const FORMAT_MESSAGE_FROM_SYSTEM = 0x00001000;
    public const FORMAT_MESSAGE_IGNORE_INSERTS = 0x00000200;

    private ?User32 $ffi = null;

    public function __construct()
    {
        $this->ffi = new User32();
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
                $activeWindowTitle = (new Win32Window($hWnd))->getTitle();
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
                $titles[] = [$hWnd, (new Win32Window($hWnd))->getTitle()];
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
