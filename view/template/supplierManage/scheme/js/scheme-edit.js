$(function () {
    /**
     * 方案名称唯一性验证
     */
    var url = "?model=supplierManage_scheme_scheme&action=checkRepeat";
    if ($("#parentId").val()) {
        url += "&id=" + $("#parentId").val();
    }
    $("#schemeName").ajaxCheck({
        url: url,
        alertText: "* 该名称已存在",
        alertTextOk: "* 该名称可用"
    });
    $("#schemeCode").ajaxCheck({
        url: url,
        alertText: "* 该编码已存在",
        alertTextOk: "* 该编码可用"
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
            display: '评估部门',
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
            display: '评估部门Id',
            name: 'assesDeptId',
            type: 'hidden'
        }, {
            display: '评估项目',
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
            display: '评估指标',
            name: 'assesStandard',
            validation: {
                required: true
            }
        }, {
            display: '负责人',
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
            display: '负责人Id',
            name: 'assesManId',
            type: 'hidden'
        }, {
            display: '指标权重',
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
            display: '评估说明',
            name: 'assesExplain',
            tclass: 'txtlong',
            width: 380
        }]
    });
    /**
     * 验证信息
     */
    validate({
        "formManName": {
            required: true
        }
    });

});
// 根据从表的总权重必须为100
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
        alert("权重总和必须为100！");
        return false;
    }
    return true;
}