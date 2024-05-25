<?php

declare(strict_types=1);

namespace Local\Driver\Win32\Lib;

final class OutPrecision
{
    public const OUT_DEFAULT_PRECIS = 0x00000000;
    public const OUT_STRING_PRECIS = 0x00000001;
    public const OUT_STROKE_PRECIS = 0x00000003;
    public const OUT_TT_PRECIS = 0x00000004;
    public const OUT_DEVICE_PRECIS = 0x00000005;
    public const OUT_RASTER_PRECIS = 0x00000006;
    public const OUT_TT_ONLY_PRECIS = 0x00000007;
    public const OUT_OUTLINE_PRECIS = 0x00000008;
    public const OUT_SCREEN_OUTLINE_PRECIS = 0x00000009;
    public const OUT_PS_ONLY_PRECIS = 0x0000000A;
}
