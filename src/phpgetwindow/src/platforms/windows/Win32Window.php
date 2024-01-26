<?php

namespace He426100\phpgetwindow\platforms\windows;

use FFI;
use wchar2string;
use He426100\phpgetwindow\BaseWindow;

class Win32Window extends BaseWindow
{
    public const SW_MINIMIZE = 6;
    public const SW_MAXIMIZE = 3;
    public const SW_HIDE = 0;
    public const SW_SHOW = 5;
    public const SW_RESTORE = 9;

    public const HWND_TOP = 0;

    public const WM_CLOSE = 0x0010;

    private ?FFI $ffi = null;

    protected $kernel;

    public function __construct(protected $hWnd)
    {
        $this->ffi = FFI::cdef(file_get_contents(__DIR__ . '/windows.h'), 'user32.dll');
        $this->kernel = new kernel32();
    }

    public function getWindowRect()
    {
        $rect = $this->ffi->new("RECT");
        $result = $this->ffi->GetWindowRect($this->hWnd, FFI::addr($rect));
        if ($result != 0) {
            return [$rect->left, $rect->top, $rect->right, $rect->bottom];
        }
        $this->kernel->throwLastError();
    }

    public function close()
    {
        $result = $this->ffi->PostMessageA($this->hWnd, self::WM_CLOSE, 0, 0);
        if ($result == 0) {
            $this->kernel->throwLastError();
        }
    }

    public function minimize()
    {
        $this->ffi->ShowWindow($this->hWnd, self::SW_MINIMIZE);
    }

    public function maximize()
    {
        $this->ffi->ShowWindow($this->hWnd, self::SW_MAXIMIZE);
    }

    public function restore()
    {
        $this->ffi->ShowWindow($this->hWnd, self::SW_RESTORE);
    }

    public function show()
    {
        $this->ffi->ShowWindow($this->hWnd, self::SW_SHOW);
    }

    public function hide()
    {
        $this->ffi->ShowWindow($this->hWnd, self::SW_HIDE);
    }

    public function activate()
    {
        $result = $this->ffi->SetForegroundWindow($this->hWnd);
        if ($result == 0) {
            $this->kernel->throwLastError();
        }
    }

    public function resize($widthOffset, $heightOffset)
    {
        [$left, $top, $right, $bottom] = $this->getWindowRect();
        [$width, $height] = [$right - $left, $bottom - $top];
        $result = $this->ffi->SetWindowPos($this->hWnd, self::HWND_TOP, $left, $top, $width + $widthOffset, $height + $heightOffset, 0);
        if ($result == 0) {
            $this->kernel->throwLastError();
        }
    }

    public function resizeRel($widthOffset, $heightOffset)
    {
        return $this->resize($widthOffset, $heightOffset);
    }

    public function resizeTo($newWidth, $newHeight)
    {
        [$left, $top, $right, $bottom] = $this->getWindowRect();
        $result = $this->ffi->SetWindowPos($this->hWnd, self::HWND_TOP, $left, $top, $newWidth, $newHeight, 0);
        if ($result == 0) {
            $this->kernel->throwLastError();
        }
    }

    public function move($xOffset, $yOffset)
    {
        [$left, $top, $right, $bottom] = $this->getWindowRect();
        [$width, $height] = [$right - $left, $bottom - $top];
        $result = $this->ffi->SetWindowPos($this->hWnd, self::HWND_TOP, $left + $xOffset, $top + $yOffset, $width, $height, 0);
        if ($result == 0) {
            $this->kernel->throwLastError();
        }
    }

    public function moveRel($xOffset, $yOffset)
    {
        return $this->move($xOffset, $yOffset);
    }

    public function moveTo($newLeft, $newTop)
    {
        [$left, $top, $right, $bottom] = $this->getWindowRect();
        [$width, $height] = [$right - $left, $bottom - $top];
        $result = $this->ffi->SetWindowPos($this->hWnd, self::HWND_TOP, $newLeft, $newTop, $width, $height, 0);
        if ($result == 0) {
            $this->kernel->throwLastError();
        }
    }

    public function isMinimized()
    {
        return $this->ffi->IsIconic($this->hWnd) != 0;
    }

    public function isMaximized()
    {
        return $this->ffi->IsZoomed($this->hWnd) != 0;
    }

    public function isActive()
    {
        return (new windows)->getActiveWindow()->getHwnd() == $this->hWnd;
    }

    public function getHwnd()
    {
        return $this->hWnd;
    }

    public function getTitle()
    {
        $textLenInCharacters = $this->ffi->GetWindowTextLengthW($this->hWnd);
        $stringBuffer = FFI::new("unsigned short[" . ($textLenInCharacters + 1) . "]", false);
        $this->ffi->GetWindowTextW($this->hWnd, $stringBuffer, $textLenInCharacters + 1);
        return wchar2string($stringBuffer);
    }

    public function isVisible()
    {
        return $this->ffi->IsWindowVisible($this->hWnd);
    }
}
