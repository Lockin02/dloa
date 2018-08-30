var show_page = function () {
    $("#resourceapplyGrid").yxgrid("reload");
};

$(function () {
    var projectCode = $("#projectCode").val();
    var projectId = $("#projectId").val();
    $("#resourceapplyGrid").yxgrid({
        model: 'engineering_resources_resourceapply',
        action: 'proPageJson',
        param: {
            projectId: projectId,
            projectCode: projectCode
        },
        title: '项目设备申请',
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
            name: 'confirmStatus',
            display: '确认',
            sortable: true,
            width: 30,
            align: 'center',
            process: function (v, row) {
                switch (v) {
                    case '0' :
                        return '';
                    case '1' :
                        return '<img src="images/icon/ok3.png" title="正在确认"/>';
                    case '2' :
                        return '<img src="images/icon/ok2.png" title="已确认,确认人[' + row.confirmName + '],确认时间[' + row.confirmTime + ']"/>';
                }
            }
        }, {
            name: 'status',
            display: '处理',
            sortable: true,
            width: 30,
            align: 'center',
            process: function (v) {
                switch (v) {
                    case '0' :
                        return '';
                    case '1' :
                        return '<img src="images/icon/cicle_blue.png" title="处理中"/>';
                    case '2' :
                        return '<img src="images/icon/cicle_green.png" title="已处理"/>';
                }
            }
        }, {
            name: 'formNo',
            display: '申请单编号',
            sortable: true,
            width: 120,
            process: function (v, row) {
                return "<a href='javascript:void(0)' onclick='showOpenWin(\"?model=engineering_resources_resourceapply&action=toView&id="
                    + row.id + '&skey=' + row.skey_ + "\",1,700,1100," + row.id + ")'>" + v + "</a>";
            }
        }, {
            name: 'applyUser',
            display: '申请人',
            sortable: true,
            width: 80,
            hide: true
        }, {
            name: 'applyUserId',
            display: '申请人id',
            sortable: true,
            hide: true
        }, {
            name: 'applyDate',
            display: '申请日期',
            sortable: true,
            width: 75
        }, {
            name: 'applyTypeName',
            display: '申请类型',
            sortable: true,
            width: 70
        }, {
            name: 'getTypeName',
            display: '领用方式',
            sortable: true,
            width: 70
        }, {
            name: 'place',
            display: '设备使用地',
            sortable: true
        }, {
            name: 'deptName',
            display: '所属部门',
            sortable: true,
            hide: true
        }, {
            name: 'projectCode',
            display: '项目编号',
            sortable: true,
            width: 120
        }, {
            name: 'projectName',
            display: '项目名称',
            sortable: true,
            width: 120
        }, {
            name: 'managerName',
            display: '项目经理',
            sortable: true,
            width: 80,
            hide: true
        }, {
            name: 'managerId',
            display: '项目经理id',
            sortable: true,
            hide: true
        }, {
            name: 'remark',
            display: '备注信息',
            sortable: true,
            width: 130,
            hide: true
        }, {
            name: 'ExaStatus',
            display: '审批状态',
            sortable: true,
            width: 70
        }, {
            name: 'ExaDT',
            display: '审批日期',
            sortable: true,
            width: 75
        }, {
            name: 'createName',
            display: '创建人',
            sortable: true,
            hide: true
        }, {
            name: 'createTime',
            display: '创建时间',
            sortable: true,
            hide: true
        }, {
            name: 'updateName',
            display: '修改人',
            sortable: true,
            hide: true
        }, {
            name: 'updateTime',
            display: '修改时间',
            sortable: true,
            hide: true
        }],
        toAddConfig: {
            toAddFn: function () {
                showOpenWin("?model=engineering_resources_resourceapply&action=toAdd", 1, 700, 1100, 'toRAdd');
            }
        },
        toEditConfig: {
            showMenuFn: function (row) {
                return row.confirmStatus == "0" && (row.ExaStatus == '待提交' || row.ExaStatus == '打回');
            },
            toEditFn: function (p, g) {
                var rowObj = g.getSelectedRow();
                var row = rowObj.data('data');
                showOpenWin("?model=engineering_resources_resourceapply&action=toEdit&id="
                + row.id + "&skey=" + row['skey_'], 1, 700, 1100, row.id);
            }
        },
        toViewConfig: {
            toViewFn: function (p, g) {
                var rowObj = g.getSelectedRow();
                var row = rowObj.data('data');
                showOpenWin("?model=engineering_resources_resourceapply&action=toView&id="
                + row[p.keyField], 1, 700, 1100, row.id);
            }
        },
        //过滤数据
        comboEx: [{
            text: '单据状态',
            key: 'status',
            data: [{
                text: '未处理',
                value: '0'
            }, {
                text: '处理中',
                value: '1'
            }, {
                text: '已处理',
                value: '2'
            }]
        }, {
            text: '审核状态',
            key: 'ExaStatus',
            type: 'workFlow'
        }],
        searchitems: [{
            display: "申请单号",
            name: 'formNoSch'
        }, {
            display: "项目编号",
            name: 'projectCodeSch'
        }, {
            display: "项目名称",
            name: 'projectNameSch'
        }]
    });
});