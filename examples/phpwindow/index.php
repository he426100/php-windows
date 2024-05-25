<?php

require __DIR__ . '/../../vendor/autoload.php';

use FFI\CData;
use Local\Driver\Win32\Lib\Ole32;
use Local\Driver\Win32\Lib\User32;
use Local\Driver\Win32\Lib\Kernel32;
use Local\Driver\Win32\Lib\WindowStyle;
use Local\Driver\Win32\Lib\WindowExtendedStyle;
use Local\Driver\Win32\Lib\WindowClassStyle;
use Local\Driver\Win32\Lib\Cursor;
use Local\Driver\Win32\Lib\Icon;
use Local\Driver\Win32\Lib\Color;
use Local\Driver\Win32\Lib\WindowMessage;
use He426100\phpgetwindow\platforms\windows\kernel32 as WinKernel32;
use He426100\phpgetwindow\platforms\windows\Win32Window;

final class CreateInfo
{
    /**
     * @var int<0, max>
     */
    public const DEFAULT_WIDTH = 640;

    /**
     * @var int<0, max>
     */
    public const DEFAULT_HEIGHT = 480;

    /**
     * @param int<0, max> $width
     * @param int<0, max> $height
     */
    public function __construct(
        public string $title = '',
        public int $width = self::DEFAULT_WIDTH,
        public int $height = self::DEFAULT_HEIGHT,
        public bool $resizable = false,
        public bool $visible = true,
        public bool $debug = false,
    ) {}
}

final class Application
{
    private const COINIT_APARTMENTTHREADED = 0x02;
    private const CW_USER_DEFAULT = 0x8000_0000;

    private bool $isRunning = false;
    private CData $message;
    private Win32Window $windows;
    private Ole32 $ole32;
    private User32 $user32;
    private Kernel32 $kernel32;

    private const DEFAULT_EX_WINDOW_STYLE = WindowExtendedStyle::WS_EX_LTRREADING
        | WindowExtendedStyle::WS_EX_LEFT
        | WindowExtendedStyle::WS_EX_RIGHTSCROLLBAR
        | WindowExtendedStyle::WS_EX_APPWINDOW;

    private const DEFAULT_WINDOW_STYLE = WindowStyle::WS_OVERLAPPEDWINDOW
        | WindowStyle::WS_CLIPSIBLINGS
        | WindowStyle::WS_CLIPCHILDREN
        | WindowStyle::WS_POPUP;

    public function __construct()
    {
        $this->ole32 = new Ole32();
        $this->user32 = new User32();
        $this->kernel32 = new Kernel32();
        $this->message = $this->createMessage();
        $this->ole32->CoInitializeEx(null, self::COINIT_APARTMENTTHREADED);
    }

    public function run(): void
    {
        if ($this->isRunning === true) {
            return;
        }

        $this->isRunning = true;

        $this->createWindow();

        // @phpstan-ignore-next-line
        while ($this->isRunning) {
            if ($this->user32->GetMessageW($this->message, null, 0, 0)) {
                $this->user32->TranslateMessage($this->message);
                $this->user32->DispatchMessageW($this->message);
            }
            \usleep(1);
        }
    }

    public function isRunning(): bool
    {
        return $this->isRunning;
    }

    private function createWindow(): void
    {
        $info = new CreateInfo(
            title: 'Example Application',
            resizable: true,
        );
        $instance = $this->kernel32->GetModuleHandleA(null);
        $class = $this->createWindowClass($info, $instance);
        $process = $this->registerWindowFunction($class);
        $ptr = $this->registerWindowClass($process);
        $hwnd = $this->user32->CreateWindowExW(
            /* DWORD     dwExStyle */
            self::DEFAULT_EX_WINDOW_STYLE,
            /* LPCSTR    lpClassName */
            string2wchar(__CLASS__, false),
            /* LPCSTR    lpWindowName */
            string2wchar($info->title, false),
            /* DWORD     dwStyle */
            self::DEFAULT_WINDOW_STYLE,
            /* int       X */
            self::CW_USER_DEFAULT,
            /* int       Y */
            self::CW_USER_DEFAULT,
            /* int       nWidth */
            500,
            /* int       nHeight */
            300,
            /* HWND      hWndParent */
            null,
            /* HMENU     hMenu */
            null,
            /* HINSTANCE hInstance */
            $instance,
            /* LPVOID    lpPara */
            null
        );
        if (!$hwnd) {
            $kernel32 = new WinKernel32();
            throw new \Exception('Could not create window: ' . $kernel32->formatMessage($kernel32->getLastError()));
        }

        $this->windows = new Win32Window($hwnd);
        $this->windows->show();
    }

    private function registerWindowClass(CData $info): CData
    {
        if (!$this->user32->RegisterClassW(\FFI::addr($info))) {
            $kernel32 = new WinKernel32();
            throw new \Exception('Could not initialize window class: ' . $kernel32->formatMessage($kernel32->getLastError()));
        }

        return $info;
    }

    private function registerWindowFunction(CData $info): CData
    {
        // @phpstan-ignore-next-line
        $info->lpfnWndProc = function (mixed $hWnd, int $msg, int $wParam, int $lParam): int {
            $window = new Win32Window($hWnd);

            if ($window !== null) {
                $result = $this->processWindow($window, $msg, $wParam, $lParam);

                if ($result !== null) {
                    return $result;
                }
            }

            return $this->user32->DefWindowProcW($hWnd, $msg, $wParam, $lParam);
        };

        return $info;
    }

    private function createWindowClass(CreateInfo $info, CData $instance): CData
    {
        $class = $this->user32->new('WNDCLASSW');

        if ($class === null) {
            throw new \RuntimeException('Could not create window class structure');
        }

        $class->hCursor = $this->user32->LoadCursorW(null, Cursor::IDC_ARROW->toCData());
        $class->cbClsExtra = 0;
        $class->cbWndExtra = 0;
        $class->style = WindowClassStyle::CS_HREDRAW | WindowClassStyle::CS_VREDRAW | WindowClassStyle::CS_OWNDC;
        $class->hIcon = $this->user32->LoadIconW(null, Icon::IDI_APPLICATION->toCData());
        $class->hInstance = $instance;
        $class->lpszMenuName = null;
        $class->lpszClassName = string2wchar(__CLASS__, false);
        $class->hbrBackground = $this->user32->GetSysColorBrush(Color::COLOR_WINDOW);

        /** @var CData */
        return $class;
    }

    private function processWindow(Win32Window $window, int $msg, int $wParam, int $lParam): ?int
    {
        switch ($msg) {
            case WindowMessage::WM_CLOSE:
                echo 'processWindow: WM_CLOSE' . PHP_EOL;
                $this->stop();
                return 0;

            case WindowMessage::WM_SETFOCUS:
                echo 'processWindow: WM_SETFOCUS' . PHP_EOL;
                return 0;

            case WindowMessage::WM_KILLFOCUS:
                echo 'processWindow: WM_KILLFOCUS' . PHP_EOL;
                return 0;

            case WindowMessage::WM_MOVE:
                echo 'processWindow: WM_MOVE, ' . self::loWord($lParam) . ', ' . self::hiWord($lParam). PHP_EOL;
                return 0;

            case WindowMessage::WM_ACTIVATE:
                if ($wParam !== 1) {
                    echo 'processWindow: WM_ACTIVATE, Hidden' . PHP_EOL;
                } else {
                    echo 'processWindow: WM_ACTIVATE, Shown' . PHP_EOL;
                }
                return 0;

            case WindowMessage::WM_CAPTURECHANGED:
                // change full size/normal states
                echo sprintf("[%08d] %s\n", $msg, WindowMessage::get($msg)), PHP_EOL;
                return 0;

            case WindowMessage::WM_SIZE:
                echo 'processWindow: WM_SIZE, ' . \max(0, self::loWord($lParam)) . ', ' . \max(0, self::hiWord($lParam)) . PHP_EOL;
                return 0;
        }
        return null;
    }

    private static function loWord(int $value): int
    {
        return ($value &= 0xffff) > 32767 ? $value - 65535 : $value;
    }

    /**
     * ```
     *  (unsigned short)((unsigned long)value >> 16) & 0xffff
     * ```
     */
    private static function hiWord(int $value): int
    {
        $value = ($value >> 16) & 0xffff;

        return $value > 32767 ? $value - 65535 : $value;
    }

    private function createMessage(): CData
    {
        // @phpstan-ignore-next-line
        return \FFI::addr($this->user32->new('MSG'));
    }

    public function stop(): void
    {
        if ($this->isRunning === false) {
            return;
        }
        $this->isRunning = false;
    }

    public function __destruct()
    {
        $this->ole32->CoUninitialize();
    }
}

(new Application)->run();
