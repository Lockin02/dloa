/**
 * TODO1 :���б�ҳ��checkboxѡ�� TODO2 :��֯������Աѡ�� TODO3 :JS��֤ TODO4 :С���ܷ��� TODO5
 * :�Ҽ��˵�ͼ���ַ�������� TODO6 :������ TODO7 :������㲿�� TODO8 :��ʼ��JS����
 */

// TODO;���б�ҳ��checkboxѡ��
/**
 * �б�ҳ��ȫѡcheckbox����
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
 * �б�ҳ��ĵ�ѡcheckbox����
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
 * ɾ����Ϣ����
 *
 * @return {Boolean}
 */
function deleteObjs(objectName) {
    var checkIDS = checkOne();
    var ids = checkIDS.substring(0, checkIDS.length - 1);
    if (checkIDS.length == 0) {
        alert("��ʾ: ��ѡ��һ����Ϣ.");
        return false;
    }
    var msg = "ȷ��Ҫɾ��!";
    if (window.confirm(msg)) {
        // alert("?model=" + objectName + "&action=deletes&id=" + ids
        // + "&url=" + encodeURIComponent(document.location.search))
        location.href = "?model=" + objectName + "&action=deletes&id=" + ids
        + "&url=" + encodeURIComponent(document.location.search);
    }
}

/**
 * ajax��ʽɾ����Ϣ����
 *
 * @return {Boolean}
 */
function deleteObjsAjax(objectName) {
    var checkIDS = checkOne();
    var ids = checkIDS.substring(0, checkIDS.length - 1);
    if (checkIDS.length == 0) {
        alert("��ʾ: ��ѡ��һ����Ϣ.");
        return false;
    }
    if (window.confirm("ȷ��Ҫɾ����")) {
        var url = "?model=" + objectName + "&action=ajaxdeletes&id=" + ids;
        $.post(url, {}, function(data) {
            alert(data)
            if (data == '1') {

            }
        });
    }
}

/**
 * ɾ��������ĳ����
 *
 * @return {Array}
 */
Array.prototype.delMyArray = function(n) { // n��ʾ�ڼ����0��ʼ����
// prototypeΪ����ԭ�ͣ�ע������Ϊ���������Զ��巽���ķ�����
    if (n < 0) // ���n<0���򲻽����κβ�����
        return this;
    else
        return this.slice(0, n).concat(this.slice(n + 1, this.length));
}

/**
 * �ж��Ƿ���������
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
 * ��ȡurl���� �������id ��������Ӧ��ֵ
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

// TODO;��֯������Աѡ��
/** start:����֯��������ѡ����Ա */

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

/** start:����֯��������ѡ����Ա(��ѡ) */

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

/** end:����֯��������ѡ����Ա(��ѡ) */

/** start:����֯��������ѡ����(��ѡ) */

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
/** end:����֯��������ѡ����Ա */
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

// TODO;JS��֤
/** start:�Ǹ�����У�� */
function checkIntNum(obj) {
    var re = /^[1-9]d*|0$/;

    if (!re.test(obj.value)) {
        if (isNaN(obj.value)) {
            alert("������Ǹ�����!");
            obj.value = "";
            obj.focus();
            return false;
        }
    }

}
/** end:�Ǹ�����У�� */

/** start:����У��* */
function checkNum(obj) {
    var re = /^-?[0-9]*(\.\d*)?$|^-?0(\.\d*)?$/;
    if (!re.test(obj.value)) {
        if (isNaN(obj.value))
            alert("�Ƿ�����");
        obj.value = "";
        // obj.focus();
        return false;
    } else {
        return true;
    }
}
/** end:����У�� */

/** ���У�� */
function checkMoney(v) {
    var re = /^[0-9]*(\.[0-9]{1,2})?$/;
    if (!re.test(v)) {
        return false;
    } else {
        return true;
    }
}

/** �绰���� * */
function checkPhone(phone) {
    // ��֤�绰�����ֻ����룬����153��159�Ŷ�
    if (phone != "") {
        var p1 = /^(([0\+]\d{2,3}-)?(0\d{2,3})-)?(\d{7,8})(-(\d{3,}))?$/;
        var me = false;
        if (p1.test(phone))
            me = true;
        if (!me) {
            alert('�Բ���������ĵ绰�����д������ź͵绰����֮������-�ָ�');
            return false;
        }
    }
    return true;
}

/** �ֻ����� * */
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
            alert('�Բ�����������ֻ���С��ͨ�����д���');
            return false;
        }
        return true;
    }
}

/** ������֤ * */
function ismail(mail) {
    var filter = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
    if (filter.test(mail))
        return true;
    else {
        alert('���ĵ����ʼ���ʽ����ȷ');
        return false;
    }
}

// TODO;С���ܷ���
/**
 * �����ַ������߿ո�
 */
// ȥ���ַ������ߵĿո�
function strTrim(str) {
    str = str + '';
    return str.replace(/(^\s*)|(\s*$)/g, "");
}
/**
 * ��ֹsubmit�ύ��
 *
 * @param {}
 *            ev
 * @return {Boolean} ��form ��ǩ��������´��� onkeypress="javascript:return
 *         NoSubmit(event);"
 */
function NoSubmit(ev) {
    if (ev.keyCode == 13) {
        return false;
    }
    return true;
}

/*******************************************************************************
 * ҳ���ӡ���� ������Ҫ���ص�Ԫ�ط���
 * <p class='noprint' >
 * </p>
 * ��
 ******************************************************************************/
function printPage() {
    $('p.noprint').hide();
    if (typeof(window.print) != 'undefined') {
        window.print();
    }
    $('p.noprint').show();
}

/*
 * ��ȡ�����ֵ�����
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
 * ���ݱ����ȡ�����ֵ���� keyΪ����,ֵΪ�����ֵ�������
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
 * ��ȡ�����ֵ����� -- ����code��ȡname
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
 * �첽��ȡ����
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
 * ��������ֵ����ݵ�����ѡ��
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
 * ��������ֵ�Ϊcheckbox 1.�����ֵ����� 2.��Ⱦ����id 3.������ 4.�Ƿ���Ҫ�������� 5.��Ⱦ��������
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

        // ��̬������
        var dnsObjIdArr = objId + 'Arr';
        var dnsobjNameArr = objName + 'Arr';
        var thisTemp = [];
        thisTemp = eval(dnsObjIdArr);

        if (tempStr != "") {
            // tempArr = tempStr.split(",");
            thisTemp = tempStr.split(",");
        }

        // �жϵ�ǰ�ڵ�id�Ƿ�����������
        // index = tempArr.indexOf($(this).val());
        index = jQuery.inArray($(this).val(), thisTemp);

        if (index != -1) {
            // �����,��ɾ�������еĸýڵ�id
            // tempArr.splice(index, 1);
            thisTemp.splice(index, 1);
        } else {
            // ���û��,��id����������
            // tempArr.push($(this).val());
            thisTemp.push($(this).val());
        }
        // var tempStr = tempArr.toString();
        tempStr = thisTemp.toString();
        // eval(dnsObjIdArr) = thisTemp;
        $("#" + objId).val(tempStr);

        // ��������
        var thisTempName = [];
        if (needName != undefined) {
            thisTempName = eval(dnsobjNameArr);

            var tempStrName = $("#" + objName).val();
            if (tempStrName != "") {
                thisTempName = tempStrName.split(",");
            }

            // �жϵ�ǰ�ڵ�id�Ƿ�����������
            // index = tempArrName.indexOf($(this).attr("title"));
            index = jQuery.inArray($(this).attr("title"), thisTempName);

            if (index != -1) {
                // �����,��ɾ�������еĸýڵ�id
                thisTempName.splice(index, 1);
            } else {
                // ���û��,��id����������
                thisTempName.push($(this).attr("title"));
            }
            var tempStrName = thisTempName.toString();
            $("#" + objName).val((tempStrName));
        }

    });
}

/*
 * ��ȡ�����ֵ����ݲ���ӵ�����ѡ��
 */
function getAndAddDataToSelect(code, selectId, defaultVal) {
    var data = getData(code);
    addDataToSelect(data, selectId, defaultVal);
}

/**
 * tab��ͷ��ʾ����
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
 * ��url�����и�����鷵��
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
 * �б�˫����ɫ�滻��ʾ ��������ڸ���ҳ����� $(document).ready(function(){ rowsColorChange(); });
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
 * �����󴰿ڷ���
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
 * ����ȫ������
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
 * targetObj: Ŀ������������<font style='color:blue; background-color:yellow;'>��ݼ�</font>����������Ŀ������click�¼�
 * ctrlKey: �Ƿ�ס��Ctrl��ϼ� shiftKey: �Ƿ�ס��Shift��ϼ� altKey: �Ƿ�ס��Alt��ϼ�
 * keycode:������Ӧ����ֵ
 *
 */
function Hotkey(event, targetObj, ctrlKey, shiftKey, altKey, keycode) {
    if (targetObj && event.ctrlKey == ctrlKey && event.shiftKey == shiftKey
        && event.altKey == altKey && event.keyCode == keycode)
        targetObj.click();
}

/*
 * �÷��������Զ��жϵ�ǰҳ����thickbox������window�����رմ��� ���ʱ�䣺2010��12��19��
 */
function closeFun() {
    if (parent.$("#TB_window").length == 1) {// �ж�thinkboc���Ƴ������Ƿ����
        self.parent.tb_remove();
    } else if (window.opener != null) {
        window.close();
    } else if (parent.window.opener) { // add by suxc �жϷ��������Ƿ�Ϊtab���͵� 2011-9-14
        parent.window.close();
    } else if (window.location) { // add by suxc �жϷ��������Ƿ�Ϊhistory.back
        // 2011-9-13
        window.history.back();
    } else {
        parent.window.close();
    }
}

// ���ie6�Ҽ��޷���ת����
function toUrl(url) {
    function tourl() {
        location = url;
    }

    setTimeout(tourl, 0);
}
// �����
function round(num) {
    return Math.round(num * 100) / 100;
}

/**
 * ��������(round()����)������С�����Nλ�ĺ���
 * @param v ��ʾҪת����ֵ
 * @param e ��ʾҪ������λ��
 * @returns {number}
 */
function round2(v, e) {
    var t = 1;
    for (; e > 0; t *= 10, e--);
    for (; e < 0; t /= 10, e++);
    return Math.round(v * t) / t;
}

/**
 * �Ҽ�ͼƬ���� TODO:ȫ������ͼƬ����Ū�ã��������ƶ����Ҽ�Js�����ļ�����ȥ
 */
var oa_cMenuImgArr = [];

oa_cMenuImgArr['add'] = "images/icon/add.gif"; // ���/�½�
oa_cMenuImgArr['read'] = "images/icon/view.gif"; // �鿴
oa_cMenuImgArr['edit'] = "images/icon/edit.gif"; // �޸�/�༭
oa_cMenuImgArr['del'] = "images/icon/delete.gif"; // ɾ��
oa_cMenuImgArr['close'] = "images/icon/close.gif"; // �ر�
oa_cMenuImgArr['change'] = "images/icon/icon141.gif"; // ���
oa_cMenuImgArr['stop'] = "images/icon/icon141.gif"; // ��ͣ
oa_cMenuImgArr['reback'] = "images/icon/icon141.gif"; // �ָ�
oa_cMenuImgArr['published'] = "images/icon/icon141.gif"; // ����
oa_cMenuImgArr['open'] = "images/icon/icon103.gif"; // ��
oa_cMenuImgArr['readExa'] = "images/icon/icon103.gif"; // �鿴����
oa_cMenuImgArr['template'] = "images/icon/template.gif"; // ģ��
oa_cMenuImgArr['red'] = "images/icon/red.gif"; // ���
oa_cMenuImgArr['green'] = "images/icon/green.gif"; // �̵�
oa_cMenuImgArr['focus'] = "images/icon/icon096.gif"; // ��ӹ�ע
oa_cMenuImgArr['unfocus'] = "images/icon/icon098.gif"; // ȡ����ע
oa_cMenuImgArr['break'] = "images/icon/icon105.gif";// ���
oa_cMenuImgArr['merge'] = "images/icon/template.gif";// �ϲ�
oa_cMenuImgArr['audit'] = "images/icon/icon093.gif";// ���
oa_cMenuImgArr['unaudit'] = "images/icon/icon094.gif";// �����
oa_cMenuImgArr['print'] = "images/icon/print.gif";// �����

/** **************************����**************************************** */
// TODO;������

/**
 * ��̬�б���Ⱦ���
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
    // ����ж�����
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
 * ����
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
        alert('����ֵ����');
        moneyFormatObj.val('');
        moneyObj.val('');
    } else {
        moneyFormatObj.val(moneyFormat2(intInput, n, n));
        moneyObj.val(intInput);
    }
}

/**
 * ��ʽ�����
 *
 * @param {}
 *            num ����Ľ��
 * @param {}
 *            n С�����nλ
 * @param {}
 *            zl :zeroLeng���С�����Ϊ0��0�ĳ���
 * @return {String}
 */
function moneyFormat2(num, n, zl) {
    if (num == "******") {// ��д��Ȩ���жϵķ���
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
    var num = parseFloat(num * 10000000 / 10000000);// ����С��������0
    num = round2(num, n);
    num = num + "";// ת���ַ���
    var pIndex = num.indexOf('.');
    var s = num;
    if (pIndex > 0) {
        s = num.substring(0, pIndex);
        var e = num.substring(pIndex + 1);// С����󲿷�
    }
    // ����ǧ��λ
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
    } else {// û��С��������
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
 * ���� =====������0
 *
 * @param {this}
 *            code
 * @return {money} moneyFormatObj Ϊ ��ʾ����� moneyObj Ϊ ��ֵ����� ��ʾ������ Ϊ ��ֵ��id + '_' +
 *         �ַ���
 */
function moneyFormat3(obj, n) {
    var moneyFormatObj = $('#' + obj.id);
    var index = obj.id.lastIndexOf("_");
    var moneyObj = $('#' + obj.id.substr(0, index));
    var intInput = moneyFormatObj.val().replace(/,|\s/g, '');
    if (isNaN(intInput) || intInput.indexOf('e') != -1
        || parseInt(intInput) < 0) {
        alert('����ֵ����');
        moneyFormatObj.val('');
        moneyObj.val('');
    } else {
        if (intInput < 0 || intInput == 0) {
            alert('��������0');
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
// ������ô���
function setMoney(thisId, thisVal, n) {
    if (n == undefined) {
        n = 2;
    }
    document.getElementById(thisId).value = thisVal;

    var thisShowId = thisId + "_v";
    document.getElementById(thisShowId).value = moneyFormat2(thisVal, n, n);
}

/** **************************����Ӽ�,�����㷽��*************************** */
// TODO;������㲿��
// �˷������������õ���ȷ�ĳ˷����
// ˵����javascript�ĳ˷������������������������˵�ʱ���Ƚ����ԡ�����������ؽ�Ϊ��ȷ�ĳ˷������
// ���ã�accMul(arg1,arg2)
// ����ֵ��arg1����arg2�ľ�ȷ���
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

// ��Number��������һ��mul�����������������ӷ��㡣
Number.prototype.mul = function(arg) {
    return accMul(arg, this);
}

/**
 * ���㴦��ǰ��������ʱȥ��ǧ��λ��
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
        // �����
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
            alert("������ֵ����");
            return 0;
        }
    } catch (e) {
        return 0;
    }
    return Number(str);
}

// ���������������õ���ȷ�ĳ������
// ˵����javascript�ĳ�����������������������������ʱ���Ƚ����ԡ�����������ؽ�Ϊ��ȷ�ĳ��������
// ���ã�accDiv(arg1,arg2)
// ����ֵ��arg1����arg2�ľ�ȷ���
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

// �ӷ������������õ���ȷ�ļӷ����
// ˵����javascript�ļӷ������������������������ӵ�ʱ���Ƚ����ԡ�����������ؽ�Ϊ��ȷ�ļӷ������
// ���ã�accAdd(arg1,arg2)
// ����ֵ��arg1����arg2�ľ�ȷ���
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
 * js �����ֵ���
 *
 * @param {}
 *            arr js���� ����Ҫ��ӵ�ֵ����һ�����鴫����
 * @param {}
 *            n С���������λ��
 * @return {} ������ӵ�ֵ
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
        //��ֵ��������� Infinity ��֪����ô�������ʱ��1����
//		m = Math.pow(10, Math.max(num, argTemp))
        m = Math.pow(10, 1)
        num = parseFloat((num * m + argTemp * m) / m).toFixed(n) * 10000
        / 10000;
    }
    return num;
}
// ��Number��������һ��add�����������������ӷ��㡣
Number.prototype.add = function(arg) {
    return accAdd(arg, this);
}

// ��������
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
    // ��̬���ƾ��ȳ���
    return ((arg1 * m - arg2 * m) / m).toFixed(n);
}
// /��number������һ��sub�����������������ӷ���
Number.prototype.sub = function(arg) {
    return accSub(arg, this);
};

/** �������˷����� */
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
 * ����������� arg3 Ϊ��ʱֱ����� ��Ϊ��ʱ����id���
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
 * �¸��㣬ֱ�Ӵ�ֵ
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
 * ���� �� ���� �����µ�����
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

/** ���ڲ�ļ��� */
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

// Ӌ���씵�ĺ��� - ������ĩ
// beginDate��endDate����2007-8-10��ʽ
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
/** **************************��ʼ������********************************** */
// TODO;��ʼ��JS����
/**
 * ��ʼ�����س���
 */
$(document).ready(function() {
    // ��ҳ�����ת�������ּ��� add by suxc 2011-08-10
    $("#pageGoNumb_yx").bind('change', function() {
        var thisValue = parseInt($("#pageGoNumb_yx").val());
        var nextValue = parseInt($("#pageGoNumb_next").val());
        if (isNaN(thisValue)) {
            alert("����������");
            $("#pageGoNumb_yx").val(nextValue);
        }
        if (thisValue < 1) {
            alert("��������ֲ���С��1");
            $("#pageGoNumb_yx").val(nextValue);
        }
    });

    // ��λformValidator��֤�������ʾ�����ʽ
    $(".tipShortTxt").css("width", "275px");
    $(".tipLongTxt").css("width", "505px");
    $(".dbcolumnTipTxt").css("width", "265px");
    $(".dbcolumnShortTxt").css("width", "200px");

    $(".menu_menus li").each(function() {

        // �������
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

    //�鿴��ʽ�Զ��и�
    $(".textarea_read").each(function() {
        $(this).height($(this)[0].scrollHeight).attr('readonly', true);
    });

    // ��Ⱦ ǧ��λ���
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

    // ��Ⱦ ǧ��λ���
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

    // ������֤���������� by LiuB
    $.each($(".Num"), function(i, n) {
        if ($(this).get(0).tagName == 'INPUT') {
            $(this).bind("blur", function() {
                var Num = $(this).val();
                var t = /^[0-9]*[1-9][0-9]*$/;
                if (t.test(Num) == false) {
                    alert("����ȷ��д");
                    $(this).val("");
                }
            });
        }
    });
    // ��֤ʵ��
    $.each($(".realNum"), function(i, n) {
        if ($(this).get(0).tagName == 'INPUT') {
            $(this).bind("blur", function() {
                var Num = $(this).val();
                var t = /^(-|\+)?\d+(\.\d+)?$/;
                if (t.test(Num) == false) {
                    alert("����ȷ��д��ֵ");
                    $(this).val("");
                }
            });
        }
    });
    // �绰��֤ ƥ���ʽ��11λ�ֻ�����,3-4λ���ţ�7-8λֱ�����룬1��4λ�ֻ��� by LiuB
    $.each($(".Tel"), function(i, n) {
        if ($(this).get(0).tagName == 'INPUT') {
            $(this).bind("blur", function() {
                var tel = $(this).val();
                var t = /(\d{11})|^((\d{7,8})|(\d{4}|\d{3})-(\d{7,8})|(\d{4}|\d{3})-(\d{7,8})-(\d{4}|\d{3}|\d{2}|\d{1})|(\d{7,8})-(\d{4}|\d{3}|\d{2}|\d{1}))$/;
                if (t.test(tel) == false) {
                    alert("����ȷ��д�绰��Ϣ��");
                    $(this).val("");
                }
            });
        }
    });

    // Email��֤ by LiuB
    $.each($(".Email"), function(i, n) {
        if ($(this).get(0).tagName == 'INPUT') {

            $(this).bind("blur", function() {
                var email = $(this).val();
                var E = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
                if (E.test(email) == false) {
                    alert("����д��ȷ��������Ϣ");
                    $(this).val("");
                }
            });
        }
    });


    //��Ⱦ������Ϣ
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

    //����input sub
    initForbitInputSub();
});

/**
 * ��ʼ�� �ı��س��ύ������
 */
function initForbitInputSub() {
    // Ϊ���� �س� ���ύ���¼�
    $("input").keypress(function NoSubmit(event) {
        if (event.keyCode == 13) {
            return false;
        }
        return true;
    });
}
/**
 * ��ʽ��ǧ��λ
 */
function formateMoney() {

    // ��Ⱦ ǧ��λ���
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

    // ��Ⱦ ǧ��λ���
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

    // ��Ⱦ ǧ��λ��� ����0
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

    //����input sub
    initForbitInputSub();
}

/**
 * �ж�ҳ���Ƿ��������
 */
function isTopFrame() {
    if (parent.T_today) {
        return true;
    }
    return false;
}

/**
 * ��ȡurl����
 *
 * @param {string}
 *            key
 * @param {string}
 *            url
 * @return {} ����url��key����Ӧ��ֵ zengzx 2011��8��22�� 14:28:17
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
 * ����url����
 *
 * @param {string}
 *            key
 * @param {string}
 *            value
 * @param {string}
 *            url
 * @return {} zengzx 2011��8��22�� 14:28:26
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
// Ϊÿ���ı���option����title����
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
 * �Ƿ�������
 */
function isNum(val) {
    var re = /^[1-9]\d*$/;
    return re.test(val);
}

// �ж����ڸ�ʽ����YYYY-MM-DD��ʽ
function isDate(str) {
    var re = /^\d{4}-\d{1,2}-\d{1,2}$/;
    if (re.test(str)) {
        // ��ʼ���ڵ��߼��жϣ��Ƿ�Ϊ�Ϸ�������
        var array = str.split('-');
        var date = new Date(array[0], parseInt(array[1], 10) - 1, array[2]);
        if (!((date.getFullYear() == parseInt(array[0], 10))
            && ((date.getMonth() + 1) == parseInt(array[1], 10)) && (date
                .getDate() == parseInt(array[2], 10)))) {
            // ������Ч������
            return false;
        }
        return true;
    }
    // ���ڸ�ʽ����
    return false;
}

var toChinseMoney = function(num) {
    if (num == "" || num == undefined) {
        return "";
    }
    var strOutput = "";
    var strUnit = 'Ǫ��ʰ��Ǫ��ʰ��Ǫ��ʰԪ�Ƿ�';
    num += "00";
    var intPos = num.indexOf('.');
    if (intPos >= 0)
        num = num.substring(0, intPos) + num.substr(intPos + 1, 2);
    strUnit = strUnit.substr(strUnit.length - num.length);
    for (var i = 0; i < num.length; i++)
        strOutput += '��Ҽ��������½��ƾ�'.substr(num.substr(i, 1), 1)
        + strUnit.substr(i, 1);
    return strOutput.replace(/������$/, '��').replace(/��[Ǫ��ʰ]/g, '��').replace(
        /��{2,}/g, '��').replace(/��([��|��])/g, '$1').replace(/��+Ԫ/, 'Ԫ')
        .replace(/����{0,3}��/, '��').replace(/^Ԫ/, "��Ԫ");
};

jQuery.fn.CloneTableHeader = function(tableId, tableParentDivId) {
    // ��ȡ�����ͷ���ڵ�DIV,���DIV�Ѵ������Ƴ�
    var obj = document.getElementById("tableHeaderDiv" + tableId);
    if (obj) {
        jQuery(obj).remove();
    }
    var browserName = navigator.appName;// ��ȡ�������Ϣ,���ں���������������
    var ver = navigator.appVersion;
    var browserVersion = parseFloat(ver.substring(ver.indexOf("MSIE") + 5, ver
        .lastIndexOf("Windows")));
    var content = document.getElementById(tableParentDivId);
    var scrollWidth = content.offsetWidth - content.clientWidth;
    var tableOrg = jQuery("#" + tableId);// ��ȡ������
    var table = tableOrg.clone();// ��¡������
    table.attr("id", "cloneTable");
    // ע��:��Ҫ��Ҫ����ı�ͷ����thead��
    var tableHeader = jQuery(tableOrg).find("thead");
    var tableHeaderHeight = tableHeader.height();
    tableHeader.hide();
    var colsWidths = jQuery(tableOrg)
        .find("tbody:last-child tr:first-child td").map(function() {
            return jQuery(this).width();
        });// ��̬��ȡÿһ�еĿ��
    var tableCloneCols = jQuery(table).find("thead tr:last-child th");
    if (colsWidths.size() > 0) {// ���������Ϊ����ı�ͷ��ȸ�ֵ(��Ҫ������IE8)
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
        });// ��̬��ȡÿһ�еĿ��
    var tableCloneCols = jQuery(table).find("thead tr:last th");
    if (colsWidths.size() > 0) {// ���������Ϊ����ı�ͷ��ȸ�ֵ(��Ҫ������IE8)
        for (i = 0; i < tableCloneCols.size(); i++) {
            tableCloneCols.eq(i).width(colsWidths[i]);
        }
    }

    // ���������ͷ��DIV����,����������
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
 * post��ʽ�ύ������������
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

// ����undefined,���ؿ��ַ���
function filterUndefined(thisVal) {
    if (thisVal == undefined) {
        return "";
    } else {
        return thisVal;
    }
}

// ��������Ĭ��ֵ
function setSelect(thisVal) {
    var hiddenObj = thisVal + "Hidden";
    var thisValObj = document.getElementById(hiddenObj);

    if (thisValObj == undefined) {
        alert(thisVal + 'û���������ض���');
    } else {
        if (thisValObj.value != "") {
            document.getElementById(thisVal).value = thisValObj.value;
        }
    }
}

/**
 * ���ݵ�ǰ���ڣ���ȡ���ܵ�һ�������
 */
function showWeekFirstDay(thisDate) {
    var Nowdate = new Date(thisDate);
    var WeekFirstDay = new Date(Nowdate - (Nowdate.getDay() - 1) * 86400000);
    return formatDate(WeekFirstDay);
}

/**
 * ���ݵ�ǰ���ڣ���ȡ�������һ�������
 */
function showWeekLastDay(thisDate) {
    var Nowdate = new Date(thisDate);
    var WeekFirstDay = new Date(Nowdate - (Nowdate.getDay() - 1) * 86400000);
    var WeekLastDay = new Date((WeekFirstDay / 1000 + 6 * 86400) * 1000);
    return formatDate(WeekLastDay);
}

/**
 * �����ɱ༭�����ͷ
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
 * ��ʼ����ֵ,�����жϱ���Ƿ��޸�����
 */
function initFormVal() {
    // �洢ԭʼֵ
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
 * �жϱ��Ƿ��޸�
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
        alert("û�и����κ����ݣ��޷��ύ���.")
    }

    return isChange;
}


/**
 * ��ģ������
 * @param {} btnId
 * @param {} tblId
 */
function showAndHide(btnId, tblId) {
    //���������
    var tblObj = $("table[id^='" + tblId + "']");
    //������ǰ������״̬������ʾ
    if (tblObj.is(":hidden")) {
        tblObj.show();
        $("#" + btnId).attr("src", "images/icon/info_up.gif");
    } else {
        tblObj.hide();
        $("#" + btnId).attr("src", "images/icon/info_right.gif");
    }
}

/**
 * ��ģ������--div
 * @param {} btnId
 * @param {} tblId
 */
function showAndHideDiv(btnId, tblId) {
    //���������
    var tblObj = $("div[id^='" + tblId + "']");
    //������ǰ������״̬������ʾ
    if (tblObj.is(":hidden")) {
        tblObj.show();
        $("#" + btnId).attr("src", "images/icon/info_up.gif");
    } else {
        tblObj.hide();
        $("#" + btnId).attr("src", "images/icon/info_right.gif");
    }
}

//--���֤������֤-֧���µĴ�x���֤
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
        error = "������18��15λ�����֤����.";
        alert(error);
        //frmAddUser.txtIDCard.focus();
        return false;
    }
    // check and set value
    for (i = 0; i < intStrLen; i++) {
        varArray[i] = idNumber.charAt(i);
        if ((varArray[i] < '0' || varArray[i] > '9') && (i != 17)) {
            error = "���֤�������.";
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
            error = "���֤��������Ϣ����ȷ��.";
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
            error = "���֤Ч��λ����!��ȷΪ�� " + intCheckDigit + ".";
            alert(error);
            return false;
        }
    }
    else {        //length is 15
        //check date
        var date6 = idNumber.substring(6, 12);
        if (checkDate(date6) == false) {
            alert("���֤������Ϣ����.");
            return false;
        }
    }
    //alert ("Correct.");
    return true;
}

function checkDate(date) {
    return true;
}
