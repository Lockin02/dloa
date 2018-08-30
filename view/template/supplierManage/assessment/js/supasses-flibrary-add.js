$(document).ready(function () {
    $("#menberName").yxselect_user({
        hiddenId: 'menberId',
        mode: "check"
    });
    uploadfile = createSWFUpload({
        "serviceType": "oa_supp_suppasses"
    });
    $("#newsupp").hide();
    $("#yearsupp").hide();
    if ($("#assessType").val() == "xgyspg") {
        $("#newsupp").show();
        $("#yearsupp").hide();
        $("#suppassesTable").html("");
        $("#assessName").val("");
        $("#assessId").val("");
        $("#assessCode").val("");
        $("#totalNum").val("");
    }
    if ($("#assessType").val() == "gysjd") {
        $("#newsupp").hide();
        $("#yearsupp").show();
        $("#suppassesTable").html("");
        $("#assessName").val("");
        $("#assessId").val("");
        $("#assessCode").val("");
        $("#totalNum").val("");
    }
    if ($("#assessType").val() == "gysnd") {
        $("#newsupp").hide();
        $("#yearsupp").show();
        $("#suppassesTable").html("");
        $("#assessName").val("");
        $("#assessId").val("");
        $("#assessCode").val("");
        $("#totalNum").val("");
    }
    var assessType = $("#assessType").val();
    $("#assessName").yxcombogrid_scheme({
        hiddenId: 'assessId',
        width: 500,
        gridOptions: {
            showcheckbox: false,
            param: {"schemeType": assessType},
            event: {
                'row_dblclick': function (e, row, data) {
                    $("#assessCode").val(data.schemeCode);
                    $("#suppassesTable").html("");
                    $("#suppassesTable").yxeditgrid({
                        objName: 'supasses[assesmentitem]',
                        url: '?model=supplierManage_assessment_assesmentitem&action=addItemJson',
                        param: {parentId: data.id},
                        isAddAndDel: false,
                        colModel: [{
                            display: '��������',
                            name: 'assesDept',
                            type: 'txt',
                            width: 100,
                            readonly: true,
                            validation: {
                                required: true
                            },
                            process: function ($input) {
                                var rowNum = $input.data("rowNum");
                                $input.yxselect_dept({
                                    hiddenId: 'suppassesTable_cmp_assesDeptId' + rowNum
                                });
                            }
                        }, {
                            display: '��������Id',
                            name: 'assesDeptId',
                            type: 'hidden'
                        }, {
                            display: '������Ŀ',
                            name: 'assesProName',
                            type: 'statictext'
                        }, {
                            display: '������Ŀ',
                            name: 'assesProName',
                            type: 'hidden'
                        }, {
                            display: '����ָ��',
                            name: 'assesStandard',
                            type: 'statictext'
                        }, {
                            display: '����ָ��',
                            name: 'assesStandard',
                            type: 'hidden'
                        }, {
                            display: 'ָ��Ȩ��',
                            name: 'assesProportion',
                            type: 'statictext'
                        }, {
                            display: 'ָ��Ȩ��',
                            name: 'assesProportion',
                            type: 'hidden'
                        }, {
                            display: '����˵��',
                            name: 'assesExplain',
                            type: 'statictext'
                        }, {
                            display: '����˵��',
                            name: 'assesExplain',
                            type: 'hidden'
                        }, {
                            display: '������',
                            name: 'assesMan',
                            type: 'txt',
                            width: 100,
                            readonly: true,
                            validation: {
                                required: true
                            },
                            process: function ($input) {
                                var rowNum = $input.data("rowNum");
                                $input.yxselect_user({
                                    mode: 'check',
                                    hiddenId: 'suppassesTable_cmp_assesManId' + rowNum
                                });
                            }
                        }, {
                            display: '������Id',
                            name: 'assesManId',
                            type: 'hidden'
                        }]
                    });
                }
            }
        }
    });
});

// �����ܷ���
function check_all() {
    var totalNum = 0;
    var cmps = $("#suppassesTable").yxeditgrid("getCmpByCol", "assesScore");
    cmps.each(function () {
        totalNum = accAdd(totalNum, $(this).val(), 2);
    });
    $("#totalNum").val(totalNum);
    checkGrade(totalNum);//���ֹ�Ӧ�̵ȼ�
    //	return false;
}

//���ֹ�Ӧ�̵ȼ�
function checkGrade(totalNum) {
    var assessType = $("#assessType").val();
    if (assessType == "gysjd" || assessType == "gysnd") {
        if (totalNum > 90) {
            $("#suppGrade").val("A");
        } else if (totalNum == 75 || totalNum > 75) {
            $("#suppGrade").val("B");
        } else if (totalNum > 60 || totalNum == 60) {
            $("#suppGrade").val("C");
        } else if (totalNum < 60) {
            $("#suppGrade").val("D");
        }
    } else if (assessType == "xgyspg") {
        if (totalNum > 70 || totalNum == 70) {
            $("#suppGrade").val("C");
        }
    }
}


//ֱ���ύ����
function toSubmit() {
    document.getElementById('form1').action = "index1.php?model=supplierManage_assessment_supasses&action=add&type=passSupp&actType=audit";
}