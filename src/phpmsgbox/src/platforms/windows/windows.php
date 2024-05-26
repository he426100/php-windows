<?php

namespace He426100\phpmsgbox\platforms\windows;

use Local\Com\WideString;
use Local\Driver\Win32\Lib\User32;
use He426100\phpmsgbox\phpmsgbox;
use He426100\phpmsgbox\platforms\platform;

final class windows implements platform
{
    public const MB_OK = 0x0;
    public const MB_OKCANCEL = 0x1;
    public const MB_ABORTRETRYIGNORE = 0x2;
    public const MB_YESNOCANCEL = 0x3;
    public const MB_YESNO = 0x4;
    public const MB_RETRYCANCEL = 0x5;
    public const MB_CANCELTRYCONTINUE = 0x6;

    public const NO_ICON = 0;
    public const MB_ICONERRPR = 0x10;
    public const MB_ICONSTOP = 0x10;
    public const MB_ICONHAND = 0x10;
    public const STOP = 0x10;
    public const MB_ICONQUESTION = 0x20;
    public const QUESTION = 0x20;
    public const MB_ICONEXCLAIMATION = 0x30;
    public const WARNING = 0x30;
    public const MB_ICONINFOMRAITON = 0x40;
    public const MB_ICONASTERISK = 0x40;
    public const INFO = 0x40;

    public const MB_DEFAULTBUTTON1 = 0x0;
    public const MB_DEFAULTBUTTON2 = 0x100;
    public const MB_DEFAULTBUTTON3 = 0x200;
    public const MB_DEFAULTBUTTON4 = 0x300;

    public const MB_SETFOREGROUND = 0x10000;
    public const MB_TOPMOST = 0x40000;

    public const IDABORT = 0x3;
    public const IDCANCEL = 0x2;
    public const IDCONTINUE = 0x11;
    public const IDIGNORE = 0x5;
    public const IDNO = 0x7;
    public const IDOK = 0x1;
    public const IDRETRY = 0x4;
    public const IDTRYAGAIN = 0x10;
    public const IDYES = 0x6;
    
    private ?User32 $ffi = null;

    public function __construct()
    {
        $this->ffi = new User32();
    }

    /**
     * Displays a simple message box with text && a single OK button. Returns the text of the button clicked on.
     * @param string $text 
     * @param string $title 
     * @param string $button 
     * @param int $icon
     * @return string 
     */
    public function alert($text, $title, $button = phpmsgbox::OK_TEXT, $icon = self::NO_ICON)
    {
        $text = (string)$text;
        $this->ffi->MessageBoxW(0, WideString::toWideString($text), WideString::toWideString($title), self::MB_OK | self::MB_SETFOREGROUND | self::MB_TOPMOST | $icon);
        return $button;
    }

    public function confirm($text, $title, $buttons = [phpmsgbox::OK_TEXT, phpmsgbox::CANCEL_TEXT], $icon = self::QUESTION)
    {
        $text = (string)$text;
        $buttonFlag = null;
        if (count($buttons) == 1) {
            if ($buttons[0] == phpmsgbox::OK_TEXT) {
                $buttonFlag = self::MB_OK;
            }
        } elseif (count($buttons) == 2) {
            if ($buttons[0] == phpmsgbox::OK_TEXT && $buttons[1] == phpmsgbox::CANCEL_TEXT) {
                $buttonFlag = self::MB_OKCANCEL;
            } elseif ($buttons[0] == phpmsgbox::YES_TEXT && $buttons[1] == phpmsgbox::NO_TEXT) {
                $buttonFlag = self::MB_YESNO;
            } elseif ($buttons[0] == phpmsgbox::RETRY_TEXT && $buttons[1] == phpmsgbox::CANCEL_TEXT) {
                $buttonFlag = self::MB_RETRYCANCEL;
            }
        } elseif (count($buttons) == 3) {
            if (
                $buttons[0] == phpmsgbox::ABORT_TEXT
                && $buttons[1] == phpmsgbox::RETRY_TEXT
                && $buttons[2] == phpmsgbox::IGNORE_TEXT
            ) {
                $buttonFlag = self::MB_ABORTRETRYIGNORE;
            } elseif (
                $buttons[0] == phpmsgbox::CANCEL_TEXT
                && $buttons[1] == phpmsgbox::TRY_AGAIN_TEXT
                && $buttons[2] == phpmsgbox::CONTINUE_TEXT
            ) {
                $buttonFlag = self::MB_CANCELTRYCONTINUE;
            } elseif (
                $buttons[0] == phpmsgbox::YES_TEXT
                && $buttons[1] == phpmsgbox::NO_TEXT
                && $buttons[2] == phpmsgbox::CANCEL_TEXT
            ) {
                $buttonFlag = self::MB_YESNOCANCEL;
            }
        }
        $retVal = $this->ffi->MessageBoxW(
            0,
            WideString::toWideString($text),
            WideString::toWideString($title),
            $buttonFlag | self::MB_SETFOREGROUND | self::MB_TOPMOST | $icon
        );
        if ($retVal == self::IDOK || count($buttons) == 1) {
            return phpmsgbox::OK_TEXT;
        } elseif ($retVal == self::IDCANCEL) {
            return phpmsgbox::CANCEL_TEXT;
        } elseif ($retVal == self::IDYES) {
            return phpmsgbox::YES_TEXT;
        } elseif ($retVal == self::IDNO) {
            return phpmsgbox::NO_TEXT;
        } elseif ($retVal == self::IDTRYAGAIN) {
            return phpmsgbox::RETRY_TEXT;
        } elseif ($retVal == self::IDRETRY) {
            return phpmsgbox::RETRY_TEXT;
        } elseif ($retVal == self::IDIGNORE) {
            return phpmsgbox::IGNORE_TEXT;
        } elseif ($retVal == self::IDCONTINUE) {
            return phpmsgbox::CONTINUE_TEXT;
        } elseif ($retVal == self::IDABORT) {
            return phpmsgbox::ABORT_TEXT;
        } else {
            throw new \Exception('Unexpected return value from MessageBox: " . (retVal)');
        }
    }
}
