<?php

declare(strict_types=1);

namespace Local\Driver\Win32\Lib;

/**
 * https://learn.microsoft.com/en-us/openspecs/windows_protocols/ms-wmf/c85e4c50-f581-4d22-826c-854e7b50e75d
 * @package Local\Driver\Win32\Lib
 */
final class ClipPrecision
{
    public const CLIP_DEFAULT_PRECIS = 0x00000000;
    public const CLIP_CHARACTER_PRECIS = 0x00000001;
    public const CLIP_STROKE_PRECIS = 0x00000002;
    public const CLIP_LH_ANGLES = 0x00000010;
    public const CLIP_TT_ALWAYS = 0x00000020;
    public const CLIP_DFA_DISABLE = 0x00000040;
    public const CLIP_EMBEDDED = 0x00000080;
}
