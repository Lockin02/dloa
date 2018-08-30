var show_page = function (page) {
    $("#supassesGrid").yxgrid("reload");
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
    $("#supassesGrid").yxgrid({
        model: 'supplierManage_assessment_supasses',
        title: '供应商评估',
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
            name: 'assessType',
            display: '评估类型',
            sortable: true,
            datacode: 'FALX'
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
            width: 50
        }, {
            name: 'isFirst',
            display: '是否首评',
            sortable: false,
            width: 70,
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
            name: 'assessCode',
            display: '评估方案编号',
            hide: true
        }, {
            name: 'assesManName',
            display: '评估发起人',
            sortable: true,
            width: 70
        }, {
            name: 'ExaStatus',
            display: '审批状态',
            width: 70
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
        menusEx: [
            {
                text: '查看',
                icon: 'view',
                action: function (row, rows, grid) {
                    if (row) {
                        window.open("?model=supplierManage_assessment_supasses&action=toView&id=" + row.id + "&skey=" + row['skey_']);
                    }
                }
            }
            , {
                name: 'aduit',
                text: '审批情况',
                icon: 'view',
                showMenuFn: function (row) {
                    if (row.ExaStatus == '打回' || row.ExaStatus == '完成') {
                        return true;
                    }
                    return false;
                },
                action: function (row, rows, grid) {
                    if (row) {
                        showThickboxWin("controller/common/readview.php?itemtype=oa_supp_suppasses&pid="
                            + row.id
                            + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=900");
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
        }, {
            display: "评估发起人",
            name: 'assesManName'
        }]
    });
});