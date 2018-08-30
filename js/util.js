// calculate the ipt_* input values
function calculate() {
	var inputs = document.getElementsByTagName("input");
	var sum = 0;
	for (var i = 0; i < inputs.length; i++) {

		if (inputs[i].getAttribute("type") != "text")
			continue;
		if (inputs[i].getAttribute("id").indexOf("ipt_") < 0)
			continue;
		if (isNaN(inputs[i].getAttribute("value"))) {
			alert("金额填错了,请修正!");
			inputs[i].select();
			// inputs[i].focus();
			return;
		}

		sum += inputs[i].getAttribute("value") * 1;
	}
	document.getElementById("sum").setAttribute("value", sum);
}

// Calendar
function CAL() {
	var bsYear;
	var bsDate;
	var bsWeek;
	var arrLen = 8;
	var sValue = 0;
	var dayiy = 0;
	var miy = 0;
	var iyear = 0;
	var dayim = 0;
	var spd = 86400;

	var year1999 = "30;29;29;30;29;29;30;29;30;30;30;29"; // 354
	var year2000 = "30;30;29;29;30;29;29;30;29;30;30;29"; // 354
	var year2001 = "30;30;29;30;29;30;29;29;30;29;30;29;30"; // 384
	var year2002 = "30;30;29;30;29;30;29;29;30;29;30;29"; // 354
	var year2003 = "30;30;29;30;30;29;30;29;29;30;29;30"; // 355
	var year2004 = "29;30;29;30;30;29;30;29;30;29;30;29;30"; // 384
	var year2005 = "29;30;29;30;29;30;30;29;30;29;30;29"; // 354
	var year2006 = "30;29;30;29;30;30;29;29;30;30;29;29;30";
	var month1999 = "正月;二月;三月;四月;五月;六月;七月;八月;九月;十月;十一月;十二月";
	var month2001 = "正月;二月;三月;四月;闰四月;五月;六月;七月;八月;九月;十月;十一月;十二月";
	var month2004 = "正月;二月;闰二月;三月;四月;五月;六月;七月;八月;九月;十月;十一月;十二月";
	var month2006 = "正月;二月;三月;四月;五月;六月;七月;闰七月;八月;九月;十月;十一月;十二月";
	var Dn = "初一;初二;初三;初四;初五;初六;初七;初八;初九;初十;十一;十二;十三;十四;十五;十六;十七;十八;十九;二十;廿一;廿二;廿三;廿四;廿五;廿六;廿七;廿八;廿九;三十";

	var Ys = new Array(arrLen);
	Ys[0] = 919094400;
	Ys[1] = 949680000;
	Ys[2] = 980265600;
	Ys[3] = 1013443200;
	Ys[4] = 1044028800;
	Ys[5] = 1074700800;
	Ys[6] = 1107878400;
	Ys[7] = 1138464000;

	var Yn = new Array(arrLen); // 农历年的名称
	Yn[0] = "己卯年";
	Yn[1] = "庚辰年";
	Yn[2] = "辛巳年";
	Yn[3] = "壬午年";
	Yn[4] = "癸未年";
	Yn[5] = "甲申年";
	Yn[6] = "乙酉年";
	Yn[7] = "丙戌年";
	var D = new Date();
	var yy = D.getFullYear();// .getYear();
	var mm = D.getMonth() + 1;
	var dd = D.getDate();
	var ww = D.getDay();
	if (ww.toString() == "0")
		ww = "<font color=RED>星期日</font>";
	if (ww.toString() == "1")
		ww = "星期一";
	if (ww.toString() == "2")
		ww = "星期二";
	if (ww.toString() == "3")
		ww = "星期三";
	if (ww.toString() == "4")
		ww = "星期四";
	if (ww.toString() == "5")
		ww = "星期五";
	if (ww.toString() == "6")
		ww = "<font color=RED>星期六</font>";
	ww = ww;
	var ss = parseInt(D.getTime() / 1000);
	if (yy < 100)
		yy = "19" + yy;

	for (i = 0; i < arrLen; i++)
		if (ss >= Ys[i]) {
			iyear = i;
			sValue = ss - Ys[i]; // 当年的秒数
		}
	dayiy = parseInt(sValue / spd) + 1; // 当年的天数

	var dpm = year1999;
	if (iyear == 1)
		dpm = year2000;
	if (iyear == 2)
		dpm = year2001;
	if (iyear == 3)
		dpm = year2002;
	if (iyear == 4)
		dpm = year2003;
	if (iyear == 5)
		dpm = year2004;
	if (iyear == 6)
		dpm = year2005;
	if (iyear == 7)
		dpm = year2006;
	dpm = dpm.split(";");
	var Mn = month1999;
	if (iyear == 2)
		Mn = month2001;
	if (iyear == 5)
		Mn = month2004;
	if (iyear == 7)
		Mn = month2006;
	Mn = Mn.split(";");
	Dn = Dn.split(";");
	dayim = dayiy;

	var total = new Array(13);
	total[0] = parseInt(dpm[0]);
	for (i = 1; i < dpm.length - 1; i++)
		total[i] = parseInt(dpm[i]) + total[i - 1];
	for (i = dpm.length - 1; i > 0; i--)
		if (dayim > total[i - 1]) {
			dayim = dayim - total[i - 1];
			miy = i;
		}
	bsWeek = ww;
	bsDate = yy + "年" + mm + "月";
	bsDate2 = dd;
	bsYear = "　农历" + Yn[iyear];
	bsYear2 = Mn[miy] + Dn[dayim - 1];
	if (ss >= Ys[7] || ss < Ys[0])
		bsYear = Yn[7];

	document.write(bsDate + bsDate2 + "日 ");
	document.write(bsWeek);
}

// 只能输入数字与小数点
// onKeyPress="if (event.keyCode!=46 && event.keyCode!=45 && (event.keyCode<48
// || event.keyCode>57)) event.returnValue=false";
// onKeyPress=inputnum(event);
function inputnum(event) {
	if (event.keyCode != 46 && event.keyCode != 45
			&& (event.keyCode < 48 || event.keyCode > 57))
		event.returnValue = false;
}

// export <table> to Excel file
function exToExcel(TableId) {
	var elTable = document.getElementById(TableId);
	if (elTable == null)
		return;
	var oRangeRef = document.body.createTextRange();
	oRangeRef.moveToElementText(elTable);
	oRangeRef.execCommand("Copy");
	var appExcel = new ActiveXObject("Excel.Application");
	appExcel.Workbooks.Add().Worksheets.Item(1).Paste();
	appExcel.Visible = true;
	appExcel = null;
}
function copyToClipboard() {
	txt = document.getElementById('body').value;
	alert(txt);
	if (window.clipboardData) {
		window.clipboardData.clearData();
		window.clipboardData.setData("Text", txt);
	} else if (navigator.userAgent.indexOf("Opera") != -1) {
		window.location = txt;
	} else if (window.netscape) {
		try {
			netscape.security.PrivilegeManager
					.enablePrivilege("UniversalXPConnect");
		} catch (e) {
			alert("被浏览器拒绝！\n请在浏览器地址栏输入'about:config'并回车\n然后将'signed.applets.codebase_principal_support'设置为'true'");
		}
		var clip = Components.classes['@mozilla.org/widget/clipboard;1']
				.createInstance(Components.interfaces.nsIClipboard);
		if (!clip)
			return;
		var trans = Components.classes['@mozilla.org/widget/transferable;1']
				.createInstance(Components.interfaces.nsITransferable);
		if (!trans)
			return;
		trans.addDataFlavor('text/unicode');
		var str = new Object();
		var len = new Object();
		var str = Components.classes["@mozilla.org/supports-string;1"]
				.createInstance(Components.interfaces.nsISupportsString);
		var copytext = txt;
		str.data = copytext;
		trans.setTransferData("text/unicode", str, copytext.length * 2);
		var clipid = Components.interfaces.nsIClipboard;
		if (!clip)
			return false;
		clip.setData(trans, null, clipid.kGlobalClipboard);
	}
}
//
function changeNumToMoney(num) {
	if (parseInt(num) <= 0) {
		return 0;
	} else {
		var numStr = num.toString();
		var numLen = numStr.length;
		var pointIndex = numStr.indexOf(".");
		if (pointIndex == -1) {
			return numStr.replace(/\B(?=([\d]{3})+$)/g, ',');
		} else {
			var numStrTmp = numStr.substr(0, pointIndex);
			var numStrTmp1 = numStr.substr(pointIndex, numLen);
			numStrTmp = numStrTmp.replace(/\B(?=([\d]{3})+$)/g, ',');
			return numStrTmp + numStrTmp1;
		}
	}
}

/*******************************************************************************
 * --------------------------@@以下为合同采购及研发新框架结合新加属性及函数@@-----------------------------------
 ******************************************************************************/
// 新加入工具类
var dateUtil = {
	getCurDate : function() {
		var nowdate = new Date();
		var month = nowdate.getMonth() + 1;
		var day = nowdate.getDate();
		var year = nowdate.getYear();
		return year + "-" + month + "-" + day;
	}
};

function round(num) {
	return Math.round(num * 100) / 100;
}

/*
 * 将String类型解析为Date类型. parseDate('2006-1-1') return new Date(2006,0,1)
 * parseDate(' 2006-1-1 ') return new Date(2006,0,1) parseDate('2006-1-1
 * 15:14:16') return new Date(2006,0,1,15,14,16) parseDate(' 2006-1-1 15:14:16 ')
 * return new Date(2006,0,1,15,14,16); parseDate('2006-1-1 15:14:16.254') return
 * new Date(2006,0,1,15,14,16,254) parseDate(' 2006-1-1 15:14:16.254 ') return
 * new Date(2006,0,1,15,14,16,254) parseDate('不正确的格式') retrun null
 */
function parseDate(str) {
	if (typeof str == 'string') {
		var results = str.match(/^ *(\d{4})-(\d{1,2})-(\d{1,2}) *$/);
		if (results && results.length > 3)
			return new Date(parseInt(results[1]), parseInt(results[2]) - 1,
					parseInt(results[3]));
		results = str
				.match(/^ *(\d{4})-(\d{1,2})-(\d{1,2}) +(\d{1,2}):(\d{1,2}):(\d{1,2}) *$/);
		if (results && results.length > 6)
			return new Date(parseInt(results[1]), parseInt(results[2]) - 1,
					parseInt(results[3]), parseInt(results[4]),
					parseInt(results[5]), parseInt(results[6]));
		results = str
				.match(/^ *(\d{4})-(\d{1,2})-(\d{1,2}) +(\d{1,2}):(\d{1,2}):(\d{1,2})\.(\d{1,9}) *$/);
		if (results && results.length > 7)
			return new Date(parseInt(results[1]), parseInt(results[2]) - 1,
					parseInt(results[3]), parseInt(results[4]),
					parseInt(results[5]), parseInt(results[6]),
					parseInt(results[7]));
	}
	return null;
}

/*
 * 将Date/String类型,解析为String类型. 传入String类型,则先解析为Date类型 不正确的Date,返回 ''
 * 如果时间部分为0,则忽略,只返回日期部分.
 */
function formatDate(v) {
	if (typeof v == 'string')
		v = parseDate(v);
	if (v instanceof Date) {
		var y = v.getFullYear();
		var m = v.getMonth() + 1;
		if (m.toString().length == 1) {
			m = "0" + m.toString();
		}
		var d = v.getDate();
		if (d.toString().length == 1) {
			d = "0" + d.toString();
		}
		var h = v.getHours();
		return y + '-' + m + '-' + d;
	}
	return '';
}

function formatTime(v) {
	if (typeof v == 'string')
		v = parseDate(v);
	if (v instanceof Date) {
		var y = v.getFullYear();
		var m = v.getMonth() + 1;
		var d = v.getDate();
		var h = v.getHours();
		var i = v.getMinutes();
		var s = v.getSeconds();
		var ms = v.getMilliseconds();
		return y + '-' + m + '-' + d + ' ' + h + ':' + i + ':' + s;
	}
	return '';
}