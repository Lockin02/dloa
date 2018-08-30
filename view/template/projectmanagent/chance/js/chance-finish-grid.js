var show_page = function (page) {
    $("#finishChanceGrid").yxgrid("reload");
};
$(function () {
    var buttonsEx = [{
        name: 'excelOut',
        text: "导出",
        icon: 'excel',
        action: function(row) {
            var i = 1;
            var colId = "";
            var colName = "";
            $("#finishChanceGrid_hTable").children("thead").children("tr")
                .children("th").each(function () {
                if ($(this).css("display") != "none"
                    && $(this).attr("colId") != undefined) {
                    colName += $(this).children("div").html() + ",";
                    colId += $(this).attr("colId") + ",";
                    i++;
                }
            });

            window.open("?model=projectmanagent_chance_chance&action=exportExcelData&placeValuesBefore"
                +"&ColId=" + colId + "&ColName=" + colName);
        }
    }];

    $("#finishChanceGrid").yxgrid({
        model: 'projectmanagent_chance_chance',
        title: '销售商机',
        param: {'statusArr': '3,4'},
        customCode: 'finishChanceGrid',
        event: {
            'row_dblclick': function (e, row, data) {
                showModalWin("?model=projectmanagent_chance_chance&action=toViewTab&id=" + data.id + "&skey=" + row['skey_']
                    + "&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=900"
                );
            }
        },
        //列信息
        colModel: [{
            display: 'id',
            name: 'id',
            sortable: true,
            hide: true
        }, {
            name: 'chanceCode',
            display: '商机编号',
            sortable: true,
            process: function (v, row) {
                return '<a href="javascript:void(0)" onclick="javascript:showModalWin(\'?model=projectmanagent_chance_chance&action=toViewTab&id='
                    + row.id
                    + '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800\')">'
                    + "<font color = '#4169E1'>"
                    + v
                    + "</font>"
                    + '</a>';
            }
        }, {
            name: 'chanceName',
            display: '商机名称',
            sortable: true
        }, {
            name: 'chanceMoney',
            display: '商机金额',
            sortable: true
        }, {
            name: 'customerName',
            display: '客户名称',
            sortable: true
        }, {
            name: 'customerType',
            display: '客户类型',
            datacode: 'KHLX',
            sortable: true
        }, {
            name: 'status',
            display: '商机状态',
            process: function (v) {
                if (v == 0) {
                    return "跟踪中";
                } else if (v == 3) {
                    return "关闭";
                } else if (v == 4) {
                    return "已生成合同";
                } else if (v == 5) {
                    return "跟踪中"
                } else if (v == 6) {
                    return "暂停"
                }
            },
            sortable: true
        }, {
            name: 'chanceType',
            display: '商机类型',
            datacode: 'HTLX',
            sortable: true
        }, {
            //   name : 'chanceStage',
            //   display : '商机阶段',
            //   datacode : 'SJJD',
            //   sortable : true,
            //	process : function(v, row) {
            //		return '<a href="javascript:void(0)" onclick="javascript:showThickboxWin(\'?model=projectmanagent_chance_chance&action=boostChanceStageInfo&id='
            //				+ row.id
            //				+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=600\')">'
            //				+ "<font color = '#4169E1'>"
            //				+ v
            //				+ "</font>"
            //				+ '</a>';
            //	}
            //},{
            name: 'winRate',
            display: '商机赢率',
            datacode: 'SJYL',
            sortable: true,
            process: function (v, row) {
                return '<a href="javascript:void(0)" onclick="javascript:showThickboxWin(\'?model=projectmanagent_chance_chance&action=winRateInfo&id='
                    + row.id
                    + '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=600\')">'
                    + "<font color = '#4169E1'>"
                    + v
                    + "</font>"
                    + '</a>';
            }
        }, {
            name: 'predictContractDate',
            display: '预计合同签署日期',
            sortable: true
        }, {
            name: 'predictExeDate',
            display: '预计合同执行日期',
            sortable: true
        }, {
            name: 'contractPeriod',
            display: '合同执行周期（月）',
            sortable: true
        }],

        comboEx: [{
            text: '商机类型',
            key: 'chanceType',
            datacode: 'HTLX'
        },
            {
                text: '商机状态',
                key: 'status',
                data: [{
                    text: '跟踪中',
                    value: '0'
                },{
                    text: '关闭',
                    value: '3'
                },{
                    text: '已生成合同',
                    value: '4'
                },{
                    text: '跟踪中',
                    value: '5'
                },{
                    text: '暂停',
                    value: '6'
                }]
            }
        ],
        //扩展右键菜单
        menusEx: [{
            text: '查看',
            icon: 'view',
            action: function (row) {
                if (row) {
                    showModalWin("?model=projectmanagent_chance_chance&action=toViewTab&id=" + row.id + "&skey=" + row['skey_']
                        + "&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=900"
                    );
                }
            }

        }],

        buttonsEx : buttonsEx,

        //快速搜索
        searchitems: [{
            display: '商机名称',
            name: 'chanceName'
        }, {
            display: '客户名称',
            name: 'customerName'
        }, {
            display: '商机编号',
            name: 'chanceCode'
        }],
        //默认搜索顺序
        sortorder: "DSC",
        //显示查看按钮
        isViewAction: false,
        isAddAction: false,
        isEditAction: false,
        isDelAction: false,
        showcheckbox: false
    });
});