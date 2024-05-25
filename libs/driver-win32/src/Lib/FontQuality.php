<?php

declare(strict_types=1);

namespace Local\Driver\Win32\Lib;

final class FontQuality
{
    public const DEFAULT_QUALITY = 0x00;
    public const DRAFT_QUALITY = 0x01;
    public const PROOF_QUALITY = 0x02;
    public const NONANTIALIASED_QUALITY = 0x03;
    public const ANTIALIASED_QUALITY = 0x04;
    public const CLEARTYPE_QUALITY = 0x05;
}
