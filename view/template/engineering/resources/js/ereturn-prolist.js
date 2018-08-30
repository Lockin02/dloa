var show_page = function () {
    $("#ereturnlistGrid").yxgrid("reload");
};

$(function () {
    var projectId = $("#projectId").val();
    $("#ereturnlistGrid").yxgrid({
        model: 'engineering_resources_ereturn',
        action: 'proPageJson',
        param: {
            projectId: projectId
        },
        title: '设备归还单',
        isDelAction: false,
        isOpButton: false,
        isAddAction: false,
        isEditAction: false,
        showcheckbox: false,
        //列信息
        colModel: [{
            display: 'id',
            name: 'id',
            sortable: true,
            hide: true
        }, {
            name: 'status',
            display: '状态',
            sortable: true,
            width: 30,
            align: 'center',
            process: function (v) {
                switch (v) {
                    case '0' :
                        return '<img src="images/icon/cicle_grey.png" title="未提交"/>';
                    case '1' :
                        return '<img src="images/icon/cicle_blue.png" title="已提交"/>';
                    case '2' :
                        return '<img src="images/icon/cicle_green.png" title="已确认"/>';
                }
            }
        }, {
            display: '申请单编号',
            name: 'formNo',
            width: 140,
            sortable: true,
            process: function (v, row) {
                return "<a href='javascript:void(0)' onclick='showOpenWin(\"?model=engineering_resources_ereturn&action=toView&id="
                    + row.id + "\",1,700,1100," + row.id + ")'>" + v + "</a>";
            }
        }, {
            name: 'applyUser',
            display: '申请人',
            sortable: true,
            width: 90,
            hide: true
        }, {
            name: 'applyDate',
            display: '申请日期',
            sortable: true,
            width: 90
        }, {
            name: 'areaName',
            display: '归还区域',
            sortable: true,
            width: 80
        }, {
            name: 'projectCode',
            display: '项目编号',
            sortable: true,
            width: 150
        }, {
            name: 'projectName',
            display: '项目名称',
            sortable: true,
            width: 150
        }, {
            name: 'managerName',
            display: '项目经理',
            sortable: true,
            width: 80,
            hide: true
        }, {
            name: 'expressName',
            display: '快递公司',
            sortable: true,
            width: 120
        }, {
            name: 'expressNo',
            display: '快递单号',
            sortable: true,
            width: 120
        }, {
            name: 'remark',
            display: '备注信息',
            sortable: true,
            width: 130
        }],
        toViewConfig: {
            toViewFn: function (p, g) {
                var rowObj = g.getSelectedRow();
                var row = rowObj.data('data');
                showOpenWin("?model=engineering_resources_ereturn&action=toView&id="
                + row[p.keyField], 1, 700, 1100, row.id);
            }
        },

        searchitems: [{
            display: "申请单编号",
            name: 'formNoSch'
        }, {
            display: "归还区域",
            name: 'areaNameSch'
        }, {
            display: "项目编号",
            name: 'projectCodeSch'
        }, {
            display: "项目名称",
            name: 'projectNameSch'
        }],

        //过滤数据
        comboEx: [{
            text: '状态',
            key: 'status',
            data: [{
                text: '待提交',
                value: '0'
            }, {
                text: '已提交',
                value: '1'
            }, {
                text: '已确认',
                value: '2'
            }]
        }]
    });
});