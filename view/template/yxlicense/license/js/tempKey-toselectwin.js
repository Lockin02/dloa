//��ʼ��licenseѡ��
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
                            //ģ�岿����Ⱦ
                            $("#licenseTemplate").empty();
                            $("#templateId").val(data.templateId);
                            initTemplate(data.licenseType, data.templateId);
//							initTemplateById(data.templateId);

                            if (data.thisVal != "" || data.extVal != "") {
                                //��ѡ���
                                $("#licenseDiv").append(data.modalStr);
                                //ѡ����Ⱦ
                                if (data.thisVal != "") {
                                    idArr = data.thisVal.split(",");
                                    for (var i = 0; i < idArr.length; i++) {
                                        dis(idArr[i]);
                                    }
                                }
                                //�ı�������Ⱦ
                                if (data.extVal != "") {
                                    dataStr = eval('(' + data.extVal + ')');
                                    initInput(dataStr)
                                }
                                //���ı���Ⱦ
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
                    alert('��ʼ��ʧ��');
                    $("#objType").attr("disabled", false);
                }
            }
        )
    } else {
        $("#objType").attr("disabled", false);
    }
});

//����LICENSE
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
        alert("û��ѡ���κμ������ã�");
        return false;
    }
    if (licenseType == "") {//ȡ��license
        alert('���ܱ�������ͣ�');
        location.reload();
        return false;
    } else {//����license
//		return false;
        if (licenseId != "") {
            if (oldLicenseType == licenseType) {
                if (actType == 'edit') {//����Ǳ༭״̬,��ֱ������
                    $.post("?model=yxlicense_license_tempKey&action=saveRecord",
                        {
                            "licenseType": licenseType, "id": licenseId,
                            "thisVal": thisVal, "rowVal": rowVal,
                            "extVal": $.obj2json(dataStr), "templateId": templateId
                        },
                        function (data) {
                            if (data != 0) {
                                alert('����ɹ�');
                                window.returnValue = data;
                                window.close();
                            } else {
                                alert('����ʧ��');
                                closeFun();
                            }
                        }
                    );
                } else {//����ֱ���޸�
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
                                alert('����ɹ�');
                                window.returnValue = data;
                                window.close();
                            } else {
                                alert('����ʧ��');
                                closeFun();
                            }
                        }
                    );
                }
            } else {
                if (confirm('�����ԭ����������Ϣ�ᶪʧ,Ҫ����ô?')) {
                    if (actType == 'edit') {//����Ǳ༭״̬,��ֱ������
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
                                    alert('����ɹ�');
                                    window.returnValue = data;
                                    window.close();
                                } else {
                                    alert('����ʧ��');
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
                                    alert('����ɹ�');
                                    window.returnValue = data;
                                    window.close();
                                } else {
                                    alert('����ʧ��');
                                    closeFun();
                                }
                            }
                        );
                    }
                } else {
                    return false;
                }
            }
        } else {//����license
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
                        alert('����ɹ�');
                        window.returnValue = data;
                        window.close();
                    } else {
                        alert('����ʧ��');
                        window.close();
                    }
                }
            );
        }
    }
}