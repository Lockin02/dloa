var show_page = function () {
    $("#supassesMyGrid").yxgrid("reload");
};

//查看评估详细信息
function viewDetail(id, skey) {
    showOpenWin("?model=supplierManage_assessment_supasses&action=toView&id=" + id + "&skey=" + skey);
}

/**
 * 查看供应商信息
 */
function viewSupplier(suppId) {
    var skey = "";
    $.ajax({
        type: "POST",
        url: "?model=supplierManage_formal_flibrary&action=md5RowAjax",
        data: {
            "id": suppId
        },
        async: false,
        success: function (data) {
            skey = data;
        }
    });
    showModalWin("?model=supplierManage_formal_flibrary&action=toRead&id="
        + suppId
        + "&skey="
        + skey
        + "&perm=view&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=600&width=1100");
}
$(function () {
    $("#supassesMyGrid").yxgrid({
        model: 'supplierManage_assessment_supasses',
        action: "myListJson",
        title: '我发起的供应商评估',
        showcheckbox: false,
        isDelAction: false,
        isAddAction: false,
        isToolBar: false,
        bodyAlign: 'center',
        //列信息
        colModel: [{
            display: 'id',
            name: 'id',
            sortable: true,
            hide: true
        }, {
            name: 'formCode',
            display: '单据编号',
            sortable: true,
            width: 150,
            process: function (v, row) {
                var skey = row['skey_'];
                return "<a href='#' title='查看详细信息' onclick='viewDetail(\""
                    + row.id
                    + "\",\""
                    + skey
                    + "\")' >"
                    + v
                    + "</a>";
            }
        }, {
            name: 'assessTypeName',
            display: '评估类型',
            sortable: true
        }, {
            name: 'suppId',
            display: '供应商ID',
            hide: true
        }, {
            name: 'suppName',
            display: '供应商名称',
            sortable: true,
            width: 160,
            process: function (v, row) {
                return "<a href='#' onclick='viewSupplier("
                    + row.suppId
                    + ")' >"
                    + v
                    + "</a>";
            }
        }, {
            name: 'totalNum',
            display: '总分',
            sortable: true,
            width: 60
        }, {
            name: 'suppGrade',
            display: '所属等级',
            sortable: true,
            width: 60
        }, {
            name: 'isFirst',
            display: '是否首评',
            sortable: false,
            width: 60,
            process: function (v, row) {
                if (row.assessType == "xgyspg") {
                    if (v == 1) {
                        return '首评';
                    } else {
                        return "第二次评估";
                    }
                } else {
                    return "";
                }
            }
        }, {
            name: 'assessName',
            display: '评估方案名称',
            width: 160,
            sortable: true
        }, {
            name: 'ExaStatus',
            display: '审批状态',
            width: 60,
            sortable: true
        }, {
            name: 'ExaDT',
            display: '审批时间',
            width: 130
        }],

        comboEx: [{
            text: '评估类型',
            key: 'assessType',
            data: [{
                text: '新供应商评估',
                value: 'xgyspg'
            }, {
                text: '供应商季度考核',
                value: 'gysjd'
            }, {
                text: '供应商年度考核',
                value: 'gysnd'
            }]
        }, {
            text: '审批状态',
            key: 'ExaStatus',
            data: [{
                text: '未提交',
                value: '未提交'
            }, {
                text: '部门审批',
                value: '部门审批'
            }, {
                text: '打回',
                value: '打回'
            }, {
                text: '完成',
                value: '完成'
            }]
        }],

        //扩展右键
        menusEx: [{
            text: '查看',
            icon: 'view',
            action: function (row, rows, grid) {
                if (row) {
                    window.open("?model=supplierManage_assessment_supasses&action=toView&id=" + row.id + "&skey=" + row['skey_']);
                }
            }
        }, {
            text: '修改',
            icon: 'edit',
            showMenuFn: function (row) {
                if (row.ExaStatus == "未提交" || row.ExaStatus == "打回") {
                    return true;
                }
                return false;
            },
            action: function (row, rows, grid) {
                if (row) {
                    location = "?model=supplierManage_assessment_supasses&action=toEdit&id=" + row.id + "&skey=" + row['skey_'];
                }
            }
        }, {
            name: 'sumbit',
            text: '提交评估',
            icon: 'edit',
            showMenuFn: function (row) {
                if (row.ExaStatus == '未提交') {
                    return true;
                }
                return false;
            },
            action: function (row, rows, grid) {
                if (row) {
                    $.ajax({
                        type: "POST",
                        url: "?model=supplierManage_assessment_supasses&action=sumbitAsses",
                        data: {
                            id: row.id
                        },
                        success: function (msg) {
                            if (msg == 1) {
                                alert('提交成功!');
                                show_page();
                            }
                        }
                    });
                } else {
                    alert("请选中一条数据");
                }
            }
        }, {
            name: 'aduit',
            text: '审批情况',
            icon: 'view',
            showMenuFn: function (row) {
                if (row.ExaStatus == '打回' || row.ExaStatus == '完成' || row.ExaStatus == '部门审批') {
                    return true;
                }
                return false;
            },
            action: function (row, rows, grid) {
                if (row) {
                    showThickboxWin("controller/common/readview.php?itemtype=oa_supp_suppasses&pid="
                        + row.id
                        + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=200&width=800");
                }
            }
        }, {
            text: '二次评估',
            icon: 'edit',
            showMenuFn: function (row) {
                if (row.ExaStatus == '完成' && row.totalNum < 70 && row.assessType == "xgyspg" && row.assesState == 1 && row.isFirst == 1) {
                    return true;
                }
                return false;
            },
            action: function (row, rows, grid) {
                if (row) {
                    location = "?model=supplierManage_assessment_supasses&action=toSecondAss&id=" + row.id + "&skey=" + row['skey_'];
                }
            }
        },
            {
                text: '删除',
                icon: 'delete',
                showMenuFn: function (row) {
                    if (row.ExaStatus == '打回' || row.ExaStatus == "未提交") {
                        return true;
                    }
                    return false;
                },
                action: function (row, rows, grid) {
                    if (row) {
                        if (window.confirm("确认要删除?")) {
                            $.ajax({
                                type: "POST",
                                url: "?model=supplierManage_assessment_supasses&action=deletesInfo",
                                data: {
                                    id: row.id
                                },
                                success: function (msg) {
                                    if (msg == 1) {
                                        alert('删除成功!');
                                        show_page();
                                    }
                                }
                            });
                        }
                    }
                }
            }],
        toEditConfig: {
            action: 'toEdit'
        },
        toViewConfig: {
            action: 'toView'
        },
        searchitems: [{
            display: "供应商名称",
            name: 'suppName'
        }]
    });
});