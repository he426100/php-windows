<?php

if (!function_exists('string2wchar')) {
    /**
     * php string转windows wchar
     * 主要为MessageBoxW设计，无法使用php-ffi/scalar-utils的Type::wideString
     */
    function string2wchar($string)
    {
        $stringUtf16 = mb_convert_encoding($string, 'UTF-16LE', 'UTF-8');
        $length = \strlen($nullTerminated = $stringUtf16 . "\0\0");
        $instance = \FFI::new("uint16_t[$length]");
        \FFI::memcpy($instance, $nullTerminated, $length);
        return $instance;
    }
}

if (!function_exists('wchar2string')) {
    /**
     * WCHAR 转 php string
     * 复制自 php-ffi/scalar-utils
     * @param CData $cdata 
     * @param ?int $size 
     * @return int|string 
     */
    function wchar2string($cdata, $size = null)
    {
        [$i, $result] = [0, ''];
        if ($size !== null) {
            for ($i = 0; $i < $size; ++$i) {
                $char = $cdata[$i];
                $result .= \is_int($char) ? \mb_chr($char) : $char;
            }

            return $result;
        }
        do {
            $char = $cdata[$i];
            if ($char === 0 || $char === "\0") {
                return $result;
            }
            $result .= \is_int($char) ? \mb_chr($char) : $char;
        } while (++$i);

        return $result;
    }
}

if (!function_exists('is_shift_character')) {
    /**
     * Returns True if the ``character`` is a keyboard key that would require the shift key to be held down, such as
     * uppercase letters or the symbols on the keyboard's number row.
     * @param string $char 
     * @return bool 
     */
    function is_shift_character($char)
    {
        # NOTE TODO - This will be different for non-qwerty keyboards.
        return ctype_upper($char) || in_array($char, str_split('~!@#$%^&*()_+{}|:"<>?'));
    }
}

if (!function_exists('point_in_rect')) {
    /**
     * Returns ``True`` if the ``(x, y)`` point is within the box described by ``(left, top, width, height)``.
     * @param x
     * @param y
     * @param left
     * @param top
     * @param width
     * @param height
     * @return bool 
     */
    function point_in_rect($x, $y, $left, $top, $width, $height)
    {
        return $left < $x && $x < $left + $width && $top < $y && $y < $top + $height;
    }
}
