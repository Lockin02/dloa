var show_page = function(page) {
	$("#mycontractGrid").yxgrid("reload");
};
$(function() {
	$("#mycontractGrid").yxgrid({
		model : 'contract_contract_contract',
		action : 'MyconPageJson',
		title : '合同主表',
		param : {
			'states' : '0,1,2,3,4,5,6,7',
			'mycontractArr' : $("#userId").val(),
			'isTemp' : '0'
		},

		title : '合同信息',
		isViewAction : false,
		isEditAction : false,
		isDelAction : false,
		showcheckbox : false,
		isAddAction : false,
		customCode : 'mycontractA',
		// 扩展右键菜单
		menusEx : [{
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
				if (row && (row.ExaStatus == '未审批' || row.ExaStatus == '打回') && row.isSubApp == '0') {
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
			text : '提交审批',
			icon : 'add',
			showMenuFn : function(row) {
				if (row && (row.ExaStatus == '未审批' || row.ExaStatus == '打回') && row.isSubApp == '0') {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				if (window.confirm(("确定提交审批吗?"))) {
					if (row.isEngConfirm == '1' || row.isSaleConfirm == '1' || row.isRdproConfirm == '1') {
                         if(row.isSubApp == "1"){
                           alert("已提交确认成本概算！");
                           $("#mycontractGrid").yxgrid("reload");
                         }else{
                           $.ajax({
							type : "POST",
							url : "?model=contract_contract_contract&action=ajaxSubApp",
							data : {
								id : row.id
							},
							success : function(msg) {
								if (msg) {
								   alert("已提交确认成本概算！");
								   $("#mycontractGrid").yxgrid("reload");
								}
							}
						});
                         }
					} else {
						$.ajax({
							type : "POST",
							url : "?model=contract_contract_contract&action=ajaxFlowDeptIds",
							data : {
								id : row.id
							},
							success : function(msg) {
								if (msg) {
									if (row.winRate == '50%') {
										showThickboxWin('controller/contract/contract/ewf_index_50_list.php?actTo=ewfSelect&billId='
												+ row.id
												+ '&billDept='
												+ msg
												+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=750");
									} else {
										showThickboxWin('controller/contract/contract/ewf_index_Other_list.php?actTo=ewfSelect&billId='
												+ row.id
												+ '&billDept='
												+ msg
												+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=750");
									}
								}
							}
						});
					}
				}
			}
		},{
				name : 'cancel',
				text : '撤销审批',
				icon : 'edit',
				showMenuFn : function(row) {
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
								}else{
									alert(msg)
									switch(msg){
										case '合同审批A' : var url = 'controller/contract/contract/ewf_index_50.php?actTo=delWork&billId=';break;
										case '合同审批B' : var url = 'controller/contract/contract/ewf_index_Other.php?actTo=delWork&billId=';break;
										case '合同审批TA' :var url = 'controller/contract/contract/ewf_index_50_list_temp.php?actTo=delWork&billId=';break;
										case '合同审批TB' :var url = 'controller/contract/contract/ewf_index_Other_list_temp.php?actTo=delWork&billId=';break;
									}
									$.ajax({
										    type: "GET",
										    url: url,
										    data: {"billId" : row.id },
										    async: false,
										    success: function(data){
										    	alert(data)
										    	show_page();
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
		}, {
			text : '导出',
			icon : 'add',
			action : function(row) {
				window
						.open('?model=contract_common_relcontract&action=importCont&id='
								+ row.id
								+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=600');
			}
		}, {
			text : '删除',
			icon : 'delete',
			showMenuFn : function(row) {
				if (row && (row.state == '0' || row.ExaStatus == '打回') && row.isSubApp == '0') {
					return true;
				}
				return false;
			},
			action : function(row) {
				if (window.confirm(("确定要删除?"))) {
					$.ajax({
						type : "POST",
						url : "?model=contract_contract_contract&action=ajaxdeletes",
						data : {
							id : row.id
						},
						success : function(msg) {
							if (msg == 1) {
								alert('删除成功！');
								$("#mycontractGrid").yxgrid("reload");
							} else {
								alert('删除失败! ');
							}
						}
					});
				}
			}
		}, {
			text : '开票申请',
			icon : 'add',
			showMenuFn : function(row) {
				if (row && (row.state == '2' || row.state == '4')) {
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
				if (row && (row.state == '2' || row.state == '4')) {
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
				if (row && row.status == 3) {
					return false;
				}
				if (row && (row.ExaStatus == "完成" && row.isStamp != "1"))
					return true;
				else
					return false;
			},
			action : function(row, rows, grid) {
				if (row) {
					if (row.isNeedStamp == '1') {
						alert('此合同已申请盖章,不能重复申请');
						return false;
					}
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
						+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=200&width=500');
			}
		}, {
			text : '关闭合同',
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
		}],

        lockCol:['flag'],//锁定的列名
		// 列信息
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'flag',
			display : '沟通板',
			sortable : true,
			width : 40,
			process : function(v, row) {
			 if (row.id == "allMoney" || row.id == undefined || row.id == '') {
				 return "合计";
			 }
			  if(v == ''){
			     return '<a href="javascript:void(0)" onclick="javascript:showThickboxWin(\'?model=contract_contract_contract&action=listremark&id='
						+ row.id
						+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=600&width=900\')">'
						+ "<img src='images/icon/icon139.gif' />" + '</a>';
			  }else{
				  return '<a href="javascript:void(0)" onclick="javascript:showThickboxWin(\'?model=contract_contract_contract&action=listremark&id='
						+ row.id
						+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=600&width=900\')">'
						+ "<img src='images/icon/icon095.gif' />" + '</a>';
			  }

			}
		}, {
			name : 'createTime',
			display : '建立时间',
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
			name : 'signSubject',
			display : '签约主体',
			sortable : true,
			datacode : 'QYZT',
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
						if( row.id == undefined ) return moneyFormat2(v);
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
						if( row.id == undefined ) return moneyFormat2(v);
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
					name : 'areaPrincipal',
					display : '区域负责人',
					sortable : true
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
				}],

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
			}, {
				text : '已合并',
				value : '5'
			}, {
				text : '已拆分',
				value : '6'
			}, {
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
		}],
		sortname : "createTime"
			// // 高级搜索
			// advSearchOptions : {
			// modelName : 'orderInfo',
			// // 选择字段后进行重置值操作
			// selectFn : function($valInput) {
			// $valInput.yxcombogrid_area("remove");
			// },
			// searchConfig : [{
			// name : '创建日期',
			// value : 'c.createTime',
			// changeFn : function($t, $valInput) {
			// $valInput.click(function() {
			// WdatePicker({
			// dateFmt : 'yyyy-MM-dd'
			// });
			// });
			// }
			// }, {
			// name : '归属区域',
			// value : 'c.areaPrincipal',
			// changeFn : function($t, $valInput, rowNum) {
			// if (!$("#areaPrincipalId" + rowNum)[0]) {
			// $hiddenCmp = $("<input type='hidden' id='areaPrincipalId"
			// + rowNum + "' value=''>");
			// $valInput.after($hiddenCmp);
			// }
			// $valInput.yxcombogrid_area({
			// hiddenId : 'areaPrincipalId' + rowNum,
			// height : 200,
			// width : 550,
			// gridOptions : {
			// showcheckbox : true
			// }
			// });
			// }
			// }]
			//		}
	});
});