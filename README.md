# phpautogui

### 简介
基于[php ffi](https://www.php.net/manual/zh/book.ffi.php)，移植`pyautogui`、`pymsgbox`、`pygetwindow`

### 示例
- phpautogui notepad
```php
<?php

require __DIR__ . '/../vendor/autoload.php';

use He426100\phpautogui\phpautogui;
use He426100\phpautogui\platforms\windows\windows;

$auto = new phpautogui(new windows);
$auto->press('win');
usleep(0.2 * 1000_000);

$auto->typewrite('notepad.exe', 0.1);
usleep(0.2 * 1000_000);

$auto->press('enter');
usleep(2 * 1000_000);

$auto->typewrite('PHP is the best language.', 0.1);
```

- phpautogui mouse
```php
<?php

require __DIR__ . '/../vendor/autoload.php';

use He426100\phpautogui\phpautogui;
use He426100\phpautogui\platforms\windows\windows;

$auto = new phpautogui(new windows);
$size = $auto->size();
$position = $auto->position();
echo 'currentX: ' . $position[0], ', currentY: ' . $position[1], ', width: ' . $size[0], ', height: ' . $size[1], PHP_EOL;

$s = microtime(true);
$central = [$size[0] / 2, $size[1] / 2];
$auto->moveTo($central[0], $central[1], 0.5);
assert($auto->position() == $central);
echo 'moved to (', $central[0], ', ', $central[1], '), used ' . (microtime(true) - $s) . ' s', PHP_EOL;
```

- phpmsgbox
```php
<?php

require __DIR__ . '/../vendor/autoload.php';

use He426100\phpmsgbox\phpmsgbox;
use He426100\phpmsgbox\platforms\windows\windows;

$msg = new phpmsgbox(new windows);
$msg->alert('php是最好的语言。', '温馨提示');
echo $msg->confirm('php是最好的语言。', '温馨提示'), PHP_EOL;
```

- phpgetwindow
```
<?php

require __DIR__ . '/../vendor/autoload.php';

use He426100\phpgetwindow\phpgetwindow;
use He426100\phpgetwindow\platforms\windows\windows;

$native = new windows;
$window = new phpgetwindow($native);
echo "活动窗口标题: " . $window->getActiveWindowTitle() . "\n";
echo "所有窗口标题:\n";
print_r($window->getAllTitles());
```

### 如何写cdef
1. 数据类型  
- [windows-data-types](https://learn.microsoft.com/en-us/windows/win32/winprog/windows-data-types)  
- [rect](https://learn.microsoft.com/en-us/windows/win32/api/windef/ns-windef-rect)  
- [point](https://learn.microsoft.com/en-us/windows/win32/api/windef/ns-windef-point)  
- 用`vs_BuildTools`下载`windows sdk`后在`C:\Program Files (x86)\Windows Kits\10\Include\10.0.17134.0\um`中找到对应的头文件，比如`winint.h`  

2. 函数接口  
- [getcursorpos](https://learn.microsoft.com/en-us/windows/win32/api/winuser/nf-winuser-getcursorpos)  
- [getdesktopwindow](https://learn.microsoft.com/en-us/windows/win32/api/winuser/nf-winuser-getdesktopwindow)  
- [GetWindowRect](https://learn.microsoft.com/en-us/windows/win32/api/winuser/nf-winuser-getwindowrect)  
- [keybd_event](https://learn.microsoft.com/en-us/windows/win32/api/winuser/nf-winuser-keybd_event)  
- [VkKeyScanA](https://learn.microsoft.com/en-us/windows/win32/api/winuser/nf-winuser-VkKeyScanA)  
- [MessageBoxA](https://learn.microsoft.com/en-us/windows/win32/api/winuser/nf-winuser-MessageBoxA)  
- [MessageBoxW](https://learn.microsoft.com/en-us/windows/win32/api/winuser/nf-winuser-MessageBoxW)  

3. 回调函数
- EnumWindows.WNDENUMPROC  
    原版定义，见 [nf-winuser-EnumWindows](https://learn.microsoft.com/en-us/windows/win32/api/winuser/nf-winuser-EnumWindows)  
    ```c++
    BOOL EnumWindows(
    [in] WNDENUMPROC lpEnumFunc,
    [in] LPARAM      lParam
    );
    ```
    在ffi中写法是  
    ```php
    BOOL EnumWindows(void (*)(HWND, LPARAM), LPARAM);
    ```
    chatglm4的解释如下：
    >> 在 PHP FFI 中，您可以简化这个定义，直接使用 void (*)(HWND, LPARAM) 来表示一个接受 HWND 和 LPARAM 参数的回调函数指针。这是因为 PHP FFI 不需要您预先定义回调函数的型，它允许您直接使用函数定义作为参数类型。  

    千问的解释如下：
    >> 在PHP FFI中，由于不能直接声明一个函数指针类型，因此通常使用void (*)(HWND, LPARAM)来表示这样一个接受特定参数类型的函数指针。这里的void表示回调函数没有返回值（在C中通常是BOOL或者WNDENUMPROC定义的返回类型），圆括号内的(HWND, LPARAM)则是指明回调函数需要接受的参数类型。
