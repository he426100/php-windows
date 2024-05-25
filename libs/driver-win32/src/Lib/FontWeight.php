<?php

declare(strict_types=1);

namespace Local\Driver\Win32\Lib;

/**
 * @link https://learn.microsoft.com/en-us/windows/win32/api/wingdi/nf-wingdi-createfontw
 * @package Local\Driver\Win32\Lib
 */
final class FontWeight
{
    public const FW_DONTCARE = 0;
    public const FW_THIN = 100;
    public const FW_EXTRALIGHT = 200;
    public const FW_ULTRALIGHT = 200;
    public const FW_LIGHT = 300;
    public const FW_NORMAL = 400;
    public const FW_REGULAR = 400;
    public const FW_MEDIUM = 500;
    public const FW_SEMIBOLD = 600;
    public const FW_DEMIBOLD = 600;
    public const FW_BOLD = 700;
    public const FW_EXTRABOLD = 800;
    public const FW_ULTRABOLD = 800;
    public const FW_HEAVY = 900;
    public const FW_BLACK = 900;
}
