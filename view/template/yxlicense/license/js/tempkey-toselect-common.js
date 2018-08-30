/**
 * ��Ⱦǰ��̨����
 */
var idArr = new Array();
var idStr = "";
var index = 1;
var i = 0;

var rowStr = [];
var dataStr = {};
var tempStr = {};

//��ʼ��һ��ģ�建������
var templateStr = "";
//ģ��ָ��
var templateIndex = 1;
resetTemplate();
//��ʼ����������ֵ
function initInput(objectArr) {
    for (var t in objectArr) {
        $("#" + t).val(objectArr[t]);
        $("#" + t + "_v").html(objectArr[t]);
    }
}

//�������ֵ
function initInputClear(objectArr) {
    for (var t in objectArr) {
        $("#" + t).val("");
        $("#" + t + "_v").html("");
    }
}

//�������
function initRowClear() {
    $("#licenseDiv tr").each(function () {
        if ($(this).attr('value')) {
            $(this).remove();
        }
    });
}

//����Ⱦ����
function initRow(rowVal, extVal) {
    if (rowVal != "") {
        var rowArr = rowVal.split(',');
        var extArr = eval("(" + extVal + ")");
        for (var i = 0; i < rowArr.length; i++) {
            var key = rowArr[i];
            var str = "<td class='clickBtn'><img id='" + i + "' onclick='deleLine(" + key + ");' src='images/removeline.png' /></td>"; // ������
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

//ѡ�� ��Ⱦlicense�б�
function toselect(licenseType) {
    $("#licenseDiv").html("");

    var licenseTemplateObj = $("#licenseTemplate");
    licenseTemplateObj.empty();

    //�л�licenseʱ��ջ����ֵ
    dataStr = {};
    $("#extVal").val('');
    $("#thisVal").val('');
    $("#rowVal").val('');

    //��Ⱦ��Ӧlicense
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
        licenseTemplateObj.append("<option value=''>��ѡ��</option>");
    }
    return true;
}

//��ʼ��ģ��
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
                    //�������
                    var templateObj = $("#licenseTemplate option[idtitle='" + defaultVal + "']");
                    templateObj.attr("selected", true);
                    templateStr = templateObj.val();
                }
            } else {
                var licenseTemplateObj = $("#licenseTemplate");
                licenseTemplateObj.append("<option value=''>�����ģ��</option>");
            }
        }
    });
    return true;
}

//����ģ�嵽������
function addTempateToSelect(data, selectId) {
    $("#" + selectId).append("<option value=''>��ѡ��</option>");
    dataRows = eval('(' + data + ')');
    for (var i = 0, l = dataRows.length; i < l; i++) {
        $("#" + selectId).append("<option title='" + dataRows[i].remark + "' idTitle='" + dataRows[i].id
        + "' innerTitle='" + dataRows[i].extVal + "' rowVal='" + dataRows[i].rowVal + "'licenseType='" + dataRows[i].licenseType
        + "' value='" + dataRows[i].thisVal + "'>" + dataRows[i].name
        + "</option>");
    }
}

//ѡ��ģ�����ҳ��ֵ
function setTemplate(thisId) {
    //ģ�建�����
    if (thisId != undefined) {
        var thisTid = $("#" + thisId).find("option:selected").attr('idtitle');

        if (thisTid != undefined) {
            $("#templateId").val(thisTid);
        } else {
            $("#templateId").val(0);
        }
    }

    //���ģ�������Ϣ
    clearTemplate();

    //��ԭģ��
    resetTemplate();
}

//����ģ�����
function setTemplateClear() {
    //��ȡ��ѡ��Ķ���
    var selectedObj = $("#licenseTemplate").find("option:selected");
    //��ȡѡ��ֵ
    var thisValSel = selectedObj.attr("value");
    var extValSel = selectedObj.attr("innerTitle");
    var rowValSel = selectedObj.attr("rowVal");

    if (thisValSel == "" && extValSel == "" && rowValSel == "") {
        return false;
    }

    if (!($(".tr_even").length)) { //δ����ʱ����Ⱦ
        if (rowValSel && rowValSel != "") {
            initRow(rowValSel, extValSel);
            $("#rowVal").val(rowValSel);
        }
    }
    //ѡ����Ⱦ
    if (thisValSel && thisValSel != "") {
        templateStr = thisValSel;
        idArr = thisValSel.split(",");
        for (var i = 0; i < idArr.length; i++) {
            dis(idArr[i]);
        }
        $("#thisVal").val(thisValSel);
    }
    //�ı�������Ⱦ
    if (extValSel != "" && extValSel != undefined) {
        dataStr = eval('(' + extValSel + ')');
        initInput(dataStr);
        $("#extVal").val(extValSel);
    }
    $("#templateId").val(selectedObj.attr('idTitle'));
}

//��շ���
function clearTemplate(thisIdVal) {
    var selectedObj = $("#licenseTemplate").find("option[idTitle='" + thisIdVal + "']");
    //��ȡѡ��ֵ
    var extValSel = selectedObj.attr("innerTitle");

    $("#licenseDiv div").remove();

    //�ı�������Ⱦ
    if (extValSel != "" && extValSel != undefined) {
        dataStr = eval('(' + extValSel + ')');
        initInputClear(dataStr)
    }

    //��������з���
    initRowClear();
}

//����ģ��
function resetTemplate() {
    //��ջ���
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

//��Ⱦlicense ͨ��
function initLicense(licenseType) {
    $.post("?model=yxlicense_license_tempKey&action=returnHtml", {'licenseType': licenseType}, function (data) {
        $("#licenseDiv").append(data);
        //��ʼ��ѡ��ֵ
        var thisVal = $("#thisVal").val();
        var extVal = $("#extVal").val();
        var rowVal = $("#rowVal").val();

        if (thisVal != "" || extVal != "" || rowVal != "") {
            //����Ⱦ
            if (rowVal != "") {
                initRow(rowVal, extVal);
            }
            //ѡ����Ⱦ
            if (thisVal != "") {
                idArr = thisVal.split(",");
                for (var i = 0; i < idArr.length; i++) {
                    disInit(idArr[i]);
                }
            }
            //�ı�������Ⱦ
            if (extVal != "") {
                dataStr = eval('(' + extVal + ')');
                initInput(dataStr)
            }
        }
    });
}

//ѡ�� ��Ⱦlicense�б�
function toselicense(licenseType) {
    $("#licenseDiv").html("");

    //��Ⱦ��Ӧlicense
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

//ֱ����Ⱦģ��
function initTemplateById(templateId) {
    $.ajax({
        type: "POST",
        url: "?model=yxlicense_license_template&action=getTemplate",
        data: {'id': templateId},
        async: false,
        success: function (data) {
            //��ȡ��ѡ��Ķ���
            var selectedObj = eval("(" + data + ")");
            //��ȡѡ��ֵ
            var thisValSel = selectedObj.thisVal;
            var extValSel = selectedObj.extVal;
            if (thisValSel == "" && extValSel == "") {
                return false;
            }
            //ѡ����Ⱦ
            if (thisValSel != "") {
                templateStr = thisValSel;
                idArr = thisValSel.split(",");
                for (var i = 0; i < idArr.length; i++) {
                    dis(idArr[i]);
                }
            }
            //�ı�������Ⱦ
            if (extValSel != "" && extValSel != undefined) {
                dataStr = eval('(' + extValSel + ')');
                initInput(dataStr)
            }
//			$("#templateId").val(selectedObj.attr('idTitle'));
        }
    });
    return true;
}

/******************************** ҳ����ʾ��ѡ�񷽷� ******************************/

var thisFocus = "";
//��ʾ����ĳ���� - ����flee
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
//input��ֵ
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

//��ʾ/���ض���
function dis(name) {
    name = name + "";
    var fileName = $("#fileName").val();
    var obj = document.getElementById(name);
    var a = obj.getElementsByTagName("div");
    if (a.length > 0) {
        if (fileName != "") {
            $("#div" + name).remove();
        } else {
            //�жϵ�ǰ�ڵ�id�Ƿ�����������
            index = idArr.indexOf(name);
            if (index != -1) {
                //�����,��ɾ�������еĸýڵ�id
                idArr.splice(index, 1);
            }
            //������ת�����ַ�ת
            var idStr = idArr.toString();
            $("#thisVal").val(idStr);
            $("#div" + name).remove();
        }
    } else {
        if (fileName != "") {
            $("#" + name).append("<div id=div" + name + ">��</div>");
        } else {
            //�жϵ�ǰ�ڵ�id�Ƿ�����������
            index = idArr.indexOf(name);
            if (index == -1) {
                idArr.push(name);
            }
            //������ת�����ַ�ת
            var idStr = idArr.toString();
            $("#thisVal").val(idStr);

            if (templateStr != undefined) {
                //�жϵ�ǰ�ڵ�id�Ƿ�����������
                templateIndex = templateStr.indexOf(name);
                if (templateIndex == -1) {
                    $("#" + name).append("<div style='color:green;font-weight:bold' id=div" + name + ">[��]</div>");
                } else {
                    $("#" + name).append("<div id=div" + name + ">��</div>");
                }
            } else {
                $("#" + name).append("<div style='color:green;font-weight:bold' id=div" + name + ">[��]</div>");
            }
        }
    }
}

//iframe���ط���
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

//������з���(�༭)
function addNew() {
    var rowValObj = $("#rowVal"); // �����ݶ���
    var rowVal = ''; // ���� + 1 Ϊ����ʵ������
    var rowArr = []; // ��idֵ����
    var colArr = $(".tempLine"); // ����Ϣ
    var rowObj = $("tr[id^='row_']");
    rowObj.each(function () {
        rowVal = $(this).attr('value') * 1;
        rowArr.push(rowVal);
    });
    var rowVal = rowVal + 1; // ���� + 1 Ϊ����ʵ������
    rowArr.push(rowVal);
    var str = "<td class='clickBtn'><img id='" + rowVal + "' onclick='deleLine(" + rowVal + ");' src='images/removeline.png' /></td>"; // ������
    colArr.each(function () {
        str += "<td ondblclick=\"disAndfocus('GMS" + $(this).val() + '-' + rowVal + "')\"><span id='GMS" + $(this).val() + '-' + rowVal + "_v'></span>"
        + "<input type=\"text\" class=\"txtmiddle\" id='GMS" + $(this).val() + '-' + rowVal + "' onblur=\"changeInput('GMS" + $(this).val() + '-' + rowVal + "')\" style=\"display:none\"/></td>";
    });
    $("#tableHead").append("<tr class=\"tr_even\" id=row_" + rowVal + " value=" + rowVal + ">" + str + "</tr>");
    rowValObj.val(rowArr.toString());
}

//ɾ����ǰ��
function deleLine(rowVal) {
    var rowObj = $("tr[id^='row_']");
    var rowArr = []; // ��idֵ����
    $("#row_" + rowVal).remove();
    rowObj.each(function () {
        if (rowVal * 1 != $(this).attr('value') * 1) rowArr.push($(this).attr('value') * 1);
    });
    $("#rowVal").val(rowArr.toString());
}