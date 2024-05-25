<?php

declare(strict_types=1);

namespace Local\Driver\Win32\Lib;

/**
 * https://learn.microsoft.com/en-us/openspecs/windows_protocols/ms-wmf/0d0b32ac-a836-4bd2-a112-b6000a1b4fc9
 * @package Local\Driver\Win32\Lib
 */
final class CharacterSet
{
   public const ANSI_CHARSET = 0x00000000;
   public const DEFAULT_CHARSET = 0x00000001;
   public const SYMBOL_CHARSET = 0x00000002;
   public const MAC_CHARSET = 0x0000004D;
   public const SHIFTJIS_CHARSET = 0x00000080;
   public const HANGUL_CHARSET = 0x00000081;
   public const JOHAB_CHARSET = 0x00000082;
   public const GB2312_CHARSET = 0x00000086;
   public const CHINESEBIG5_CHARSET = 0x00000088;
   public const GREEK_CHARSET = 0x000000A1;
   public const TURKISH_CHARSET = 0x000000A2;
   public const VIETNAMESE_CHARSET = 0x000000A3;
   public const HEBREW_CHARSET = 0x000000B1;
   public const ARABIC_CHARSET = 0x000000B2;
   public const BALTIC_CHARSET = 0x000000BA;
   public const RUSSIAN_CHARSET = 0x000000CC;
   public const THAI_CHARSET = 0x000000DE;
   public const EASTEUROPE_CHARSET = 0x000000EE;
   public const OEM_CHARSET = 0x000000FF;
}
