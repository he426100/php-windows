typedef unsigned short WCHAR;
typedef WCHAR *LPWSTR;
typedef unsigned long DWORD;
typedef unsigned int UINT;
typedef unsigned long WPARAM;
typedef UINT HWND;
typedef long LPARAM;
typedef int BOOL;

typedef struct tagPOINT
{
    long x;
    long y;
} POINT, *PPOINT;

typedef struct
{
    long left;
    long top;
    long right;
    long bottom;
} RECT;

HWND GetForegroundWindow();
int GetWindowTextLengthW(HWND hWnd);
int GetWindowTextW(HWND hWnd, LPWSTR lpString, int nMaxCount);
BOOL EnumWindows(void (*)(HWND, LPARAM), LPARAM);
BOOL IsWindowVisible(HWND hWnd);
BOOL GetWindowRect(HWND hWnd, RECT *);
BOOL ShowWindow(HWND hWnd, int nCmdShow);
BOOL PostMessageA(HWND hWnd, UINT Msg, WPARAM wParam, LPARAM lParam);
BOOL SetWindowPos(HWND hWnd, HWND hWndInsertAfter, int X, int Y, int cx, int cy, UINT uFlags);
BOOL IsIconic(HWND hWnd);
BOOL IsZoomed(HWND hWnd);
BOOL GetCursorPos(POINT *);
int GetSystemMetrics(int nIndex);
BOOL SetForegroundWindow(HWND hWnd);
