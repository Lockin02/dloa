// ѭ����ȡhtmlҳ�� - jquery html()�����������
(function ($) {
    var oldHTML = $.fn.html;
    $.fn.formhtml = function () {
        if (arguments.length)
            return oldHTML.apply(this, arguments);
        $("input,button", this).each(function () {
            this.setAttribute('value', this.value);
        });
        $("textarea", this).each(function () {
            this.innerText = this.value;
        });

        $(":radio,:checkbox", this).each(function () {
            if (this.checked)
                this.setAttribute('checked', 'checked');
            else
                this.removeAttribute('checked');
        });
        $("option", this).each(function () {
            if (this.selected)
                this.setAttribute('selected', 'selected');
            else
                this.removeAttribute('selected');
        });
        return oldHTML.apply(this);
    };

})(jQuery);

// ��ȡ��Ʒֵ
function getGoodsValue() {
    var idArr = [];
    var thisId;
    var thisVal;
    var innerArr;

    // ��ѡ��ֵ����
    $("input:radio:visible").each(function () {
        if ($(this).attr('checked') == true
            && $(this).is(":hidden") == false) {
            thisId = this.value;
            var valObj = $("#numinput_" + thisId);
            var licenseId = $(this).attr("licenseId");
            if (valObj.length == 0) {
                thisVal = '';
            } else {
                thisVal = valObj.val();
            }
            var grouprow = $(this).parentsUntil("td>span")
                .attr("grouprow");
            // idArr[thisId] = thisVal;
            if (!grouprow) {
                grouprow = $(this).parent("span").attr("grouprow");
            }
            var arr = [thisId, thisVal, licenseId ? licenseId : 0,
                grouprow];
            idArr.push(arr);
        }
    });

    // ��ѡ��ֵ����
    $("input:checkbox:visible").each(function () {
        if ($(this).attr('checked') == true
            && $(this).is(":hidden") == false) {
            thisId = this.value

            var valObj = $("#numinput_" + thisId);
            var licenseId = $(this).attr("licenseId");
            if (valObj.length == 0) {
                thisVal = '';
            } else {
                thisVal = valObj.val();
            }
            var grouprow = $(this).parentsUntil("td>span")
                .attr("grouprow");
            if (!grouprow) {
                grouprow = $(this).parent("span").attr("grouprow");
            }
            // idArr[thisId] = thisVal;
            var arr = [thisId, thisVal, licenseId ? licenseId : 0,
                grouprow];
            idArr.push(arr);
        }
    });

    // �ı���ֻ��
    $("textarea").each(function () {
        if ($(this).val() != "") {
            thisId = $(this).attr("id");
            thisVal = this.value;
            var licenseId = $(this).attr("licenseId");
            var grouprow = $(this).attr("grouprow");
            var arr = ["i" + thisId, thisVal, licenseId ? licenseId : 0,
                grouprow ? grouprow : 0];
            // idArr[thisId] = thisVal;
            idArr.push(arr);
        }
    });

    // ����
    $(".other_input").each(function () {
        if ($(this).val() != "") {
            thisId = $(this).attr("id");
            thisVal = this.value;
            var grouprow = $(this).attr("grouprow");
            var arr = ["t" + thisId, thisVal, 0,
                grouprow ? grouprow : 0];
            // idArr[thisId] = thisVal;
            idArr.push(arr);
        }
    });

    return idArr;
}

// ��������֤
function checkForm() {
    var rs = true;

    // ��ѡ���ֵ
    var checkboxNum = 0;
    var radioNum = 0;

    $.each($("#settingInfo tr:visible[isleast='on']"), function (i, n) {
        if (rs == true) {
            propertyname = $(this).attr('propertyname');

            checkboxNum = $("#settingInfo tr[isleast='on'][propertyname='"
            + propertyname + "'] input:checkbox[checked]").length;
            radioNum = $("#settingInfo tr[isleast='on'][propertyname='"
            + propertyname + "'] input:radio[checked]").length;
            if (checkboxNum == 0 && radioNum == 0) {
                alert(propertyname + ' ����ѡ��һ�� ');
                rs = false;
            }
        }
    });

    $.each($("div:visible[isleast='on']"), function (i, n) {
        if (rs == true) {
            propertyname = $(this).attr('propertyname');
            checkboxNum = $(this).children("span")
                .children("input:checkbox[checked]").length;
            radioNum = $(this).children("span")
                .children("input:radio[checked]").length;
            if (checkboxNum == 0 && radioNum == 0) {
                alert(propertyname + ' ����ѡ��һ�� ');
                rs = false;
            }
        }
    });

    return rs;
}

/** ************** ѡ��� ***************** */

var allSelect = {};// ����ѡ���� key��id value��parentId
var seletedItem = {};// ����ѡ���� radio key:name checkbox key:id value:id

// ��ʼ��ѡ��ֵ
$(function () {
    var goodsValueObj = $("#goodsValue");
    if (goodsValueObj.val() != '') {
        // ѡ��ֵ����
        var objectArr = eval("(" + goodsValueObj.val() + ")");
        // Ԫ������
        var thisType;
        // Ԫ������
        var thisName;

        for (key in objectArr) {
            thisTypeObj = $("input[value='" + key + "']");

            // �ж����ͳ�ʼ������
            if (thisTypeObj.attr('type') == 'radio') {
                // ��Ⱦѡ��������
                thisName = thisTypeObj.attr("name");
                seletedItem[thisName] = key;

                if (thisTypeObj.attr("parentId") != undefined) {
                    // ��Ⱦѡ����
                    allSelect[key] = thisTypeObj.attr("parentId");
                }

            } else if (thisTypeObj.attr('type') == 'checkbox') {
                // ��Ⱦѡ��������
                seletedItem[key] = key;

                if (thisTypeObj.attr("parentId") != undefined) {
                    // ��Ⱦѡ����
                    allSelect[key] = thisTypeObj.attr("parentId");
                }
            }
        }
    }
    // ����û�������
    $(".form_text_right").each(function () {
        var $td = $(this);
        var isHide = true;
        $td.children().each(function () {
            if ($(this).is(":visible")) {
                isHide = false;
                return false;
            }

        });
        if (isHide)
            $td.parent("tr").hide();
    });

    // ����������ʾ
    $(".num_input").live("blur", function () {
        var newVal = $(this).val();
        var oldVal = $(this).attr("defVal");
        if ($(this).next().next("font").size() > 0) {
            $(this).next().next("font").remove();
        }
        if (newVal != oldVal) {
            $(this).next().after("&nbsp;<font color='red'>" + oldVal
            + "=>" + newVal + "</font>");
        }
    });
    // ��ѡ����ȡ��ѡ��
    $(":radio").live("click", function () {
        var nm = $(this).attr("name");
        $(":radio[name=" + nm + "]:not(:checked)").attr("tag", 0);
        if ($(this).attr("tag") == 1) {
            $(this).attr("checked", false);
            $(this).attr("tag", 0);
            var $span = $(this).parent("span");
            var grouprow = $span.attr("grouprow");
            $span.siblings("span[grouprow='" + grouprow + "']")
                .nextAll("div").hide();
        } else {
            $(this).attr("tag", 1);
        }
    });
});

// �ж�һ��Ԫ���Ƿ�����������
Array.prototype.in_array = function (e) {
    for (i = 0; i < this.length; i++) {
        if (this[i] == e)
            return true;
    }
    return false;
}

/**
 * �����������ȡ������Ϣ
 *
 * @param {}
 *            elId
 */
function showItemEqusByElId(el, elId) {
    $.ajax({
        url: "?model=goods_goods_propertiesitem&action=getJson&id="
        + elId,
        success: function (data) {
            data = eval("(" + data + ")");
            if (data.productCode) {
                // alert($(el).html())
                var $span = $(el).parent("span");
                $span.append(data.productCode + "��" + data.productName
                + "��");
                var $input = $("<input type='text' class='txtshort' value='"
                + data.proNum + "'>");
                $span.append($input);
            }
        }
    });
}

// �洢ÿ����̬��ӵ�span
var $spanObj = {};
// ����ÿ�θ��ƽڵ㶯̬�������������������¼һ���ڵ㸴�ƵĴ����������Ͳ��ᵼ��name���ظ�
var nameIndex = {};
/**
 * ѡ��ѡ��Ŀ
 */
function checkedItem(el, elId, ckType) {
    var $span = $(el).parent("span");
    var group = $span.attr("group");
    var grouprow = $span.attr("grouprow");
    var isAppendItem = true;
    if (!$spanObj[elId]) {
        $spanObj[elId] = {};
    }
    if (ckType == "0") {// radio
        delete seletedItem[seletedItem[el.name]];
        seletedItem[el.name] = elId;
        seletedItem[elId] = elId;
        $span.parent().find("input[parentId='" + group + "']").attr("disabled",
            true);
        // $("#license" + elId).attr("disabled", false);// ����license
        $("#license" + elId).attr("parentId", group);
        $span.find("input[id^='license']").attr("disabled", false);

        // ѭ������ѡ���������divѡ��
        for (var key in allSelect) {
            var parentId = allSelect[key];
            var parentIdArr = parentId.split("_");// or �Ĺ�ϵ

            for (var i = 0; i < parentIdArr.length - 1; i++) {
                var showNum = 0;
                var pArr = parentIdArr[i].split("/");// and �Ĺ�ϵ
                for (var j = 0; j < pArr.length - 1; j++) {
                    var p = pArr[j];
                    if (p == elId && seletedItem[p]) {
                        showNum++;
                    }
                }
                if (showNum == pArr.length - 1 && !$spanObj[elId][key]) {
                    var gr = $("#" + key).attr("grouprow");
                    $spanObj[elId][gr] = $("<div parentId='"
                    + elId
                    + "' style='margin-bottom:10px;padding-left:10px;border:1px ridge;'><div style='padding-top:5px;padding-bottom:10px'></div></div>");
                }
            }
        }
        // ����ͬ���ѡ��
        $span.siblings("span[grouprow='" + grouprow + "']").nextAll("div")
            .hide();
        // �����û��׷�ӣ�׷����div
        if ($span.next("div").size() == 0 && !$span.is(":hidden")) {
            var arr = [];
            // ������
            for (var k in $spanObj[elId]) {
                arr.push($spanObj[elId][k]);
            }
            for (var i = arr.length - 1; i >= 0; i--) {
                $span.after(arr[i]);
            }
        } else {// ��׷����ֱ����ʾ
            $span.nextAll("[parentId='" + elId + "']").show();
            isAppendItem = false;
        }
    } else {// checkbox
        $span.find("input[id^='license']").attr("disabled",
            !$(el).attr("checked"));
        if ($(el).attr("checked")) {
            seletedItem[elId] = elId;
            // ѭ������ѡ���������divѡ��
            for (var key in allSelect) {
                var parentId = allSelect[key];
                var parentIdArr = parentId.split("_");// or �Ĺ�ϵ

                for (var i = 0; i < parentIdArr.length - 1; i++) {
                    var showNum = 0;
                    var pArr = parentIdArr[i].split("/");// and �Ĺ�ϵ
                    for (var j = 0; j < pArr.length - 1; j++) {
                        var p = pArr[j];
                        if (p == elId && seletedItem[p]) {
                            showNum++;
                        }
                    }
                    if (showNum == pArr.length - 1 && !$spanObj[elId][key]) {
                        var gr = $("#" + key).attr("grouprow");
                        $spanObj[elId][gr] = $("<div parentId='"
                        + elId
                        + "' style='margin-bottom:10px;padding-left:10px;border:1px;ridge'><div style='padding-top:5px;padding-bottom:10px'></div></div>");
                    }
                }
            }
            // �����û��׷�ӣ�׷����div
            if ($span.next("div").size() == 0 && !$span.is(":hidden")) {
                var arr = [];
                // ������
                for (var k in $spanObj[elId]) {
                    arr.push($spanObj[elId][k]);
                }
                for (var i = arr.length - 1; i >= 0; i--) {
                    $span.after(arr[i]);
                }
            } else {// ��׷����ֱ����ʾ
                $span.nextAll("[parentId='" + elId + "']").show();
                isAppendItem = false;
            }
        } else {
            delete seletedItem[elId];
            $span.nextAll("[parentId='" + elId + "']").hide();
        }

    }
    if (isAppendItem && !$span.is(":hidden")) {
        // ������ʾ���� ����parentId��ѡ�е��������
        for (var key in allSelect) {// ѭ������ѡ����ж���parentId�Ƿ���ѡ������
            var parentId = allSelect[key];
            var parentIdArr = parentId.split("_");// or �Ĺ�ϵ

            for (var i = 0; i < parentIdArr.length - 1; i++) {
                var showNum = 0;
                var pArr = parentIdArr[i].split("/");// and �Ĺ�ϵ
                for (var j = 0; j < pArr.length - 1; j++) {
                    var p = pArr[j];
                    if (p == elId && seletedItem[p]) {
                        showNum++;
                    }
                }
                if (showNum == pArr.length - 1) {
                    $("#" + key).show();
                    var gr = $("#" + key).attr("grouprow");
                    var propertyname = $("#" + key).parents("tr")
                        .attr("propertyname");
                    var isleast = $("#" + key).parents("tr").attr("isleast");// ����ѡ��һ��
                    var $c = $("#" + key).clone();
                    var n = $c.children("input").eq(0).attr("name");
                    $spanObj[elId][gr].attr("propertyname", propertyname);
                    if (isleast) {
                        $spanObj[elId][gr].attr("isleast", isleast);
                        propertyname += "<font color=red>*</font>";
                    }

                    if ($spanObj[elId][gr].children("div").html() == '') {
                        $spanObj[elId][gr].children("div").html("<b>��"
                        + propertyname + "��</b>");
                        if (nameIndex[n]) {
                            nameIndex[n] += 1;
                        } else {
                            nameIndex[n] = 1;
                        }
                    }
                    $c.children("input").eq(0).attr("name", n + nameIndex[n]);
                    $c.removeAttr("id");
                    $c.attr("grouprow", $span.attr("grouprow"));
                    $spanObj[elId][gr].append($c);
                    $("#" + key).hide();
                    $c.each(function () {
                        var $input = $(this).children("input").eq(0);
                        var c = $input.attr("checked");
                        if (c) {
                            $input[0].click();
                        }
                    });
                    continue;
                }
            }
        }
    }
}

/**
 * �鿴��ϸ
 */
function toViewTip(id) {
    showThickboxWin("?model=goods_goods_properties&action=toViewTip&id=" + id
    + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=650");
}

/**
 * �鿴��ϸ
 */
function toViewProductInfo(productId) {
    showThickboxWin("?model=stock_productinfo_productinfo&action=view&id="
    + productId
    + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=700");
}

// iframe���ط���
function back() {
    var goodsId = $("#goodsId").val();
    var goodsName = $("#goodsName").val();
    window.location.href = '?model=contract_contract_product&action=toSetProductInfo'
    + '&isMoney='
    + $("#isMoney").val()
    + '&isSale='
    + $("#isSale").val()
    + '&goodsId='
    + goodsId
    + '&goodsName='
    + goodsName
    + '&isEncrypt='
    + $("#isEncrypt").val()
    + '&number='
    + $("#number").val()
    + '&price='
    + $("#price").val()
    + '&money='
    + $("#money").val()
    + '&cacheId=' + $("#cacheId").val();
}

// ��ѡlicense�󷵻�
function setLicenseId(licenseId, button) {
    $(button).attr("licenseId", licenseId);
    $(button).siblings("input").attr("licenseId", licenseId);
}

// ��ѡlicense�����÷���ֵ
function setLicenseId2(licenseId, buttonId) {
    $("#"+buttonId).attr("licenseId", licenseId);
    $("#"+buttonId).siblings("input").attr("licenseId", licenseId);
}

// ���²�Ʒ
function updateGoods() {
    if (confirm('��ʾ�����²�Ʒ�󣬻ᵼ��ĳЩ�����õ�ѡ�����ʾ��ȷ�ϸ�����')) {
        var licenseInfo = {}; // �����licenseId
        $("#settingInfo input:checked").each(function () {
            if ($(this).attr("licenseId") != undefined) {
                var thisId = $(this).attr("id");
                licenseInfo[thisId] = $(this).attr("licenseId");
            }
        });
        $.ajax({
            url: "?model=goods_goods_properties&action=ajaxGetGoods",
            data: {goodsId: $("#goodsId").val()},
            async: false,
            success: function (html) {
                if (html != "") {
                    $("#settingInfo").html(html);

                    var i;
                    // ������ѡ������ݼӵ��µı����
                    var goodsValue = eval("(" + $("#goodsValue").val() + ")");
                    for (i in goodsValue) {
                        if (i * 1 == i) {
                            var inputObj;

                            // �ı���
                            if (goodsValue[i][0].indexOf("i") > -1) {
                                var tmp = goodsValue[i][0];
                                tmp = tmp.replace("i", "")
                                $("#" + tmp).val(goodsValue[i][1]);
                            } else { // ����ѡ���
                                inputObj = $("#radio_" + goodsValue[i][0]);
                                if (inputObj.length > 0) inputObj.trigger("click");

                                inputObj = $("#checkbox_" + goodsValue[i][0]);
                                if (inputObj.length > 0) {
                                    inputObj.trigger("click");
                                }

                                if (goodsValue[i][1] != "") {
                                    $("#numinput_" + goodsValue[i][0]).val(goodsValue[i][1]).trigger("blur");
                                }
                            }
                        }
                    }

                    for (i in licenseInfo) {
                        var inputObj = $("#" + i);
                        inputObj.attr("licenseId", licenseInfo[i]);
                        $("#license" + inputObj.val()).attr("licenseId", licenseInfo[i]);
                    }

                    alert('������ɣ�������Ʒ��Ϣ��ʼ��Ч��');
                }
            }
        });
    }
}