var show_page = function () {
    $("#invotherGrid").yxsubgrid("reload");
};

$(function () {

	// Դ�����ͻ�ȡ
	var sourceTypeArr = [];
	var o = {
		value: 'none',
		text: '��Դ��'
	};
	sourceTypeArr.push(o);

	var datadictData = getData('YFQTYD');
	if (datadictData) {
		for (var i = 0; i < datadictData.length; i++) {
			var o = {
				value: datadictData[i].dataCode,
				text: datadictData[i].dataName
			};
			sourceTypeArr.push(o);
		}
	}

    $("#invotherGrid").yxsubgrid({
        model: 'finance_invother_invother',
        title: 'Ӧ��������Ʊ',
        isDelAction: false,
        customCode: 'invotherGrid',
        isOpButton: false,
        //����Ϣ
        colModel: [{
            display: 'id',
            name: 'id',
            sortable: true,
            hide: true
        }, {
            name: 'status',
            display: '�ύ',
            align: 'center',
            process: function (v) {
                switch (v) {
                    case '1' :
                        return '<img src="images/icon/ok3.png" title="���ύ" style="width:15px;height:15px;">';
                    case '2' :
                        return '<img src="images/icon/ok1.png" title="�Ѵ��" style="width:15px;height:15px;">';
                    default :
                        return;
                }
            },
            width: 25
        }, {
            name: 'ExaStatus',
            display: '���',
            width: 25,
            align: 'center',
            process: function (v, row) {
                switch (v) {
                    case '1' :
                        return '<img title="�����[' + row.exaMan + ']\n�������[' + row.ExaDT
                        + ']" src="images/icon/ok3.png" style="width:15px;height:15px;">';
                    default :
                }
            }
        }, {
            name: 'invoiceCode',
            display: '��Ʊ���',
            sortable: true,
            width: 140,
            process: function (v, row) {
                if (row.isRed == "1") {
                    return "<a href='#' style='color:red' onclick='showModalWin(\"?model=finance_invother_invother&action=toView&id=" +
                        row.id + '&skey=' + row.skey_ + "\")'>" + v + "</a>";
                } else {
                    return "<a href='#' onclick='showModalWin(\"?model=finance_invother_invother&action=toView&id=" +
                        row.id + '&skey=' + row.skey_ + "\")'>" + v + "</a>";
                }
            },
            hide: true
        }, {
            name: 'invoiceNo',
            display: '��Ʊ����',
            sortable: true,
            process: function (v, row) {
                if (row.isRed == "1") {
                    return "<a href='#' style='color:red' onclick='showModalWin(\"?model=finance_invother_invother&action=toView&id=" +
                        row.id + '&skey=' + row.skey_ + "\")'>" + v + "</a>";
                } else {
                    return "<a href='#' onclick='showModalWin(\"?model=finance_invother_invother&action=toView&id=" +
                        row.id + '&skey=' + row.skey_ + "\")'>" + v + "</a>";
                }
            }
        }, {
            name: 'sourceType',
            display: 'Դ������',
            sortable: true,
            width: 80,
            datacode: 'YFQTYD'
        }, {
            name: 'menuNo',
            display: 'Դ�����',
            sortable: true
        }, {
            name: 'supplierName',
            display: '��Ӧ������',
            sortable: true,
            width: 120
        }, {
            name: 'formDate',
            display: '��������',
            sortable: true,
            width: 80
        }, {
            name: 'payDate',
            display: '��������',
            sortable: true,
            width: 80
        }, {
            name: 'isRed',
            display: '�Ƿ����',
            sortable: true,
            hide: true
        }, {
            name: 'taxRate',
            display: '˰��(%)',
            sortable: true,
            width: 60
        }, {
            name: 'invType',
            display: '��Ʊ����',
            sortable: true,
            datacode: 'FPLX',
            width: 80
        }, {
            name: 'formNumber',
            display: '������',
            sortable: true,
            width: 80
        }, {
            name: 'amount',
            display: '�ܽ��',
            sortable: true,
            width: 80,
            process: function (v) {
                return moneyFormat2(v);
            }
        }, {
            name: 'formAssessment',
            display: '����˰��',
            sortable: true,
            width: 80,
            process: function (v) {
                return moneyFormat2(v);
            }
        }, {
            name: 'formCount',
            display: '��˰�ϼ�',
            sortable: true,
            width: 80,
            process: function (v) {
                return moneyFormat2(v);
            }
        }, {
            name: 'hookMoney',
            display: '�������',
            sortable: true,
            width: 80,
            process: function (v) {
                return moneyFormat2(v);
            }
        }, {
            name: 'departments',
            display: '����',
            sortable: true,
            width: 80
        }, {
            name: 'salesman',
            display: 'ҵ��Ա',
            sortable: true,
            width: 80
        }, {
            name: 'businessBelongName',
            display: '������˾',
            sortable: true,
            width: 80
        }, {
            name: 'ExaDT',
            display: '�������',
            sortable: true,
            hide: true
        }, {
            name: 'exaMan',
            display: '�����',
            sortable: true,
            width: 80,
            hide: true
        }, {
            name: 'belongId',
            display: '������Ʊid',
            sortable: true,
            hide: true
        }, {
            name: 'updateTime',
            display: '�������',
            sortable: true,
            width: 130,
            hide: true
        }],
        // ���ӱ������
        subGridOptions: {
            url: '?model=finance_invother_invotherdetail&action=pageJson',
            param: [
                {
                    paramId: 'mainId',// ���ݸ���̨�Ĳ�������
                    colId: 'id'// ��ȡ���������ݵ�������
                }
            ],
            colModel: [{
                name: 'productName',
                display: '��Ʊ��Ŀ',
                width: 140
            }, {
                display: '����',
                name: 'number'
            }, {
                name: 'price',
                display: '����',
                process: function (v) {
                    return moneyFormat2(v, 6, 6);
                }
            }, {
                name: 'taxPrice',
                display: '��˰����',
                process: function (v) {
                    return moneyFormat2(v, 6, 6);
                }
            }, {
                name: 'assessment',
                display: '˰��',
                process: function (v) {
                    return moneyFormat2(v);
                },
                width: 70
            }, {
                name: 'amount',
                display: '���',
                process: function (v) {
                    return moneyFormat2(v);
                },
                width: 80
            }, {
                name: 'allCount',
                display: '��˰�ϼ�',
                process: function (v) {
                    return moneyFormat2(v);
                },
                width: 80
            }, {
                name: 'objCode',
                display: 'Դ�����',
                width: 120
            }]
        },
        toAddConfig: {
            toAddFn: function () {
                showModalWin("?model=finance_invother_invother&action=toAdd");
            }
        },
        toEditConfig: {
            showMenuFn: function (row) {
                return row.status == 0 || row.status == 2;
            },
            toEditFn: function (p, g) {
                var rowObj = g.getSelectedRow();
                var rowData = rowObj.data('data');
                showModalWin("?model=finance_invother_invother&action=toEdit&id=" + rowData[p.keyField]);
            }
        },
        toViewConfig: {
            toViewFn: function (p, g) {
                var rowObj = g.getSelectedRow();
                var rowData = rowObj.data('data');
                showModalWin("?model=finance_invother_invother&action=toView&id=" + rowData[p.keyField]);
            }
        },
        buttonsEx: [{
            name: 'add',
            text: "����EXCEL",
            icon: 'excel',
            action: function () {
                showThickboxWin('?model=finance_invother_invother&action=toExportExcel'
                + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=300&width=600");
            }
        }, {
            name: 'add',
            text: "���б�",
            icon: 'search',
            action: function () {
                showModalWin('?model=finance_invother_invother&action=listInfo', 1);
            }
        }],
        menusEx: [
            {
                text: '���',
                icon: 'edit',
                showMenuFn: function (row) {
                    return row.ExaStatus == 0 && row.status == 1;
                },
                action: function (row, rows, grid) {
                    showModalWin('?model=finance_invother_invother&action=toVerify&id=' + row.id + "&skey=" + row.skey_);
                }
            },
            {
                text: "ɾ��",
                icon: 'delete',
                showMenuFn: function (row) {
                    return row.status == 0 || row.status == 2;
                },
                action: function (row) {
                    if (window.confirm(("ȷ��Ҫɾ��?"))) {
                        $.ajax({
                            type: "POST",
                            url: "?model=finance_invother_invother&action=ajaxdeletes",
                            data: {
                                id: row.id
                            },
                            success: function (msg) {
                                if (msg == 1) {
                                    alert('ɾ���ɹ���');
                                    show_page();
                                } else {
                                    alert("ɾ��ʧ��! ");
                                }
                            }
                        });
                    }
                }
            },
            {
                text: "�����",
                icon: 'edit',
                showMenuFn: function (row) {
                    return row.ExaStatus == 1;
                },
                action: function (row) {
                    if (window.confirm(("ȷ��Ҫ�����?"))) {
                        $.ajax({
                            type: "POST",
                            url: "?model=finance_invother_invother&action=unaudit",
                            data: {
                                "id": row.id
                            },
                            success: function (msg) {
                                if (msg == 1) {
                                    alert('����˳ɹ���');
                                    show_page();
                                } else {
                                    alert('�����ʧ��!');
                                }
                            }
                        });
                    }
                }
            }
        ],
        searchitems: [{
            display: "��Ʊ����",
            name: 'invoiceNoSearch'

        }, {
            display: "��Ʊ���",
            name: 'invoiceCodeSearch'

        }, {
            display: "��Ӧ��",
            name: 'supplierName'

        }, {
            display: "��������",
            name: 'departments'

        }, {
            display: "ҵ����Ա",
            name: 'salesman'

        }, {
            display: "�����",
            name: 'exaMan'
        }, {
            display: "Դ�����",
            name: 'menuNoSearch'
        }],
        comboEx: [{
			text: "Դ������",
			key: 'sourceType',
			data: sourceTypeArr
		}, {
            text: "����״̬",
            key: 'status',
            value: '1',
            data: [{
                text: '����',
                value: '0'
            }, {
                text: '���ύ',
                value: '1'
            }, {
                text: '�Ѵ��',
                value: '2'
            }]
        }, {
			text: "���״̬",
			key: 'ExaStatus',
			value: '0',
			data: [{
				text: 'δ���',
				value: '0'
			}, {
				text: '�����',
				value: '1'
			}]
		}]
    });
});