typedef unsigned int UINT;
typedef char TCHAR;
typedef unsigned short WCHAR;
typedef const TCHAR *LPCTSTR;
typedef const WCHAR *LPCWSTR;
typedef LPCTSTR LPCSTR;
typedef UINT HWND;

int MessageBoxA(
  HWND   hWnd,
  LPCSTR lpText,
  LPCSTR lpCaption,
  UINT   uType
);

int MessageBoxW(
  HWND    hWnd,
  LPCWSTR lpText,
  LPCWSTR lpCaption,
  UINT    uType
);
