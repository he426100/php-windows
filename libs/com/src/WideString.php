<?php

declare(strict_types=1);

namespace Local\Com;

use FFI\CData;

final class WideString
{
    /**
     * @var non-empty-string
     */
    private const DEFAULT_INTERNAL_ENCODING = 'UTF-8';

    /**
     * @var non-empty-string
     */
    private const DEFAULT_EXTERNAL_ENCODING = 'UTF-16LE';

    /**
     * @var non-empty-string
     */
    private const WIDE_STRING_SUFFIX = "\0\0";

    /**
     * @api
     * @param non-empty-string|null $encoding
     */
    public static function decode(string $text, ?string $encoding = null): string
    {
        if ($text === '') {
            return '';
        }

        return \mb_convert_encoding(
            $text,
            self::DEFAULT_INTERNAL_ENCODING,
            $encoding ?? self::DEFAULT_EXTERNAL_ENCODING,
        );
    }

    /**
     * @api
     * @param non-empty-string|null $encoding
     */
    public static function encode(string $text, ?string $encoding = null): string
    {
        if ($text === '') {
            return '';
        }

        return \mb_convert_encoding(
            $text,
            $encoding ?? self::DEFAULT_EXTERNAL_ENCODING,
            self::DEFAULT_INTERNAL_ENCODING,
        );
    }

    /**
     * @api
     * @param non-empty-string|null $encoding
     */
    public static function toWideString(string $text, ?string $encoding = null): string
    {
        return self::encode($text, $encoding)
            . self::WIDE_STRING_SUFFIX;
    }

    /**
     * @api
     * @param non-empty-string|null $encoding
     */
    public static function toWideStringCData(object $ffi, string $text, ?string $encoding = null): CData
    {
        $text = self::toWideString($text, $encoding);
        $instance = $ffi->new("const char[" . \strlen($text) . "]", false);
        \FFI::memcpy($instance, $text, \strlen($text));

        /** @var CData */
        return $instance;
    }

    /**
     * @api
     * @param non-empty-string|null $encoding
     */
    public static function fromWideString(CData $text, ?string $encoding = null): string
    {
        $index = 0;
        $result = '';

        while (true) {
            $result .= $text[$index++];
            if (\str_ends_with($result, self::WIDE_STRING_SUFFIX) && ($index % 2 === 0)) {
                $nonSuffixed = \substr($result, 0, -2);

                return self::decode($nonSuffixed, $encoding);
            }
        }
    }
}
