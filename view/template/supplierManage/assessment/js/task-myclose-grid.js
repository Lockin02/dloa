var show_page = function (page) {
    $("#taskMyCloseGrid").yxgrid("reload");
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
    $("#taskMyCloseGrid").yxgrid({
        model: 'supplierManage_assessment_task',
        title: '已完成评估任务',
        action: 'myPageJson',
        param: {stateArr: '2,3'},
        showcheckbox: false,
        isAddAction: false,
        isEditAction: false,
        isDelAction: false,
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
                return "<a href='#' onclick='showThickboxWin(\"?model=supplierManage_assessment_task&action=toView&id=" + row.id + '&skey=' + row.skey_ + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=800\")'>" + v + "</a>";
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
            name: 'purchManName',
            display: '任务下达人',
            sortable: true
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
        }]
    });
});