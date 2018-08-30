//初始化license选单
$(function () {
    var licenseVal = $('#licenseId').val();

    if (licenseVal != "") {
        $.post("?model=yxlicense_license_tempKey&action=getRecord",
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
                    } else {
                        if (data.licenseStr == undefined) {
                            //模板部分渲染
                            $("#licenseTemplate").empty();
                            $("#templateId").val(data.templateId);
                            initTemplate(data.licenseType, data.templateId);
//							initTemplateById(data.templateId);

                            if (data.thisVal != "" || data.extVal != "") {
                                //先选择表单
                                $("#licenseDiv").append(data.modalStr);
                                //选择渲染
                                if (data.thisVal != "") {
                                    idArr = data.thisVal.split(",");
                                    for (var i = 0; i < idArr.length; i++) {
                                        dis(idArr[i]);
                                    }
                                }
                                //文本输入渲染
                                if (data.extVal != "") {
                                    dataStr = eval('(' + data.extVal + ')');
                                    initInput(dataStr)
                                }
                                //行文本渲染
                                if (data.rowVal != "") {
                                    initRow(data.rowVal, data.extVal);
                                }
                            }
                        } else {
                            $("#licenseDiv").append(data.licenseStr);
                            $("#thisVal").val(data.thisVal);
                            $("#fileName").val(data.fileName);
                        }
                    }
                    $("#objType").attr("disabled", false);
                } else {
                    alert('初始化失败');
                    $("#objType").attr("disabled", false);
                }
            }
        )
    } else {
        $("#objType").attr("disabled", false);
    }
});

//保存LICENSE
function saveTemplate() {
    var licenseType = $("#objType").val();
    var oldLicenseType = $("#licenseType").val();
    var licenseId = $("#licenseId").val();
    var thisVal = $("#thisVal").val();
    var actType = $("#actType").val();
    var fileName = $("#fileName").val();
    var templateId = $("#templateId").val();
    var rowVal = $("#rowVal").val();
    var extVal = $.obj2json(dataStr);

    if (thisVal == "" && licenseType != "" && extVal == "") {
        alert("没有选择任何加密配置！");
        return false;
    }
    if (licenseType == "") {//取消license
        alert('不能保存空类型！');
        location.reload();
        return false;
    } else {//保存license
//		return false;
        if (licenseId != "") {
            if (oldLicenseType == licenseType) {
                if (actType == 'edit') {//如果是编辑状态,则直接新增
                    $.post("?model=yxlicense_license_tempKey&action=saveRecord",
                        {
                            "licenseType": licenseType, "id": licenseId,
                            "thisVal": thisVal, "rowVal": rowVal,
                            "extVal": $.obj2json(dataStr), "templateId": templateId
                        },
                        function (data) {
                            if (data != 0) {
                                alert('保存成功');
                                window.returnValue = data;
                                window.close();
                            } else {
                                alert('保存失败');
                                closeFun();
                            }
                        }
                    );
                } else {//否则直接修改
                    $.post("?model=yxlicense_license_tempKey&action=saveRecord",
                        {
                            "licenseType": licenseType,
                            "id": licenseId,
                            "thisVal": thisVal,
                            "extVal": extVal,
                            "rowVal": rowVal,
                            "templateId": templateId
                        },
                        function (data) {
                            if (data != 0) {
                                alert('保存成功');
                                window.returnValue = data;
                                window.close();
                            } else {
                                alert('保存失败');
                                closeFun();
                            }
                        }
                    );
                }
            } else {
                if (confirm('保存后原加密申请信息会丢失,要继续么?')) {
                    if (actType == 'edit') {//如果是编辑状态,则直接新增
                        $.post("?model=yxlicense_license_tempKey&action=addRecord",
                            {
                                "licenseType": licenseType,
                                "thisVal": thisVal,
                                "extVal": extVal,
                                "rowVal": rowVal,
                                "templateId": templateId
                            },
                            function (data) {
                                if (data != 0) {
                                    alert('保存成功');
                                    window.returnValue = data;
                                    window.close();
                                } else {
                                    alert('保存失败');
                                    closeFun();
                                }
                            }
                        );
                    } else {
                        $.post("?model=yxlicense_license_tempKey&action=saveRecord",
                            {
                                "licenseType": licenseType,
                                "id": licenseId,
                                "thisVal": thisVal,
                                "extVal": extVal,
                                "rowVal": rowVal,
                                "templateId": templateId
                            },
                            function (data) {
                                if (data != 0) {
                                    alert('保存成功');
                                    window.returnValue = data;
                                    window.close();
                                } else {
                                    alert('保存失败');
                                    closeFun();
                                }
                            }
                        );
                    }
                } else {
                    return false;
                }
            }
        } else {//新增license
            $.post("?model=yxlicense_license_tempKey&action=addRecord",
                {
                    "licenseType": licenseType,
                    "thisVal": thisVal,
                    "extVal": extVal,
                    "rowVal": rowVal,
                    "templateId": templateId
                },
                function (data) {
                    if (data != 0) {
                        alert('保存成功');
                        window.returnValue = data;
                        window.close();
                    } else {
                        alert('保存失败');
                        window.close();
                    }
                }
            );
        }
    }
}