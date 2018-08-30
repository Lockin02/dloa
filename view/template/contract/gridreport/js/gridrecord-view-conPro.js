var show_page = function (page) {
    $("#conProListGrid").yxgrid("reload");
};
$(function () {
    $("#conProListGrid").yxgrid({
        model: 'contract_conproject_conproject',
        action: 'contractProReportJson',
//        customCode: 'conprojectStoreListNewList',
        title: '产品营收',
        isOpButton: false,
        isViewAction: false,
        isEditAction: false,
        isDelAction: false,
        isAddAction: false,
        showcheckbox: false,
        leftLayout: false,
//		lockCol : ['conflag','checkTip'],// 锁定的列名
        //列信息
        colModel: [
            {
                display: 'id',
                name: 'id',
                sortable: true,
                hide: true
            },
            {
                name: 'exeDeptName',
                display: '执行区域',
                sortable: true
            },
            {
                name: 'conProductName',
                display: '产品名称',
                sortable: true,
                width: 220,
                align: 'left'
            },
            {
                name: 'earnings',
                display: '收入',
                sortable: true,
                align: 'right',
                width: 100,
                process: function (v) {
                    if (v == '0.00') {
                        return "-";
                    }
                    return moneyFormat2(v);
                },
                align: 'right'
            },
            {
                name: 'gross',
                display: '成本',
                sortable: true,
                align: 'right',
                width: 100,
                process: function (v) {
                    if (v == '0.00') {
                        return "-";
                    }
                    return moneyFormat2(v);
                },
                align: 'right'
            },
            {
                name: 'exgross',
                display: '毛利率',
                sortable: true,
                align: 'right',
                width: 70,
                process: function (v) {
                    if (v)
                        if (v < 0)
                            return "<span class='red'>" + v + "%</span>";
                        else if (v == 0)
                            return "-";
                        else
                            return v + "%";
                    else
                        return "-";
                }
            }
        ],
        comboEx: [
        {
            text: '执行区域',
            key: 'exeDeptId',
            datacode: 'GCSCX'
        },
            {
                text: '数据过滤',
                key: 'warningStr',
                value : '1',
                data: [
                    {
                        text: '有效数据',
                        value: '1'
                    }
                ]
            }
    ],
        // 默认搜索顺序
        sortorder: "desc",
        searchitems: [
            {
                display: "合同编号",
                name: 'contractCode'
            },
            {
                display: "项目编号",
                name: 'projectCode'
            },
            {
                display: "执行区域",
                name: 'proLineName'
            }
        ]



    });


});





