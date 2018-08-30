var show_page = function() {
	$("#myinvotherGrid").yxgrid("reload");
};

$(function() {
	//	发票录入权限
	var invoiceLimit = false;
	var objType = $("#objType").val();
	var modelName = objType == "YFQTYD01" ? 'contract_outsourcing_outsourcing' : 'contract_other_other';

	$.ajax({
		type: 'POST',
		url: '?model=' + modelName + '&action=getLimits',
		data: {
			limitName: '发票权限'
		},
		async: false,
		success: function(data) {
			if (data == 1) {
				invoiceLimit = true;
			}
		}
	});

    // 列
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
        display: '提交',
        align: 'center',
        process: function(v) {
            switch (v) {
                case '1' :
                    return '<img src="images/icon/ok3.png" title="已提交" style="width:15px;height:15px;">';
                case '2' :
                    return '<img src="images/icon/delete.gif" title="已打回" style="width:15px;height:15px;">';
                default :
                    return "";
            }
        },
        width: 25
    }, {
        name: 'ExaStatus',
        display: '审核',
        width: 25,
        align: 'center',
        process: function(v, row) {
            if (v == "1") {
                return '<img title="审核人[' + row.exaMan + ']\n审核日期[' + row.ExaDT
                    + ']" src="images/icon/ok3.png" style="width:15px;height:15px;">';
            } else {
                return "";
            }
        }
    }, {
        name: 'invoiceCode',
        display: '发票编号',
        sortable: true,
        width: 130,
        process: function (v, row) {
            if (row.isRed == "1") {
                return "<span class='red' title='红字发票'>" + v + "</span>";
            } else {
                return v;
            }
        }
    }, {
        name: 'invoiceNo',
        display: '发票号码',
        sortable: true,
        process: function (v, row) {
            if (row.isRed == "1") {
                return "<span class='red' title='红字发票'>" + v + "</span>";
            } else {
                return v;
            }
        }
    }, {
        name: 'supplierName',
        display: '供应商名称',
        sortable: true,
        width: 120,
        hide: true
    }, {
        name: 'formDate',
        display: '单据日期',
        sortable: true,
        width: 80
    }];

    if (objType == 'YFQTYD03') {
        colModel.push({
            name: 'period',
            display: '归属月份',
            sortable: true,
            width: 60
        });
    }

    // 继续填充列头
    colModel.push({
        name: 'isRed',
        display: '是否红字',
        sortable: true,
        hide: true
    }, {
        name: 'taxRate',
        display: '税率(%)',
        sortable: true,
        width: 50
    }, {
        name: 'invType',
        display: '发票类型',
        sortable: true,
        datacode: 'FPLX',
        width: 80
    }, {
        name: 'formNumber',
        display: '总数量',
        sortable: true,
        width: 60
    }, {
        name: 'amount',
        display: '总金额',
        sortable: true,
        process: function(v) {
            return moneyFormat2(v);
        },
        width: 80
    }, {
        name: 'formAssessment',
        display: '单据税额',
        sortable: true,
        process: function(v) {
            return moneyFormat2(v);
        },
        width: 80
    }, {
        name: 'formCount',
        display: '价税合计',
        sortable: true,
        process: function(v) {
            return moneyFormat2(v);
        },
        width: 80
    }, {
        name: 'departments',
        display: '部门',
        sortable: true,
        width: 80
    }, {
        name: 'salesman',
        display: '业务员',
        sortable: true,
        width: 80
    }, {
        name: 'ExaDT',
        display: '审核日期',
        sortable: true,
        hide: true
    }, {
        name: 'exaMan',
        display: '审核人',
        sortable: true,
        width: 80,
        hide: true
    }, {
        name: 'belongId',
        display: '所属发票id',
        sortable: true,
        hide: true
    }, {
        name: 'updateTime',
        display: '最近更新',
        sortable: true,
        width: 130,
        hide: true
    });

	$("#myinvotherGrid").yxgrid({
		model: 'finance_invother_invother',
		action: 'historyJson',
		param: {"dObjId": $("#objId").val(), "dObjType": objType},
		title: '应付其他发票',
		isAddAction: false,
		isDelAction: false,
		showcheckbox: true,
		isOpButton: false,
		//列信息
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
		// 扩展右键菜单
		menusEx: [{
			text: '审核',
			icon: 'edit',
			showMenuFn: function(row) {
				return row.ExaStatus == "0" && row.status == "1" && invoiceLimit == true;
			},
			action: function(row) {
				showModalWin('?model=finance_invother_invother&action=toVerify&id=' + row.id + "&skey=" + row.skey_, 1,
					row.id);
			}
		}, {
			text: "删除",
			icon: 'delete',
			showMenuFn: function(row) {
				return $.inArray(parseInt(row.status), [0,2]) > -1 && $("#userId").val() == row.createId;
			},
			action: function(row) {
				if (window.confirm(("确定要删除?"))) {
					$.ajax({
						type: "POST",
						url: "?model=finance_invother_invother&action=ajaxdeletes",
						data: {
							id: row.id
						},
						success: function(msg) {
							if (msg == 1) {
								alert('删除成功!');
								show_page();
							} else {
								alert("删除失败!");
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
			display: "发票号码",
			name: 'invoiceNoSearch'

		}, {
			display: "发票编号",
			name: 'invoiceCodeSearch'

		}, {
			display: "供应商",
			name: 'supplierName'

		}, {
			display: "部门名称",
			name: 'departments'

		}, {
			display: "业务人员",
			name: 'salesman'

		}, {
			display: "审核人",
			name: 'exaMan'
		}]
	});
});