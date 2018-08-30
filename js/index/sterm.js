//节气子类
function sTerm(year, month, date) {
    var monthLength = D0(year, month + 1, date) - D0(year, month, date);

    var sN0 = 2 * month - 2;
    var sDt0 = S(year, sN0, 1);
    var sD0 = revD0(year, floor(sDt0));
    var sM0 = floor(sD0 / 100);
    sDate0 = sD0 % 100;

    var sN1 = 2 * month - 1;
    var sDt1 = S(year, sN1, 1);
    var sD1 = revD0(year, floor(sDt1));
    var sM1 = floor(sD1 / 100);
    var sDate1 = sD1 % 100;

    var sN2 = 2 * month;
    var sDt2 = S(year, sN2, 1);
    var sD2 = revD0(year, floor(sDt2));
    var sM2 = floor(sD2 / 100);
    var sDate2 = sD2 % 100;

    var sN3 = 2 * month + 1;
    if (sN3 > 24)
        sN3 -= 24;
    var sDt3 = S(year, sN3, 1);
    var sD3 = revD0(year, floor(sDt3));
    var sM3 = floor(sD3 / 100);
    var sDate3 = sD3 % 100;

    if (sM0 == month) {
        sN2 = sN1;
        sN1 = sN0;
        sDt2 = sDt1;
        sDt1 = sDt0;
        sDate2 = sDate1;
        sDate1 = sDate0;
    }

    if (sM3 == month) {
        sN1 = sN2;
        sN2 = sN3;
        sDt1 = sDt2;
        sDt2 = sDt3;
        sDate1 = sDate2;
        sDate2 = sDate3;
    }

    sN1 = rem(sN1 - 1, 24) + 1;
    sN2 = rem(sN2 - 1, 24) + 1;

    if (sDate2 > monthLength) {
        sDate2 -= monthLength;
    }

    if (date == sDate1)
        return sStr(sN1);
    else if (date == sDate2)
        return sStr(sN2);
    else
        return '';
}

//节气函数
function S(y, n, pd) {  //pd取值为0或1，分别表示平气和定气,该函数返回节气的D0值
    var juD = y * (365.2423112 - 6.4e-14 * (y - 100) * (y - 100) - 3.047e-8 * (y - 100)) + 15.218427 * n + 1721050.71301;//儒略日
    var tht = 3e-4 * y - 0.372781384 - 0.2617913325 * n;//角度
    var yrD = (1.945 * sin(tht) - 0.01206 * sin(2 * tht)) * (1.048994 - 2.583e-5 * y);//年差实均数
    var shuoD = -18e-4 * sin(2.313908653 * y - 0.439822951 - 3.0443 * n);//朔差实均数
    var vs = (pd) ? (juD + yrD + shuoD - ESD(y, 1, 0) - 1721425) : (juD - ESD(y, 1, 0) - 1721425);
    return vs;
}

//节气
function sStr(v) {
    return '小寒大寒立春雨水惊蛰春分清明谷雨立夏小满芒种夏至小暑大暑立秋处暑白露秋分寒露霜降立冬小雪大雪冬至'.substring(2 * v - 2, 2 * v);
}

//判断Gregorian历还是Julian历
function ifGr(y, m, d) {  //阳历y年m月(1,2,..,12,下同)d日=1,2,3分别表示标准日历,Gregorge历和Julian历，下同，其中所谓“标准日历是指：1582-10-4之前采用Julian，1582-10-15以后采用Gregorian，1582-10-5 ~ 1582-10-14为空”
    if (y > 1582 || (y == 1582 && m > 10) || (y == 1582 && m == 10 && d > 14))
        return 1;  //Gregorian
    else
    if (y == 1582 && m == 10 && d >= 5 && d <= 14)
        return -1;  //空
    else
        return 0;  //Julian
}

//日差天数
function D0(y, m, d) {
    var ifG = ifGr(y, m, d);
    var monL = new Array(0, 31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);

    if (ifG == -1) {
        return Infinity;
    }

    if (ifG == 1) {
        if ((y % 100 != 0 && y % 4 == 0) || (y % 400 == 0)) {
            monL[2] += 1;
        }
        else ;
    }
    else {
        if (y % 4 == 0) {
            monL[2] += 1;
        }
        else ;
    }

    var v = 0;
    for (var i = 0; i <= m - 1; i++) {
        v += monL[i];
    }
    v += d;

    return v;
}

//反日差天数
function revD0(y, x) {  // y年日差天数D0为x
    var j,m,mL;

    for (j = 1; j <= 12; j++) {
        mL = D0(y, j + 1, 1) - D0(y, j, 1);

        if (x <= mL || j == 12) {
            m = j;
            break;
        }
        else {
            x -= mL;
        }

    }

    if (y == 1582 && m == 10 && x >= 5 && x <= 14) {
        return Infinity;
    }

    return 100 * m + x;
}

//标准天数(Standard Days)(y年m月d日距该历制的1年1月0日的天数)
function SD(y, m, d) {
    if (ifGr(y, m, d) == -1)
        return Infinity;

    if (ifGr(y, m, d) == 1)
        return (y - 1) * 365 + floor((y - 1) / 4) - floor((y - 1) / 100) + floor((y - 1) / 400) + D0(y, m, d);   //Gregorian的标准天数

    else
        return (y - 1) * 365 + floor((y - 1) / 4) + D0(y, m, d);                                     //Julian的标准天数

}

//等效标准天数(Equivalent Standard Days)(y年m月d日距该历制的1年1月0日的天数)
function ESD(y, m, d) {
    if (ifGr(y, m, d) == -1)
        return Infinity;

    if (ifGr(y, m, d) == 1)
        return SD(y, m, d);   //Gregorian的标准天数

    else
        return SD(y, m, d) - 2;   //Julian的标准天数

}

function tail(x) {
    return x - floor(x);
}
//广义求余
function rem(x, w) {
    return tail(x / w) * w;
}

function sin(x) {
    return Math.sin(x);
}

function floor(x) {
    return Math.floor(x);
}


function CalConv() {
    FIRSTYEAR = 1998;
    LASTYEAR = 2031;
    today = new Date();
    SolarYear = today.getFullYear();
    SolarMonth = today.getMonth() + 1;
    SolarDate = today.getDate();
    Weekday = today.getDay();
    LunarCal = [
        new tagLunarCal(27, 5, 3, 43, 1, 0, 0, 1, 0, 0, 1, 1, 0, 1, 1, 0, 1),
        new tagLunarCal(46, 0, 4, 48, 1, 0, 0, 1, 0, 0, 1, 0, 1, 1, 1, 0, 1), /* 88 */
        new tagLunarCal(35, 0, 5, 53, 1, 1, 0, 0, 1, 0, 0, 1, 0, 1, 1, 0, 1), /* 89 */
        new tagLunarCal(23, 4, 0, 59, 1, 1, 0, 1, 0, 1, 0, 0, 1, 0, 1, 0, 1),
        new tagLunarCal(42, 0, 1, 4, 1, 1, 0, 1, 0, 1, 0, 0, 1, 0, 1, 0, 1),
        new tagLunarCal(31, 0, 2, 9, 1, 1, 0, 1, 1, 0, 1, 0, 0, 1, 0, 1, 0),
        new tagLunarCal(21, 2, 3, 14, 0, 1, 0, 1, 1, 0, 1, 0, 1, 0, 1, 0, 1), /* 93 */
        new tagLunarCal(39, 0, 5, 20, 0, 1, 0, 1, 0, 1, 1, 0, 1, 0, 1, 0, 1),
        new tagLunarCal(28, 7, 6, 25, 1, 0, 1, 0, 1, 0, 1, 0, 1, 1, 0, 1, 1),
        new tagLunarCal(48, 0, 0, 30, 0, 0, 1, 0, 0, 1, 0, 1, 1, 1, 0, 1, 1),
        new tagLunarCal(37, 0, 1, 35, 1, 0, 0, 1, 0, 0, 1, 0, 1, 1, 0, 1, 1), /* 97 */
        new tagLunarCal(25, 5, 3, 41, 1, 1, 0, 0, 1, 0, 0, 1, 0, 1, 0, 1, 1),
        new tagLunarCal(44, 0, 4, 46, 1, 0, 1, 0, 1, 0, 0, 1, 0, 1, 0, 1, 1),
        new tagLunarCal(33, 0, 5, 51, 1, 0, 1, 1, 0, 1, 0, 0, 1, 0, 1, 0, 1),
        new tagLunarCal(22, 4, 6, 56, 1, 0, 1, 1, 0, 1, 0, 1, 0, 1, 0, 1, 0), /* 101 */
        new tagLunarCal(40, 0, 1, 2, 1, 0, 1, 1, 0, 1, 0, 1, 0, 1, 0, 1, 0),
        new tagLunarCal(30, 9, 2, 7, 0, 1, 0, 1, 0, 1, 0, 1, 1, 0, 1, 0, 1),
        new tagLunarCal(49, 0, 3, 12, 0, 1, 0, 0, 1, 0, 1, 1, 1, 0, 1, 0, 1),
        new tagLunarCal(38, 0, 4, 17, 1, 0, 1, 0, 0, 1, 0, 1, 1, 0, 1, 1, 0), /* 105 */
        new tagLunarCal(27, 6, 6, 23, 0, 1, 0, 1, 0, 0, 1, 0, 1, 0, 1, 1, 1),
        new tagLunarCal(46, 0, 0, 28, 0, 1, 0, 1, 0, 0, 1, 0, 1, 0, 1, 1, 0),
        new tagLunarCal(35, 0, 1, 33, 0, 1, 1, 0, 1, 0, 0, 1, 0, 0, 1, 1, 0),
        new tagLunarCal(24, 4, 2, 38, 0, 1, 1, 1, 0, 1, 0, 0, 1, 0, 1, 0, 1), /* 109 */
        new tagLunarCal(42, 0, 4, 44, 0, 1, 1, 0, 1, 0, 1, 0, 1, 0, 1, 0, 1),
        new tagLunarCal(31, 0, 5, 49, 1, 0, 1, 0, 1, 1, 0, 1, 0, 1, 0, 1, 0),
        new tagLunarCal(21, 2, 6, 54, 0, 1, 0, 1, 0, 1, 0, 1, 1, 0, 1, 0, 1),
        new tagLunarCal(40, 0, 0, 59, 0, 1, 0, 0, 1, 0, 1, 1, 0, 1, 1, 0, 1), /* 113 */
        new tagLunarCal(28, 6, 2, 5, 1, 0, 1, 0, 0, 1, 0, 1, 0, 1, 1, 1, 0),
        new tagLunarCal(47, 0, 3, 10, 1, 0, 1, 0, 0, 1, 0, 0, 1, 1, 1, 0, 1),
        new tagLunarCal(36, 0, 4, 15, 1, 1, 0, 1, 0, 0, 1, 0, 0, 1, 1, 0, 1),
        new tagLunarCal(25, 5, 5, 20, 1, 1, 1, 0, 1, 0, 0, 1, 0, 0, 1, 1, 0), /* 117 */
        new tagLunarCal(43, 0, 0, 26, 1, 1, 0, 1, 0, 1, 0, 1, 0, 0, 1, 0, 1),
        new tagLunarCal(32, 0, 1, 31, 1, 1, 0, 1, 1, 0, 1, 0, 1, 0, 1, 0, 0),
        new tagLunarCal(22, 3, 2, 36, 0, 1, 1, 0, 1, 0, 1, 1, 0, 1, 0, 1, 0) ];
    /* 民国年月日 */
    SolarCal = [ 31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31 ];
    SolarDays = [ 0, 31, 59, 90, 120, 151, 181, 212, 243, 273, 304, 334, 365, 396, 0, 31, 60, 91, 121, 152, 182, 213, 244, 274, 305, 335, 366, 397 ];

    if (SolarYear <= FIRSTYEAR || SolarYear > LASTYEAR) return 1;
    sm = SolarMonth - 1;
    if (sm < 0 || sm > 11) return 2;
    leap = GetLeap(SolarYear);
    if (sm == 1)
        d = leap + 28;
    else
        d = SolarCal[sm];
    if (SolarDate < 1 || SolarDate > d) return 3;
    y = SolarYear - FIRSTYEAR;
    acc = SolarDays[ leap * 14 + sm ] + SolarDate;
    kc = acc + LunarCal[y].BaseKanChih;
    Kan = kc % 10;
    Chih = kc % 12;

    Age = kc % 60;
    if (Age < 22)
        Age = 22 - Age;
    else
        Age = 82 - Age;

    if (acc <= LunarCal[y].BaseDays) {
        y--;
        LunarYear = SolarYear - 1;
        leap = GetLeap(LunarYear);
        sm += 12;
        acc = SolarDays[leap * 14 + sm] + SolarDate;
    }
    else
        LunarYear = SolarYear;
    l1 = LunarCal[y].BaseDays;
    for (i = 0; i < 13; i++) {
        l2 = l1 + LunarCal[y].MonthDays[i] + 29;
        if (acc <= l2) break;
        l1 = l2;
    }
    LunarMonth = i + 1;
    LunarDate = acc - l1;
    im = LunarCal[y].Intercalation;
    if (im != 0 && LunarMonth > im) {
        LunarMonth--;
        if (LunarMonth == im) LunarMonth = -im;
    }
    if (LunarMonth > 12) LunarMonth -= 12;

    function initArray() {
        this.length = initArray.arguments.length
        for (var i = 0; i < this.length; i++)
            this[i + 1] = initArray.arguments[i]
    }

    var d = new initArray("星期日", "星期一", "星期二", "星期三", "星期四", "星期五", "星期六");
    months = ["一","二","三","四","五","六","七","八","九","十","十一","十二"];
    days = ["初一","初二","初三","初四","初五","初六","初七","初八","初九","初十","十一","十二","十三","十四","十五","十六","十七","十八","十九","二十","廿一","廿二","廿三","廿四","廿五","廿六","廿七","廿八","廿九","三十"];
    return "农历" + months[LunarMonth - 1] + "月" + days[LunarDate - 1] + "&nbsp;";
}
/* 是否有闰年, 0 平年, 1 闰年 */
function GetLeap(year) {
    if (year % 400 == 0)
        return 1;
    else if (year % 100 == 0)
        return 0;
    else if (year % 4 == 0)
        return 1;
    else
        return 0;
}
function tagLunarCal(d, i, w, k, m1, m2, m3, m4, m5, m6, m7, m8, m9, m10, m11, m12, m13) {
    this.BaseDays = d;
    this.Intercalation = i;
    /* 0代表此年沒有闰月 */
    this.BaseWeekday = w;
    /* 民国1月1日星期減 1 */
    this.BaseKanChih = k;
    /* 民国1月1日干支序号减 1 */
    this.MonthDays = [ m1, m2, m3, m4, m5, m6, m7, m8, m9, m10, m11, m12, m13 ];
    /* 此農曆年每月之大小, 0==小月(29日), 1==大月(30日) */
}