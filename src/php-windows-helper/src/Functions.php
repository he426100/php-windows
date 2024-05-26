<?php

use FFI\CData;

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
