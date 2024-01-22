<?php

namespace He426100\phpmsgbox\platforms;

interface platform
{
    public function alert($text, $title, $button, $icon);
    public function confirm($text, $title, $buttons, $icon);
}
