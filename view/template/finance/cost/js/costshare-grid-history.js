var show_page = function () {
    $("#costshareGrid").yxgrid("reload");
};

$(function () {
    $("#costshareGrid").yxgrid({
        model: 'finance_cost_costshare',
        action: 'pageJsonHistory',
        param: {objId: $("#objId").val(), objType: $("#objType").val()},
        title: '��̯��ϸ�б�',
        isOpButton: false,
        isAddAction: false,
        isEditAction: false,
        isViewAction: false,
        isDelAction: false,
        showcheckbox: false,
        //����Ϣ
        colModel: [{
            display: 'id',
            name: 'id',
            sortable: true,
            hide: true
        }, {
			name: 'submitStatus',
			display: '�ύ',
			width: 25,
			align: 'center',
			process: function (v, row) {
				if (row.auditStatus && $.inArray(parseInt(row.auditStatus), [1,2]) > -1) {
					return '<img title="���ύ" src="images/icon/ok3.png" style="width:15px;height:15px;">';
				}
			}
		}, {
            name: 'auditStatus',
            display: '���',
            width: 25,
            align: 'center',
            process: function (v, row) {
				if (v == "1") {
					return '<img title="�����[' + row.auditor + ']\n�������[' + row.auditDate
						+ ']" src="images/icon/ok3.png" style="width:15px;height:15px;">';
				}
            }
        }, {
            name: 'hookStatus',
            display: '����',
            align: 'center',
            process: function (v) {
                switch (v) {
                    case '1' :
                        return '<img src="images/icon/ok3.png" title="�ѹ���" style="width:15px;height:15px;">';
                    case '2' :
                        return '<img src="images/icon/ok2.png" title="���ֹ���" style="width:15px;height:15px;">';
                    default :
                        return;
                }
            },
            width: 25
        }, {
            name: 'currency',
            display: '����',
            width: 40
        }, {
            name: 'costMoney',
            display: '��̯���',
            process: function (v) {
				if (v < 0) {
					return '<span class="red">' + moneyFormat2(v) + '</span>';
				} else {
					return moneyFormat2(v);
				}
            },
            width: 80,
            sortable: true
        }, {
			name: 'hookMoney',
			display: '�ѹ������',
			process: function (v) {
				if (v < 0) {
					return '<span class="red">' + moneyFormat2(v) + '</span>';
				} else {
					return moneyFormat2(v);
				}
			},
			width: 80,
			sortable: true
		}, {
			name: 'unHookMoney',
			display: 'δ�������',
			process: function (v) {
				if (v < 0) {
					return '<span class="red">' + moneyFormat2(v) + '</span>';
				} else {
					return moneyFormat2(v);
				}
			},
			width: 80,
			sortable: true,
			hide: true
		}, {
            name: 'hookMoney',
            display: '�ۼƹ������',
            align: 'right',
            process: function (v) {
                if (v < 0) {
                    return '<span class="red">' + moneyFormat2(v) + '</span>';
                } else {
                    return moneyFormat2(v);
                }
            },
            width: 80,
            sortable: true,
            hide: true
        }, {
            name: 'unHookMoney',
            display: 'δ�������',
            align: 'right',
            process: function (v) {
                if (v < 0) {
                    return '<span class="red">' + moneyFormat2(v) + '</span>';
                } else {
                    return moneyFormat2(v);
                }
            },
            width: 80,
            sortable: true,
            hide: true
        }, {
            name: 'companyName',
            display: '��˾����',
            sortable: true,
            width: 60,
            hide: true
        }, {
            name: 'moduleName',
            display: '�������',
            sortable: true,
            width: 60
        }, {
            name: 'belongCompanyName',
            display: '������˾',
            sortable: true,
            width: 60
        }, {
            name: 'objId',
            display: 'Դ��id',
            sortable: true,
            hide: true
        }, {
            name: 'objType',
            display: 'Դ������',
            sortable: true,
            width: 60,
            process: function (v) {
                switch (v) {
                    case '1' :
                        return '�⳥��';
                    case '2' :
                        return '������ͬ';
                    default :
                        return v;
                }
            },
            hide: true
        }, {
            name: 'objCode',
            display: 'Դ�����',
            sortable: true,
            width: 120,
            process: function (v, row) {
                if (v != "") {
                    return "<a href='javascript:void(0)' onclick='viewInfo(\"" + row.objId + "\",\"" + row.objType
                    + "\")'>" + v + "</a>";
                }
            },
            hide: true
        }, {
            name: 'supplierName',
            display: '��Ӧ��',
            sortable: true,
            width: 120,
            hide: true
        }, {
            name: 'inPeriod',
            display: '�����ڼ�',
            sortable: true,
            width: 60
        }, {
            name: 'belongPeriod',
            display: '�����ڼ�',
            sortable: true,
            width: 60
        }, {
            name: 'detailType',
            display: 'ҵ������',
            sortable: true,
            width: 70,
            process: function (v) {
                switch (v) {
                    case '1' :
                        return '���ŷ���';
                    case '2' :
                        return '��ͬ��Ŀ����';
                    case '3' :
                        return '�з���Ŀ����';
                    case '4' :
                        return '��ǰ����';
                    case '5' :
                        return '�ۺ����';
                    default :
                        return v;
                }
            }
        }, {
            name: 'parentTypeId',
            display: 'parentTypeId',
            hide: true
        }, {
            name: 'parentTypeName',
            display: '������ϸ�ϼ�',
            hide: true
        }, {
            name: 'costTypeId',
            display: 'costTypeId',
            hide: true
        }, {
            name: 'costTypeName',
            display: '������ϸ',
            width: 70
        }, {
            name: 'headDeptName',
            display: '��������',
            sortable: true,
            width: 80,
            hide: true
        }, {
            name: 'belongDeptName',
            display: '��������',
            sortable: true,
            width: 80
        }, {
            name: 'chanceCode',
            display: '�̻����',
            sortable: true
        }, {
            name: 'projectCode',
            display: '��Ŀ���',
            sortable: true
        }, {
            name: 'projectName',
            display: '��Ŀ����',
            sortable: true
        }, {
            name: 'contractCode',
            display: '��ͬ���',
            sortable: true
        }, {
            name: 'customerName',
            display: '�ͻ�����',
            sortable: true,
            width: 150
        }, {
            name: 'customerType',
            display: '�ͻ�����',
            sortable: true
        }, {
            name: 'province',
            display: '����ʡ��',
            sortable: true,
            width: 70
        }],
        menusEx: [{
            text: '������¼',
            icon: 'edit',
            showMenuFn: function (row) {
                return row.hookStatus != '0';
            },
            action: function (row) {
                showOpenWin("?model=finance_cost_costHook&hookId="
                + row.id, 1, 700, 1100, row.id);
            }
        }],
        //��������
        comboEx: [{
            text: '���״̬',
            key: 'auditStatus',
            data: [{
                text: 'δ���',
                value: '2'
            }, {
                text: '�����',
                value: '1'
            }]
        }, {
            text: '����״̬',
            key: 'hookStatusArr',
            data: [{
                text: 'δ����',
                value: '0'
            }, {
                text: '�ѹ���',
                value: '1'
            }, {
                text: '���ֹ���',
                value: '2'
            }]
        }],
        searchitems: [{
            display: "�̻����",
            name: 'chanceCodeSearch'
        }, {
            display: "��Ŀ����",
            name: 'projectNameSearch'
        }, {
            display: "��Ŀ���",
            name: 'projectCodeSearch'
        }, {
            display: "��ͬ���",
            name: 'contractCodeSearch'
        }]
    });
});