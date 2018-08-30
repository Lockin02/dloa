var show_page = function (page) {
    $("#serviceProductGrid").yxgrid("reload");
};
$(function () {
    buttonsArr = [];

    $("#serviceProductGrid").yxgrid({
        model: 'projectmanagent_chance_chance',
        action: 'serviceProductJson',
        title: '销售商机',
        leftLayout: false,
        customCode: 'serviceProductGrid',
        event: {
            'row_dblclick': function (e, row, data) {
                showModalWin("?model=projectmanagent_chance_chance&action=toViewTab&id="
                    + data.id
                    + "&skey="
                    + row['skey_']
                    + "&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=900");
            }
        },
        // 列信息
        colModel: [{
            display: 'id',
            name: 'id',
            sortable: true,
            hide: true
        }, {
            name: 'chanceType',
            display: '商机类型',
            datacode: 'SJLX',
            sortable: true
        }, {
            name: 'chanceName',
            display: '商机名称',
            sortable: true,
            width: 150,
            process: function (v, row) {
                return '<a href="javascript:void(0)" onclick="javascript:showModalWin(\'?model=projectmanagent_chance_chance&action=toViewTab&id='
                    + row.id
                    + '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800\')">'
                    + "<font color = '#4169E1'>" + v + "</font>" + '</a>';
            }
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
            hide: true,
            sortable: true
        }, {
            name: 'createTime',
            display: '建立时间',
            sortable: true,
            hide: true
        }, {
            name: 'newUpdateDate',
            display: '最近更新时间',
            sortable: true,
            hide: true
        }, {
            name: 'chanceCode',
            display: '商机编号',
            sortable: true,
            process: function (v, row) {
                return '<a href="javascript:void(0)" onclick="javascript:showModalWin(\'?model=projectmanagent_chance_chance&action=toViewTab&id='
                    + row.oldId
                    + '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800\')">'
                    + "<font color = '#4169E1'>" + v + "</font>" + '</a>';
            },
            hide: true
        }, {
            name: 'conProductName',
            display: '服务产品名称',
            sortable: true,
            width: 200
        }, {
            name: 'number',
            display: '数量(人)',
            sortable: true
        }, {
            name: 'winRate',
            display: '赢率',
            datacode: 'SJYL',
            sortable: true,
            width: 80
        }, {
        //    name: 'chanceStage',
        //    display: '商机阶段',
        //    datacode: 'SJJD',
        //    sortable: true,
        //    width: 80
        //}, {
            name: 'predictExeDate',
            display: '预计合同执行日期',
            sortable: true
        }, {
            name: 'contractPeriod',
            display: '合同执行周期（月）',
            sortable: true
        }, {
            name: 'Province',
            display: '省份',
            sortable: true,
            width: 70
        }, {
            name: 'City',
            display: '城市',
            sortable: true,
            width: 70
        }],
        comboEx: [{
            text: '商机状态',
            key: 'status',
            value: '5',
            data: [{
                text: '跟踪中',
                value: '5'
            }, {
                text: '暂停',
                value: '6'
            }, {
                text: '关闭',
                value: '3'
            }, {
                text: '已生成合同',
                value: '4'
            }]
        }],
        buttonsEx: buttonsArr,
        // 扩展右键菜单
        menusEx: [{
            text: '查看',
            icon: 'view',
            action: function (row) {
                if (row) {
                    showModalWin("?model=projectmanagent_chance_chance&action=toViewTab&id="
                        + row.id
                        + "&skey="
                        + row['skey_']
                        + "&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=900");
                }
            }

        }],
        // 快速搜索
        searchitems: [{
            display: '商机编号',
            name: 'chanceCode'
        }, {
            display: '商机名称',
            name: 'chanceName'
        }, {
            display: '产品名称',
            name: 'goodsNameSer'
        }],
        // 默认搜索顺序
        sortorder: "DSC",
        // 显示查看按钮
        isViewAction: false,
        isAddAction: false,
        isEditAction: false,
        isDelAction: false,
        showcheckbox: false
    });
});
