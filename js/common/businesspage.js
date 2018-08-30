/**
 * TODO1 :旧列表页面checkbox选择 TODO2 :组织机构人员选择 TODO3 :JS验证 TODO4 :小功能方法 TODO5
 * :右键菜单图标地址保存数组 TODO6 :金额处理部分 TODO7 :浮点计算部分 TODO8 :初始化JS部分
 */

// TODO;旧列表页面checkbox选择
/**
 * 列表页面全选checkbox函数
 */
function checkAll() {
    var datacb = document.getElementsByName('datacb');
    if (document.getElementById('titlecb').checked == true) {
        for (var i = 0, l = datacb.length; i < l; i++) {
            datacb[i].checked = true;
        }
    } else {
        for (var i = 0, l = datacb.length; i < l; i++) {
            datacb[i].checked = false;
        }
    }
}
/**
 * 列表页面的单选checkbox函数
 *
 * @return {}
 */
function checkOne() {
    var checkIDS = "";
    var datacb = document.getElementsByName('datacb');
    for (var i = 0, l = datacb.length; i < l; i++) {
        if ((datacb[i].name == "datacb") && (datacb[i].checked == true)) {
            checkIDS += datacb[i].value + ",";
        }
    }
    return checkIDS;
}
/**
 * 删除信息操作
 *
 * @return {Boolean}
 */
function deleteObjs(objectName) {
    var checkIDS = checkOne();
    var ids = checkIDS.substring(0, checkIDS.length - 1);
    if (checkIDS.length == 0) {
        alert("提示: 请选择一条信息.");
        return false;
    }
    var msg = "确认要删除!";
    if (window.confirm(msg)) {
        // alert("?model=" + objectName + "&action=deletes&id=" + ids
        // + "&url=" + encodeURIComponent(document.location.search))
        location.href = "?model=" + objectName + "&action=deletes&id=" + ids
        + "&url=" + encodeURIComponent(document.location.search);
    }
}

/**
 * ajax方式删除信息操作
 *
 * @return {Boolean}
 */
function deleteObjsAjax(objectName) {
    var checkIDS = checkOne();
    var ids = checkIDS.substring(0, checkIDS.length - 1);
    if (checkIDS.length == 0) {
        alert("提示: 请选择一条信息.");
        return false;
    }
    if (window.confirm("确认要删除？")) {
        var url = "?model=" + objectName + "&action=ajaxdeletes&id=" + ids;
        $.post(url, {}, function(data) {
            alert(data)
            if (data == '1') {

            }
        });
    }
}

/**
 * 删除数组中某对象
 *
 * @return {Array}
 */
Array.prototype.delMyArray = function(n) { // n表示第几项，从0开始算起。
// prototype为对象原型，注意这里为对象增加自定义方法的方法。
    if (n < 0) // 如果n<0，则不进行任何操作。
        return this;
    else
        return this.slice(0, n).concat(this.slice(n + 1, this.length));
}

/**
 * 判断是否在数组中
 *
 * @return {Array}
 */
Array.prototype.in_array = function(e) {
    for (i = 0; i < this.length; i++) {
        if (this[i] == e)
            return true;
    }
    return false;
}

/**
 * 获取url参数 传入参数id 返回所对应的值
 */
function getOutParameter() {
    var Url = top.window.location.href;
    var u, g, StrBack = '';
    if (arguments[arguments.length - 1] == "#")
        u = Url.split("#");
    else
        u = Url.split("?");
    if (u.length == 1)
        g = '';
    else
        g = u[1];

    if (g != '') {
        gg = g.split("&");
        var MaxI = gg.length;
        str = arguments[0] + "=";
        for (i = 0; i < MaxI; i++) {
            if (gg[i].indexOf(str) == 0) {
                StrBack = gg[i].replace(str, "");
                break;
            }
        }
    }
    return StrBack;
}

// TODO;组织机构人员选择
/** start:从组织机构窗口选择人员 */

function loadOrgWindow(orgIdEL, orgNameEL) {

    // URL = "module/user_select_single?toname=" + orgNameEL + "&toid=" +
    // orgIdEL;
    URL = "module/user_select_single?toid=" + orgIdEL + "&toname=" + orgNameEL;
    var evt = window.event ? window.event : null;
    var evtClientX = evt ? evt.clientX : 0;
    var evtOffsetX = evt ? evt.offsetX : 0;
    var evtClientY = evt ? evt.clientY : 0;
    var evtOffsetY = evt ? evt.offsetY : 0;
    loc_x = document.body.scrollLeft + evtClientX - evtOffsetX - 100;
    loc_y = document.body.scrollTop + evtClientY - evtOffsetY + 170;
    window
        .showModalDialog(
        URL,
        self,
        "edge:raised;scroll:0;status:0;help:0;resizable:1;dialogWidth:450px;dialogHeight:450px;dialogTop:"
        + loc_y + "px;dialogLeft:" + loc_x + "px");
}

/** start:从组织机构窗口选择人员(多选) */

function loadOrgWindow2(orgIdEL, orgNameEL) {

    // URL = "module/user_select_single?toname=" + orgNameEL + "&toid=" +
    // orgIdEL;
    URL = "module/user_select?toid=" + orgIdEL + "&toname=" + orgNameEL;
    var evt = window.event ? window.event : null;
    var evtClientX = evt ? evt.clientX : 0;
    var evtOffsetX = evt ? evt.offsetX : 0;
    var evtClientY = evt ? evt.clientY : 0;
    var evtOffsetY = evt ? evt.offsetY : 0;
    loc_x = document.body.scrollLeft + evtClientX - evtOffsetX - 100;
    loc_y = document.body.scrollTop + evtClientY - evtOffsetY + 170;
    window
        .showModalDialog(
        URL,
        self,
        "edge:raised;scroll:0;status:0;help:0;resizable:1;dialogWidth:450px;dialogHeight:450px;dialogTop:"
        + loc_y + "px;dialogLeft:" + loc_x + "px");
}

/** end:从组织机构窗口选择人员(多选) */

/** start:从组织机构窗口选择部门(多选) */

function loadOrgWindowDept(orgIdEL, orgNameEL) {

    // URL = "module/user_select_single?toname=" + orgNameEL + "&toid=" +
    // orgIdEL;
    var URL = "module/dept_select?toid=" + orgIdEL + "&toname=" + orgNameEL;
    var evt = window.event ? window.event : null;
    var evtClientX = evt ? evt.clientX : 0;
    var evtOffsetX = evt ? evt.offsetX : 0;
    var evtClientY = evt ? evt.clientY : 0;
    var evtOffsetY = evt ? evt.offsetY : 0;
    loc_x = document.body.scrollLeft + evtClientX - evtOffsetX - 100;
    loc_y = document.body.scrollTop + evtClientY - evtOffsetY + 170;
    window
        .showModalDialog(
        URL,
        self,
        "edge:raised;scroll:0;status:0;help:0;resizable:1;dialogWidth:450px;dialogHeight:450px;dialogTop:"
        + loc_y + "px;dialogLeft:" + loc_x + "px");
}
/** end:从组织机构窗口选择人员 */
function loadOrgWindowDeptSingle(orgIdEL, orgNameEL) {
    URL = "module/adept_select_single?toname=" + orgNameEL + "&toid=" + orgIdEL;
    // var URL = "module/dept_select?toid=" + orgIdEL + "&toname=" + orgNameEL;
    var evt = window.event ? window.event : null;
    var evtClientX = evt ? evt.clientX : 0;
    var evtOffsetX = evt ? evt.offsetX : 0;
    var evtClientY = evt ? evt.clientY : 0;
    var evtOffsetY = evt ? evt.offsetY : 0;
    loc_x = document.body.scrollLeft + evtClientX - evtOffsetX - 100;
    loc_y = document.body.scrollTop + evtClientY - evtOffsetY + 170;
    window
        .showModalDialog(
        URL,
        self,
        "edge:raised;scroll:0;status:0;help:0;resizable:1;dialogWidth:450px;dialogHeight:450px;dialogTop:"
        + loc_y + "px;dialogLeft:" + loc_x + "px");
}

function clearOrgInfo(orgIdEL, orgNameEL) {
    document.getElementById(orgIdEL).value = "";
    document.getElementById(orgNameEL).value = "";
}

// TODO;JS验证
/** start:非负整数校验 */
function checkIntNum(obj) {
    var re = /^[1-9]d*|0$/;

    if (!re.test(obj.value)) {
        if (isNaN(obj.value)) {
            alert("请输入非负整数!");
            obj.value = "";
            obj.focus();
            return false;
        }
    }

}
/** end:非负整数校验 */

/** start:数字校验* */
function checkNum(obj) {
    var re = /^-?[0-9]*(\.\d*)?$|^-?0(\.\d*)?$/;
    if (!re.test(obj.value)) {
        if (isNaN(obj.value))
            alert("非法数字");
        obj.value = "";
        // obj.focus();
        return false;
    } else {
        return true;
    }
}
/** end:数字校验 */

/** 金额校验 */
function checkMoney(v) {
    var re = /^[0-9]*(\.[0-9]{1,2})?$/;
    if (!re.test(v)) {
        return false;
    } else {
        return true;
    }
}

/** 电话号码 * */
function checkPhone(phone) {
    // 验证电话号码手机号码，包含153，159号段
    if (phone != "") {
        var p1 = /^(([0\+]\d{2,3}-)?(0\d{2,3})-)?(\d{7,8})(-(\d{3,}))?$/;
        var me = false;
        if (p1.test(phone))
            me = true;
        if (!me) {
            alert('对不起，您输入的电话号码有错误。区号和电话号码之间请用-分割');
            return false;
        }
    }
    return true;
}

/** 手机号码 * */
function checkMobile(mobile) {
    if (mobile != "") {
        var reg0 = /^13\d{5,9}$/;
        var reg1 = /^153\d{4,8}$/;
        var reg2 = /^159\d{4,8}$/;
        var reg3 = /^0\d{10,11}$/;
        var reg4 = /^150\d{4,8}$/;
        var reg5 = /^158\d{4,8}$/;
        var reg6 = /^15\d{5,9}$/;
        var my = false;
        if (reg0.test(mobile))
            my = true;
        if (reg1.test(mobile))
            my = true;
        if (reg2.test(mobile))
            my = true;
        if (reg3.test(mobile))
            my = true;
        if (reg4.test(mobile))
            my = true;
        if (reg5.test(mobile))
            my = true;
        if (reg6.test(mobile))
            my = true;
        if (!my) {
            alert('对不起，您输入的手机或小灵通号码有错误。');
            return false;
        }
        return true;
    }
}

/** 邮箱验证 * */
function ismail(mail) {
    var filter = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
    if (filter.test(mail))
        return true;
    else {
        alert('您的电子邮件格式不正确');
        return false;
    }
}

// TODO;小功能方法
/**
 * 消除字符串两边空格
 */
// 去除字符串两边的空格
function strTrim(str) {
    str = str + '';
    return str.replace(/(^\s*)|(\s*$)/g, "");
}
/**
 * 禁止submit提交表单
 *
 * @param {}
 *            ev
 * @return {Boolean} 在form 标签上添加如下代码 onkeypress="javascript:return
 *         NoSubmit(event);"
 */
function NoSubmit(ev) {
    if (ev.keyCode == 13) {
        return false;
    }
    return true;
}

/*******************************************************************************
 * 页面打印功能 将所需要隐藏的元素放入
 * <p class='noprint' >
 * </p>
 * 中
 ******************************************************************************/
function printPage() {
    $('p.noprint').hide();
    if (typeof(window.print) != 'undefined') {
        window.print();
    }
    $('p.noprint').show();
}

/*
 * 获取数据字典数据
 */
function getData(code) {
    var responseText = $.ajax({
        url: 'index1.php?model=system_datadict_datadict&action=pageJsonDesc&limit=999',
        type: "POST",
        data: "parentCode=" + code,
        async: false
    }).responseText;
    var o = eval("(" + responseText + ")");
    return o.collection;
}

/**
 * 根据编码获取数据字典对象 key为编码,值为数据字典项名称
 *
 * @param {}
 *            code
 * @return {}
 */
function getDataObj(code) {
    var d = getData(code);
    var obj = {};
    for (var i = 0; i < d.length; i++) {
        obj[d[i].dataCode] = d[i].dataName;
    }
    return obj;
}

/*
 * 获取数据字典数据 -- 根据code获取name
 */
function getDataByCode(code) {
    var responseText = $.ajax({
        url: 'index1.php?model=system_datadict_datadict&action=getDataJsonByCode',
        type: "GET",
        data: "code=" + code,
        async: false
    }).responseText;
    return responseText;
}
/**
 * 异步获取数组
 *
 * @param {}
 *            code
 * @return {}
 */
function getDataCus(code, myurl, key) {
    var responseText = $.ajax({
        url: myurl,
        type: "POST",
        data: key + '=' + code,
        async: false
    }).responseText;
    var o = eval("(" + responseText + ")");
    return o.collection;
}
/*
 * 添加数据字典数据到下拉选择
 */
function addDataToSelect(data, selectId, defaultVal) {
	var str = "";
	defaultVal = defaultVal ? defaultVal : "";
    for (var i = 0, l = data.length; i < l; i++) {
		if (data[i].dataCode == defaultVal) {
			str += "<option title='" + data[i].remark
				+ "' selected='selected' value='" + data[i].dataCode + "'>" + data[i].dataName
				+ "</option>";
		} else {
			str += "<option title='" + data[i].remark
				+ "' value='" + data[i].dataCode + "'>" + data[i].dataName
				+ "</option>";
		}
    }
	$("#" + selectId).append(str);
}

/**
 * 添加数据字典为checkbox 1.数据字典数组 2.渲染对象id 3.分行数 4.是否需要保存名称 5.渲染对象名称
 */
function addDataToCheckbox(data, objId, spaNum, needName, objName) {
    var appStr = "";
    var appName = "check_" + objId;
    var divLocal = objId + "AppDiv";
    var oldArr = $("#" + objId).val();
    var tempStr = $("#" + objId).val();
    var tempArr = tempStr.split(",");
    for (var i = 0, l = data.length; i < l; i++) {
        if (i != 0 && i % spaNum == 0) {
            appStr += "<br/>";
        }
        if (jQuery.inArray(data[i].dataCode, tempArr) == -1) {
            appStr += "<input type='checkbox' name=" + appName + " value='"
            + data[i].dataCode + "' title='" + data[i].remark + "' />"
            + data[i].dataName + " ";
        } else {
            appStr += "<input type='checkbox' name=" + appName + " value='"
            + data[i].dataCode + "' title='" + data[i].remark
            + "' checked='checked'/>" + data[i].dataName + " ";
        }
    }

    $("#" + divLocal).html(appStr);

    $("input[name='" + appName + "']").bind('click', function() {

        // 动态变量名
        var dnsObjIdArr = objId + 'Arr';
        var dnsobjNameArr = objName + 'Arr';
        var thisTemp = [];
        thisTemp = eval(dnsObjIdArr);

        if (tempStr != "") {
            // tempArr = tempStr.split(",");
            thisTemp = tempStr.split(",");
        }

        // 判断当前节点id是否在数组里面
        // index = tempArr.indexOf($(this).val());
        index = jQuery.inArray($(this).val(), thisTemp);

        if (index != -1) {
            // 如果有,则删除数组中的该节点id
            // tempArr.splice(index, 1);
            thisTemp.splice(index, 1);
        } else {
            // 如果没有,把id放入数组中
            // tempArr.push($(this).val());
            thisTemp.push($(this).val());
        }
        // var tempStr = tempArr.toString();
        tempStr = thisTemp.toString();
        // eval(dnsObjIdArr) = thisTemp;
        $("#" + objId).val(tempStr);

        // 设置名称
        var thisTempName = [];
        if (needName != undefined) {
            thisTempName = eval(dnsobjNameArr);

            var tempStrName = $("#" + objName).val();
            if (tempStrName != "") {
                thisTempName = tempStrName.split(",");
            }

            // 判断当前节点id是否在数组里面
            // index = tempArrName.indexOf($(this).attr("title"));
            index = jQuery.inArray($(this).attr("title"), thisTempName);

            if (index != -1) {
                // 如果有,则删除数组中的该节点id
                thisTempName.splice(index, 1);
            } else {
                // 如果没有,把id放入数组中
                thisTempName.push($(this).attr("title"));
            }
            var tempStrName = thisTempName.toString();
            $("#" + objName).val((tempStrName));
        }

    });
}

/*
 * 获取数据字典数据并添加到下拉选择
 */
function getAndAddDataToSelect(code, selectId, defaultVal) {
    var data = getData(code);
    addDataToSelect(data, selectId, defaultVal);
}

/**
 * tab表头显示函数
 */
function topTabShow(toplist, numb, addUrl) {
    var $options = $(".tab_options");
    var tab_str = "<ul class='tab_ul'>";
    for (var v in toplist) {
        // alert(v)
        if (addUrl != null && addUrl != undefined && addUrl != "") {
            toplist[v].url += addUrl;
        }
        var tab_li = "";
        if (toplist[v].choose != null && numb == toplist[v].choose) {
            tab_li = "<li class='tab_li'>" + "<div class='tab_li_left'></div>"
            + "<div class='tab_li_center'><a href='" + toplist[v].url
            + "' class='c1'>" + toplist[v].name + "</a></div>"
            + "<div class='tab_li_right'></div>" + "</li>";
        } else if (toplist[v].choose != null) {
            tab_li = "<li class='tab_li'>" + "<div class='tab_left'></div>"
            + "<div class='tab_center'><a href='" + toplist[v].url
            + "' class='d1'>" + toplist[v].name + "</a></div>"
            + "<div class='tab_right'></div>" + "</li>";
        }
        tab_str += tab_li;
    }
    tab_str += "</ul>";
    $options.empty();
    $options.append(tab_str);

    $(".tab_options .tab_ul .tab_li").bind("click", function() {
        $(".tab_options .tab_ul .tab_li .tab_li_left")
            .attr("class", "tab_left");
        $(".tab_options .tab_ul .tab_li .tab_li_center").attr("class",
            "tab_center");
        $(".tab_options .tab_ul .tab_li .tab_li_right").attr("class",
            "tab_right");

        $(this).find(".tab_left").attr("class", "tab_li_left");
        $(this).find(".tab_center").attr("class", "tab_li_center");
        $(this).find(".tab_right").attr("class", "tab_li_right");
    });
}

/**
 * 将url参数切割成数组返回
 *
 * @param {}
 *            paras
 * @return {String}
 */
function request(paras) {
    var url = location.href;
    var paraString = url.substring(url.indexOf("?") + 1, url.length).split("&");
    var paraObj = {};
    for (var i = 0; j = paraString[i]; i++) {
        paraObj[j.substring(0, j.indexOf("=")).toLowerCase()] = j.substring(j.indexOf("=") + 1, j.length);
    }
    var returnValue = paraObj[paras.toLowerCase()];
    if (typeof(returnValue) == "undefined") {
        return "";
    } else {
        return returnValue;
    }
}

/**
 * 列表单双行颜色替换显示 如需调用在各次页面加入 $(document).ready(function(){ rowsColorChange(); });
 */
function rowsColorChange() {
    $(".tr_even").each(function() {
        $(this).bind("mouseover", function() {
            $(this).attr("class", "tr_mouseover");
        });

        $(this).bind("mouseout", function() {
            $(this).attr("class", "tr_even");
        });
    });
    $(".tr_odd").each(function() {
        $(this).bind("mouseover", function() {
            $(this).attr("class", "tr_mouseover");
        });

        $(this).bind("mouseout", function() {
            $(this).attr("class", "tr_odd");
        });
    });
}

/**
 * 弹出大窗口方法
 */
function showOpenWin(url, isRandom, thisHeight, thisWidth, winName) {
    var thisNum = 'newwindow';
    if (isRandom == 1) {
        if (isRandom == 1) {
            if (winName != undefined) {
                thisNum = winName;
            } else {
                thisNum = Math.round(Math.random() * 1000);
            }
        }
    }

    var height = 668;
    if (thisHeight != undefined) {
        height = thisHeight;
    }
    var width = 1000;
    if (thisWidth != undefined) {
        width = thisWidth;
    }
    window.open(url, thisNum,
        'top=0,left=0,menubar=0,toolbar=0,status=1,scrollbars=1,resizable=1,width='
        + width + ',height=' + height);
}

/**
 * 弹出全屏窗口
 */
function showModalWin(url, isRandom, winName) {
    if (url !== undefined && url !== null && url !== '') {
        var thisNum = 'newwindow';
        var scrWidth = screen.availWidth;
        var scrHeight = screen.availHeight;

        if (isRandom == 1) {
            if (winName != undefined) {
                thisNum = winName;
            } else {
                thisNum = Math.round(Math.random() * 1000);
            }
        }
        var self = window
            .open(
            url,
            thisNum,
            "resizable=yes,toolbar=no, menubar=no,scrollbars=yes,location=no,status=no,top=0,left=0,width="
            + scrWidth + ",height=" + scrHeight);
        self.resizeTo(scrWidth, scrHeight);
        self.moveTo(0, 0);
    }
}

/*
 * targetObj: 目标对象，如果满足<font style='color:blue; background-color:yellow;'>快捷键</font>条件，触发目标对象的click事件
 * ctrlKey: 是否按住了Ctrl组合键 shiftKey: 是否按住了Shift组合键 altKey: 是否按住了Alt组合键
 * keycode:按键对应的数值
 *
 */
function Hotkey(event, targetObj, ctrlKey, shiftKey, altKey, keycode) {
    if (targetObj && event.ctrlKey == ctrlKey && event.shiftKey == shiftKey
        && event.altKey == altKey && event.keyCode == keycode)
        targetObj.click();
}

/*
 * 该方法用于自动判断当前页面是thickbox或者是window，并关闭窗口 添加时间：2010年12月19日
 */
function closeFun() {
    if (parent.$("#TB_window").length == 1) {// 判断thinkboc的移除方法是否存在
        self.parent.tb_remove();
    } else if (window.opener != null) {
        window.close();
    } else if (parent.window.opener) { // add by suxc 判断返回类型是否为tab类型的 2011-9-14
        parent.window.close();
    } else if (window.location) { // add by suxc 判断返回类型是否为history.back
        // 2011-9-13
        window.history.back();
    } else {
        parent.window.close();
    }
}

// 解决ie6右键无法跳转问题
function toUrl(url) {
    function tourl() {
        location = url;
    }

    setTimeout(tourl, 0);
}
// 随机数
function round(num) {
    return Math.round(num * 100) / 100;
}

/**
 * 四舍五入(round()方法)并保留小数点后N位的函数
 * @param v 表示要转换的值
 * @param e 表示要保留的位数
 * @returns {number}
 */
function round2(v, e) {
    var t = 1;
    for (; e > 0; t *= 10, e--);
    for (; e < 0; t /= 10, e++);
    return Math.round(v * t) / t;
}

/**
 * 右键图片数组 TODO:全部操作图片数组弄好，把数组移动到右键Js基础文件里面去
 */
var oa_cMenuImgArr = [];

oa_cMenuImgArr['add'] = "images/icon/add.gif"; // 添加/新建
oa_cMenuImgArr['read'] = "images/icon/view.gif"; // 查看
oa_cMenuImgArr['edit'] = "images/icon/edit.gif"; // 修改/编辑
oa_cMenuImgArr['del'] = "images/icon/delete.gif"; // 删除
oa_cMenuImgArr['close'] = "images/icon/close.gif"; // 关闭
oa_cMenuImgArr['change'] = "images/icon/icon141.gif"; // 变更
oa_cMenuImgArr['stop'] = "images/icon/icon141.gif"; // 暂停
oa_cMenuImgArr['reback'] = "images/icon/icon141.gif"; // 恢复
oa_cMenuImgArr['published'] = "images/icon/icon141.gif"; // 发布
oa_cMenuImgArr['open'] = "images/icon/icon103.gif"; // 打开
oa_cMenuImgArr['readExa'] = "images/icon/icon103.gif"; // 查看审批
oa_cMenuImgArr['template'] = "images/icon/template.gif"; // 模板
oa_cMenuImgArr['red'] = "images/icon/red.gif"; // 红灯
oa_cMenuImgArr['green'] = "images/icon/green.gif"; // 绿灯
oa_cMenuImgArr['focus'] = "images/icon/icon096.gif"; // 添加关注
oa_cMenuImgArr['unfocus'] = "images/icon/icon098.gif"; // 取消关注
oa_cMenuImgArr['break'] = "images/icon/icon105.gif";// 拆分
oa_cMenuImgArr['merge'] = "images/icon/template.gif";// 合并
oa_cMenuImgArr['audit'] = "images/icon/icon093.gif";// 审核
oa_cMenuImgArr['unaudit'] = "images/icon/icon094.gif";// 反审核
oa_cMenuImgArr['print'] = "images/icon/print.gif";// 反审核

/** **************************金额处理**************************************** */
// TODO;金额处理部分

/**
 * 动态列表渲染金额
 */
function createFormatOnClick(objName, key1, key2, key3, n, negative) {
    if (n == undefined) {
        n = 2;
    }
    if (negative == undefined) {
        negative = false;
    }
    var $thisObj = $('#' + objName);
    var $strHidden = $("<input type='hidden' name='" + $thisObj.attr('name')
    + "' id='" + $thisObj.attr('id') + "' value='" + $thisObj.val()
    + "' />");
    // 变更判断所需
    $strHidden.data("oldVal", $thisObj.data("oldVal"));// add by chengl
    // 2012-09-17
    // $strHidden.attr("oldVal",$thisObj.data("oldVal"));
    $thisObj.attr('name', '');
    $thisObj.attr('id', $thisObj.attr('id') + '_v');
    $thisObj.val(moneyFormat2($thisObj.val(), n, n));
    $thisObj.bind("blur", function() {
        moneyFormat1(this, n, negative);
        if (key1 != undefined) {
            FloatMul(key1, key2, key3, 2);
        }
    });
    $thisObj.after($strHidden);
}

/**
 * 金额处理
 * @param obj
 * @param n
 * @param negative
 */
function moneyFormat1(obj, n, negative) {
    if (negative == undefined) {
        negative = false;
    }
    var moneyFormatObj = $('#' + obj.id);
    var index = obj.id.lastIndexOf("_");
    var moneyObj = $('#' + obj.id.substr(0, index));
    var intInput = moneyFormatObj.val().replace(/,|\s/g, '');
    if (isNaN(intInput) || intInput.indexOf('e') != -1 || (negative == false && parseInt(intInput) < 0)) {
        alert('输入值有误');
        moneyFormatObj.val('');
        moneyObj.val('');
    } else {
        moneyFormatObj.val(moneyFormat2(intInput, n, n));
        moneyObj.val(intInput);
    }
}

/**
 * 格式化金额
 *
 * @param {}
 *            num 传入的金额
 * @param {}
 *            n 小数点后n位
 * @param {}
 *            zl :zeroLeng如果小数点后为0，0的长度
 * @return {String}
 */
function moneyFormat2(num, n, zl) {
    if (num == "******") {// 先写死权限判断的返回
        return num;
    }
    if (n == undefined)
        n = 2;
    num = computePre(num);
    if (zl == undefined) {
        zl = n;
    }
    if (num === null || num === '') {
        return '';
    }
    if (strTrim(num) === '') {
        return '';
    }
    if (isNaN(num)) {
        return num;
    }
    var num = parseFloat(num * 10000000 / 10000000);// 处理小数点后面的0
    num = round2(num, n);
    num = num + "";// 转成字符串
    var pIndex = num.indexOf('.');
    var s = num;
    if (pIndex > 0) {
        s = num.substring(0, pIndex);
        var e = num.substring(pIndex + 1);// 小数点后部分
    }
    // 加入千分位
    var re = /(-?\d+)(\d{3})/;
    while (re.test(s)) {
        s = s.replace(re, "$1,$2")
    }
    if (pIndex > 0) {
        num = s + "." + e;
        if (e.length < n) {
            for (var i = 0; i < n - e.length; i++) {
                num += "0";
            }
        }
    } else {// 没有小数点的情况
        if (zl > 0) {
            num = s + ".";
            for (var i = 0; i < zl; i++) {
                num += "0";
            }
        }

    }
    return num;
}

/**
 * 金额处理 =====金额大于0
 *
 * @param {this}
 *            code
 * @return {money} moneyFormatObj 为 显示域对象 moneyObj 为 数值域对象 显示域命名 为 数值域id + '_' +
 *         字符串
 */
function moneyFormat3(obj, n) {
    var moneyFormatObj = $('#' + obj.id);
    var index = obj.id.lastIndexOf("_");
    var moneyObj = $('#' + obj.id.substr(0, index));
    var intInput = moneyFormatObj.val().replace(/,|\s/g, '');
    if (isNaN(intInput) || intInput.indexOf('e') != -1
        || parseInt(intInput) < 0) {
        alert('输入值有误');
        moneyFormatObj.val('');
        moneyObj.val('');
    } else {
        if (intInput < 0 || intInput == 0) {
            alert('金额需大于0');
            moneyFormatObj.val('');
            moneyObj.val('');
        } else {
            // intInput = Number(intInput);
            newintInput = moneyFormat2(intInput, n, n);
            moneyFormatObj.val(newintInput);
            moneyObj.val(intInput);
        }
    }
}
// 金额设置处理
function setMoney(thisId, thisVal, n) {
    if (n == undefined) {
        n = 2;
    }
    document.getElementById(thisId).value = thisVal;

    var thisShowId = thisId + "_v";
    document.getElementById(thisShowId).value = moneyFormat2(thisVal, n, n);
}

/** **************************浮点加减,金额计算方法*************************** */
// TODO;浮点计算部分
// 乘法函数，用来得到精确的乘法结果
// 说明：javascript的乘法结果会有误差，在两个浮点数相乘的时候会比较明显。这个函数返回较为精确的乘法结果。
// 调用：accMul(arg1,arg2)
// 返回值：arg1乘以arg2的精确结果
function accMul(arg1, arg2, n) {
    if (!n)
        n = 2;
    arg1 = computePre(arg1);
    arg2 = computePre(arg2);
    if (arg1 == "" || arg1 == undefined)
        arg1 = 0;
    if (arg2 == "" || arg2 == undefined)
        arg2 = 0;
    var m = 0, s1 = arg1.toString(), s2 = arg2.toString();
    try {
        m += s1.split(".")[1].length;
    } catch (e) {
    }
    try {
        m += s2.split(".")[1].length;
    } catch (e) {
    }
    returnVal = Number(s1.replace(".", "")) * Number(s2.replace(".", ""))
    / Math.pow(10, m);
    return parseFloat(returnVal).toFixed(n) * 10000 / 10000;
}

// 给Number类型增加一个mul方法，调用起来更加方便。
Number.prototype.mul = function(arg) {
    return accMul(arg, this);
}

/**
 * 计算处理前方法（暂时去除千分位）
 *
 * @param {}
 *            arg
 * @return {}
 */
function computePre(arg) {
    if (!arg) {
        return arg;
    }
    if (typeof(arg) == "string") {
        arg = arg.trim();
    } else {
        // 检测金额
        // var re = /^((([1-9]{1}\\d{0,14}))|([0]{1}))([.][0-9]{1,6})?$/;
        // //alert(arg+"==>"+re.test(arg))
        // if (re.test(arg)) {
        // // if (isNaN(arg)) {
        // // return arg;
        // // }
        // return arg;
        // }
        if (!isNaN(arg)) {
            return arg;
        }
    }
    try {
        var str = arg.toString();
        str = str.replaceAll(",", "");
        var i = str.indexOf(".");
        var str1 = str;
        if (i > 0) {
            str1 = str.substr(0, i);
        }
        if (str1.length > 15) {
            alert("输入数值过大");
            return 0;
        }
    } catch (e) {
        return 0;
    }
    return Number(str);
}

// 除法函数，用来得到精确的除法结果
// 说明：javascript的除法结果会有误差，在两个浮点数相除的时候会比较明显。这个函数返回较为精确的除法结果。
// 调用：accDiv(arg1,arg2)
// 返回值：arg1除以arg2的精确结果
function accDiv(arg1, arg2, n) {
    if (!n)
        n = 2;
    arg1 = computePre(arg1);
    arg2 = computePre(arg2);
    if (arg1 == "" || arg1 == undefined)
        arg1 = 0;
    if (arg2 == "" || arg2 == undefined)
        arg2 = 0;
    var t1 = 0, t2 = 0, r1, r2;
    try {
        t1 = arg1.toString().split(".")[1].length;
    } catch (e) {
    }
    try {
        t2 = arg2.toString().split(".")[1].length;
    } catch (e) {
    }
    with (Math) {
        r1 = Number(arg1.toString().replace(".", ""));
        r2 = Number(arg2.toString().replace(".", ""));
        returnVal = (r1 / r2) * pow(10, t2 - t1);
        return parseFloat(returnVal).toFixed(n);
    }
}

// 加法函数，用来得到精确的加法结果
// 说明：javascript的加法结果会有误差，在两个浮点数相加的时候会比较明显。这个函数返回较为精确的加法结果。
// 调用：accAdd(arg1,arg2)
// 返回值：arg1加上arg2的精确结果
function accAdd(arg1, arg2, n) {
    if (!n)
        n = 2;
    arg1 = computePre(arg1);
    arg2 = computePre(arg2);
    if (arg1 == "" || arg1 == undefined)
        arg1 = 0;
    if (arg2 == "" || arg2 == undefined)
        arg2 = 0;
    if (isNaN(n)) {
        n = 0;
    }
    var r1, r2, m;
    try {
        r1 = arg1.toString().split(".")[1].length
    } catch (e) {
        r1 = 0
    }
    try {
        r2 = arg2.toString().split(".")[1].length
    } catch (e) {
        r2 = 0
    }
    m = Math.pow(10, Math.max(r1, r2))
    return parseFloat((arg1 * m + arg2 * m) / m).toFixed(n) * 10000 / 10000;
}

/**
 * js 多个数值相加
 *
 * @param {}
 *            arr js数组 将需要相加的值塞进一个数组传进来
 * @param {}
 *            n 小数点后保留的位数
 * @return {} 返回相加的值
 */
function accAddMore(arr, n) {
    if (!n) {
        n = 2;
    }
    if (isNaN(n)) {
        n = 0;
    }
    var num = 0;
    var length = arr.length
    for (i = 0; i < length; i++) {
        argTemp = computePre(arr[i])
        if (argTemp == "" || argTemp == undefined) {
            argTemp = 0;
        }
        //数值过大会回输出 Infinity 不知道怎么解决，暂时用1代替
//		m = Math.pow(10, Math.max(num, argTemp))
        m = Math.pow(10, 1)
        num = parseFloat((num * m + argTemp * m) / m).toFixed(n) * 10000
        / 10000;
    }
    return num;
}
// 给Number类型增加一个add方法，调用起来更加方便。
Number.prototype.add = function(arg) {
    return accAdd(arg, this);
}

// 减法函数
function accSub(arg1, arg2, n) {
    if (!n)
        n = 2;
    arg1 = computePre(arg1);
    arg2 = computePre(arg2);
    if (arg1 == "" || arg1 == undefined)
        arg1 = 0;
    if (arg2 == "" || arg2 == undefined)
        arg2 = 0;
    var r1, r2, m;
    if (isNaN(n)) {
        n = 0;
    }
    try {
        r1 = arg1.toString().split(".")[1].length
    } catch (e) {
        r1 = 0
    }
    try {
        r2 = arg2.toString().split(".")[1].length
    } catch (e) {
        r2 = 0
    }
    m = Math.pow(10, Math.max(r1, r2));
    // last modify by deeka
    // 动态控制精度长度
    return ((arg1 * m - arg2 * m) / m).toFixed(n);
}
// /给number类增加一个sub方法，调用起来更加方便
Number.prototype.sub = function(arg) {
    return accSub(arg, this);
};

/** 浮点数乘法运算 */
function FloatMul(arg1, arg2, arg3, choose) {
    var value1 = $('#' + arg1).val();
    var value2 = $('#' + arg2).val();
    if (value1 != "" && value2 != "") {
        if (arg3 == "") {
            return accMul(value1, value2, 2);
        } else {
            returnNum = accMul(value1, value2, 2);
            if (choose == '1') {
                $('#' + arg3).val(returnNum);
            } else {
                var newReturnMoney = moneyFormat2(returnNum);
                $('#' + arg3).val(returnNum);
                $('#' + arg3 + '_v').val(newReturnMoney);
            }
        }
    } else
        return false;
}

/**
 * 浮点除法运算 arg3 为空时直接输出 不为空时根据id输出
 */
function FloatDiv(arg1, arg2, arg3) {
    var value1 = $('#' + arg1).val();
    var value2 = $('#' + arg2).val();
    var t1 = 0, t2 = 0, r1, r2;
    if (value1 != "" && value2 != "") {
        var thisVal = accDiv(value1, value2, 2);
        if (arg3 == "") {
            return thisVal;
        } else {
            var newReturnMoney = moneyFormat2(thisVal);
            $('#' + arg3).val(thisVal);
            $('#' + arg3 + '_v').val(newReturnMoney);
        }
    } else {
        return false;
    }
}

/**
 * 新浮点，直接传值
 *
 * @param {}
 *            ev
 * @return {Boolean}
 */
function NewFloatDiv(arg1, arg2) {
    arg1 = computePre(arg1);
    arg2 = computePre(arg2);
    var t1 = 0, t2 = 0, r1, r2;
    try {
        t1 = arg1.toString().split(".")[1].length
    } catch (e) {
    }
    try {
        t2 = arg2.toString().split(".")[1].length
    } catch (e) {
    }
    with (Math) {
        r1 = Number(arg1.toString().replace(".", ""))
        r2 = Number(arg2.toString().replace(".", ""))
        return (r1 / r2) * pow(10, t2 - t1);
    }
}
/**
 * 日期 加 天数 返回新的日期
 *
 * @param {string}
 *            DateStr
 * @param {string}
 *            day
 * @return {string} newDay
 */
function stringToDate(DateStr, day) {
    var addDay = day * 1
    var converted = Date(converted);
    var myDate = new Date(converted);
    var arys = DateStr.split('-');
    myDate = new Date(arys[0], --arys[1], arys[2]);
    var newDay = new Date(myDate.getFullYear(), myDate.getMonth(), myDate
        .getDate()
    + addDay)
    return newDay;
}

/** 日期差的计算 */
function plusDateInfo(startEl, endEl) {
    var startDate = $('#' + startEl).val();
    var endDate = $('#' + endEl).val();

    if (startDate != "" && endDate != "") {
        var startYear = startDate.substring(0, startDate.indexOf('-'));
        var startMonth = startDate.substring(5, startDate.lastIndexOf('-'));
        var startDay = startDate.substring(startDate.length, startDate
            .lastIndexOf('-')
        + 1);

        var endYear = endDate.substring(0, endDate.indexOf('-'));
        var endMonth = endDate.substring(5, endDate.lastIndexOf('-'));
        var endDay = endDate.substring(endDate.length, endDate.lastIndexOf('-')
        + 1);

        var dayNum = ((Date.parse(endMonth + '/' + endDay + '/' + endYear) - Date
            .parse(startMonth + '/' + startDay + '/' + startYear)) / 86400000);
        return dayNum + 1;
    }
}

// 計算天數的函數 - 包含周末
// beginDate和endDate都是2007-8-10格式
function DateDiff(startDate, endDate) {
    if (startDate != "" && endDate != "") {
        var startYear = startDate.substring(0, startDate.indexOf('-'));
        var startMonth = startDate.substring(5, startDate.lastIndexOf('-'));
        var startDay = startDate.substring(startDate.length, startDate
            .lastIndexOf('-')
        + 1);

        var endYear = endDate.substring(0, endDate.indexOf('-'));
        var endMonth = endDate.substring(5, endDate.lastIndexOf('-'));
        var endDay = endDate.substring(endDate.length, endDate.lastIndexOf('-')
        + 1);
        var dayNum = ((Date.parse(endMonth + '/' + endDay + '/' + endYear) - Date
            .parse(startMonth + '/' + startDay + '/' + startYear)) / 86400000);
        return dayNum;
    }
}
/** **************************初始化加载********************************** */
// TODO;初始化JS部分
/**
 * 初始化加载程序
 */
$(document).ready(function() {
    // 对页面的跳转进行数字检验 add by suxc 2011-08-10
    $("#pageGoNumb_yx").bind('change', function() {
        var thisValue = parseInt($("#pageGoNumb_yx").val());
        var nextValue = parseInt($("#pageGoNumb_next").val());
        if (isNaN(thisValue)) {
            alert("请输入数字");
            $("#pageGoNumb_yx").val(nextValue);
        }
        if (thisValue < 1) {
            alert("输入的数字不能小于1");
            $("#pageGoNumb_yx").val(nextValue);
        }
    });

    // 定位formValidator验证表单组件显示宽度样式
    $(".tipShortTxt").css("width", "275px");
    $(".tipLongTxt").css("width", "505px");
    $(".dbcolumnTipTxt").css("width", "265px");
    $(".dbcolumnShortTxt").css("width", "200px");

    $(".menu_menus li").each(function() {

        // 左边树形
        $(this).bind("mouseover", function() {
            if ($(this).attr("class") != "selected") {
                $(this).attr("class", "over");
            }
        });

        $(this).bind("mouseout", function() {
            if ($(this).attr("class") != "selected") {
                $(this).attr("class", " ");
            }
        });

        $(this).bind("click", function() {
            $(this).parent().find(".selected")
                .attr("class", "");
            $(this).attr("class", "selected");
        });
    });

    //查看样式自动行高
    $(".textarea_read").each(function() {
        $(this).height($(this)[0].scrollHeight).attr('readonly', true);
    });

    // 渲染 千分位金额
    $.each($(".formatMoney"), function(i, n) {
        if ($(this).get(0).tagName == 'INPUT') {
            var strHidden = "<input type='hidden' name='" + n.name
                + "' id='" + n.id + "' value='" + n.value + "' />";
            $(this).attr('name', '');
            $(this).attr('id', n.id + '_v');
            $(this).val(moneyFormat2(n.value, 2));
            $(this).bind("blur", function() {
                moneyFormat1(this, 2);
                if (n.onblur)
                    n.onblur();
            });
            $(this).after(strHidden);
        } else {
            returnMoney = moneyFormat2($(this).text(), 2);
            $(this).text(returnMoney);
        }
    });

    // 渲染 千分位金额
    $.each($(".formatMoneySix"), function(i, n) {
        if ($(this).get(0).tagName == 'INPUT') {
            var strHidden = "<input type='hidden' name='" + n.name
                + "' id='" + n.id + "' value='" + n.value + "' />";
            $(this).attr('name', '');
            $(this).attr('id', n.id + '_v');
            $(this).val(moneyFormat2(n.value, 6, 6));
            $(this).bind("blur", function() {
                moneyFormat1(this, 6, 6);
                if (n.onblur)
                    n.onblur();
            });
            $(this).after(strHidden);
        } else {
            returnMoney = moneyFormat2($(this).text(), 6, 6);
            $(this).text(returnMoney);
        }
    });

    // 数量验证（正整数） by LiuB
    $.each($(".Num"), function(i, n) {
        if ($(this).get(0).tagName == 'INPUT') {
            $(this).bind("blur", function() {
                var Num = $(this).val();
                var t = /^[0-9]*[1-9][0-9]*$/;
                if (t.test(Num) == false) {
                    alert("请正确填写");
                    $(this).val("");
                }
            });
        }
    });
    // 验证实数
    $.each($(".realNum"), function(i, n) {
        if ($(this).get(0).tagName == 'INPUT') {
            $(this).bind("blur", function() {
                var Num = $(this).val();
                var t = /^(-|\+)?\d+(\.\d+)?$/;
                if (t.test(Num) == false) {
                    alert("请正确填写数值");
                    $(this).val("");
                }
            });
        }
    });
    // 电话验证 匹配格式：11位手机号码,3-4位区号，7-8位直播号码，1－4位分机号 by LiuB
    $.each($(".Tel"), function(i, n) {
        if ($(this).get(0).tagName == 'INPUT') {
            $(this).bind("blur", function() {
                var tel = $(this).val();
                var t = /(\d{11})|^((\d{7,8})|(\d{4}|\d{3})-(\d{7,8})|(\d{4}|\d{3})-(\d{7,8})-(\d{4}|\d{3}|\d{2}|\d{1})|(\d{7,8})-(\d{4}|\d{3}|\d{2}|\d{1}))$/;
                if (t.test(tel) == false) {
                    alert("请正确填写电话信息！");
                    $(this).val("");
                }
            });
        }
    });

    // Email验证 by LiuB
    $.each($(".Email"), function(i, n) {
        if ($(this).get(0).tagName == 'INPUT') {

            $(this).bind("blur", function() {
                var email = $(this).val();
                var E = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
                if (E.test(email) == false) {
                    alert("请填写正确的邮箱信息");
                    $(this).val("");
                }
            });
        }
    });


    //渲染机构信息
    if ($("#sysCompanyDiv")[0]) {
        var name = $("#sysCompanyDiv").attr("name");
        var val = $("#sysCompanyDiv").attr("val");
        var data = $.ajax({
            url: "index1.php?model=deptuser_branch_branch&action=listJson",
            async: false
        }).responseText;
        data = eval("(" + data + ")");
        var $select = $("<select class='select' name='" + name + "[sysCompanyCode]'></select>");
        for (var i = 0; i < data.length; i++) {
            var v = data[i];
            var $option = $("<option value='" + v.NamePT + "'>" + v.NameCN + "</option>");
            $select.append($option);
        }

        $("#sysCompanyDiv").append($select);
        $select.val(val);
        $select.change(function() {
            var v = $(this).find("option:selected").text();
            $("#sysCompanyName").val(v);
        });
        var $sysCompanyName = $("<input type='hidden' id='sysCompanyName' name='" + name + "[sysCompanyName]'></input>");
        $("#sysCompanyDiv").append($sysCompanyName);
        $select.trigger("change");

    }

    $("form").append($("#submitTag_").clone());
    $("#submitTag_").remove();

    //禁用input sub
    initForbitInputSub();
});

/**
 * 初始化 文本回车提交表单禁用
 */
function initForbitInputSub() {
    // 为表单绑定 回车 不提交表单事件
    $("input").keypress(function NoSubmit(event) {
        if (event.keyCode == 13) {
            return false;
        }
        return true;
    });
}
/**
 * 格式化千分位
 */
function formateMoney() {

    // 渲染 千分位金额
    $.each($(".formatMoney"), function(i, n) {
        var idStr = "" + $(this).attr('id');
        if ($(this).get(0).tagName == 'INPUT'
            && idStr.indexOf("_v") <= 1) {
            var strHidden = $("<input type='hidden' name='" + n.name
            + "' id='" + n.id + "' value='" + n.value + "' />");
            $(this).attr('name', '');
            $(this).attr('id', n.id + '_v');
            $(this).val(moneyFormat2(n.value));
            $(this).bind("blur", function() {
                moneyFormat1(this, 2);
                if (n.onblur)
                    n.onblur();
            });
            $(this).after(strHidden);
        } else {
            returnMoney = moneyFormat2($(this).text(), 2);
            if (returnMoney != "")
                $(this).text(returnMoney);
        }
    });

    // 渲染 千分位金额
    $.each($(".formatMoneySix"), function(i, n) {
        var idStr = "" + $(this).attr('id');
        if ($(this).get(0).tagName == 'INPUT'
            && idStr.indexOf("_v") <= 1) {
            var strHidden = "<input type='hidden' name='" + n.name
                + "' id='" + n.id + "' value='" + n.value + "' />";
            $(this).attr('name', '');
            $(this).attr('id', n.id + '_v');
            $(this).val(moneyFormat2(n.value, 6, 6));
            $(this).bind("blur", function() {
                moneyFormat1(this, 6, 6);
                if (n.onblur)
                    n.onblur();
            });
            $(this).after(strHidden);
        } else {
            returnMoney = moneyFormat2($(this).text(), 6, 6);
            if (returnMoney != "")
                $(this).text(returnMoney);
        }
    });

    // 渲染 千分位金额 大于0
    $.each($(".formatMoneyGreaterZero"), function(i, n) {
        var idStr = "" + $(this).attr('id');
        if ($(this).get(0).tagName == 'INPUT'
            && idStr.indexOf("_v") <= 1) {
            var strHidden = $("<input type='hidden' name='" + n.name
            + "' id='" + n.id + "' value='" + n.value + "' />");
            $(this).attr('name', '');
            $(this).attr('id', n.id + '_v');
            $(this).val(moneyFormat2(n.value));
            $(this).bind("blur", function() {
                moneyFormat3(this, 2);
                if (n.onblur)
                    n.onblur();
            });
            $(this).after(strHidden);
        } else {
            returnMoney = moneyFormat2($(this).text(), 2);
            if (returnMoney != "")
                $(this).text(returnMoney);
        }
    });

    //禁用input sub
    initForbitInputSub();
}

/**
 * 判断页面是否处于最顶层框架
 */
function isTopFrame() {
    if (parent.T_today) {
        return true;
    }
    return false;
}

/**
 * 获取url参数
 *
 * @param {string}
 *            key
 * @param {string}
 *            url
 * @return {} 返回url中key所对应的值 zengzx 2011年8月22日 14:28:17
 */
function getQuery(key, url) {
    var reg = new RegExp('^\\S*(\\?|&)' + key + '=([^&]*)\\S*$');
    var l = url || window.location.href;
    if (reg.test(l)) {
        return decodeURIComponent(l.replace(reg, '$2'));
    } else {
        return null;
    }
}

/**
 * 设置url参数
 *
 * @param {string}
 *            key
 * @param {string}
 *            value
 * @param {string}
 *            url
 * @return {} zengzx 2011年8月22日 14:28:26
 */
function setQuery(key, value, url) {
    var reg = new RegExp(key + '=[^&]*(?=&|$)');
    var l = url || window.location.href;
    if (reg.test(l)) {
        return l.replace(reg, key + '=' + encodeURIComponent(value));
    } else {
        return l + (/\?/.test(l) ? '&' : '?') + key + '='
            + encodeURIComponent(value);
    }
}
// 为每个文本框及option加上title属性
if (jQuery) {
    jQuery(function() {
        jQuery("input[type=text]").each(function() {
            jQuery(this).attr('title', jQuery(this).val());
        });
        jQuery("option").each(function() {
            if (jQuery(this).attr('title') == '') {
                jQuery(this).attr('title', jQuery(this).html());
            }
        });
    })
}

/**
 * 是否是整数
 */
function isNum(val) {
    var re = /^[1-9]\d*$/;
    return re.test(val);
}

// 判断日期格式符合YYYY-MM-DD格式
function isDate(str) {
    var re = /^\d{4}-\d{1,2}-\d{1,2}$/;
    if (re.test(str)) {
        // 开始日期的逻辑判断，是否为合法的日期
        var array = str.split('-');
        var date = new Date(array[0], parseInt(array[1], 10) - 1, array[2]);
        if (!((date.getFullYear() == parseInt(array[0], 10))
            && ((date.getMonth() + 1) == parseInt(array[1], 10)) && (date
                .getDate() == parseInt(array[2], 10)))) {
            // 不是有效的日期
            return false;
        }
        return true;
    }
    // 日期格式错误
    return false;
}

var toChinseMoney = function(num) {
    if (num == "" || num == undefined) {
        return "";
    }
    var strOutput = "";
    var strUnit = '仟佰拾亿仟佰拾万仟佰拾元角分';
    num += "00";
    var intPos = num.indexOf('.');
    if (intPos >= 0)
        num = num.substring(0, intPos) + num.substr(intPos + 1, 2);
    strUnit = strUnit.substr(strUnit.length - num.length);
    for (var i = 0; i < num.length; i++)
        strOutput += '零壹贰叁肆伍陆柒捌玖'.substr(num.substr(i, 1), 1)
        + strUnit.substr(i, 1);
    return strOutput.replace(/零角零分$/, '整').replace(/零[仟佰拾]/g, '零').replace(
        /零{2,}/g, '零').replace(/零([亿|万])/g, '$1').replace(/零+元/, '元')
        .replace(/亿零{0,3}万/, '亿').replace(/^元/, "零元");
};

jQuery.fn.CloneTableHeader = function(tableId, tableParentDivId) {
    // 获取冻结表头所在的DIV,如果DIV已存在则移除
    var obj = document.getElementById("tableHeaderDiv" + tableId);
    if (obj) {
        jQuery(obj).remove();
    }
    var browserName = navigator.appName;// 获取浏览器信息,用于后面代码区分浏览器
    var ver = navigator.appVersion;
    var browserVersion = parseFloat(ver.substring(ver.indexOf("MSIE") + 5, ver
        .lastIndexOf("Windows")));
    var content = document.getElementById(tableParentDivId);
    var scrollWidth = content.offsetWidth - content.clientWidth;
    var tableOrg = jQuery("#" + tableId);// 获取表内容
    var table = tableOrg.clone();// 克隆表内容
    table.attr("id", "cloneTable");
    // 注意:需要将要冻结的表头放入thead中
    var tableHeader = jQuery(tableOrg).find("thead");
    var tableHeaderHeight = tableHeader.height();
    tableHeader.hide();
    var colsWidths = jQuery(tableOrg)
        .find("tbody:last-child tr:first-child td").map(function() {
            return jQuery(this).width();
        });// 动态获取每一列的宽度
    var tableCloneCols = jQuery(table).find("thead tr:last-child th");
    if (colsWidths.size() > 0) {// 根据浏览器为冻结的表头宽度赋值(主要是区分IE8)
        for (i = 0; i < tableCloneCols.size(); i++) {
            if (i == tableCloneCols.size() - 1) {
                if (browserVersion == 8.0)
                    tableCloneCols.eq(i).width(colsWidths[i] + scrollWidth);
                else
                    tableCloneCols.eq(i).width(colsWidths[i]);
            } else {
                tableCloneCols.eq(i).width(colsWidths[i]);
            }
        }
    }

    var colsWidths = jQuery(tableOrg).find("tbody:last tr:first td").map(
        function() {
            return jQuery(this).width();
        });// 动态获取每一列的宽度
    var tableCloneCols = jQuery(table).find("thead tr:last th");
    if (colsWidths.size() > 0) {// 根据浏览器为冻结的表头宽度赋值(主要是区分IE8)
        for (i = 0; i < tableCloneCols.size(); i++) {
            tableCloneCols.eq(i).width(colsWidths[i]);
        }
    }

    // 创建冻结表头的DIV容器,并设置属性
    var headerDiv = document.createElement("div");
    headerDiv.appendChild(table[0]);
    jQuery(headerDiv).css("height", tableHeaderHeight);
    jQuery(headerDiv).css("overflow", "hidden");
    jQuery(headerDiv).css("z-index", "20");
    jQuery(headerDiv).css("width", "100%");
    jQuery(headerDiv).attr("id", "tableHeaderDiv" + tableId);
    jQuery(headerDiv).insertBefore(tableOrg.parent());
}

String.prototype.replaceAll = function(reallyDo, replaceWith, ignoreCase) {
    if (!RegExp.prototype.isPrototypeOf(reallyDo)) {
        return this.replace(new RegExp(reallyDo, (ignoreCase ? "gi" : "g")),
            replaceWith);
    } else {
        return this.replace(reallyDo, replaceWith);
    }
}
String.prototype.trim = function() {
    return this.replace(/(^\s*)|(\s*$)/g, "");
}

/**
 * post方式提交表单并弹出窗口
 *
 * @param url
 * @param data
 * @param name
 * @return
 */
function openPostWindow(url, data, name) {

    var tempForm = document.createElement("form");

    tempForm.id = "tempForm1";

    tempForm.method = "post";

    tempForm.action = url;

	tempForm.target = name;
	if ($.isArray(data)) {
		for (var i = 0; i < data.length; i++) {
			var datax = data[i];
			if ($.isArray(datax)) {
				for (var j = 0; j < datax.length; j++) {
					for (var k in datax[j]) {
						if (k > 0 || k == 'bomConfigId' || k == 'planId') {
							var hideInput = document.createElement("input");
							hideInput.type = "hidden";
							hideInput.name = "data[" + i + "][" + k + "]";
							hideInput.value = datax[j][k];
							tempForm.appendChild(hideInput);
						}
					};
				}
			} else if ($.isPlainObject(datax)) {
				for (var k in datax) {
					var hideInput = document.createElement("input");
					hideInput.type = "hidden";
					hideInput.name = "data[" + i + "][" + k + "]";
					hideInput.value = datax[k];
					tempForm.appendChild(hideInput);
				}

            } else {
                var hideInput = document.createElement("input");
                hideInput.type = "hidden";
                hideInput.name = "data[" + i + "]";
                hideInput.value = datax;
                tempForm.appendChild(hideInput);
            }
        }

    } else {
        var hideInput = document.createElement("input");
        hideInput.type = "hidden";
        hideInput.name = "data"
        hideInput.value = data;
        tempForm.appendChild(hideInput);
    }
    $(tempForm).bind("submit", function() {
        openWindow(name);
    });

    document.body.appendChild(tempForm);

    $(tempForm).trigger("submit");

    tempForm.submit();

    document.body.removeChild(tempForm);

}

function openWindow(name) {

    var width = Math.round((window.screen.width - 1000) / 2);
    var height = Math.round((window.screen.height - 600) / 2);
    window.open('about:blank', name,
        ' resizable=yes,width=200,height=200,left=' + width + ',top='
        + height);
}

// 过滤undefined,返回空字符串
function filterUndefined(thisVal) {
    if (thisVal == undefined) {
        return "";
    } else {
        return thisVal;
    }
}

// 设置下拉默认值
function setSelect(thisVal) {
    var hiddenObj = thisVal + "Hidden";
    var thisValObj = document.getElementById(hiddenObj);

    if (thisValObj == undefined) {
        alert(thisVal + '没有设置隐藏对象');
    } else {
        if (thisValObj.value != "") {
            document.getElementById(thisVal).value = thisValObj.value;
        }
    }
}

/**
 * 根据当前日期，获取本周第一天的日期
 */
function showWeekFirstDay(thisDate) {
    var Nowdate = new Date(thisDate);
    var WeekFirstDay = new Date(Nowdate - (Nowdate.getDay() - 1) * 86400000);
    return formatDate(WeekFirstDay);
}

/**
 * 根据当前日期，获取本周最后一天的日期
 */
function showWeekLastDay(thisDate) {
    var Nowdate = new Date(thisDate);
    var WeekFirstDay = new Date(Nowdate - (Nowdate.getDay() - 1) * 86400000);
    var WeekLastDay = new Date((WeekFirstDay / 1000 + 6 * 86400) * 1000);
    return formatDate(WeekLastDay);
}

/**
 * 锁定可编辑表格列头
 */
function lockEditGridTh() {
    var h = 200;
    $("#bodyDiv").attr("style", "height:" + h + "px;overflow-y:scroll;");
    $("#itemtable").width('100%');
    $("#itemtable th").each(function(index) {
        var ththObj = $("#bodyDiv tr td").eq(index);
        if ($(this).html() && ththObj.length > 0) {
            var thw = $(this).width();
            var tdw = ththObj.width();
            if (thw > tdw) {
                tdw = thw;
            }
            $(this).width(tdw);
            ththObj.width(tdw);
        }
    });
}

/**
 * 初始化表单值,用于判断变更是否修改数据
 */
function initFormVal() {
    // 存储原始值
    $("input,textarea,select").each(function() {
        var type = $(this).attr("type");
        if (type == "radio" || type == "checkbox") {
            $(this).data("oldVal", $(this).attr("checked"));
        } else if ($(this).context.nodeName == "TEXTAREA") {
            $(this).data("oldVal", $(this).val());
        } else {
            $(this).data("oldVal", $(this).val());
        }
    });
}

/**
 * 判断表单是否修改
 */
function isFormChange() {
    var isChange = false;
    $("input,textarea,select").each(function() {
        var type = $(this).attr("type");
        var oldVal = $(this).data("oldVal");
        var newVal = "";
        if (type != "button" && $(this).attr("id") && $(this).attr("name")) {
            if (type == "radio" || type == "checkbox") {
                newVal = $(this).attr("checked");
            } else if ($(this).context.nodeName == "TEXTAREA") {
                newVal = $(this).val();
            } else {
                newVal = $(this).val();
            }

            if (oldVal != newVal) {
                //alert($(this).attr("id") + "==>" + oldVal + "===>" + newVal)
                isChange = true;
            }
        }
    });
    if (!isChange) {
        alert("没有更改任何数据，无法提交变更.")
    }

    return isChange;
}


/**
 * 表单模块收缩
 * @param {} btnId
 * @param {} tblId
 */
function showAndHide(btnId, tblId) {
    //缓存表格对象
    var tblObj = $("table[id^='" + tblId + "']");
    //如果表格当前是隐藏状态，则显示
    if (tblObj.is(":hidden")) {
        tblObj.show();
        $("#" + btnId).attr("src", "images/icon/info_up.gif");
    } else {
        tblObj.hide();
        $("#" + btnId).attr("src", "images/icon/info_right.gif");
    }
}

/**
 * 表单模块收缩--div
 * @param {} btnId
 * @param {} tblId
 */
function showAndHideDiv(btnId, tblId) {
    //缓存表格对象
    var tblObj = $("div[id^='" + tblId + "']");
    //如果表格当前是隐藏状态，则显示
    if (tblObj.is(":hidden")) {
        tblObj.show();
        $("#" + btnId).attr("src", "images/icon/info_up.gif");
    } else {
        tblObj.hide();
        $("#" + btnId).attr("src", "images/icon/info_right.gif");
    }
}

//--身份证号码验证-支持新的带x身份证
function isIdCardNo(num) {
    var factorArr = new Array(7, 9, 10, 5, 8, 4, 2, 1, 6, 3, 7, 9, 10, 5, 8, 4, 2, 1);
    var error;
    var varArray = new Array();
    var intValue;
    var lngProduct = 0;
    var intCheckDigit;
    var intStrLen = num.length;
    var idNumber = num;
    // initialize
    if ((intStrLen != 15) && (intStrLen != 18)) {
        error = "请输入18或15位的身份证号码.";
        alert(error);
        //frmAddUser.txtIDCard.focus();
        return false;
    }
    // check and set value
    for (i = 0; i < intStrLen; i++) {
        varArray[i] = idNumber.charAt(i);
        if ((varArray[i] < '0' || varArray[i] > '9') && (i != 17)) {
            error = "身份证号码错误.";
            alert(error);
            //frmAddUser.txtIDCard.focus();
            return false;
        } else if (i < 17) {
            varArray[i] = varArray[i] * factorArr[i];
        }
    }
    if (intStrLen == 18) {
        //check date
        var date8 = idNumber.substring(6, 14);
        if (checkDate(date8) == false) {
            error = "身份证中日期信息不正确！.";
            alert(error);
            return false;
        }
        // calculate the sum of the products
        for (i = 0; i < 17; i++) {
            lngProduct = lngProduct + varArray[i];
        }
        // calculate the check digit
        intCheckDigit = 12 - lngProduct % 11;
        switch (intCheckDigit) {
            case 10:
                intCheckDigit = 'X';
                break;
            case 11:
                intCheckDigit = 0;
                break;
            case 12:
                intCheckDigit = 1;
                break;
        }
        // check last digit
        if (varArray[17].toUpperCase() != intCheckDigit) {
            error = "身份证效验位错误!正确为： " + intCheckDigit + ".";
            alert(error);
            return false;
        }
    }
    else {        //length is 15
        //check date
        var date6 = idNumber.substring(6, 12);
        if (checkDate(date6) == false) {
            alert("身份证日期信息有误！.");
            return false;
        }
    }
    //alert ("Correct.");
    return true;
}

function checkDate(date) {
    return true;
}
