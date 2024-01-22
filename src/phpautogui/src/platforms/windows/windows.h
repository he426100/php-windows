#define FFI_LIB "user32.dll"

typedef unsigned char BYTE;
typedef unsigned long ULONG;
typedef unsigned long DWORD;
typedef int BOOL;
typedef unsigned int UINT;
typedef long LONG;
typedef unsigned long ULONG_PTR;
typedef void* HWND;
typedef char CHAR;
typedef short SHORT;
typedef unsigned short WCHAR;
typedef void VOID;

typedef struct tagRECT {
    LONG left;
    LONG top;
    LONG right;
    LONG bottom;
} RECT, *PRECT, *NPRECT, *LPRECT;

typedef struct tagPOINT {
    LONG x;
    LONG y;
} POINT, *PPOINT, *NPPOINT, *LPPOINT;

void keybd_event(
    BYTE bVk, 
    BYTE bScan, 
    DWORD dwFlags, 
    ULONG_PTR dwExtraInfo
);

void mouse_event(
    DWORD dwFlags, 
    DWORD dx, 
    DWORD dy, 
    DWORD dwData, 
    ULONG_PTR dwExtraInfo
);

HWND GetDesktopWindow();
BOOL GetCursorPos(POINT *lpPoint);
BOOL SetCursorPos(int X, int Y);
BOOL GetWindowRect(HWND hWnd, RECT *lpRect);
int GetSystemMetrics(int nIndex);
SHORT VkKeyScanA(CHAR ch);
