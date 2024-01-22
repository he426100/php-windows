<?php

namespace He426100\phpgetwindow\platforms;

interface platform
{
    public function getActiveWindow();
    public function getActiveWindowTitle();
    public function getWindowsAt($x, $y);
    public function getWindowsWithTitle($title);
    public function getAllWindows();
    public function getAllTitles();
}
