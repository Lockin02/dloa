var show_page = function (page) {
    $("#contractGrid").yxgrid("reload");
};
$(function () {
    $("#contractGrid").yxgrid({
        model: 'contract_contract_contract',
        action: 'pageJsonBasic',
        title: '合同基础数据列表',
        isViewAction: false,
        isEditAction: false,
        isDelAction: false,
        isAddAction: false,
        // 列信息
        colModel: [
            {
                display: 'id',
                name: 'id',
                sortable: true,
                hide: true
            },
            {
                name: 'ExaDTOne',
                display: "合同建立时间",
                sortable: true,
                width: 80
            },
            {
                name: 'contractCode',
                display: '合同编号',
                sortable: true,
                width: 120,
                process: function (v, row) {
                    return '<a href="javascript:void(0)" onclick="javascript:showModalWin(\'?model=contract_contract_contract&action=toViewTab&id='
                        + row.id
                        + '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800\')">'
                        + "<font color = '#4169E1'>"
                        + v
                        + "</font>"
                        + '</a>';
                }
            },
            {
                name: 'contractName',
                display: '合同名称',
                sortable: true,
                width: 180
            },
            {
                name: 'contractMoney',
                display: '合同金额',
                sortable: true,
                width: 80
            },
            {
                name: 'projectCode',
                display: '项目编号',
                sortable: true,
                width: 120,
                process: function (v) {
                    return "<span class='newline'>" + v + "</span>"
                }
            },
            {
                name: 'projectName',
                display: '项目名称',
                sortable: true,
                width: 150,
                process: function (v) {
                    return "<span class='newline'>" + v + "</span>"
                }
            },
            {
                name: 'proMoney',
                display: '项目金额',
                sortable: true,
                width: 80,
                process: function (v) {
                    return "<span class='newline'>" + v + "</span>"
                }
            },
            {
                name: 'projectType',
                display: '项目类型',
                sortable: true,
                width: 60,
                process: function (v) {
                    return "<span class='newline'>" + v + "</span>"
                }
            },
            {
                name: 'createTime',
                display: '合同提交日期',
                sortable: true,
                width: 80
            },
            {
                name: 'costAppDate',
                display: '成本审核日期',
                sortable: true,
                width: 80
            },
            {
                name: 'shipTimes',
                display: '单个合同发货次数',
                sortable: true,
                width: 100
            },
            {
                name: 'standardDate',
                display: '标准交期',
                sortable: true,
                width: 80
            },
            {
                name: 'shipPlanDate',
                display: '预计发货日期',
                sortable: true,
                width: 80
            },
            {
                name: 'shipDate',
                display: '实际发货日期',
                sortable: true,
                width: 80
            },
            {
                name: 'estimates',
                display: '项目概算',
                sortable: true,
                width: 80,
                process: function (v) {
                    return "<span class='newline'>" + v + "</span>"
                }
            },
            {
                name: 'saleCost',
                display: '实际发货成本',
                sortable: true,
                width: 80
            },
            {
                name: 'cost',
                display: '项目决算',
                sortable: true,
                width: 80,
                process: function (v) {
                    return "<span class='newline'>" + v + "</span>"
                }
            },
            {
                name: 'earnings',
                display: '项目收入',
                sortable: true,
                width: 80,
                process: function (v) {
                    return "<span class='newline'>" + v + "</span>"
                }
            },
            {
                name: 'exgross',
                display: '预计毛利率',
                sortable: true,
                width: 80
            },
            {
                name: 'rateOfGross',
                display: '毛利率',
                sortable: true,
                width: 80
            },
            {
                name: 'schedule',
                display: '项目进度',
                sortable: true,
                width: 80,
                process: function (v) {
                    return "<span class='newline'>" + v + "</span>"
                }
            },
            {
                name: 'isAcquiringDate',
                display: '合同收单日期',
                sortable: true,
                width: 80
            },
            {
                name: 'signinDate',
                display: '合同签收日期',
                sortable: true,
                width: 80
            }
        ],
        buttonsEx : {
			name : 'export',
			text : "导出",
			icon : 'excel',
			action : function(row) {
				window.open("?model=contract_contract_contract&action=basicExportExcel");
			}
		}
    });
});
