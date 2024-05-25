<?php

declare(strict_types=1);

namespace Local\Driver\Win32\Lib;

/**
 * @link https://docs.microsoft.com/en-us/windows/win32/api/winuser/nf-winuser-loadimagew
 */
final class ImageType
{
    public const IMAGE_BITMAP = 0x00;
    public const IMAGE_ICON = 0x01;
    public const IMAGE_CURSOR = 0x02;
}
