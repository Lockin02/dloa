// 循环获取html页面 - jquery html()方法的替代版
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

// 获取产品值
function getGoodsValue() {
    var idArr = [];
    var thisId;
    var thisVal;
    var innerArr;

    // 单选框值隐藏
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

    // 复选框值隐藏
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

    // 文本框只读
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

    // 其他
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

// 表单必填验证
function checkForm() {
    var rs = true;

    // 被选择的值
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
                alert(propertyname + ' 至少选择一项 ');
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
                alert(propertyname + ' 至少选择一项 ');
                rs = false;
            }
        }
    });

    return rs;
}

/** ************** 选项部分 ***************** */

var allSelect = {};// 所有选择项 key：id value：parentId
var seletedItem = {};// 所有选中项 radio key:name checkbox key:id value:id

// 初始化选项值
$(function () {
    var goodsValueObj = $("#goodsValue");
    if (goodsValueObj.val() != '') {
        // 选中值数组
        var objectArr = eval("(" + goodsValueObj.val() + ")");
        // 元素类型
        var thisType;
        // 元素名称
        var thisName;

        for (key in objectArr) {
            thisTypeObj = $("input[value='" + key + "']");

            // 判断类型初始化数组
            if (thisTypeObj.attr('type') == 'radio') {
                // 渲染选中项数组
                thisName = thisTypeObj.attr("name");
                seletedItem[thisName] = key;

                if (thisTypeObj.attr("parentId") != undefined) {
                    // 渲染选择项
                    allSelect[key] = thisTypeObj.attr("parentId");
                }

            } else if (thisTypeObj.attr('type') == 'checkbox') {
                // 渲染选中项数组
                seletedItem[key] = key;

                if (thisTypeObj.attr("parentId") != undefined) {
                    // 渲染选择项
                    allSelect[key] = thisTypeObj.attr("parentId");
                }
            }
        }
    }
    // 隐藏没有项的行
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

    // 数量差异显示
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
    // 单选单击取消选择
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

// 判断一个元素是否在数组里面
Array.prototype.in_array = function (e) {
    for (i = 0; i < this.length; i++) {
        if (this[i] == e)
            return true;
    }
    return false;
}

/**
 * 根据配置项获取物料信息
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
                $span.append(data.productCode + "【" + data.productName
                + "】");
                var $input = $("<input type='text' class='txtshort' value='"
                + data.proNum + "'>");
                $span.append($input);
            }
        }
    });
}

// 存储每个动态添加的span
var $spanObj = {};
// 由于每次复制节点动态产生，这个变量用来记录一个节点复制的次数，这样就不会导致name的重复
var nameIndex = {};
/**
 * 选择选项目
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
        // $("#license" + elId).attr("disabled", false);// 处理license
        $("#license" + elId).attr("parentId", group);
        $span.find("input[id^='license']").attr("disabled", false);

        // 循环所有选择项，构件子div选项
        for (var key in allSelect) {
            var parentId = allSelect[key];
            var parentIdArr = parentId.split("_");// or 的关系

            for (var i = 0; i < parentIdArr.length - 1; i++) {
                var showNum = 0;
                var pArr = parentIdArr[i].split("/");// and 的关系
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
        // 隐藏同组的选项
        $span.siblings("span[grouprow='" + grouprow + "']").nextAll("div")
            .hide();
        // 如果还没有追加，追加子div
        if ($span.next("div").size() == 0 && !$span.is(":hidden")) {
            var arr = [];
            // 倒序处理
            for (var k in $spanObj[elId]) {
                arr.push($spanObj[elId][k]);
            }
            for (var i = arr.length - 1; i >= 0; i--) {
                $span.after(arr[i]);
            }
        } else {// 有追加则直接显示
            $span.nextAll("[parentId='" + elId + "']").show();
            isAppendItem = false;
        }
    } else {// checkbox
        $span.find("input[id^='license']").attr("disabled",
            !$(el).attr("checked"));
        if ($(el).attr("checked")) {
            seletedItem[elId] = elId;
            // 循环所有选择项，构件子div选项
            for (var key in allSelect) {
                var parentId = allSelect[key];
                var parentIdArr = parentId.split("_");// or 的关系

                for (var i = 0; i < parentIdArr.length - 1; i++) {
                    var showNum = 0;
                    var pArr = parentIdArr[i].split("/");// and 的关系
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
            // 如果还没有追加，追加子div
            if ($span.next("div").size() == 0 && !$span.is(":hidden")) {
                var arr = [];
                // 倒序处理
                for (var k in $spanObj[elId]) {
                    arr.push($spanObj[elId][k]);
                }
                for (var i = arr.length - 1; i >= 0; i--) {
                    $span.after(arr[i]);
                }
            } else {// 有追加则直接显示
                $span.nextAll("[parentId='" + elId + "']").show();
                isAppendItem = false;
            }
        } else {
            delete seletedItem[elId];
            $span.nextAll("[parentId='" + elId + "']").hide();
        }

    }
    if (isAppendItem && !$span.is(":hidden")) {
        // 进行显示处理 规则：parentId在选中的项组合中
        for (var key in allSelect) {// 循环所有选择项，判断其parentId是否在选中项中
            var parentId = allSelect[key];
            var parentIdArr = parentId.split("_");// or 的关系

            for (var i = 0; i < parentIdArr.length - 1; i++) {
                var showNum = 0;
                var pArr = parentIdArr[i].split("/");// and 的关系
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
                    var isleast = $("#" + key).parents("tr").attr("isleast");// 至少选中一项
                    var $c = $("#" + key).clone();
                    var n = $c.children("input").eq(0).attr("name");
                    $spanObj[elId][gr].attr("propertyname", propertyname);
                    if (isleast) {
                        $spanObj[elId][gr].attr("isleast", isleast);
                        propertyname += "<font color=red>*</font>";
                    }

                    if ($spanObj[elId][gr].children("div").html() == '') {
                        $spanObj[elId][gr].children("div").html("<b>【"
                        + propertyname + "】</b>");
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
 * 查看明细
 */
function toViewTip(id) {
    showThickboxWin("?model=goods_goods_properties&action=toViewTip&id=" + id
    + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=650");
}

/**
 * 查看明细
 */
function toViewProductInfo(productId) {
    showThickboxWin("?model=stock_productinfo_productinfo&action=view&id="
    + productId
    + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=700");
}

// iframe返回方法
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

// 勾选license后返回
function setLicenseId(licenseId, button) {
    $(button).attr("licenseId", licenseId);
    $(button).siblings("input").attr("licenseId", licenseId);
}

// 勾选license后设置返回值
function setLicenseId2(licenseId, buttonId) {
    $("#"+buttonId).attr("licenseId", licenseId);
    $("#"+buttonId).siblings("input").attr("licenseId", licenseId);
}

// 更新产品
function updateGoods() {
    if (confirm('提示：更新产品后，会导致某些已启用的选项不再显示，确认更新吗？')) {
        var licenseInfo = {}; // 缓存的licenseId
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
                    // 将现有选择的内容加到新的表格中
                    var goodsValue = eval("(" + $("#goodsValue").val() + ")");
                    for (i in goodsValue) {
                        if (i * 1 == i) {
                            var inputObj;

                            // 文本域
                            if (goodsValue[i][0].indexOf("i") > -1) {
                                var tmp = goodsValue[i][0];
                                tmp = tmp.replace("i", "")
                                $("#" + tmp).val(goodsValue[i][1]);
                            } else { // 其他选择框
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

                    alert('更新完成，保存后产品信息开始生效。');
                }
            }
        });
    }
}