<?php

namespace He426100\phpautogui;

use He426100\phpautogui\platforms\platform;

class phpautogui
{
    public const KEY_NAMES = [
        "\t",
        "\n",
        "\r",
        " ",
        "!",
        '"',
        "#",
        "$",
        "%",
        "&",
        "'",
        "(",
        ")",
        "*",
        "+",
        ",",
        "-",
        ".",
        "/",
        "0",
        "1",
        "2",
        "3",
        "4",
        "5",
        "6",
        "7",
        "8",
        "9",
        ":",
        ";",
        "<",
        "=",
        ">",
        "?",
        "@",
        "[",
        "\\",
        "]",
        "^",
        "_",
        "`",
        "a",
        "b",
        "c",
        "d",
        "e",
        "f",
        "g",
        "h",
        "i",
        "j",
        "k",
        "l",
        "m",
        "n",
        "o",
        "p",
        "q",
        "r",
        "s",
        "t",
        "u",
        "v",
        "w",
        "x",
        "y",
        "z",
        "{",
        "|",
        "}",
        "~",
        "accept",
        "add",
        "alt",
        "altleft",
        "altright",
        "apps",
        "backspace",
        "browserback",
        "browserfavorites",
        "browserforward",
        "browserhome",
        "browserrefresh",
        "browsersearch",
        "browserstop",
        "capslock",
        "clear",
        "convert",
        "ctrl",
        "ctrlleft",
        "ctrlright",
        "decimal",
        "del",
        "delete",
        "divide",
        "down",
        "end",
        "enter",
        "esc",
        "escape",
        "execute",
        "f1",
        "f10",
        "f11",
        "f12",
        "f13",
        "f14",
        "f15",
        "f16",
        "f17",
        "f18",
        "f19",
        "f2",
        "f20",
        "f21",
        "f22",
        "f23",
        "f24",
        "f3",
        "f4",
        "f5",
        "f6",
        "f7",
        "f8",
        "f9",
        "final",
        "fn",
        "hanguel",
        "hangul",
        "hanja",
        "help",
        "home",
        "insert",
        "junja",
        "kana",
        "kanji",
        "launchapp1",
        "launchapp2",
        "launchmail",
        "launchmediaselect",
        "left",
        "modechange",
        "multiply",
        "nexttrack",
        "nonconvert",
        "num0",
        "num1",
        "num2",
        "num3",
        "num4",
        "num5",
        "num6",
        "num7",
        "num8",
        "num9",
        "numlock",
        "pagedown",
        "pageup",
        "pause",
        "pgdn",
        "pgup",
        "playpause",
        "prevtrack",
        "print",
        "printscreen",
        "prntscrn",
        "prtsc",
        "prtscr",
        "return",
        "right",
        "scrolllock",
        "select",
        "separator",
        "shift",
        "shiftleft",
        "shiftright",
        "sleep",
        "space",
        "stop",
        "subtract",
        "tab",
        "up",
        "volumedown",
        "volumemute",
        "volumeup",
        "win",
        "winleft",
        "winright",
        "yen",
        "command",
        "option",
        "optionleft",
        "optionright",
    ];
    public const KEYBOARD_KEYS = self::KEY_NAMES;  # keeping old KEYBOARD_KEYS for backwards compatibility

    # Constants for the mouse button names:
    public const LEFT = "left";
    public const MIDDLE = "middle";
    public const RIGHT = "right";
    public const PRIMARY = "primary";
    public const SECONDARY = "secondary";

    public const MINIMUM_DURATION = 0.1;
    public const MINIMUM_SLEEP = 0.05;
    public const PAUSE = 0.1;
    public const DARWIN_CATCH_UP_TIME = 0.01;
    public const FAILSAFE = true;
    public const FAILSAFE_POINTS = [[0, 0]];

    public function __construct(private platform $platform)
    {
    }

    /**
     * Returns an (x, y) tuple of the point that has progressed a proportion ``n`` along the line defined by the two 
     * ``x1``, ``y1`` and ``x2``, ``y2`` coordinates. 
     * This function was copied from pytweening module, so that it can be called even if PyTweening is not installed.
     * @param int $x1 
     * @param int $y1 
     * @param int $x2 
     * @param int $y2 
     * @param int $n 
     * @return array 
     */
    public function getPointOnLine($x1, $y1, $x2, $y2, $n)
    {
        $x = (($x2 - $x1) * $n) + $x1;
        $y = (($y2 - $y1) * $n) + $y1;
        return [$x, $y];
    }

    /**
     * Returns ``n``, where ``n`` is the float argument between ``0.0`` and ``1.0``. This function is for the default
     * linear tween for mouse moving functions.
     * This function was copied from PyTweening module, so that it can be called even if PyTweening is not installed.
     * @param float $n 
     * @return float 
     */
    public function linear($n)
    {
        if (!($n >= 0.0 && $n <= 1.0)) {
            throw new \Exception("Argument must be between 0.0 and 1.0.");
        }
        return $n;
    }

    /**
     * A helper function for performing a pause at the end of a PhpAutoGUI function based on some settings.
     * If ``_pause`` is ``True``, then sleep for ``PAUSE`` seconds (the global pause setting).
     * @param bool $pause 
     * @return void 
     */
    public function handlePause($pause)
    {
        if ($pause) {
            assert(is_int(self::PAUSE) || is_float(self::PAUSE));
            usleep(self::PAUSE * 1_000_000);
        }
    }

    /**
     * Returns a ``Point`` object based on ``firstArg`` and ``secondArg``, which are the first two arguments passed to
     * several PhpAutoGUI functions. If ``firstArg`` and ``secondArg`` are both ``None``, returns the current mouse cursor
     * position.
     * @param null|int|array $x 不支持string（尚未移植pyscreenze、pygetwindow）
     * @param null|int|array $y 
     * @return int[] 
     */
    public function normalizeXYArgs($x, $y)
    {
        if (!isset($x) && !isset($y)) {
            return $this->position();
        }
        if (!isset($x) && isset($y)) {
            return [$this->position()[0], (int)$y];
        }
        if (isset($x) && !isset($y)) {
            return [(int)$x, $this->position()[1]];
        }
        if (is_array($x)) {
            $x = array_values($x);
            return [(int)$x[0], (int)$x[1]];
        }
        return [(int)$x, (int)$y];
    }

    /**
     * Returns the current xy coordinates of the mouse cursor as a two-integer tuple.
     * @param ?int $x If not None, this argument overrides the x in the return value.
     * @param ?int $y If not None, this argument overrides the y in the return value.
     * @return int[] 
     */
    public function position($x = null, $y = null)
    {
        [$posx, $posy] = $this->platform->position();
        if (isset($x)) {  # If set, the x parameter overrides the return value.
            $posx = (int)$x;
        }
        if (isset($y)) {  # If set, the y parameter overrides the return value.
            $posy = (int)$y;
        }
        return [(int)$posx, (int)$posy];
    }

    public function size()
    {
        [$width, $height] = $this->platform->size();
        return [(int)$width, (int)$height];
    }

    public function resolution()
    {
        return $this->size();
    }

    public function onScreen($x, $y = null)
    {
        [$x, $y] = $this->normalizeXYArgs($x, $y);
        $x = (int)$x;
        $y = (int)$y;
        [$width, $height] = $this->size();
        return ($x >= 0 && $x < $width && $y >= 0 && $y < $height);
    }

    public function normalizeButton($button)
    {
        $button = strtolower($button);
        if (!in_array($button, [self::LEFT, self::MIDDLE, self::RIGHT, self::PRIMARY, self::SECONDARY, 1, 2, 3])) {
            throw new \InvalidArgumentException("button argument must be one of ('left', 'middle', 'right', 'primary', 'secondary', 1, 2, 3)");
        }
        if (in_array($button, [self::PRIMARY, self::SECONDARY])) {
            return $this->platform->mouseIsSwapped() ? [self::LEFT, self::RIGHT][$button == self::PRIMARY]
                : [self::RIGHT, self::LEFT][$button == self::PRIMARY];
        }
        return [
            self::LEFT => self::LEFT,
            self::MIDDLE => self::MIDDLE,
            self::RIGHT => self::RIGHT,
            1 => self::LEFT,
            2 => self::MIDDLE,
            3 => self::RIGHT,
        ][$button];
    }

    /**
     * Performs pressing a mouse button down (but not up).
     * The x and y parameters detail where the mouse event happens. If None, the
     * current mouse position is used. If a float value, it is rounded down. If
     * outside the boundaries of the screen, the event happens at edge of the
     * screen.
     * @param null|int|float|array $x The x position on the screen where the mouse down happens. None by default. If tuple, this is used for x and y.
     * @param null|int|float|array $y The y position on the screen where the mouse down happens. None by default.
     * @param string|int $button 
     * @param float $duration 
     * @return void 
     */
    public function mouseDown($x = null, $y = null, $button = self::PRIMARY, $duration = 0.0, $tween='linear', $pause = true)
    {
        $button = $this->normalizeButton($button);
        [$x, $y] = $this->normalizeXYArgs($x, $y);
        $this->mouseMoveDrag('move', $x, $y, 0, 0, duration: 0);
        $this->platform->mouseDown($x, $y, $button);
    }

    /**
     * Performs pressing a mouse button up (but not down beforehand).
     * The x and y parameters detail where the mouse event happens. If None, the
     * current mouse position is used. If a float value, it is rounded down. If
     * outside the boundaries of the screen, the event happens at edge of the
     * screen.
     * @param null|int|float|array $x The x position on the screen where the mouse down happens. None by default. If tuple, this is used for x and y.
     * @param null|int|float|array $y The y position on the screen where the mouse down happens. None by default.
     * @param string|int $button 
     * @param float $duration 
     * @return void 
     */
    public function mouseUp($x = null, $y = null, $button = self::PRIMARY, $duration = 0.0, $tween='linear', $pause = true)
    {
        $button = $this->normalizeButton($button);
        [$x, $y] = $this->normalizeXYArgs($x, $y);
        $this->mouseMoveDrag('move', $x, $y, 0, 0, duration: 0);
        $this->platform->mouseUp($x, $y, $button);
    }

    /**
     * Performs pressing a mouse button down and then immediately releasing it. Returns ``None``.
     * When no arguments are passed, the primary mouse button is clicked at the mouse cursor's current location.
     * 
     * 
     * @param null|int|float|array $x If integers for ``x`` and ``y`` are passed, the click will happen at that XY coordinate. If ``x`` is a sequence of two coordinates, those coordinates will be used for the XY coordinate to click on.
     * @param null|int|float|array $y
     * @param int $clicks how many clicks to make, and defaults to ``1``
     * @param float $interval how many seconds to wait in between each click
     * @param string|int $button LEFT|MIDDLE|RIGHT|PRIMARY|SECONDARY, defaults to ``PRIMARY``
     * @param float $duration 
     * @return void 
     */
    public function click($x = null, $y = null, $clicks = 1, $interval = 0.0, $button = self::PRIMARY, $duration = 0.0, $tween='linear', $pause = true)
    {
        $button = $this->normalizeButton($button);
        [$x, $y] = $this->normalizeXYArgs($x, $y);
        $this->mouseMoveDrag('move', $x, $y, 0, 0, duration: 0);
        for ($i = 0; $i < $clicks; $i++) {
            $this->failSafeCheck();
            if (in_array($button, [self::LEFT, self::MIDDLE, self::RIGHT])) {
                $this->platform->click($x, $y, $button);
            }
            usleep($interval * 1_000_000);
        }
    }

    public function leftClick($x = null, $y = null, $interval = 0.0, $duration = 0.0, $tween='linear', $pause = true)
    {
        $this->click($x, $y, 1, $interval, self::LEFT, $duration, pause: $pause);
    }

    public function rightClick($x = null, $y = null, $interval = 0.0, $duration = 0.0, $tween='linear', $pause = true)
    {
        $this->click($x, $y, 1, $interval, self::RIGHT, $duration, pause: $pause);
    }

    public function middleclick($x = null, $y = null, $interval = 0.0, $duration = 0.0, $tween='linear', $pause = true)
    {
        $this->click($x, $y, 1, $interval, self::MIDDLE, $duration, pause: $pause);
    }

    public function doubleClick($x = null, $y = null, $interval = 0.0, $button = self::LEFT, $duration = 0.0, $tween='linear', $pause = true)
    {
        $this->click($x, $y, 2, $interval, $button, $duration, pause: $pause);
    }

    public function tripleClick($x = null, $y = null, $interval = 0.0, $button = self::LEFT, $duration = 0.0, $tween='linear', $pause = true)
    {
        $this->click($x, $y, 3, $interval, $button, $duration, pause: $pause);
    }

    public function scroll($clicks, $x = null, $y = null)
    {
        if (is_array($x) && array_is_list($x)) {
            [$x, $y] = $x;
        }
        [$x, $y] = $this->position($x, $y);
        $this->platform->scroll($clicks, $x, $y);
    }

    public function hscroll($clicks, $x = null, $y = null)
    {
        if (is_array($x) && array_is_list($x)) {
            [$x, $y] = $x;
        }
        [$x, $y] = $this->position($x, $y);
        $this->platform->hscroll($clicks, $x, $y);
    }

    public function vscroll($clicks, $x = null, $y = null)
    {
        if (is_array($x) && array_is_list($x)) {
            [$x, $y] = $x;
        }
        [$x, $y] = $this->position($x, $y);
        $this->platform->vscroll($clicks, $x, $y);
    }

    public function moveTo($x = null, $y = null, $duration = 0.0, $tween='linear', $pause = true)
    {
        [$x, $y] = $this->normalizeXYArgs($x, $y);
        $this->mouseMoveDrag("move", $x, $y, 0, 0, $duration, $tween);
    }

    public function moveRel($xOffset = null, $yOffset = null, $duration = 0.0, $tween='linear', $pause = true)
    {
        [$xOffset, $yOffset] = $this->normalizeXYArgs($xOffset, $yOffset);
        $this->mouseMoveDrag("move", null, null, $xOffset, $yOffset, $duration, $tween);
    }

    public function move($xOffset = null, $yOffset = null, $duration = 0.0, $tween='linear', $pause = true)
    {
        return $this->moveRel($xOffset, $yOffset, $duration, $tween);
    }

    public function dragTo($x = null, $y = null, $duration = 0.0, $tween = 'linear', $button = self::PRIMARY, $pause = true, $mouseDownUp = true)
    {
        [$x, $y] = $this->normalizeXYArgs($x, $y);
        if ($mouseDownUp) {
            $this->mouseDown(button: $button, pause: false);
        }
        $this->mouseMoveDrag("drag", $x, $y, 0, 0, $duration, $tween, $button);
        if ($mouseDownUp) {
            $this->mouseUp(button: $button, pause: false);
        }
    }

    public function dragRel($xOffset = null, $yOffset = null, $duration = 0.0, $tween = 'linear', $button = self::PRIMARY, $pause = true, $mouseDownUp = true)
    {
        if (!isset($xOffset)) {
            $xOffset = 0;
        }
        if (!isset($yOffset)) {
            $yOffset = 0;
        }
        if (is_array($xOffset) && array_is_list($xOffset)) {
            [$xOffset, $yOffset] = $xOffset;
        }
        if ($xOffset == 0 && $yOffset == 0) {
            return;
        }
        [$mousex, $mousey] = $this->platform->position();
        if ($mouseDownUp) {
            $this->mouseDown(button: $button, pause: false);
        }
        $this->mouseMoveDrag("drag", $mousex, $mousey, $xOffset, $yOffset, $duration, $tween, $button);
        if ($mouseDownUp) {
            $this->mouseUp(button: $button, pause: false);
        }
    }

    public function drag($xOffset = null, $yOffset = null, $duration = 0.0, $tween = 'linear', $button = self::PRIMARY, $mouseDownUp = true)
    {
        return $this->dragRel($xOffset, $yOffset, $duration, $tween, $button, $mouseDownUp);
    }

    /**
     * Handles the actual move or drag event, since different platforms implement them differently.
     * On Windows & Linux, a drag is a normal mouse move while a mouse button is
     * held down. On OS X, a distinct "drag" event must be used instead.
     * The code for moving and dragging the mouse is similar, so this function
     * handles both. Users should call the moveTo() or dragTo() functions instead
     * of calling _mouseMoveDrag().
     * @param string $moveOrDrag move|drag
     * @param null|int|float|array $x 
     * @param null|int|float|array $y 
     * @param null|int|float|array $xOffset 
     * @param null|int|float|array $yOffset 
     * @param float $duration 
     * @param string $tween 
     * @param string|int $button 
     * @return void 
     */
    public function mouseMoveDrag($moveOrDrag, $x, $y, $xOffset, $yOffset, $duration, $tween = 'linear', $button = null)
    {
        # The move and drag code is similar, but OS X requires a special drag event instead of just a move event when dragging.
        # See https://stackoverflow.com/a/2696107/1893164
        assert(in_array($moveOrDrag, ["move", "drag"]), "moveOrDrag must be in ('move', 'drag'), not " . $moveOrDrag);
        // Only OS X needs the drag event specifically.
        $moveOrDrag = 'move';
        $xOffset = isset($xOffset) ? (int)$xOffset : 0;
        $yOffset = isset($yOffset) ? (int)$yOffset : 0;

        if (!isset($x) && !isset($y) && $xOffset == 0 && $yOffset == 0) {
            return;
        }

        [$startx, $starty] = $this->position();
        $x = isset($x) ? (int)$x : $startx;
        $y = isset($y) ? (int)$y : $starty;

        # x, y, xOffset, yOffset are now int.
        $x += $xOffset;
        $y += $yOffset;

        [$width, $height] = $this->size();

        # Make sure x and y are within the screen bounds.
        # x = max(0, min(x, width - 1))
        # y = max(0, min(y, height - 1))

        # If the duration is small enough, just move the cursor there instantly.
        $steps = [[$x, $y]];

        if ($duration > self::MINIMUM_DURATION) {
            $num_steps = max($width, $height);
            $sleep_amount = $duration / $num_steps;
            if ($sleep_amount < self::MINIMUM_SLEEP) {
                $num_steps = intval($duration / self::MINIMUM_SLEEP);
                $sleep_amount = $duration / $num_steps;
            }

            for ($i = 0; $i < $num_steps; $i++) {
                $steps[] = $this->getPointOnLine($startx, $starty, $x, $y, $this->$tween($i / $num_steps));
            }
            $steps[] = [$x, $y];
        }

        foreach ($steps as [$tweenX, $tweenY]) {
            if (count($steps) > 1) {
                usleep($sleep_amount * 1_000_000);
            }

            $tweenX = (int)round($tweenX);
            $tweenY = (int)round($tweenY);

            if (!in_array([$tweenX, $tweenY], self::FAILSAFE_POINTS)) {
                $this->failSafeCheck();
            }

            if ($moveOrDrag == 'move') {
                $this->platform->moveTo($tweenX, $tweenY);
            } else {
                throw new \Exception("Unknown value of moveOrDrag: " . $moveOrDrag);
            }

            if (!in_array([$tweenX, $tweenY], self::FAILSAFE_POINTS)) {
                $this->failSafeCheck();
            }
        }
    }

    public function isValidKey($key)
    {
        return $this->platform->isValidKey($key);
    }

    public function keyDown($key)
    {
        if (strlen($key) > 1) {
            $key = strtolower($key);
        }

        $this->platform->keyDown($key);
    }

    public function keyUp($key)
    {
        if (strlen($key) > 1) {
            $key = strtolower($key);
        }

        $this->platform->keyUp($key);
    }

    /**
     * Performs a keyboard key press down, followed by a release.
     * @param string|array $keys The key to be pressed. The valid names are listed in KEYBOARD_KEYS, Can also be a list of such strings.
     * @param int $presses The number of press repetitions. 1 by default, for just one press.
     * @param float $interval How many seconds between each press. 0.0 by default, for no pause between presses.
     * @return void 
     */
    public function press($keys, $presses = 1, $interval = 0.0)
    {
        $keys = array_map(fn ($e) => [$e, strtolower($e)][strlen($e) > 1], (array)$keys);
        for ($i = 0; $i < $presses; $i++) {
            foreach ($keys as $k) {
                $this->failSafeCheck();
                $this->keyDown($k);
                $this->keyUp($k);
            }
            usleep($interval * 1_000_000);
        }
    }

    public function hold($keys)
    {
        $keys = array_map(fn ($e) => [$e, strtolower($e)][strlen($e) > 1], (array)$keys);
        foreach ($keys as $k) {
            $this->failSafeCheck();
            $this->keyDown($k);
        }
        try {
            yield;
        } finally {
            foreach ($keys as $k) {
                $this->failSafeCheck();
                $this->keyUp($k);
            }
        }
    }

    /**
     * Performs a keyboard key press down, followed by a release, for each of the characters in message.
     * @param string|array $message If a string, then the characters to be pressed. If a list, then the key names of the keys to press in order. The valid names are listed in KEYBOARD_KEYS
     * @param float $interval The number of seconds in between each press. 0.0 by default, for no pause in between presses.
     * @return void 
     */
    public function typewrite($message, $interval = 0.0)
    {
        foreach (str_split($message) as $key) {
            if (strlen($key) > 1) {
                $key = strtolower($key);
            }
            $this->press($key);
            usleep($interval * 1_000_000);
            $this->failSafeCheck();
        }
    }

    public function write($message, $interval = 0.0)
    {
        $this->typewrite($message, $interval);
    }

    public function hotkey($keys, $interval = 0.0)
    {
        $interval = floatval($interval);
        $keys = (array)$keys;
        foreach ($keys as $key) {
            if (strlen($key) > 1) {
                $key = strtolower($key);
            }
            $this->platform->keyDown($key);
            usleep($interval * 1_000_000);
        }

        foreach (array_reverse($keys) as $key) {
            if (strlen($key) > 1) {
                $key = strtolower($key);
            }
            $this->platform->keyUp($key);
            usleep($interval * 1_000_000);
        }
    }

    public function shortcut($keys, $interval = 0.0)
    {
        return $this->hotkey($keys, $interval);
    }

    public function failSafeCheck()
    {
        if (self::FAILSAFE && in_array($this->position(), self::FAILSAFE_POINTS)) {
            throw new \Exception('PhpAutoGUI fail-safe triggered from mouse moving to a corner of the screen. To disable this fail-safe, set phpautogui.FAILSAFE to False. DISABLING FAIL-SAFE IS NOT RECOMMENDED.');
        }
    }
}
