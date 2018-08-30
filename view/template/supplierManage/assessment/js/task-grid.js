var show_page = function (page) {
    $("#taskGrid").yxgrid("reload");
};

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
    $("#taskGrid").yxgrid({
        model: 'supplierManage_assessment_task',
        title: '供应商评估任务',
        showcheckbox: false,
        isDelAction: false,
        isEditAction: false,
        param: {stateArr: '0,1'},
        //列信息
        colModel: [{
            display: 'id',
            name: 'id',
            sortable: true,
            hide: true
        }, {
            name: 'formCode',
            display: '评估任务编号',
            sortable: true,
            width: 120,
            process: function (v, row) {
                return "<a href='#' onclick='showThickboxWin(\"?model=supplierManage_assessment_task&action=toView&id=" + row.id + '&skey=' + row.skey_ + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=350&width=800\")'>" + v + "</a>";
            }
        }, {
            name: 'stateName',
            display: '状态'
        }, {
            name: 'assessTypeName',
            display: '评估类型',
            sortable: true
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
            name: 'formDate',
            display: '任务下达时间',
            sortable: true
        }, {
            name: 'hopeDate',
            display: '期望完成时间',
            sortable: true
        }, {
            name: 'assesManName',
            display: '任务负责人',
            sortable: true
        }, {
            name: 'purchManName',
            display: '任务下达人',
            sortable: true
        }],
        menusEx: [{
            text: '变更',
            icon: 'edit',
            showMenuFn: function (row) {
                if (row.state == 0 || row.state == 1) {
                    return true;
                }
                return false;
            },
            action: function (row, rows, grid) {
                if (row) {
                    showThickboxWin("?model=supplierManage_assessment_task&action=toEdit&id="
                        + row.id + "&skey=" + row['skey_'] + "&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=800");
                } else {
                    alert("请选中一条数据");
                }
            }

        }, {
            text: '删除',
            icon: 'delete',
            showMenuFn: function (row) {
                if (row.state == 0) {
                    return true;
                }
                return false;
            },
            action: function (row, rows, grid) {
                if (row) {
                    if (window.confirm("确认要删除?")) {
                        $.ajax({
                            type: "POST",
                            url: "?model=supplierManage_assessment_task&action=ajaxdeletes",
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
            text: '状态',
            key: 'state',
            data: [{
                text: '待接收',
                value: '0'
            }, {
                text: '已接收',
                value: '1'
            }]
        }],

        toEditConfig: {
            action: 'toEdit'
        },
        toViewConfig: {
            action: 'toView'
        },
        searchitems: [{
            display: "评估任务编号",
            name: 'formCode'
        }, {
            display: "供应商名称",
            name: 'suppName'
        }, {
            display: "任务下达时间",
            name: 'formDateSear'
        }, {
            display: "任务负责人",
            name: 'assesManName'
        }]
    });
});