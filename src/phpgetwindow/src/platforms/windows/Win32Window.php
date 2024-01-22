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

    public static $ffi = null;

    protected $rect;
    protected $size;

    public function __construct(protected $hWnd)
    {
        if (is_null(self::$ffi)) {
            // 不知道为啥用load不行
            self::$ffi = FFI::cdef(file_get_contents(__DIR__ . '/windows.h'), 'user32.dll');
        }
        $this->rect = [$left, $top, $right, $bottom] = $this->getWindowRect();
        $this->size = [$right - $left, $bottom - $top];
    }

    public function getWindowRect()
    {
        $rect = self::$ffi->new("RECT");
        $result = self::$ffi->GetWindowRect($this->hWnd, FFI::addr($rect));
        if ($result != 0) {
            return [$rect->left, $rect->top, $rect->right, $rect->bottom];
        }
        throw new \Exception('get window rect failed!');
    }

    public function close()
    {
        self::$ffi->PostMessageA($this->hWnd, self::WM_CLOSE, 0, 0);
    }

    public function minimize()
    {
        self::$ffi->ShowWindow($this->hWnd, self::SW_MINIMIZE);
    }

    public function maximize()
    {
        self::$ffi->ShowWindow($this->hWnd, self::SW_MAXIMIZE);
    }

    public function restore()
    {
        self::$ffi->ShowWindow($this->hWnd, self::SW_RESTORE);
    }

    public function show()
    {
        self::$ffi->ShowWindow($this->hWnd, self::SW_SHOW);
    }

    public function hide()
    {
        self::$ffi->ShowWindow($this->hWnd, self::SW_HIDE);
    }

    public function activate()
    {
        self::$ffi->SetForegroundWindow($this->hWnd);
    }

    public function resize($widthOffset, $heightOffset)
    {
        [$left, $top, $right, $bottom] = $this->rect;
        [$width, $height] = $this->size;
        self::$ffi->SetWindowPos($this->hWnd, self::HWND_TOP, $left, $top, $width + $widthOffset, $height + $heightOffset, 0);
    }

    public function resizeRel($widthOffset, $heightOffset)
    {
        return $this->resize($widthOffset, $heightOffset);
    }

    public function resizeTo($newWidth, $newHeight)
    {
        [$left, $top, $right, $bottom] = $this->rect;
        self::$ffi->SetWindowPos($this->hWnd, self::HWND_TOP, $left, $top, $newWidth, $newHeight, 0);
    }

    public function move($xOffset, $yOffset)
    {
        [$left, $top, $right, $bottom] = $this->rect;
        [$width, $height] = $this->size;
        self::$ffi->SetWindowPos($this->hWnd, self::HWND_TOP, $left + $xOffset, $top + $yOffset, $width, $height, 0);
    }

    public function moveRel($xOffset, $yOffset)
    {
        return $this->move($xOffset, $yOffset);
    }

    public function moveTo($newLeft, $newTop)
    {
        [$width, $height] = $this->size;
        self::$ffi->SetWindowPos($this->hWnd, self::HWND_TOP, $newLeft, $newTop, $width, $height, 0);
    }

    public function isMinimized()
    {
        return self::$ffi->IsIconic($this->hWnd) != 0;
    }

    public function isMaximized()
    {
        return self::$ffi->IsZoomed($this->hWnd) != 0;
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
        $textLenInCharacters = self::$ffi->GetWindowTextLengthW($this->hWnd);
        $stringBuffer = FFI::new("unsigned short[" . ($textLenInCharacters + 1) . "]", false);
        self::$ffi->GetWindowTextW($this->hWnd, $stringBuffer, $textLenInCharacters + 1);
        return wchar2string($stringBuffer);
    }

    public function isVisible()
    {
        return self::$ffi->IsWindowVisible($this->hWnd);
    }

    public function getRect()
    {
        return $this->rect;
    }

    public function getSize()
    {
        return $this->size;
    }
}
