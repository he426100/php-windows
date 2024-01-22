<?php

if (!function_exists('string2wchar')) {
    function string2wchar($str)
    {
        $messageUtf16 = mb_convert_encoding($str, 'UTF-16LE', 'UTF-8');
        $messageLength = strlen($messageUtf16);
        $messageBuffer = \FFI::new("uint16_t[" . ($messageLength + 1) . "]");
        \FFI::memcpy($messageBuffer, $messageUtf16, $messageLength);
        return $messageBuffer;
    }
}

if (!function_exists('wchar2string')) {
    function wchar2string($wchar)
    {
        $messageLength = 0;
        while ($wchar[$messageLength]) {
            $messageLength++;
        }
        $messageUtf8 = \FFI::string($wchar, $messageLength * 2);
        return mb_convert_encoding($messageUtf8, 'UTF-8', 'UTF-16LE');
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
