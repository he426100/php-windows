# phpautogui

### 简介
基于[php ffi](https://www.php.net/manual/zh/book.ffi.php)，移植`pyautogui`、`pymsgbox`

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
$msg->alert('php is the best language.', 'tips');
echo $msg->confirm('php is the best language.', 'tips'), PHP_EOL;
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
