<?php

namespace He426100\phpmsgbox;

use He426100\phpmsgbox\platforms\platform;

class phpmsgbox
{
    public const PROPORTIONAL_FONT_FAMILY = ["MS", "Sans", "Serif"];
    public const MONOSPACE_FONT_FAMILY = "Courier";

    public const PROPORTIONAL_FONT_SIZE = 10;
    public const MONOSPACE_FONT_SIZE = [
        9
    ];  # a little smaller, because it it more legible at a smaller size
    public const TEXT_ENTRY_FONT_SIZE = 12;  # a little larger makes it easier to see


    public const STANDARD_SELECTION_EVENTS = ["Return", "Button-1", "space"];

    # constants for strings: (TODO: for internationalization, change these)
    public const OK_TEXT = "OK";
    public const CANCEL_TEXT = "Cancel";
    public const YES_TEXT = "Yes";
    public const NO_TEXT = "No";
    public const RETRY_TEXT = "Retry";
    public const ABORT_TEXT = "Abort";
    public const IGNORE_TEXT = "Ignore";
    public const TRY_AGAIN_TEXT = "Try Again";
    public const CONTINUE_TEXT = "Continue";

    public const TIMEOUT_RETURN_VALUE = "Timeout";

    public const NO_ICON = 0;
    public const STOP = 0x10;
    public const QUESTION = 0x20;
    public const WARNING = 0x30;
    public const INFO = 0x40;

    public function __construct(private platform $platform)
    {
    }

    public function alert($text, $title, $button = self::OK_TEXT, $icon = self::NO_ICON)
    {
        return $this->platform->alert($text, $title, $button, $icon);
    }

    public function confirm($text, $title, $buttons = [self::OK_TEXT, self::CANCEL_TEXT], $icon = self::QUESTION)
    {
        return $this->platform->confirm($text, $title, $buttons, $icon);
    }
}
