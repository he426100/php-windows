<?php

declare(strict_types=1);

namespace Local\Driver\Win32\Lib;

final class TextDrawStyle
{
    public const DT_TOP              = 0x00000000;
    public const DT_LEFT             = 0x00000000;
    public const DT_CENTER           = 0x00000001;
    public const DT_RIGHT            = 0x00000002;
    public const DT_VCENTER          = 0x00000004;
    public const DT_BOTTOM           = 0x00000008;
    public const DT_WORDBREAK        = 0x00000010;
    public const DT_SINGLELINE       = 0x00000020;
    public const DT_EXPANDTABS       = 0x00000040;
    public const DT_TABSTOP          = 0x00000080;
    public const DT_NOCLIP           = 0x00000100;
    public const DT_EXTERNALLEADING  = 0x00000200;
    public const DT_CALCRECT         = 0x00000400;
    public const DT_NOPREFIX         = 0x00000800;
    public const DT_INTERNAL         = 0x00001000;
    public const DT_EDITCONTROL      = 0x00002000;
    public const DT_PATH_ELLIPSIS    = 0x00004000;
    public const DT_END_ELLIPSIS     = 0x00008000;
    public const DT_MODIFYSTRING     = 0x00010000;
    public const DT_RTLREADING       = 0x00020000;
    public const DT_WORD_ELLIPSIS    = 0x00040000;
}
