var show_page = function() {
	$("#myinvotherGrid").yxgrid("reload");
};

$(function() {
	//	��Ʊ¼��Ȩ��
	var invoiceLimit = false;
	var objType = $("#objType").val();
	var modelName = objType == "YFQTYD01" ? 'contract_outsourcing_outsourcing' : 'contract_other_other';

	$.ajax({
		type: 'POST',
		url: '?model=' + modelName + '&action=getLimits',
		data: {
			limitName: '��ƱȨ��'
		},
		async: false,
		success: function(data) {
			if (data == 1) {
				invoiceLimit = true;
			}
		}
	});

    // ��
    var colModel = [{
        display: 'id',
        name: 'id',
        sortable: true,
        hide: true,
        process: function(v) {
            if (v != 'noId' && v != 'noId2') {
                return v;
            } else {
                return "";
            }
        }
    }, {
        name: 'status',
        display: '�ύ',
        align: 'center',
        process: function(v) {
            switch (v) {
                case '1' :
                    return '<img src="images/icon/ok3.png" title="���ύ" style="width:15px;height:15px;">';
                case '2' :
                    return '<img src="images/icon/delete.gif" title="�Ѵ��" style="width:15px;height:15px;">';
                default :
                    return "";
            }
        },
        width: 25
    }, {
        name: 'ExaStatus',
        display: '���',
        width: 25,
        align: 'center',
        process: function(v, row) {
            if (v == "1") {
                return '<img title="�����[' + row.exaMan + ']\n�������[' + row.ExaDT
                    + ']" src="images/icon/ok3.png" style="width:15px;height:15px;">';
            } else {
                return "";
            }
        }
    }, {
        name: 'invoiceCode',
        display: '��Ʊ���',
        sortable: true,
        width: 130,
        process: function (v, row) {
            if (row.isRed == "1") {
                return "<span class='red' title='���ַ�Ʊ'>" + v + "</span>";
            } else {
                return v;
            }
        }
    }, {
        name: 'invoiceNo',
        display: '��Ʊ����',
        sortable: true,
        process: function (v, row) {
            if (row.isRed == "1") {
                return "<span class='red' title='���ַ�Ʊ'>" + v + "</span>";
            } else {
                return v;
            }
        }
    }, {
        name: 'supplierName',
        display: '��Ӧ������',
        sortable: true,
        width: 120,
        hide: true
    }, {
        name: 'formDate',
        display: '��������',
        sortable: true,
        width: 80
    }];

    if (objType == 'YFQTYD03') {
        colModel.push({
            name: 'period',
            display: '�����·�',
            sortable: true,
            width: 60
        });
    }

    // ���������ͷ
    colModel.push({
        name: 'isRed',
        display: '�Ƿ����',
        sortable: true,
        hide: true
    }, {
        name: 'taxRate',
        display: '˰��(%)',
        sortable: true,
        width: 50
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
        width: 60
    }, {
        name: 'amount',
        display: '�ܽ��',
        sortable: true,
        process: function(v) {
            return moneyFormat2(v);
        },
        width: 80
    }, {
        name: 'formAssessment',
        display: '����˰��',
        sortable: true,
        process: function(v) {
            return moneyFormat2(v);
        },
        width: 80
    }, {
        name: 'formCount',
        display: '��˰�ϼ�',
        sortable: true,
        process: function(v) {
            return moneyFormat2(v);
        },
        width: 80
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
    });

	$("#myinvotherGrid").yxgrid({
		model: 'finance_invother_invother',
		action: 'historyJson',
		param: {"dObjId": $("#objId").val(), "dObjType": objType},
		title: 'Ӧ��������Ʊ',
		isAddAction: false,
		isDelAction: false,
		showcheckbox: true,
		isOpButton: false,
		//����Ϣ
		colModel: colModel,
		toEditConfig: {
			showMenuFn: function(row) {
				return $.inArray(parseInt(row.status), [0,2]) > -1 && $("#userId").val() == row.createId;
			},
			toEditFn: function(p, g) {
				var rowObj = g.getSelectedRow();
				var rowData = rowObj.data('data');
				showModalWin("?model=finance_invother_invother&action=toEdit&id=" + rowData[p.keyField]);
			}
		},
		toViewConfig: {
			showMenuFn: function(row) {
				return row.id != 'noId' && row.id != 'noId2';
			},
			toViewFn: function(p, g) {
				var rowObj = g.getSelectedRow();
				var rowData = rowObj.data('data');
				showModalWin("?model=finance_invother_invother&action=toView&id=" + rowData[p.keyField], 1);
			}
		},
		// ��չ�Ҽ��˵�
		menusEx: [{
			text: '���',
			icon: 'edit',
			showMenuFn: function(row) {
				return row.ExaStatus == "0" && row.status == "1" && invoiceLimit == true;
			},
			action: function(row) {
				showModalWin('?model=finance_invother_invother&action=toVerify&id=' + row.id + "&skey=" + row.skey_, 1,
					row.id);
			}
		}, {
			text: "ɾ��",
			icon: 'delete',
			showMenuFn: function(row) {
				return $.inArray(parseInt(row.status), [0,2]) > -1 && $("#userId").val() == row.createId;
			},
			action: function(row) {
				if (window.confirm(("ȷ��Ҫɾ��?"))) {
					$.ajax({
						type: "POST",
						url: "?model=finance_invother_invother&action=ajaxdeletes",
						data: {
							id: row.id
						},
						success: function(msg) {
							if (msg == 1) {
								alert('ɾ���ɹ�!');
								show_page();
							} else {
								alert("ɾ��ʧ��!");
							}
						}
					});
				}
			}
		}],
		event: {
			row_check: function(p1, p2, p3, row) {
				if (row.id != 'noId' && row.id != 'noId2') {
					var allData = $("#myinvotherGrid").yxgrid('getCheckedRows');
					var amountObj = $("#rownoId2 td[namex='amount'] div");
					var formCountObj = $("#rownoId2 td[namex='formCount'] div");
					var formAssessmentObj = $("#rownoId2 td[namex='formAssessment'] div");
					var amount = 0;
					var formCount = 0;
					var formAssessment = 0;
					if (allData.length > 0) {
						for (var i = 0; i < allData.length; i++) {
							amount = accAdd(amount, allData[i].amount, 2);
							formCount = accAdd(formCount, allData[i].formCount, 2);
							formAssessment = accAdd(formAssessment, allData[i].formAssessment, 2);
						}
					}
					amountObj.text(moneyFormat2(amount));
					formCountObj.text(moneyFormat2(formCount));
					formAssessmentObj.text(moneyFormat2(formAssessment));
				}
			}
		},
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
		}]
	});
});