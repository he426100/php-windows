<?php

namespace He426100\phpgetwindow;

abstract class BaseWindow
{
    protected $title;

    abstract public function getWindowRect();
    abstract public function close();
    abstract public function minimize();
    abstract public function maximize();
    abstract public function restore();
    abstract public function activate();
    abstract public function resizeRel($widthOffset, $heightOffset);
    abstract public function resizeTo($newWidth, $newHeight);
    abstract public function moveRel($xOffset, $yOffset);
    abstract public function moveTo($newLeft, $newTop);
    abstract public function getTitle();
    abstract public function isVisible();

    public function __toString()
    {
        [$left, $top, $right, $bottom] = $this->getWindowRect();
        $width = $right - $left;
        $height = $bottom - $top;
        return sprintf('<%s left="%s", top="%s", width="%s", height="%s", title="%s">',
            self::class,
            $left,
            $top,
            $width,
            $height,
            $this->getTitle(),
        );
    }
}   
