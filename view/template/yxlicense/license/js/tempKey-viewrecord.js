//��ʼ��һ��ģ�建������
var templateStr = "";

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

//�鿴�������button
function initBtnClears() {
    $("#licenseDiv img").each(function () {
        $(this).remove();
    });

    $("#licenseDiv td").each(function () {
        if ($(this).attr('class') == 'clickBtn') {
            $(this).remove();
        }
    });
}

//��ʼ��licenseѡ��
$(function () {
    var licenseVal = $('#licenseId').val();
    if (licenseVal == 'undefined') {
        alert('û���������License');
        window.close();
        return false;
    }
    if (licenseVal != "") {
        $.post("?model=yxlicense_license_tempKey&action=viewRecord",
            {"id": licenseVal},
            function (data) {
                if (data != 0) {
                    data = eval("(" + data + ")");
                    $("#objType").val(data.licenseType);
                    $("#licenseType").val(data.licenseType);
                    if (data.licenseType == "PN") {
                        $("#licenseDiv").html("");
                        $("#thisVal").val(data.thisVal);
                        initPN();
                        $("#outputTable").show();
                    } else {
                        if (data.licenseStr == undefined) {
                            if (data.thisVal != "" || data.extVal != "") {
                                if (data.templateId != "") {
                                    $.ajax({
                                        type: "POST",
                                        url: "?model=yxlicense_license_template&action=getTemplate",
                                        data: {'id': data.templateId},
                                        async: false,
                                        success: function (innerDate) {
                                            if (data != 0) {
                                                innerDate = eval("(" + innerDate + ")");
                                                templateStr = innerDate.thisVal;
                                            }
                                        }
                                    });
                                }
                                $("#licenseDiv").append(data.modalStr);
                                if (data.rowVal != "") {
                                    //�г�ʼ��
                                    initRow(data.rowVal, data.extVal);
                                }

                                //ѡ����Ⱦ
                                if (data.thisVal != "") {
                                    idArr = data.thisVal.split(",");
                                    for (var i = 0; i < idArr.length; i++) {
                                        setCheck(idArr[i]);
                                    }
                                }
                                //�ı�������Ⱦ
                                if (data.extVal != "") {
                                    initInput(eval('(' + data.extVal + ')'))
                                }

                                initBtnClears();
                            }
                        } else {
                            $("#licenseDiv").append(data.licenseStr);
                        }
                    }
                } else {
                    alert('��ʼ��ʧ��');
                }
            }
        )
    }
});

function initInput(objectArr) {
    for (var t in objectArr) {
        $("#" + t).val(objectArr[t]);
        $("#" + t + "_v").html(objectArr[t]);
    }
}

/*****************Navigator + Pioneer
 */
/**
 * ��Ⱦǰ��̨����
 */
var idArr = new Array();
var idStr = "";
var index = 1;
var i = 0;


function initPN() {
    $("#licenseDiv").append("<ul id='baseinfoGrid'/>");
    $("#baseinfoGrid").yxtree({
        checkable: true,
        expandSpeed: "",
        checkedObjId: "id",
        appendData: $("#thisVal").val(),
        url: '?model=yxlicense_license_baseinfo&action=getContent',
        nameCol: "describe",
        event: {
            "node_change": function (event, treeId, treeNode) {
                //�жϵ�ǰ�ڵ�id�Ƿ�����������
                index = idArr.indexOf(treeNode.id);
                if (index != -1) {
                    //�����,��ɾ�������еĸýڵ�id
                    idArr.splice(index, 1);
                } else {
                    //���û��,��id����������
                    if (treeNode.checked) {
                        idArr.push(treeNode.id);
                    }
                }
                //�����ǰ�ڵ����ӽڵ�,���еݹ�
                if (treeNode.nodes != undefined) {
                    for (i = 0; i < treeNode.nodes.length; i++) {
                        changeFn(treeNode.nodes[i]);
                    }
                }
                //������ת�����ַ�ת
                $("#thisVal").val(idArr.toString());
            }
        }
    });
}

//�ݹ�������ڵ㺯��
function changeFn(object) {
    var index = idArr.indexOf(object.id);
    if (index != -1) {
        idArr.splice(index, 1);
    } else {
        if (object.checked) {
            idArr.push(object.id);
        }
    }
    if (object.nodes != undefined) {
        for (var i = 0; i < object.nodes.length; i++) {
            changeFn(object.nodes[i]);
        }
    }
}

//��ʾ/���ض���
function setCheck(name) {
    //��������
    if (templateStr != undefined) {
        //�жϵ�ǰ�ڵ�id�Ƿ�����������
        var templateIndex = templateStr.indexOf(name);
        if (templateIndex == -1) {
            $("#" + name).append("<div style='color:green;font-weight:bold' id=div" + name + ">[��]</div>");
        } else {
            $("#" + name).append("<div id=div" + name + ">��</div>");
        }
    } else {
        $("#" + name).append("<div style='color:green;font-weight:bold' id=div" + name + ">[��]</div>");
    }
}

//�պ���,����JS����
function dis() {
}
function disAndfocus() {
}
function changeInput() {
}


/************* �鿴����Ԥ������ *************/
$(function () {
    var templateId = $("#templateId").val();
    if (templateId == "" || templateId == 0) {
        $("#noTemplate").show();
    } else {
        $("#hasTemplate").show();
    }
});


//ģ��Ԥ��
function viewTemplate() {
    var templateId = $("#templateId").val();
    showOpenWin('?model=yxlicense_license_template&action=init&perm=view&id=' + templateId, 1)
}

//�Ա�Ԥ��
function compareTemplate() {
    var licenseId = $("#licenseId").val();
    showModalWin('?model=yxlicense_license_tempKey&action=compareTemplate&id=' + licenseId, 1)
}