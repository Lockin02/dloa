var show_page = function () {
    $("#officeinfoGrid").yxsubgrid("reload");
};

$(function () {
    $("#officeinfoGrid").yxsubgrid({
        model: 'engineering_officeinfo_officeinfo',
        showcheckbox: false,
        title: '区域信息',
        colModel: [{
            display: 'id',
            name: 'id',
            sortable: true,
            hide: true
        }, {
            display: '所属板块',
            name: 'module',
            width: 70,
            sortable: true,
            datacode: 'HTBK'
        }, {
            display: '执行区域',
            name: 'productLine',
            width: 100,
            sortable: true,
            datacode: 'GCSCX'
        }, {
            display: '执行区域负责人',
            name: 'head',
            sortable: true
        }, {
            display: '区域名称',
            name: 'officeName',
            width: 80,
            sortable: true
        }, {
            display: '归属部门',
            name: 'feeDeptName',
            sortable: true
        }, {
            display: '费用归属公司',
            name: 'businessBelongName',
            width: 75,
            sortable: true
        }, {
            display: '服务总监',
            name: 'mainManager',
            width: 120,
            sortable: true
        }, {
            display: '服务经理',
            name: 'managerName',
            sortable: true,
            width: 200
        }, {
            display: '责任范围',
            name: 'rangeName',
            width: 200,
            sortable: true
        }, {
            display: '后台人员',
            name: 'assistant',
            sortable: true
        }, {
            display: '状态',
            name: 'state',
            sortable: true,
            width: 75,
            process: function(v) {
                return v == "0" ? "开启" : "关闭";
            }
        }, {
            display: '备注',
            name: 'remark',
            sortable: true,
            width: 200
        }],
        // 主从表格设置
        subGridOptions: {
            url: '?model=engineering_officeinfo_range&action=pageJson',// 获取从表数据url
            // 传递到后台的参数设置数组
            param: [
                {
                    paramId: 'officeId',// 传递给后台的参数名称
                    colId: 'id'// 获取主表行数据的列名称
                }
            ],
            // 显示的列
            colModel: [{
                name: 'proName',
                display: '省份',
                width: 120
            }, {
                name: 'managerName',
                display: '服务经理',
                width: 140
            }
            ]
        },
        menusEx: [
            {
                text: "删除",
                icon: 'delete',
                action: function (row) {
                    if (window.confirm(("确定要删除?"))) {

                        var isProjected = false;
                        $.ajax({
                            type: "POST",
                            url: "?model=engineering_officeinfo_officeinfo&action=isProjected",
                            data: {id: row.id},
                            async: false,
                            success: function (data) {
                                if (data == 1) {
                                    isProjected = true;
                                }
                            }
                        });

                        if (isProjected == false) {
                            $.ajax({
                                type: "POST",
                                url: "?model=engineering_officeinfo_officeinfo&action=ajaxdeletes",
                                data: {
                                    id: row.id
                                },
                                success: function (msg) {
                                    if (msg == 1) {
                                        alert('删除成功！');
                                        show_page();
                                    } else {
                                        alert("删除失败! ");
                                    }
                                }
                            });
                        } else {
                            alert('区域已经被项目关联，不能删除');
                        }
                    }
                }
            }
        ],
        isDelAction: false,
        /**快速搜索**/
        searchitems: [{
            display: '区域名称',
            name: 'officeName'
        }, {
            display: '区域经理',
            name: 'managerName'
        }, {
            display: '责任范围',
            name: 'rangeName'
        }, {
            display: "执行区域",
            name: 'productLineNameSch'
        }],
        //执行区域过滤
        comboEx: [{
            text: "执行区域",
            key: 'productLine',
            datacode: 'GCSCX'
        }, {
            text: '状态',
            key: 'state',
            value: '0',
            data: [
                {
                    text: '开启',
                    value: '0'
                }, {
                    text: '关闭',
                    value: '1'
                }
            ]
        }],
        sortorder: "ASC"
    });
});