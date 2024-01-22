<?php

namespace He426100\phpgetwindow;

use He426100\phpgetwindow\platforms\platform;

class phpgetwindow
{
    public function __construct(private platform $platform)
    {
    }
    
    public function getActiveWindow()
    {
        return $this->platform->getActiveWindow();
    }

    public function getActiveWindowTitle()
    {
        return $this->platform->getActiveWindowTitle();
    }

    public function getWindowsAt($x, $y)
    {
        return $this->platform->getWindowsAt($x, $y);
    }

    public function getWindowsWithTitle($title)
    {
        return $this->platform->getWindowsWithTitle($title);
    }

    public function getAllWindows()
    {
        return $this->platform->getAllWindows();
    }

    public function getAllTitles()
    {
        return $this->platform->getAllTitles();
    }
}
