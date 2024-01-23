typedef unsigned long       DWORD;
typedef unsigned long long  ULONG_PTR;
typedef long                LONG;
typedef void*               PVOID;
typedef PVOID               HANDLE;
typedef char                TCHAR;
typedef unsigned short      WCHAR;
typedef int                 BOOL;
typedef const void          *LPCVOID;
typedef WCHAR               *LPWSTR;
typedef void*               va_list;
typedef HANDLE              HLOCAL;

typedef struct _PROCESSENTRY32 {
    DWORD     dwSize;
    DWORD     cntUsage;
    DWORD     th32ProcessID;
    ULONG_PTR th32DefaultHeapID;
    DWORD     th32ModuleID;
    DWORD     cntThreads;
    DWORD     th32ParentProcessID;
    LONG      pcPriClassBase;
    DWORD     dwFlags;
    TCHAR     szExeFile[260];
} PROCESSENTRY32;

HANDLE CreateToolhelp32Snapshot(DWORD dwFlags, DWORD th32ProcessID);
BOOL Process32First(HANDLE hSnapshot, PROCESSENTRY32 *lppe);
BOOL Process32Next(HANDLE hSnapshot, PROCESSENTRY32 *lppe);
BOOL CloseHandle(HANDLE hObject);
DWORD GetLastError();

DWORD FormatMessageW(
  DWORD   dwFlags,
  LPCVOID lpSource,
  DWORD   dwMessageId,
  DWORD   dwLanguageId,
  LPWSTR  lpBuffer,
  DWORD   nSize,
  va_list *Arguments
);
void LocalFree(HLOCAL hMem);
