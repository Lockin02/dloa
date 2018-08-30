var show_page = function (page) {
    $("#serviceProductGrid").yxgrid("reload");
};
$(function () {
    buttonsArr = [];

    $("#serviceProductGrid").yxgrid({
        model: 'projectmanagent_chance_chance',
        action: 'serviceProductJson',
        title: '�����̻�',
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
        // ����Ϣ
        colModel: [{
            display: 'id',
            name: 'id',
            sortable: true,
            hide: true
        }, {
            name: 'chanceType',
            display: '�̻�����',
            datacode: 'SJLX',
            sortable: true
        }, {
            name: 'chanceName',
            display: '�̻�����',
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
            hide: true,
            sortable: true
        }, {
            name: 'createTime',
            display: '����ʱ��',
            sortable: true,
            hide: true
        }, {
            name: 'newUpdateDate',
            display: '�������ʱ��',
            sortable: true,
            hide: true
        }, {
            name: 'chanceCode',
            display: '�̻����',
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
            display: '�����Ʒ����',
            sortable: true,
            width: 200
        }, {
            name: 'number',
            display: '����(��)',
            sortable: true
        }, {
            name: 'winRate',
            display: 'Ӯ��',
            datacode: 'SJYL',
            sortable: true,
            width: 80
        }, {
        //    name: 'chanceStage',
        //    display: '�̻��׶�',
        //    datacode: 'SJJD',
        //    sortable: true,
        //    width: 80
        //}, {
            name: 'predictExeDate',
            display: 'Ԥ�ƺ�ִͬ������',
            sortable: true
        }, {
            name: 'contractPeriod',
            display: '��ִͬ�����ڣ��£�',
            sortable: true
        }, {
            name: 'Province',
            display: 'ʡ��',
            sortable: true,
            width: 70
        }, {
            name: 'City',
            display: '����',
            sortable: true,
            width: 70
        }],
        comboEx: [{
            text: '�̻�״̬',
            key: 'status',
            value: '5',
            data: [{
                text: '������',
                value: '5'
            }, {
                text: '��ͣ',
                value: '6'
            }, {
                text: '�ر�',
                value: '3'
            }, {
                text: '�����ɺ�ͬ',
                value: '4'
            }]
        }],
        buttonsEx: buttonsArr,
        // ��չ�Ҽ��˵�
        menusEx: [{
            text: '�鿴',
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
        // ��������
        searchitems: [{
            display: '�̻����',
            name: 'chanceCode'
        }, {
            display: '�̻�����',
            name: 'chanceName'
        }, {
            display: '��Ʒ����',
            name: 'goodsNameSer'
        }],
        // Ĭ������˳��
        sortorder: "DSC",
        // ��ʾ�鿴��ť
        isViewAction: false,
        isAddAction: false,
        isEditAction: false,
        isDelAction: false,
        showcheckbox: false
    });
});
