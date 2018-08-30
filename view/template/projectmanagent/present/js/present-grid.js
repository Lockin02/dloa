var show_page = function (page) {
    $("#presentGrid").yxgrid("reload");
};
$(function () {
    $("#presentGrid").yxgrid({
        model: 'projectmanagent_present_present',
        title: '赠送申请',
        // 按钮
        isViewAction: false,
        isAddAction: false,
        isEditAction: false,
        isDelAction: false,
        customCode: 'presentGrid',
        comboEx: [
            {
                text: '审批状态',
                key: 'ExaStatus',
                data: [
                    {
                        text: '未审批',
                        value: '未审批'
                    },
                    {
                        text: '部门审批',
                        value: '部门审批'
                    },
                    {
                        text: '完成',
                        value: '完成'
                    },
                    {
                        text: '物料确认',
                        value: '物料确认'
                    },
                    {
                        text: '变更审批中',
                        value: '变更审批中'
                    }
                ]
            }
        ],
        // 列信息
        colModel: [
            {
                display: 'id',
                name: 'id',
                sortable: true,
                hide: true
            },
            {
                name: 'Code',
                display: '编号',
                sortable: true
            },
            {
                name: 'customerName',
                display: '客户名称',
                sortable: true
            },
            {
                name: 'salesName',
                display: '申请人',
                sortable: true
            },
            {
                name: 'reason',
                display: '申请理由',
                sortable: true
            },
            {
                name: 'remark',
                display: '备注',
                sortable: true
            },
            {
                name: 'ExaStatus',
                display: '审批状态',
                sortable: true
            },
            {
                name: 'objCode',
                display: '业务编号',
                width: 120
            },
            {
                name: 'SingleType',
                display: '源单类型',
                width: 120,
                process: function (v) {
                    if (v == "chance") {
                        return "商机";
                    }
                }
            },
            {
                name: 'orderCode',
                display: '源单编号',
                width: 120
            },
            {
                name: 'orderName',
                display: '源单名称',
                width: 120
            }
        ],
        // 扩展右键菜单

        menusEx: [
            {
                text: '查看',
                icon: 'view',
                action: function (row) {
                    if (row) {
                        showModalWin("?model=projectmanagent_present_present&action=viewTab&id="
                            + row.id + "&skey=" + row['skey_']);
                    }
                }
            }
        ],
        /**
         * 快速搜索
         */
        searchitems: [
            {
                display: '源单编号',
                name: 'orderCode'
            },
            {
                display: '编号',
                name: 'Code'
            },
            {
                display: '客户名称',
                name: 'customerName'
            },
            {
                display: '业务编号',
                name: 'objCode'
            }
        ]
    });
});