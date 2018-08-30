$(function () {
    /**
     * ��������Ψһ����֤
     */
    var url = "?model=supplierManage_scheme_scheme&action=checkRepeat";
    if ($("#parentId").val()) {
        url += "&id=" + $("#parentId").val();
    }
    $("#schemeName").ajaxCheck({
        url: url,
        alertText: "* �������Ѵ���",
        alertTextOk: "* �����ƿ���"
    });
    $("#schemeCode").ajaxCheck({
        url: url,
        alertText: "* �ñ����Ѵ���",
        alertTextOk: "* �ñ������"
    });
    $("#schemeTable").yxeditgrid({
        objName: 'scheme[schemeItem]',
        url: '?model=supplierManage_scheme_schemeItem&action=listJson',
        param: {
            parentId: $("#parentId").val()
        },
        event: {
            removeRow: function (t, rowNum, rowData) {
                check_all();
            },
            clickAddRow: function (t, rowNum) {
                $("#schemeTable_cmp_assesManId" + rowNum).val($("#formManId").val());
                $("#schemeTable_cmp_assesMan" + rowNum).val($("#formManName").val());
                $("#schemeTable_cmp_assesDept" + rowNum).val($("#formManDept").val());
                $("#schemeTable_cmp_assesDeptId" + rowNum).val($("#formManDeptId").val());
            }
        },
        colModel: [{
            display: 'id',
            name: 'id',
            type: 'hidden'
        }, {
            display: '��������',
            name: 'assesDept',
            type: 'txt',
            width: 130,
            readonly: true,
            validation: {
                required: true
            },
            process: function ($input) {
                var rowNum = $input.data("rowNum");
                $input.yxselect_dept({
                    mode: 'check',
                    hiddenId: 'schemeTable_cmp_assesDeptId' + rowNum,
                    event: {
                        'selectReturn': function (e, data) {
                            $.ajax({
                                type: "POST",
                                url: "?model=supplierManage_scheme_scheme&action=getDeptLeader",
                                data: {
                                    deptId: data.val
                                },
                                success: function (data) {
                                    if (data != 0) {
                                        var o = eval("(" + data + ")");
                                        if (o.userId != "") {
                                            $("#schemeTable_cmp_assesManId" + rowNum).val(o.userId);
                                            $("#schemeTable_cmp_assesMan" + rowNum).val(o.userName);
                                        }
                                    } else {
                                        $("#schemeTable_cmp_assesManId" + rowNum).val("");
                                        $("#schemeTable_cmp_assesMan" + rowNum).val("");
                                    }
                                }
                            });
                        },
                        'clearReturn': function (e) {
                            $("#schemeTable_cmp_assesManId" + rowNum).val("");
                            $("#schemeTable_cmp_assesMan" + rowNum).val("");

                        }
                    }
                });
            }
        }, {
            display: '��������Id',
            name: 'assesDeptId',
            type: 'hidden'
        }, {
            display: '������Ŀ',
            name: 'assesProName',
            process: function ($input, rowData) {
                var rowNum = $input.data("rowNum");
                var g = $input.data("grid");
                $input.yxcombogrid_schemeproject({
                    hiddenId: 'schemeTable_cmp_assesId' + rowNum,
                    nameCol: 'assesProName',
                    gridOptions: {
                        showcheckbox: false,
                        param: {
                            'isDel': '0',
                            'isScrap': '0'
                        },
                        event: {
                            row_dblclick: (function (rowNum) {
                                return function (e, row, rowData) {
                                }
                            })(rowNum)
                        }
                    }
                });
            },
            validation: {
                required: true
            }
        }, {
            display: '����ָ��',
            name: 'assesStandard',
            validation: {
                required: true
            }
        }, {
            display: '������',
            name: 'assesMan',
            type: 'txt',
            width: 130,
            readonly: true,
            validation: {
                required: true
            },
            process: function ($input) {
                var rowNum = $input.data("rowNum");
                $input.yxselect_user({
                    mode: 'check',
                    hiddenId: 'schemeTable_cmp_assesManId' + rowNum
                });
            }
        }, {
            display: '������Id',
            name: 'assesManId',
            type: 'hidden'
        }, {
            display: 'ָ��Ȩ��',
            name: 'assesProportion',
            tclass: 'txtshort',
            event: {
                blur: function () {
                    check_all();
                }
            },
            validation: {
                custom: ['onlyNumber']
            }
        }, {
            display: '����˵��',
            name: 'assesExplain',
            tclass: 'txtlong',
            width: 380
        }]
    });
    /**
     * ��֤��Ϣ
     */
    validate({
        "formManName": {
            required: true
        }
    });

});
// ���ݴӱ����Ȩ�ر���Ϊ100
function check_all() {
    var rowAmountVa = 0;
    var cmps = $("#schemeTable").yxeditgrid("getCmpByCol", "assesProportion");
    cmps.each(function () {
        rowAmountVa = accAdd(rowAmountVa, $(this).val(), 2);
    });
    $("#schemeSum").val(rowAmountVa);
    return false;
}

function check_form() {
//	alert($("#schemeSum").val());
    if ($("#schemeSum").val() != "100") {
        alert("Ȩ���ܺͱ���Ϊ100��");
        return false;
    }
    return true;
}