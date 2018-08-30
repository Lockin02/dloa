var show_page = function (page) {
    $("#finishChanceGrid").yxgrid("reload");
};
$(function () {
    var buttonsEx = [{
        name: 'excelOut',
        text: "����",
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
        title: '�����̻�',
        param: {'statusArr': '3,4'},
        customCode: 'finishChanceGrid',
        event: {
            'row_dblclick': function (e, row, data) {
                showModalWin("?model=projectmanagent_chance_chance&action=toViewTab&id=" + data.id + "&skey=" + row['skey_']
                    + "&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=900"
                );
            }
        },
        //����Ϣ
        colModel: [{
            display: 'id',
            name: 'id',
            sortable: true,
            hide: true
        }, {
            name: 'chanceCode',
            display: '�̻����',
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
            display: '�̻�����',
            sortable: true
        }, {
            name: 'chanceMoney',
            display: '�̻����',
            sortable: true
        }, {
            name: 'customerName',
            display: '�ͻ�����',
            sortable: true
        }, {
            name: 'customerType',
            display: '�ͻ�����',
            datacode: 'KHLX',
            sortable: true
        }, {
            name: 'status',
            display: '�̻�״̬',
            process: function (v) {
                if (v == 0) {
                    return "������";
                } else if (v == 3) {
                    return "�ر�";
                } else if (v == 4) {
                    return "�����ɺ�ͬ";
                } else if (v == 5) {
                    return "������"
                } else if (v == 6) {
                    return "��ͣ"
                }
            },
            sortable: true
        }, {
            name: 'chanceType',
            display: '�̻�����',
            datacode: 'HTLX',
            sortable: true
        }, {
            //   name : 'chanceStage',
            //   display : '�̻��׶�',
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
            display: '�̻�Ӯ��',
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
            display: 'Ԥ�ƺ�ͬǩ������',
            sortable: true
        }, {
            name: 'predictExeDate',
            display: 'Ԥ�ƺ�ִͬ������',
            sortable: true
        }, {
            name: 'contractPeriod',
            display: '��ִͬ�����ڣ��£�',
            sortable: true
        }],

        comboEx: [{
            text: '�̻�����',
            key: 'chanceType',
            datacode: 'HTLX'
        },
            {
                text: '�̻�״̬',
                key: 'status',
                data: [{
                    text: '������',
                    value: '0'
                },{
                    text: '�ر�',
                    value: '3'
                },{
                    text: '�����ɺ�ͬ',
                    value: '4'
                },{
                    text: '������',
                    value: '5'
                },{
                    text: '��ͣ',
                    value: '6'
                }]
            }
        ],
        //��չ�Ҽ��˵�
        menusEx: [{
            text: '�鿴',
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

        //��������
        searchitems: [{
            display: '�̻�����',
            name: 'chanceName'
        }, {
            display: '�ͻ�����',
            name: 'customerName'
        }, {
            display: '�̻����',
            name: 'chanceCode'
        }],
        //Ĭ������˳��
        sortorder: "DSC",
        //��ʾ�鿴��ť
        isViewAction: false,
        isAddAction: false,
        isEditAction: false,
        isDelAction: false,
        showcheckbox: false
    });
});