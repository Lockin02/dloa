var show_page = function() {
	$("#mycontractGrid").yxsubgrid("reload");
};
$(function() {
	//初始化右键按钮数组
	var menusArr = [
	{
		text : '查看',
		icon : 'view',
		showMenuFn : function(row) {
			if (row) {
				return true;
			}
			return false;
		},
		action : function(row) {
			showModalWin('?model=contract_contract_contract&action=toViewTab&id='
					+ row.id
					+ "&skey="
					+ row['skey_']
					+ '&placeValuesBefore&TB_iframe=true&modal=false&height=800&width=900');
		}
	}, {
		text : '变更查看',
		icon : 'view',
		showMenuFn : function(row) {
			if (row && row.becomeNum != '0' && row.becomeNum != '') {
				return true;
			}
			return false;
		},
		action : function(row) {
			showModalWin('?model=contract_contract_contract&action=showViewTab&id='
					+ row.id
					+ "&skey="
					+ row['skey_']
					+ '&placeValuesBefore&TB_iframe=true&modal=false&height=800&width=900');
		}
	}, {
		text : '修改',
		icon : 'edit',
		showMenuFn : function(row) {
			if (row && (row.ExaStatus == '未审批' || row.ExaStatus == '打回')
					&& row.isSubApp == '0') {
				return true;
			}
			return false;
		},
		action : function(row) {
			showModalWin('?model=contract_contract_contract&action=init&id='
					+ row.id
					+ '&perm=edit'
					+ "&skey="
					+ row['skey_']
					+ '&placeValuesBefore&TB_iframe=true&modal=false&height=800&width=900');
		}
	}, {
		text : '海外修改',
		icon : 'edit',
		showMenuFn : function(row) {
			if (row && row.parentName != '') {
				return true;
			}
			return false;
		},
		action : function(row) {
			showModalWin('?model=contract_contract_contract&action=init&id='
					+ row.id
					+ '&perm=hwedit'
					+ "&skey="
					+ row['skey_']
					+ '&placeValuesBefore&TB_iframe=true&modal=false&height=800&width=900');
		}
	}, {
		name : 'cancel',
		text : '撤销审批',
		icon : 'edit',
		showMenuFn : function(row) {
			if (typeof(row) != 'undefined')
				if (row.ExaStatus == '部门审批') {
					return true;
				}
			return false;
		},
		action : function(row, rows, grid) {
			if (row) {
				$.ajax({
					type : "POST",
					url : "?model=common_workflow_workflow&action=isAuditedContract",
					data : {
						billId : row.id,
						examCode : 'oa_contract_contract'
					},
					success : function(msg) {
						if (msg == '1') {
							alert('单据已经存在审批信息，不能撤销审批！');
							return false;
						} else {
							switch (msg) {
								case '合同审批A' :
									var url = 'controller/contract/contract/ewf_index_50.php?actTo=delWork&billId=';
									break;
								case '合同审批B' :
									var url = 'controller/contract/contract/ewf_index_Other.php?actTo=delWork&billId=';
									break;
								case '合同审批TA' :
									var url = 'controller/contract/contract/ewf_index_50_list_temp.php?actTo=delWork&billId=';
									break;
								case '合同审批TB' :
									var url = 'controller/contract/contract/ewf_index_Other_list_temp.php?actTo=delWork&billId=';
									break;
								case '合同审批C' :
									if (row.winRate == '50%') {
										var url = 'controller/contract/contract/ewf_index_50_list.php?actTo=delWork&billId=';
									} else {
										var url = 'controller/contract/contract/ewf_index_Other_list.php?actTo=delWork&billId=';
									}
									break;
							}
							$.ajax({
								type : "GET",
								url : url,
								data : {
									"billId" : row.id
								},
								async : false,
								success : function(data) {
									$.ajax({
										type : "POST",
										url : "?model=contract_common_relcontract&action=ajaxBack",
										data : {
											id : row.id
										},
										success : function(msg) {
											if (msg == 1) {
												alert(data);
												show_page();
											}
										}
									});
								}
							});
						}
					}
				});
			} else {
				alert("请选中一条数据");
			}
		}
	}, {
		text : '审批情况',
		icon : 'view',
		showMenuFn : function(row) {
			if (row && row.ExaStatus == '部门审批') {
				return true;
			}
			return false;
		},
		action : function(row) {
			showThickboxWin('controller/contract/contract/readview.php?itemtype=oa_contract_contract&pid='
					+ row.id
					+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=600');
		}
	}, {
		text : '附件上传',
		icon : 'add',
		showMenuFn : function(row) {
			if (row) {
				return true;
			}
			return false;
		},
		action : function(row) {
			showThickboxWin('?model=contract_contract_contract&action=toUploadFile&id='
					+ row.id
					+ '&type=oa_contract_contract'
					+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=200&width=600');
		}
	},  {
		text : '删除',
		icon : 'delete',
		showMenuFn : function(row) {
			if (row && (row.state == '0' || row.ExaStatus == '打回')
					&& row.isSubApp == '0') {
				return true;
			}
			return false;
		},
		action : function(row) {
			if (window.confirm(("确定要删除?"))) {
//				$.ajax({
//					type : "POST",
//					url : "?model=contract_contract_contract&action=ajaxdeletes",
//					data : {
//						id : row.id
//					},
//					success : function(msg) {
//						if (msg == 1) {
//							alert('删除成功！');
//							$("#mycontractGrid").yxsubgrid("reload");
//						} else {
//							alert('删除失败! ');
//						}
//					}
//				});
			 this.location='controller/contract/contract/ewf_delete.php?actTo=ewfSelect&billId='
				+ row.id
//			 showThickboxWin('controller/contract/contract/ewf_delete.php?actTo=ewfSelect&billId='
//				+ row.id
//				+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=750");
			}
		}
	}, {
		text : '开票申请',
		icon : 'add',
		showMenuFn : function(row) {
			if (row && (row.state == '2' || row.state == '4') && row.invoiceCode != "HTBKP") {
				return true;
			}
			return false;
		},
		action : function(row) {
			showModalWin('?model=finance_invoiceapply_invoiceapply&action=toAdd&invoiceapply[objId]='
					+ row.id
					+ '&invoiceapply[objCode]='
					+ row.contractCode
					+ '&invoiceapply[objType]=KPRK-12');
		}
	}, {
		text : '录入不开票金额',
		icon : 'add',
		showMenuFn : function(row) {
			if (row && (row.state == '2' || row.state == '4') && row.invoiceCode != "HTBKP") {
				return true;
			}
			return false;
		},
		action : function(row) {
			showThickboxWin('?model=contract_uninvoice_uninvoice&action=toAdd&objId='
					+ row.id
					+ '&objCode='
					+ row.contractCode
					+ '&objType=KPRK-12'
					+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=800');
		}
	}, {
		text : '扣款申请',
		icon : 'add',
		showMenuFn : function(row) {
			if (row && (row.state == '2' || row.state == '4')) {
				return true;
			}
			return false;
		},
		action : function(row) {
			showThickboxWin('?model=contract_deduct_deduct&action=toAdd&contractId='
					+ row.id
					+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=700');
		}
	}, {
		name : 'stamp',
		text : '申请盖章',
		icon : 'add',
		showMenuFn : function(row) {
//			if (row && row.status == 3) {
//				return false;
//			}
//			if (row && (row.ExaStatus == "完成" && row.isStamp != "1"))
//				return true;
//			else
//				return false;
//			if(row && (row.state == '1' || row.state == '2' || row.state == '3' || row.state == '4' || row.state == '7')){
            if(row.isNeedStamp == '0'){
                return true;
            }else{
                return row.isStamp == '1';
            }
//			}else{
//				return false;
//			}
		},
		action : function(row, rows, grid) {
			if (row) {
//				if (row.isNeedStamp == '1') {
//					alert('此合同已申请盖章,不能重复申请');
//					return false;
//				}
				showThickboxWin("?model=contract_contract_contract&action=toStamp&id="
						+ row.id
						+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=300&width=750");
			} else {
				alert("请选中一条数据");
			}
		}
	}, {
		text : '变更合同',
		icon : 'edit',
		showMenuFn : function(row) {
			if (row && (row.state == '2' || row.state == '4')
					&& row.ExaStatus == '完成' && row.isSubAppChange == '0') {
				return true;
			}
			return false;
		},
		action : function(row) {
			showModalWin('?model=contract_contract_contract&action=toChange&id='
					+ row.id
					+ "&skey="
					+ row['skey_']
					+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=200&width=400');
		}
	}
			// , {
			// text : '变更物料',
			// icon : 'edit',
			// // showMenuFn : function(row) {
			// // if (row && (row.createTime < '2012-09-09') &&
			// row.ExaStatus == '完成' && (row.state == '2' || row.state ==
			// '4')){
			// // return true;
			// // }
			// // return false;
			// // },
			// action : function(row) {
			// showThickboxWin('?model=contract_contract_contract&action=toChangeEqu&contractId='
			// + row.id
			// + "&skey="
			// + row['skey_']
			// +
			// '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=600&width=1000');
			// }
			// }
	, {
		text : '合同共享',
		icon : 'add',
		showMenuFn : function(row) {
			if (row) {
				return true;
			}
			return false;
		},
		action : function(row) {
			showThickboxWin('?model=contract_contract_contract&action=toShare&id='
					+ row.id
					+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=350&width=660');
		}
	}, {
		text : '异常关闭',
		icon : 'delete',
		showMenuFn : function(row) {
			if (row && (row.state == '2' || row.state == '4')) {
				return true;
			}
			return false;
		},
		action : function(row) {
			showThickboxWin('?model=contract_contract_contract&action=closeContract&id='
					+ row.id
					+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=300&width=600');
		}
	}, {
		text : '发货物料确认',
		icon : 'edit',
		showMenuFn : function(row) {
			if (row && (row.confirmEqu == '1' || row.confirmEqu == '2' || row.confirmEqu == '3')) {
				return true;
			}
			return false;
		},
		action : function(row) {
			showModalWin('?model=contract_contract_contract&action=confirmEquView&contractId='
					+ row.id
					+ '&isSubAppChange=' + row.isSubAppChange
					+ '&confirmEqu=' + row.confirmEqu
					+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=700');
		}
	}, {
		text : '赠送申请',
		icon : 'edit',
		showMenuFn : function(row) {
			if (row && (row.prinvipalId == $("#userId").val() || row.createId == $("#userId").val()) && (row.state == '2' || row.state == '4')) {
				return true;
			}
			return false;
		},
		action : function(row) {
			showModalWin('?model=projectmanagent_present_present&action=toAdd&contractId='
					+ row.id
					+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=700');
		}
	}, {
		text : '退货申请',
		icon : 'edit',
		showMenuFn : function(row) {
			if (row && (row.prinvipalId == $("#userId").val() || row.createId == $("#userId").val()) && (row.state == '2' || row.state == '4') && row.ExaStatus == '完成') {
				return true;
			}
			return false;
		},
		action : function(row) {
			showModalWin('?model=projectmanagent_return_return&action=toAdd&contractId='
					+ row.id
					+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=700');
		}
	}, {
		text : '换货申请',
		icon : 'edit',
		showMenuFn : function(row) {
			if (row && (row.state == '2' || row.state == '3' || row.state == '4' || row.state == '7') && row.ExaStatus == '完成') {
				return true;
			}
			return false;
		},
		action : function(row) {
			showModalWin('?model=projectmanagent_exchange_exchange&action=toAdd&contractId='
					+ row.id
					+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=700');
		}
	}];
	excelMenu = {
			text : '导出',
			icon : 'add',
			showMenuFn : function(row) {
				if (row) {
					return true;
				}
				return false;
			},
			action : function(row) {
				window.open('?model=contract_common_relcontract&action=importCont&id='
								+ row.id
								+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=600');
			}
		};

	buttonsArr = [
		{
			text: "重置",
			icon: 'delete',
			action: function (row) {
				var listGrid = $("#mycontractGrid").data('yxsubgrid');
				listGrid.options.extParam = {};
				$("#caseListWrap tr").attr('style',"background-color: rgb(255, 255, 255)");
				listGrid.reload();
			}
		}
	],

	//获取导出权限
		$.ajax({
			type : "POST",
			url : "?model=contract_contract_contract&action=getLimits",
			data : {
				limitName : '合同信息导出'
			},
			async: false,
			success : function(data) {
				if(data==1){
					menusArr.push(excelMenu);
				}
			}
		});
	$("#mycontractGrid").yxsubgrid({
		model : 'contract_contract_contract',
		action : 'MyconPageJson',
		param : {
			'states' : '0,1,2,3,4,5,6,7',
//			'mycontractArr' : $("#userId").val(),
			'isTemp' : '0',
			'todo' : $("#todo").val()
		},
		leftLayout: true,
		title : '合同信息',
		isViewAction : false,
		isEditAction : false,
		isDelAction : false,
		showcheckbox : false,
		isAddAction : false,
		customCode : 'mycontractA',
		// 扩展右键菜单
		menusEx : menusArr,
		lockCol : ['flag', 'exeStatus', 'status2'],// 锁定的列名
		// 列信息
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'flag',
			display : '沟通板',
			// sortable : true,
			width : 40,
			process : function(v, row) {
				if (row.id == "allMoney" || row.id == undefined || row.id == '') {
					return "合计";
				}
				if (v == '') {
					return '<a href="javascript:void(0)" onclick="javascript:showThickboxWin(\'?model=contract_contract_contract&action=listremark&id='
							+ row.id
							+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=600&width=900\')">'
							+ "<img src='images/icon/icon139.gif' />" + '</a>';
				} else {
					return '<a href="javascript:void(0)" onclick="javascript:showThickboxWin(\'?model=contract_contract_contract&action=listremark&id='
							+ row.id
							+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=600&width=900\')">'
							+ "<img src='images/icon/icon095.gif' />" + '</a>';
				}

			}
		}, {
			name : 'ExaDTOne',
			display : '建立时间',
			sortable : true,
			width : 80,
			hide : true
		}, {
			name : 'createTime',
			display : '录入时间',
			sortable : true,
			width : 80,
			hide : true
		}, {
			name : 'isNeedStamp',
			display : '是否盖章',
			sortable : true,
			width : 80,
			process : function(v, row) {
				if (row.id == "allMoney" || row.id == undefined) {
					return "";
				} else {
					if (v == '0') {
						return "否";
					} else {

						return "是";
					}
				}
			}
		}, {
			name : 'contractType',
			display : '合同类型',
			sortable : true,
			datacode : 'HTLX',
			width : 60
		}, {
			name : 'businessBelongName',
			display : '签约公司',
			sortable : true,
			width : 60
		}, {
			name : 'contractNatureName',
			display : '合同属性',
			sortable : true,
			width : 60,
			process : function(v) {
				if (v == '') {
					return v;
					// return "金额合计";
				} else {
					return v;
				}
			}
		}, {
			name : 'contractCode',
			display : '合同编号',
			sortable : true,
			width : 180,
			process : function(v, row) {
				return '<a href="javascript:void(0)" onclick="javascript:showModalWin(\'?model=contract_contract_contract&action=toViewTab&id='
						+ row.id
						+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800\')">'
						+ "<font color = '#4169E1'>" + v + "</font>" + '</a>';
			}
		}, {
			name : 'customerName',
			display : '客户名称',
			sortable : true,
			width : 100
		}, {
			name : 'customerId',
			display : '客户Id',
			sortable : true,
			width : 100,
			hide : true
		}, {
			name : 'customerType',
			display : '客户类型',
			sortable : true,
			datacode : 'KHLX',
			width : 70
		}, {
			name : 'contractName',
			display : '合同名称',
			sortable : true,
			width : 150
		}, {
			name : 'signStatus',
			display : '签收状态',
			sortable : true,
			width : 80,
			process : function(v, row) {
				if (v == '0') {
					return "未签收";
				} else if (v == '1') {
					return "已签收";
				} else if (v == '2') {
					return "变更未签收";
				}
			}
		}
				// , {
				// name : 'contractTempMoney',
				// display : '预计合同金额',
				// sortable : true,
				// width : 80,
				// process : function(v, row) {
				// if (row.contractMoney == ''
				// || row.contractMoney == 0.00
				// || row.id == 'allMoney') {
				// return moneyFormat2(v);
				// } else {
				// return "<font color = '#B2AB9B'>" + moneyFormat2(v)
				// + "</font>";
				// }
				//
				// }
				// }
				, {
					name : 'contractMoney',
					display : '合同金额',
					sortable : true,
					width : 80,
					process : function(v) {
						return moneyFormat2(v);
					}
				}, {
					name : 'invoiceMoney',
					display : '开票金额',
					sortable : true,
					width : 80,
					process : function(v, row) {
						if (v == '') {
							return "0.00";
						} else {
							return moneyFormat2(v);
						}
					}
				}, {
					name : 'uninvoiceMoney',
					display : '不开票金额',
					sortable : true,
					width : 80,
					process : function(v, row) {
						if (row.id == undefined)
							return moneyFormat2(v);
						if (v == '') {
							return "0.00";
						} else {
							return '<a href="javascript:void(0)" onclick="javascript:showThickboxWin(\'?model=contract_uninvoice_uninvoice&action=toObjList&objId='
									+ row.id
									+ '&objType=KPRK-12'
									+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=900\')">'
									+ moneyFormat2(v) + '</a>';
						}
					}
				}, {
					name : 'deductMoney',
					display : '扣款金额',
					sortable : true,
					width : 80,
					process : function(v, row) {
						if (row.id == undefined)
							return moneyFormat2(v);
						if (v == '') {
							return "0.00";
						} else {
							return '<a href="javascript:void(0)" onclick="javascript:showThickboxWin(\'?model=contract_contract_contract&action=financialDetailTab&id='
									+ row.id
									+ '&tablename='
									+ row.contractType
									+ '&moneyType=deductMoney'
									+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=1000\')">'
									+ "<font color = '#4169E1'>"
									+ moneyFormat2(v) + "</font>" + '</a>';
						}
					}
				}, {
					name : 'badMoney',
					display : '坏账',
					sortable : true,
					width : 80,
					process : function(v, row) {
						if (row.id == "allMoney" || row.id == undefined) {
							return moneyFormat2(v);
						}
						if (v == "") {
							return "0.00";
						}
						return moneyFormat2(v);
					}
				}, {
					name : 'invoiceApplyMoney',
					display : '开票申请总金额',
					sortable : true,
					width : 80,
					process : function(v, row) {
						return moneyFormat2(v);
					}
				}, {
					name : 'surplusInvoiceMoney',
					display : '剩余开票金额',
					sortable : true,
					process : function(v, row) {
						return "<font color = 'blue'>" + moneyFormat2(v);
						+"</font>"
					}
				}, {
					name : 'incomeMoney',
					display : '已收金额',
					width : 60,
					sortable : true,
					process : function(v, row) {
						if (v == '') {
							return "0.00";
						} else {
							return moneyFormat2(v);
						}
					}
				}, {
					name : 'surOrderMoney',
					display : '签约合同应收账款余额',
					sortable : true,
					width : 120,
					process : function(v, row) {
						return "<font color = 'blue'>" + moneyFormat2(v);
						+"</font>"
					}
				}, {
					name : 'surincomeMoney',
					display : '财务应收账款余额',
					sortable : true,
					process : function(v, row) {
						return "<font color = 'blue'>" + moneyFormat2(v);
						+"</font>"
					}
				}, {
					name : 'financeconfirmPlan',
					display : '财务确认进度',
					sortable : false,
					width : 80,
					process : function(v, row) {
						if (row.id == undefined) {
							return "";
						}
						var financePlan = moneyFormat2(row.serviceconfirmMoney
								/ (accSub(row.contractMoney, row.deductMoney)));
						if (isNaN(financePlan)) {
							return "0.00%";
						} else {
							financePlan = parseFloat(financePlan).toFixed(2);
							return financePlan * 100 + "%";
						}

					}
				}, {
					name : 'isSubApp',
					display : '提交状态',
					sortable : true,
					width : 60,
					process : function(v, row) {
						if (v == '0') {
							return "未提交";
						} else if (v == '1') {
							return "已提交";
						} else {
							return "--";
						}
					}
				}, {
					name : 'ExaStatus',
					display : '审批状态',
					sortable : true,
					width : 60
				}, {
					name : 'winRate',
					display : '合同赢率',
					sortable : true,
					width : 70
				}, {
					name : 'areaName',
					display : '归属区域',
					sortable : true,
					width : 60
				}, {
					name : 'AreaLeaderNow',
					display : '区域负责人'
				}, {
					name : 'prinvipalName',
					display : '合同负责人',
					sortable : true,
					width : 80
				}, {
					name : 'state',
					display : '合同状态',
					sortable : true,
					process : function(v) {
						if (v == '0') {
							return "未提交";
						} else if (v == '1') {
							return "审批中";
						} else if (v == '2') {
							return "执行中";
						} else if (v == '3') {
							return "已关闭";
						} else if (v == '4') {
							return "已完成";
						} else if (v == '5') {
							return "已合并";
						} else if (v == '6') {
							return "已拆分";
						} else if (v == '7') {
							return "异常关闭";
						}
					},
					width : 60
				}, {
					name : 'objCode',
					display : '业务编号',
					sortable : true,
					width : 120
				}, {
					name : 'prinvipalDept',
					display : '负责人部门',
					sortable : true,
					hide : true
				}, {
					name : 'prinvipalDeptId',
					display : '负责人部门Id',
					sortable : true,
					hide : true
				}, {
					name : 'exeStatus',
					display : '执行进度',
					sortable : true,
					width : 50,
					process : function(v, row) {
						return "<p onclick='exeStatusView(" + row.id
								+ ");' style='cursor:pointer;color:blue;' >"
								+ v + "</p>";
					}
				}, {
					name : 'status2',
					display : '状态',
					sortable : false,
					width : '20',
					align : 'center',
					// hide : aaa,
					process : function(v, row) {
						if (row.id == "allMoney" || row.id == undefined
								|| row.id == '') {
							return "";
						}
						if (row.state == '3' || row.state == '7') {
							return "<img src='images/icon/icon073.gif' />";
						} else if (row.ExaStatus == '打回') {
							return "<img src='images/icon/icon070.gif' />";
						} else {
							return "<img src='images/icon/icon072.gif' />";
						}
					}
				}, {
					name : 'outstockDate',
					display : '发货完成时间',
					sortable : true,
					hide : true
				}],
		// 主从表格设置
		subGridOptions : {
			url : '?model=stock_outplan_outplan&action=pageByOrderIdBymycontract',// 获取从表数据url
			// 传递到后台的参数设置数组
			param : [{
				paramId : 'docId',// 传递给后台的参数名称
				colId : 'id'// 获取主表行数据的列名称

			}],
			// 显示的列
			colModel : [{
				display : 'id',
				name : 'id',
				sortable : true,
				hide : true
			}, {
				name : 'week',
				display : '周次',
				width : 50,
				hide : true,
				sortable : true
			}, {
				name : 'customerName',
				display : '客户名称',
				width : 150,
				sortable : true
			}, {
				name : 'planCode',
				display : '计划编号',
				width : 90,
				sortable : true,
				process : function(v,row){
				   return '<a href="javascript:void(0)" onclick="javascript:showOpenWin(\'?model=stock_outplan_outplan&action=toView&id='
							+ row.id
							+ '&docType=oa_contract_contract'
							+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800\')">'
							+ "<font color = '#4169E1'>"
							+ v
							+ "</font>"
							+ '</a>';
				}
			}, {
				name : 'docType',
				display : '发货类型',
				sortable : true,
				width : 60,
				process : function(v) {
					if (v == 'oa_contract_contract') {
						return "合同发货";
					} else if (v == 'oa_contract_exchangeapply') {
						return "换货发货";
					} else if (v == 'oa_borrow_borrow') {
						return "借用发货";
					} else if (v == 'oa_present_present') {
						return "赠送发货";
					}
				}
			}, {
				name : 'isTemp',
				display : '是否变更',
				width : 60,
				process : function(v) {
					(v == '1') ? (v = '是') : (v = '否');
					return v;
				},
				sortable : true
			}, {
				name : 'planIssuedDate',
				display : '下达日期',
				width : 75,
				sortable : true,
				hide : true
			}, {
				name : 'stockName',
				display : '发货仓库',
				sortable : true,
				hide : true
			}, {
				name : 'type',
				display : '性质',
				datacode : 'FHXZ',
				width : 70,
				sortable : true,
				hide : true
			}, {
				name : 'purConcern',
				display : '采购人员关注重点',
				hide : true,
				sortable : true
			}, {
				name : 'shipConcern',
				display : '发货人员关注',
				hide : true,
				sortable : true
			}, {
				name : 'deliveryDate',
				display : '交货日期',
				width : 75,
				sortable : true
			}, {
				name : 'shipPlanDate',
				display : '计划发货日期',
				width : 75,
				sortable : true
			}, {
				name : 'status',
				display : '单据状态',
				width : 70,
				process : function(v) {
					if (v == 'YZX') {
						return "已执行";
					} else if (v == 'BFZX') {
						return "部分执行";
					} else if (v == 'WZX') {
						return "未执行";
					} else {
						return "未执行";
					}
				},
				sortable : true
			}, {
				name : 'isOnTime',
				display : '是否按时发货',
				width : 80,
				process : function(v) {
					(v == '1') ? (v = '是') : (v = '否');
					return v;
				},
				sortable : true
			}, {
				name : 'issuedStatus',
				display : '下达状态',
				width : 60,
				process : function(v) {
					(v == '1') ? (v = '已下达') : (v = '未下达');
					return v;
				},
				sortable : true
			}, {
				name : 'docStatus',
				display : '发货状态',
				width : 70,
				process : function(v) {
					if (v == 'YWC') {
						return "已发货";
					} else if (v == 'BFFH') {
						return "部分发货";
					} else if (v == 'YGB') {
						return "停止发货";
					} else
						return "未发货";
				},
				sortable : true
			}, {
				name : 'delayType',
				display : '延期原因归类',
				hide : true,
				sortable : true
			}, {
				name : 'delayReason',
				display : '未发具体原因',
				hide : true,
				sortable : true
			}]
		},
		comboEx : [{
			text : '类型',
			key : 'contractType',
			data : [{
				text : '销售合同',
				value : 'HTLX-XSHT'
			}, {
				text : '服务合同',
				value : 'HTLX-FWHT'
			}, {
				text : '租赁合同',
				value : 'HTLX-ZLHT'
			}, {
				text : '研发合同',
				value : 'HTLX-YFHT'
			}]
		}, {
			text : '合同状态',
			key : 'state',
			data : [{
				text : '保存',
				value : '0'
			}, {
				text : '审批中',
				value : '1'
			}, {
				text : '执行中',
				value : '2'
			}, {
				text : '已完成',
				value : '4'
			}, {
				text : '已关闭',
				value : '3'
			},		/*
					 * { text : '已合并', value : '5' }, { text : '已拆分', value :
					 * '6' },
					 */{
				text : '异常关闭',
				value : '7'
			}]
		}, {
			text : '审批状态',
			key : 'ExaStatus',
			data : [{
				text : '未审批',
				value : '未审批'
			}, {
				text : '部门审批',
				value : '部门审批'
			}, {
				text : '变更审批中',
				value : '变更审批中'
			}, {
				text : '打回',
				value : '打回'
			}, {
				text : '完成',
				value : '完成'
			}]
		}],

		/**
		 * 快速搜索
		 */
		searchitems : [{
			display : '合同编号',
			name : 'contractCode'
		}, {
			display : '合同名称',
			name : 'contractName'
		}, {
			display : '客户名称',
			name : 'customerName'
		}, {
			display : '业务编号',
			name : 'objCode'
		}, {
			display : '区域信息',
			name : 'areaName'
		}, {
			display : '省份',
			name : 'contractProvince'
		}],
		sortname : "createTime",
		buttonsEx: buttonsArr,

		// 高级搜索
		advSearchOptions: {
			modelName: 'contractInfo',
			// 选择字段后进行重置值操作
			selectFn: function ($valInput) {
				$valInput.yxcombogrid_area("remove");
				$valInput.yxselect_user("remove");
			},
			searchConfig: [
				{
					name: '建立日期',
					value: 'c.ExaDTOne',
					changeFn: function ($t, $valInput) {
						$valInput.click(function () {
							WdatePicker({
								dateFmt: 'yyyy-MM-dd'
							});
						});
					}
				},
				{
					name: '年份（直接输入数字，如2013）',
					value: 'date_format(c.ExaDTOne,"%Y")'
				},
				{
					name: '月份（直接输入数字，如 04、11）',
					value: 'date_format(c.ExaDTOne,"%m")'
				},
				{
					name: '季度（直接输入数字，如 1、2、3、4）',
					value: 'quarter(c.ExaDTOne)'
				},
				{
					name: '合同类型',
					value: 'c.contractType',
					type: 'select',
					datacode: 'HTLX'
				},
				{
					name: '销售合同属性',
					value: 'c.contractNature*XS',
					type: 'select',
					datacode: 'HTLX-XSHT'
				}
				,
				{
					name: '服务合同属性',
					value: 'c.contractNature*FW',
					type: 'select',
					datacode: 'HTLX-FWHT'
				},
				{
					name: '租赁合同属性',
					value: 'c.contractNature*ZL',
					type: 'select',
					datacode: 'HTLX-ZLHT'
				},
				{
					name: '研发合同属性',
					value: 'c.contractNature*YF',
					type: 'select',
					datacode: 'HTLX-YFHT'
				}
				,
				{
					name: '客户类型',
					value: 'c.customerType',
					type: 'select',
					datacode: 'KHLX'
				}
				// , {
				// name : '剩余开票金额',
				// value : 'c.surplusInvoiceMoney'
				// }
				,
				{
					name: '签约合同应收账款余额',
					value: 'c.surOrderMoney'
				},
				{
					name: '财务应收账款余额',
					value: 'c.surincomeMoney'
				},
				{
					name: '区域负责人',
					value: 'c.areaPrincipal',
					changeFn: function ($t, $valInput, rowNum) {
						$valInput.yxcombogrid_area({
							hiddenId: 'areaPrincipalId' + rowNum,
							nameCol: 'areaPrincipal',
							height: 200,
							width: 550,
							gridOptions: {
								showcheckbox: true
							}
						});
					}
				},
				{
					name: '归属区域',
					value: 'c.areaName',
					changeFn: function ($t, $valInput, rowNum) {
						$valInput.yxcombogrid_area({
							hiddenId: 'areaCode' + rowNum,
							nameCol: 'areaName',
							height: 200,
							width: 550,
							gridOptions: {
								showcheckbox: true
							}
						});
					}
				},
				{
					name: '合同负责人',
					value: 'c.prinvipalName',
					changeFn: function ($t, $valInput, rowNum) {

						$valInput.yxselect_user({
							hiddenId: 'prinvipalId' + rowNum,
							nameCol: 'prinvipalName',
							height: 200,
							width: 550,
							gridOptions: {
								showcheckbox: true
							}
						});
					}
				},
				{
					name: '合同签署人',
					value: 'c.contractSigner',
					changeFn: function ($t, $valInput, rowNum) {

						$valInput.yxselect_user({
							hiddenId: 'contractSignerId' + rowNum,
							nameCol: 'contractSigner',
							height: 200,
							width: 550,
							gridOptions: {
								showcheckbox: true
							}
						});
					}
				},
				{
					name: '省份',
					value: 'c.contractProvince'
				},
				{
					name: '城市',
					value: 'c.contractCity'
				},
				{
					name: '合同状态',
					value: 'c.state',
					type: 'select',
					options: [
						{
							'dataName': '未提交',
							'dataCode': '0'
						},
						{
							'dataName': '审批中',
							'dataCode': '1'
						},
						{
							'dataName': '执行中',
							'dataCode': '2'
						},
						{
							'dataName': '已完成',
							'dataCode': '4'
						},
						{
							'dataName': '已关闭',
							'dataCode': '3'
						},
						{
							'dataName': '异常关闭',
							'dataCode': '7'
						}
					]

				},
				{
					name: '审批状态',
					value: 'c.ExaStatus',
					type: 'select',
					options: [
						{
							'dataName': '未审批',
							'dataCode': '未审批'
						},
						{
							'dataName': '部门审批',
							'dataCode': '部门审批'
						},
						{
							'dataName': '变更审批中',
							'dataCode': '变更审批中'
						},
						{
							'dataName': '打回',
							'dataCode': '打回'
						},
						{
							'dataName': '完成',
							'dataCode': '完成'
						}
					]

				},
				{
					name: '签约主体',
					value: 'c.businessBelong',
					type: 'select',
					datacode: 'QYZT'
				}
			]
		}
	});
});

// 执行进度显示
function exeStatusView(cid){
//	showModalDialog(url, '',"dialogWidth:900px;dialogHeight:500px;");
    showModalWin("?model=contract_contract_contract&action=exeStatusView&cid=" + cid);
}