<?php

declare(strict_types=1);

namespace Local\Driver\Win32\Lib;

final class SystemMetrics
{
    public const SM_CXSCREEN = 0;
    public const SM_CYSCREEN = 1;
    public const SM_CXVSCROLL = 2;
    public const SM_CYHSCROLL = 3;
    public const SM_CYCAPTION = 4;
    public const SM_CXBORDER = 5;
    public const SM_CYBORDER = 6;
    public const SM_CXDLGFRAME = 7;
    public const SM_CYDLGFRAME = 8;
    public const SM_CYVTHUMB = 9;
    public const SM_CXHTHUMB = 10;
    public const SM_CXICON = 11;
    public const SM_CYICON = 12;
    public const SM_CXCURSOR = 13;
    public const SM_CYCURSOR = 14;
    public const SM_CYMENU = 15;
    public const SM_CXFULLSCREEN = 16;
    public const SM_CYFULLSCREEN = 17;
    public const SM_CYKANJIWINDOW = 18;
    public const SM_MOUSEPRESENT = 19;
    public const SM_CYVSCROLL = 20;
    public const SM_CXHSCROLL = 21;
    public const SM_DEBUG = 22;
    public const SM_SWAPBUTTON = 23;
    public const SM_RESERVED1 = 24;
    public const SM_RESERVED2 = 25;
    public const SM_RESERVED3 = 26;
    public const SM_RESERVED4 = 27;
    public const SM_CXMIN = 28;
    public const SM_CYMIN = 29;
    public const SM_CXSIZE = 30;
    public const SM_CYSIZE = 31;
    public const SM_CXFRAME = 32;
    public const SM_CYFRAME = 33;
    public const SM_CXMINTRACK = 34;
    public const SM_CYMINTRACK = 35;
    public const SM_CXDOUBLECLK = 36;
    public const SM_CYDOUBLECLK = 37;
    public const SM_CXICONSPACING = 38;
    public const SM_CYICONSPACING = 39;
    public const SM_MENUDROPALIGNMENT = 40;
    public const SM_PENWINDOWS = 41;
    public const SM_DBCSENABLED = 42;
    public const SM_CMOUSEBUTTONS = 43;
    public const SM_CXFIXEDFRAME = self::SM_CXDLGFRAME;  /* ;win40 name change */
    public const SM_CYFIXEDFRAME = self::SM_CYDLGFRAME;  /* ;win40 name change */
    public const SM_CXSIZEFRAME = self::SM_CXFRAME;  /* ;win40 name change */
    public const SM_CYSIZEFRAME = self::SM_CYFRAME;  /* ;win40 name change */
    public const SM_SECURE = 44;
    public const SM_CXEDGE = 45;
    public const SM_CYEDGE = 46;
    public const SM_CXMINSPACING = 47;
    public const SM_CYMINSPACING = 48;
    public const SM_CXSMICON = 49;
    public const SM_CYSMICON = 50;
    public const SM_CYSMCAPTION = 51;
    public const SM_CXSMSIZE = 52;
    public const SM_CYSMSIZE = 53;
    public const SM_CXMENUSIZE = 54;
    public const SM_CYMENUSIZE = 55;
    public const SM_ARRANGE = 56;
    public const SM_CXMINIMIZED = 57;
    public const SM_CYMINIMIZED = 58;
    public const SM_CXMAXTRACK = 59;
    public const SM_CYMAXTRACK = 60;
    public const SM_CXMAXIMIZED = 61;
    public const SM_CYMAXIMIZED = 62;
    public const SM_NETWORK = 63;
    public const SM_CLEANBOOT = 67;
    public const SM_CXDRAG = 68;
    public const SM_CYDRAG = 69;
    public const SM_SHOWSOUNDS = 70;
    public const SM_CXMENUCHECK = 71;
    public const SM_CYMENUCHECK = 72;
    public const SM_SLOWMACHINE = 73;
    public const SM_MIDEASTENABLED = 74;
    public const SM_MOUSEWHEELPRESENT = 75;
    public const SM_XVIRTUALSCREEN = 76;
    public const SM_YVIRTUALSCREEN = 77;
    public const SM_CXVIRTUALSCREEN = 78;
    public const SM_CYVIRTUALSCREEN = 79;
    public const SM_CMONITORS = 80;
    public const SM_SAMEDISPLAYFORMAT = 81;
    public const SM_IMMENABLED = 82;
    public const SM_CXFOCUSBORDER = 83;
    public const SM_CYFOCUSBORDER = 84;
    public const SM_TABLETPC = 86;
    public const SM_MEDIACENTER = 87;
    public const SM_STARTER = 88;
    public const SM_SERVERR2 = 89;
    public const SM_MOUSEHORIZONTALWHEELPRESENT = 91;
    public const SM_CXPADDEDBORDER = 92;
    public const SM_DIGITIZER = 94;
    public const SM_MAXIMUMTOUCHES = 95;
    public const SM_CMETRICS = 97;
    public const SM_REMOTESESSION = 0x1000;
    public const SM_SHUTTINGDOWN = 0x2000;
    public const SM_REMOTECONTROL = 0x2001;
    public const SM_CARETBLINKINGENABLED = 0x2002;
    public const SM_CONVERTIBLESLATEMODE = 0x2003;
    public const SM_SYSTEMDOCKED = 0x2004;
}
