/**
 * 渲染前后台部分
 */
var idArr = new Array();
var idStr = "";
var index = 1;
var i = 0;

var rowStr = [];
var dataStr = {};
var tempStr = {};

//初始化一个模板缓存数组
var templateStr = "";
//模板指针
var templateIndex = 1;
resetTemplate();
//初始化设置输入值
function initInput(objectArr) {
    for (var t in objectArr) {
        $("#" + t).val(objectArr[t]);
        $("#" + t + "_v").html(objectArr[t]);
    }
}

//清空输入值
function initInputClear(objectArr) {
    for (var t in objectArr) {
        $("#" + t).val("");
        $("#" + t + "_v").html("");
    }
}

//清空行数
function initRowClear() {
    $("#licenseDiv tr").each(function () {
        if ($(this).attr('value')) {
            $(this).remove();
        }
    });
}

//行渲染方法
function initRow(rowVal, extVal) {
    if (rowVal != "") {
        var rowArr = rowVal.split(',');
        var extArr = eval("(" + extVal + ")");
        for (var i = 0; i < rowArr.length; i++) {
            var key = rowArr[i];
            var str = "<td class='clickBtn'><img id='" + i + "' onclick='deleLine(" + key + ");' src='images/removeline.png' /></td>"; // 功能列
            $(".tempLine").each(function () {
                var id = 'GMS' + $(this).val() + '-' + key;
                var idV = id + '_v';
                var val = extArr[id] ? extArr[id] : '';
                str += "<td ondblclick=\"disAndfocus('" + id + "')\"><span id='" + idV + "'>" + val + "</span>"
                + "<input type=\"text\" class=\"txtmiddle\" id='" + id + "' onblur=\"changeInput('" + id + "')\" style=\"display:none\" value='" + val + "'/></td>";
            });
            $("#tableHead").append("<tr class=\"tr_even\" id=row_" + key + " value=" + key + ">" + str + "</tr>");
        }
    }
}

//选择 渲染license列表
function toselect(licenseType) {
    $("#licenseDiv").html("");

    var licenseTemplateObj = $("#licenseTemplate");
    licenseTemplateObj.empty();

    //切换license时清空缓存的值
    dataStr = {};
    $("#extVal").val('');
    $("#thisVal").val('');
    $("#rowVal").val('');

    //渲染对应license
    switch (licenseType) {
        case 'PN' :
            initPN();
            break;
        case 'PIO' :
            initLicense(licenseType);
            break;
        case 'NAV' :
            initLicense(licenseType);
            break;
        case 'FL' :
            initLicense(licenseType);
            break;
        case 'FL2' :
            initLicense(licenseType);
            break;
        case 'SL' :
            initLicense(licenseType);
            break;
        case 'RCU' :
            initLicense(licenseType);
            break;
        case 'WT' :
            initLicense(licenseType);
            break;
        case 'WISER' :
            initLicense(licenseType);
            break;
        case 'Walktour Pack-Ipad' :
            initLicense(licenseType);
            break;
        default :
            initLicense(licenseType);
            break;
    }
    if (licenseType != "") {
        initTemplate(licenseType);
    } else {
        licenseTemplateObj.append("<option value=''>请选择</option>");
    }
    return true;
}

//初始化模板
function initTemplate(licenseType, defaultVal) {
    $.ajax({
        type: "POST",
        url: "?model=yxlicense_license_template&action=getTemplateByType",
        data: {'licenseType': licenseType},
        async: false,
        success: function (data) {
            if (data) {
                addTempateToSelect(data, 'licenseTemplate');
                $("#licenseTemplate").attr('disabled', false);

                if (defaultVal != undefined) {
                    //缓存对象
                    var templateObj = $("#licenseTemplate option[idtitle='" + defaultVal + "']");
                    templateObj.attr("selected", true);
                    templateStr = templateObj.val();
                }
            } else {
                var licenseTemplateObj = $("#licenseTemplate");
                licenseTemplateObj.append("<option value=''>无相关模版</option>");
            }
        }
    });
    return true;
}

//加载模板到下拉中
function addTempateToSelect(data, selectId) {
    $("#" + selectId).append("<option value=''>请选择</option>");
    dataRows = eval('(' + data + ')');
    for (var i = 0, l = dataRows.length; i < l; i++) {
        $("#" + selectId).append("<option title='" + dataRows[i].remark + "' idTitle='" + dataRows[i].id
        + "' innerTitle='" + dataRows[i].extVal + "' rowVal='" + dataRows[i].rowVal + "'licenseType='" + dataRows[i].licenseType
        + "' value='" + dataRows[i].thisVal + "'>" + dataRows[i].name
        + "</option>");
    }
}

//选择模板后处理页面值
function setTemplate(thisId) {
    //模板缓存清空
    if (thisId != undefined) {
        var thisTid = $("#" + thisId).find("option:selected").attr('idtitle');

        if (thisTid != undefined) {
            $("#templateId").val(thisTid);
        } else {
            $("#templateId").val(0);
        }
    }

    //清除模板相关信息
    clearTemplate();

    //还原模板
    resetTemplate();
}

//设置模板清空
function setTemplateClear() {
    //获取被选择的对象
    var selectedObj = $("#licenseTemplate").find("option:selected");
    //获取选择值
    var thisValSel = selectedObj.attr("value");
    var extValSel = selectedObj.attr("innerTitle");
    var rowValSel = selectedObj.attr("rowVal");

    if (thisValSel == "" && extValSel == "" && rowValSel == "") {
        return false;
    }

    if (!($(".tr_even").length)) { //未重置时行渲染
        if (rowValSel && rowValSel != "") {
            initRow(rowValSel, extValSel);
            $("#rowVal").val(rowValSel);
        }
    }
    //选择渲染
    if (thisValSel && thisValSel != "") {
        templateStr = thisValSel;
        idArr = thisValSel.split(",");
        for (var i = 0; i < idArr.length; i++) {
            dis(idArr[i]);
        }
        $("#thisVal").val(thisValSel);
    }
    //文本输入渲染
    if (extValSel != "" && extValSel != undefined) {
        dataStr = eval('(' + extValSel + ')');
        initInput(dataStr);
        $("#extVal").val(extValSel);
    }
    $("#templateId").val(selectedObj.attr('idTitle'));
}

//清空方法
function clearTemplate(thisIdVal) {
    var selectedObj = $("#licenseTemplate").find("option[idTitle='" + thisIdVal + "']");
    //获取选择值
    var extValSel = selectedObj.attr("innerTitle");

    $("#licenseDiv div").remove();

    //文本输入渲染
    if (extValSel != "" && extValSel != undefined) {
        dataStr = eval('(' + extValSel + ')');
        initInputClear(dataStr)
    }

    //调用清空行方法
    initRowClear();
}

//重置模板
function resetTemplate() {
    //清空缓存
    idArr = [];
    idStr = "";
    dataStr = {};
    tempStr = {};
    templateStr = "";

    $("#thisVal").val('');
    $("#extVal").val('');
    $("#licenseDiv div").remove();
    $("#licenseDiv span").html("");

    if ($("#templateId").val() != "") {
        setTemplateClear();
    }
}

//渲染license 通用
function initLicense(licenseType) {
    $.post("?model=yxlicense_license_tempKey&action=returnHtml", {'licenseType': licenseType}, function (data) {
        $("#licenseDiv").append(data);
        //初始化选择值
        var thisVal = $("#thisVal").val();
        var extVal = $("#extVal").val();
        var rowVal = $("#rowVal").val();

        if (thisVal != "" || extVal != "" || rowVal != "") {
            //行渲染
            if (rowVal != "") {
                initRow(rowVal, extVal);
            }
            //选择渲染
            if (thisVal != "") {
                idArr = thisVal.split(",");
                for (var i = 0; i < idArr.length; i++) {
                    disInit(idArr[i]);
                }
            }
            //文本输入渲染
            if (extVal != "") {
                dataStr = eval('(' + extVal + ')');
                initInput(dataStr)
            }
        }
    });
}

//选择 渲染license列表
function toselicense(licenseType) {
    $("#licenseDiv").html("");

    //渲染对应license
    switch (licenseType) {
        case 'PN' :
            initPN();
            break;
        case 'PIO' :
            initLicense(licenseType);
            break;
        case 'NAV' :
            initLicense(licenseType);
            break;
        case 'FL' :
            initLicense(licenseType);
            break;
        case 'FL2' :
            initLicense(licenseType);
            break;
        case 'SL' :
            initLicense(licenseType);
            break;
        case 'RCU' :
            initLicense(licenseType);
            break;
        case 'WT' :
            initLicense(licenseType);
            break;
        case 'WISER' :
            initLicense(licenseType);
            break;
        default :
            break;
    }
    return true;
}

//直接渲染模版
function initTemplateById(templateId) {
    $.ajax({
        type: "POST",
        url: "?model=yxlicense_license_template&action=getTemplate",
        data: {'id': templateId},
        async: false,
        success: function (data) {
            //获取被选择的对象
            var selectedObj = eval("(" + data + ")");
            //获取选择值
            var thisValSel = selectedObj.thisVal;
            var extValSel = selectedObj.extVal;
            if (thisValSel == "" && extValSel == "") {
                return false;
            }
            //选择渲染
            if (thisValSel != "") {
                templateStr = thisValSel;
                idArr = thisValSel.split(",");
                for (var i = 0; i < idArr.length; i++) {
                    dis(idArr[i]);
                }
            }
            //文本输入渲染
            if (extValSel != "" && extValSel != undefined) {
                dataStr = eval('(' + extValSel + ')');
                initInput(dataStr)
            }
//			$("#templateId").val(selectedObj.attr('idTitle'));
        }
    });
    return true;
}

/******************************** 页面显示和选择方法 ******************************/

var thisFocus = "";
//显示隐藏某对象 - 用于flee
function disAndfocus(name) {
    if (document.activeElement.id == "") {
        var temp = document.getElementById(name);
        temp.value = $("#" + name + "_v").html();
        if (temp.style.display == '')
            temp.style.display = "none";
        else if (temp.style.display == "none")
            temp.style.display = '';
        temp.focus();
    }
}
//input赋值
function changeInput(focusId) {
    var tempVal = $("#" + focusId).val();
    $("#" + focusId).val(tempVal).hide();
    $("#" + focusId + "_v").html(tempVal);
    //var tempStr={};
    if (tempVal != "") {
        dataStr[focusId] = tempVal;
    } else {
        delete(dataStr[focusId]);
    }
    //dataStr=tempStr;
}

//显示/隐藏对象
function dis(name) {
    name = name + "";
    var fileName = $("#fileName").val();
    var obj = document.getElementById(name);
    var a = obj.getElementsByTagName("div");
    if (a.length > 0) {
        if (fileName != "") {
            $("#div" + name).remove();
        } else {
            //判断当前节点id是否在数组里面
            index = idArr.indexOf(name);
            if (index != -1) {
                //如果有,则删除数组中的该节点id
                idArr.splice(index, 1);
            }
            //将数组转换成字符转
            var idStr = idArr.toString();
            $("#thisVal").val(idStr);
            $("#div" + name).remove();
        }
    } else {
        if (fileName != "") {
            $("#" + name).append("<div id=div" + name + ">√</div>");
        } else {
            //判断当前节点id是否在数组里面
            index = idArr.indexOf(name);
            if (index == -1) {
                idArr.push(name);
            }
            //将数组转换成字符转
            var idStr = idArr.toString();
            $("#thisVal").val(idStr);

            if (templateStr != undefined) {
                //判断当前节点id是否在数组里面
                templateIndex = templateStr.indexOf(name);
                if (templateIndex == -1) {
                    $("#" + name).append("<div style='color:green;font-weight:bold' id=div" + name + ">[√]</div>");
                } else {
                    $("#" + name).append("<div id=div" + name + ">√</div>");
                }
            } else {
                $("#" + name).append("<div style='color:green;font-weight:bold' id=div" + name + ">[√]</div>");
            }
        }
    }
}

//iframe返回方法
function back() {
    var goodsId = $("#goodsId").val();
    var goodsName = $("#goodsName").val();
    var number = $("#number").val();
    var price = $("#price").val();
    var cacheId = $("#cacheId").val();
    var money = $("#money").val();
    var isEncrypt = $("#isEncrypt").val();
    window.location.href = '?model=goods_goods_properties&action=toChooseStep'
    + '&goodsId=' + goodsId
    + '&goodsName=' + goodsName
    + '&cacheId=' + cacheId
    + '&isEncrypt=' + isEncrypt
    + '&number=' + number
    + '&price=' + money
    + '&money=' + price
    + '&warrantyPeriod=';
}

//点击增行方法(编辑)
function addNew() {
    var rowValObj = $("#rowVal"); // 行数据对象
    var rowVal = ''; // 行数 + 1 为现有实际行数
    var rowArr = []; // 行id值缓存
    var colArr = $(".tempLine"); // 列信息
    var rowObj = $("tr[id^='row_']");
    rowObj.each(function () {
        rowVal = $(this).attr('value') * 1;
        rowArr.push(rowVal);
    });
    var rowVal = rowVal + 1; // 行数 + 1 为现有实际行数
    rowArr.push(rowVal);
    var str = "<td class='clickBtn'><img id='" + rowVal + "' onclick='deleLine(" + rowVal + ");' src='images/removeline.png' /></td>"; // 功能列
    colArr.each(function () {
        str += "<td ondblclick=\"disAndfocus('GMS" + $(this).val() + '-' + rowVal + "')\"><span id='GMS" + $(this).val() + '-' + rowVal + "_v'></span>"
        + "<input type=\"text\" class=\"txtmiddle\" id='GMS" + $(this).val() + '-' + rowVal + "' onblur=\"changeInput('GMS" + $(this).val() + '-' + rowVal + "')\" style=\"display:none\"/></td>";
    });
    $("#tableHead").append("<tr class=\"tr_even\" id=row_" + rowVal + " value=" + rowVal + ">" + str + "</tr>");
    rowValObj.val(rowArr.toString());
}

//删除当前行
function deleLine(rowVal) {
    var rowObj = $("tr[id^='row_']");
    var rowArr = []; // 行id值缓存
    $("#row_" + rowVal).remove();
    rowObj.each(function () {
        if (rowVal * 1 != $(this).attr('value') * 1) rowArr.push($(this).attr('value') * 1);
    });
    $("#rowVal").val(rowArr.toString());
}