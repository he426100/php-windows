<?php

namespace He426100\phpautogui\platforms;

interface platform
{
    public function keyDown($key);
    public function keyUp($key);
    public function isValidKey($key);
    public function position();
    public function size();
    public function moveTo($x, $y);
    public function mouseDown($x, $y, $button);
    public function mouseUp($x, $y, $button);
    public function click($x, $y, $button);
    public function mouseIsSwapped();
    public function sendMouseEvent($ev, $x, $y, $dwData = 0);
    public function scroll($clicks, $x = null, $y = null);
    public function hscroll($clicks, $x, $y);
    public function vscroll($clicks, $x, $y);
}
