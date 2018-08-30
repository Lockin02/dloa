var show_page = function() {
	$("#payablesapplyGrid").yxsubgrid("reload");
};

$(function() {
	$("#payablesapplyGrid").yxsubgrid({
		model: 'finance_payablesapply_payablesapply',
		action: "myApplyJson",
		title: '我的付款申请',
		isAddAction: false,
		isEditAction: false,
		isDelAction: false,
		customCode: 'myPayablesapplyGrid',
		sortname: 'c.status,c.auditDate',
		sortorder: 'ASC',
		//列信息
		colModel: [{
			display: '打印',
			name: 'printId',
			width: 30,
			align: 'center',
			sortable: false,
			process: function(v, row) {
				if (row.id == 'noId') return '';
				if (row.printCount > 0) {
					return '<img src="images/icon/print.gif" title="已打印，打印次数为:' + row.printCount + '"/>';
				} else {
					return '<img src="images/icon/print1.gif" title="未打印过的单据"/>';
				}
			}
		}, {
			display: '付款单号',
			name: 'id',
			width: 60,
			sortable: true,
			process: function(v, row) {
				if (row.id == 'noId') {
					return v;
				}
				if (row.payFor == 'FKLX-03') {
					if (row.sourceType != '') {
						return "<a href='javascript:void(0)' title='退款申请' style='color:red' onclick='showModalWin(\"?model=finance_payablesapply_payablesapply&action=toView&id=" + row.id + '&skey=' + row.skey_ + "\")'>" + v + "</a>";
					} else {
						return "<a href='javascript:void(0)' title='退款申请' style='color:red' onclick='showModalWin(\"?model=finance_payablesapply_payablesapply&action=init&perm=view&id=" + row.id + '&skey=' + row.skey_ + "\")'>" + v + "</a>";
					}
				} else {
					if (row.sourceType != '') {
						return "<a href='javascript:void(0)' onclick='showModalWin(\"?model=finance_payablesapply_payablesapply&action=toView&id=" + row.id + '&skey=' + row.skey_ + "\")'>" + v + "</a>";
					} else {
						return "<a href='javascript:void(0)' onclick='showModalWin(\"?model=finance_payablesapply_payablesapply&action=init&perm=view&id=" + row.id + '&skey=' + row.skey_ + "\")'>" + v + "</a>";
					}
				}
			}
		}, {
			name: 'formNo',
			display: '申请单编号',
			sortable: true,
			width: 140,
			process: function(v, row) {
				if (row.id == 'noId') {
					return v;
				}
				if (row.payFor == 'FKLX-03') {
					if (row.sourceType != '') {
						return "<a href='javascript:void(0)' title='退款申请' style='color:red' onclick='showModalWin(\"?model=finance_payablesapply_payablesapply&action=toView&id=" + row.id + '&skey=' + row.skey_ + "\")'>" + v + "</a>";
					} else {
						return "<a href='javascript:void(0)' title='退款申请' style='color:red' onclick='showModalWin(\"?model=finance_payablesapply_payablesapply&action=init&perm=view&id=" + row.id + '&skey=' + row.skey_ + "\")'>" + v + "</a>";
					}
				} else {
					if (row.sourceType != '') {
						return "<a href='javascript:void(0)' onclick='showModalWin(\"?model=finance_payablesapply_payablesapply&action=toView&id=" + row.id + '&skey=' + row.skey_ + "\")'>" + v + "</a>";
					} else {
						return "<a href='javascript:void(0)' onclick='showModalWin(\"?model=finance_payablesapply_payablesapply&action=init&perm=view&id=" + row.id + '&skey=' + row.skey_ + "\")'>" + v + "</a>";
					}
				}
			}
		}, {
			name: 'formDate',
			display: '单据日期',
			sortable: true,
			width: 80
		}, {
			name: 'payDate',
			display: '期望付款日期',
			sortable: true,
			width: 80
		}, {
			name: 'actPayDate',
			display: '实际付款日期',
			sortable: true,
			width: 80
		}, {
			name: 'sourceType',
			display: '源单类型',
			sortable: true,
			datacode: 'YFRK',
			width: 80
		}, {
			name: 'payFor',
			display: '申请类型',
			sortable: true,
			datacode: 'FKLX',
			width: 80
		}, {
			name: 'supplierName',
			display: '供应商名称',
			sortable: true,
			width: 150
		}, {
			name: 'payMoney',
			display: '申请金额',
			sortable: true,
			process: function(v) {
				if (v >= 0) {
					return moneyFormat2(v);
				} else {
					return "<span class='red'>" + moneyFormat2(v) + "</span>";
				}
			},
			width: 80
		},
        {
                name: 'pchMoney',//源单金额
                display: '源单合同金额',
                sortable: false,
                process: function(v) {
                    if (v >= 0) {
                        return moneyFormat2(v);
                    } else {
                        return "<span class='red'>" + moneyFormat2(v) + "</span>";
                    }
                },
                width: 80
            },
            {
			name: 'payedMoney',
			display: '已付金额',
			sortable: true,
			process: function(v) {
				if (v >= 0) {
					return moneyFormat2(v);
				} else {
					return "<span class='red'>" + moneyFormat2(v) + "</span>";
				}
			},
			width: 80
		}, {
            name: 'payMoneyCur',
            display: '本位币金额',
            sortable: true,
            process: function (v) {
                if (v >= 0) {
                    return moneyFormat2(v);
                } else {
                    return "<span class='red'>" + moneyFormat2(v) + "</span>";
                }
            },
            width: 80
        }, {
            name: 'currency',
            display: '付款币种',
            sortable: true,
            width: 60
        }, {
            name: 'rate',
            display: '汇率',
            sortable: true,
            width: 60
        }, {
			name: 'status',
			display: '单据状态',
			sortable: true,
			datacode: 'FKSQD',
			width: 70
		}, {
			name: 'ExaStatus',
			display: '审批状态',
			sortable: true,
			width: 80
		}, {
			name: 'ExaDT',
			display: '审批时间',
			sortable: true,
			width: 80
		}, {
			name: 'deptName',
			display: '申请部门',
			sortable: true
		}, {
			name: 'salesman',
			display: '申请人',
			sortable: true
		}, {
			name: 'feeDeptName',
			display: '费用归属部门',
			sortable: true,
			width: 80
		}, {
			name: 'feeDeptId',
			display: '费用归属部门id',
			sortable: true,
			hide: true,
			width: 80
		}, {
			name: 'businessBelongName',
			display: '归属公司',
			sortable: true,
			width: 80
		}, {
			name: 'isInvoice',
			display: '是否开据发票',
			sortable: true,
			width: 80,
			process: function(v, row) {
				if (row.sourceType == 'YFRK-02') {
					if (v == '1') {
						return '是';
					} else if (v == '0') {
						return '否';
					}
				}
				else
					return '-';
			}
		}, {
			name: 'comments',
			display: '备注',
			sortable: true,
			width: 80
		}, {
			name: 'createName',
			display: '创建人',
			sortable: true
		}, {
			name: 'createTime',
			display: '创建日期',
			sortable: true,
			width: 120,
			hide: true
		}, {
			name: 'printCount',
			display: '打印次数',
			sortable: true,
			width: 80
		}],
		// 主从表格设置
		subGridOptions: {
			url: '?model=finance_payablesapply_detail&action=pageJson',// 获取从表数据url
			// 传递到后台的参数设置数组
			param: [
				{
					paramId: 'payapplyId',// 传递给后台的参数名称
					colId: 'id'// 获取主表行数据的列名称
				}
			],
			// 显示的列
			colModel: [{
				name: 'objType',
				display: '源单类型',
				datacode: 'YFRK'
			}, {
				name: 'objCode',
				display: '源单编号',
				width: 150
			}, {
				name: 'money',
				display: '申请金额',
				process: function(v) {
					return moneyFormat2(v);
				}
			}, {
				name: 'purchaseMoney',
				display: '源单金额',
				process: function(v) {
					return moneyFormat2(v);
				}
			}]
		},
		toAddConfig: {
			toAddFn: function() {
				showModalWin("?model=finance_payablesapply_payablesapply&action=toAddDept&sourceType=YFRK-04");
			}
		},
		toViewConfig: {
			toViewFn: function(p, g) {
				var rowObj = g.getSelectedRow();
				if (rowObj) {
					var rowData = rowObj.data('data');
					var keyUrl = "";
					if (rowData['skey_']) {
						keyUrl = "&skey=" + rowData['skey_'];
					}
					if (rowData.sourceType != '') {
						showModalWin("?model=finance_payablesapply_payablesapply&action=toView&id=" + rowData.id + keyUrl, 1);
					} else {
						showModalWin("?model=finance_payablesapply_payablesapply&action=init&perm=view&id=" + rowData.id + keyUrl, 1);
					}
				} else {
					alert('请选择一行记录！');
				}
			}
		},
		menusEx: [
			{
				text: '编辑',
				icon: 'edit',
				showMenuFn: function(row) {
					return row.ExaStatus == '待提交';
				},
				action: function(row) {
					if (row.sourceType != '') {
						showModalWin("?model=finance_payablesapply_payablesapply&action=toEdit&id=" + row.id + '&skey=' + row['skey_'], 1);
					} else {
						showModalWin("?model=finance_payablesapply_payablesapply&action=init&owner=my&id=" + row.id + '&skey=' + row['skey_'], 1);
					}
				}
			},
			{
				text: '提交审批',
				icon: 'add',
				showMenuFn: function(row) {
					return row.ExaStatus == '待提交';
				},
				action: function(row) {
					if (row.sourceType != 'YFRK-01') {
						//add chenrf 20130504    其它合同退款申请
						if (row.payFor == 'FKLX-03') {
							showThickboxWin('controller/finance/payablesapply/ewf_indexpayback.php?actTo=ewfSelect&billId='
							+ row.id + '&flowMoney=' + Math.abs(row.payMoney)
							+ '&billDept=' + row.feeDeptId
							+ '&skey=' + row.skey_
							+ '&billCompany=' + row.businessBelong
							+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=750");
						} else {
							//付款申请审批流
							showThickboxWin('controller/finance/payablesapply/ewf_index1.php?actTo=ewfSelect&billId='
							+ row.id + '&flowMoney=' + row.payMoney
							+ '&billDept=' + row.feeDeptId
							+ '&skey=' + row.skey_
							+ '&billCompany=' + row.businessBelong
							+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=750");
						}
					} else {
						if (row.payFor == 'FKLX-03') {
							showThickboxWin('controller/finance/payablesapply/ewf_indexback.php?actTo=ewfSelect&billId='
							+ row.id + '&flowMoney=' + Math.abs(row.payMoney)
							+ '&billDept=' + row.feeDeptId
							+ '&skey=' + row.skey_
							+ '&billCompany=' + row.businessBelong
							+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=750");

						} else {
							showThickboxWin('controller/finance/payablesapply/ewf_index.php?actTo=ewfSelect&billId='
							+ row.id + '&flowMoney=' + row.payMoney
							+ '&billDept=' + row.feeDeptId
							+ '&skey=' + row.skey_
							+ '&billCompany=' + row.businessBelong
							+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=750");
						}
					}
				}
			},
			{
				text: '删除',
				icon: 'delete',
				showMenuFn: function(row) {
					return row.ExaStatus == '待提交';
				},
				action: function(row) {
					if (window.confirm(("确定要删除?"))) {
						$.ajax({
							type: "POST",
							url: "?model=finance_payablesapply_payablesapply&action=ajaxdeletes",
							data: {
								id: row.id
							},
							success: function(msg) {
								if (msg == 1) {
									alert('删除成功！');
									show_page();
								} else {
									alert('删除失败！');
								}
							}
						});
					}
				}
			},
			{
				text: '提交财务支付',
				icon: 'edit',
				showMenuFn: function(row) {
					return row.status == 'FKSQD-00' && row.ExaStatus == '完成';
				},
				action: function(row) {
					if (row.payDate == "") {
						showThickboxWin('?model=finance_payablesapply_payablesapply&action=toConfirm&id='
						+ row.id
						+ '&supplierName=' + row['supplierName']
						+ '&payMoney=' + row['payMoney']
						+ '&skey=' + row['skey_']
						+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=700");
					} else {
						var thisDate = formatDate(new Date());
						var s = DateDiff(thisDate, row.payDate);
						// if (s > 0) {
						// 	alert('距离期望付款日期还有 ' + s + " 天，暂不能提交财务支付");
						// } else {
							showThickboxWin('?model=finance_payablesapply_payablesapply&action=toConfirm&id='
							+ row.id
							+ '&supplierName=' + row['supplierName']
							+ '&payMoney=' + row['payMoney']
							+ '&skey=' + row['skey_']
							+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=700");
						// }
					}
				}
			}, {
				name: 'file',
				text: '上传附件',
				icon: 'add',
				showMenuFn: function(row) {
					if (row.status == 3) {
						return false;
					}
				},
				action: function(row) {
					showThickboxWin("?model=finance_payablesapply_payablesapply&action=toUploadFile&id="
					+ row.id
					+ "&skey=" + row.skey_
					+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=750");
				}
			}, {
				text: '打印',
				icon: 'print',
				showMenuFn: function(row) {
					return row.status != 'FKSQD-04';
				},
				action: function(row) {
					showModalWin("?model=finance_payablesapply_payablesapply&action=toPrint&id=" + row.id + '&skey=' + row['skey_'], 1);
				}
			}, {
				text: '关闭',
				icon: 'delete',
				showMenuFn: function(row) {
					return row.ExaStatus == '完成' && (row.status == 'FKSQD-01' || row.status == 'FKSQD-00');
				},
				action: function(row) {
					showThickboxWin('?model=finance_payablesapply_payablesapply&action=toClose&id='
					+ row.id
					+ '&skey=' + row['skey_']
					+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=800");
				}
			}, {
				text: '撤销审批',
				icon: 'edit',
				showMenuFn: function(row) {
					return row.ExaStatus == '部门审批';
				},
				action: function(row) {
					if (row) {
						if (row.sourceType != 'YFRK-01') {
							//add chenrf 20130504    其它合同退款申请
							if (row.payFor == 'FKLX-03') {
								var ewfurl = 'controller/finance/payablesapply/ewf_indexpayback.php?actTo=delWork&billId=';
							} else {
								//付款申请
								var ewfurl = 'controller/finance/payablesapply/ewf_index1.php?actTo=delWork&billId=';
							}

						} else {//采购订单
							if (row.payFor == 'FKLX-03') {
								var ewfurl = 'controller/finance/payablesapply/ewf_indexpayback.php?actTo=delWork&billId=';
							} else {
								var ewfurl = 'controller/finance/payablesapply/ewf_index.php?actTo=delWork&billId=';
							}
						}

						$.ajax({
							type: "POST",
							url: "?model=common_workflow_workflow&action=isAudited",
							data: {
								billId: row.id,
								examCode: 'oa_finance_payablesapply'
							},
							success: function(msg) {
								if (msg == '1') {
									alert('单据已经存在审批信息，不能撤销审批！');
									show_page();
								} else {
									if (confirm('确定要撤消审批吗？')) {
										$.ajax({
											type: "GET",
											url: ewfurl,
											data: {"billId": row.id},
											async: false,
											success: function(data) {
												alert(data);
												show_page();
											}
										});
									}
								}
							}
						});
					} else {
						alert("请选中一条数据");
					}
				}
			}
		],
		//过滤数据
		comboEx: [{
			text: '审批状态',
			key: 'ExaStatus',
			type: 'workFlow'
		}, {
			text: '单据状态',
			key: 'status',
			datacode: 'FKSQD'
		}, {
			text: '源单类型',
			key: 'sourceType',
			datacode: 'YFRK'
		}],
		searchitems: [{
			display: '供应商名称',
			name: 'supplierName'
		}, {
			display: '申请单编号',
			name: 'formNoSearch'
		}, {
			display: '关联编号',
			name: 'objCodeSearch'
		}, {
			display: '申请金额',
			name: 'payMoney'
		}, {
			display: 'id',
			name: 'id'
		}]
	});
});