var show_page = function(page) {
	$("#myOrderInfoGrid").yxgrid("reload");
};
$(function() {
	$("#myOrderInfoGrid").yxgrid({
		model : 'projectmanagent_order_order',
		action : 'myOrderJson',
		title : '我的合同',
		param : {
			'prinvipalId' : $("#user").val()
		},
		isDelAction : false,
		isToolBar : false, //是否显示工具栏
		showcheckbox : false,
        customCode : 'myOrderInfo',
		//列信息
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'tablename',
			display : '合同类型',
			sortable : true,
			process : function(v) {
				if (v == 'oa_sale_order') {
					return "销售合同";
				} else if (v == 'oa_sale_service') {
					return "服务合同";
				} else if (v == 'oa_sale_lease') {
					return "租赁合同";
				} else if (v == 'oa_sale_rdproject') {
					return "研发合同";
				}
			}
		}, {
			name : 'orderCode',
			display : '鼎利合同号',
			sortable : true,
			width : 210
		}, {
			name : 'orderTempCode',
			display : '临时合同号',
			sortable : true,
			width : 210
		}, {
			name : 'orderName',
			display : '合同名称',
			sortable : true,
			width : 210
		}, {
			name : 'customerName',
			display : '客户名称',
			sortable : true,
			width : 150
		}, {
			name : 'orderTempMoney',
			display : '预计合同金额',
			sortable : true,
			width : 100,
			process : function(v) {
				return moneyFormat2(v);
			}
		}, {
			name : 'orderMoney',
			display : '签约合同金额',
			sortable : true,
			width : 100,
			process : function(v) {
				return moneyFormat2(v);
			}
		}, {
			name : 'invoiceMoney',
			display : '开票金额',
			width : 80,
			process : function(v) {
				return moneyFormat2(v);
			}
		}, {
			name : 'incomeMoney',
			display : '已收金额',
			process : function(v) {
				return moneyFormat2(v);
			}
		}, {
			name : 'surOrderMoney',
			display : '签约合同应收账款余额',
			sortable : false,
			width : 120,
			process : function(v, row) {
				return "<font color = 'blue'>" + moneyFormat2(v) + "</font>"
			}
		}, {
			name : 'surincomeMoney',
			display : '财务应收账款余额',
			sortable : false,
			process : function(v, row) {
				return "<font color = 'blue'>" + moneyFormat2(v) + "</font>"
			}
		},{
					name : 'budgetAll',
					display : '总预算',
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
					name : 'budgetOutsourcing',
					display : '外包费用预算',
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
					name : 'feeFieldCount',
					display : '现场费用(实时)',
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
					name : 'feeOutsourcing',
					display : '外包费用(实时)',
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
					name : 'feeAll',
					display : '总费用(实时)',
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
					name : 'projectProcess',
					display : '工作量进度',
					sortable : false,
					width : 80,
					process : function(v, row) {
						if (row.id == "allMoney" || row.id == undefined) {
							return "";
						}
						if (v == "") {
							return "0.00%";
						}
						return v + "%";
					}
				}, {
					name : 'processMoney',
					display : '按工作量执行合同额',
					sortable : false,
					width : 80,

					process : function(v,row) {
						if(row.id == "allMoney" || row.id==undefined){
						   return "";
						}
						if (v == "") {
							return "0.00";
						}
						return moneyFormat2(v);
					}
				}, {
			name : 'areaName',
			display : '归属区域',
			sortable : true,
			width : 100
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
				} else if (v == '5') {
					return "已拆分";
				}
			},
			width : 90
		}, {
			name : 'ExaStatus',
			display : '审批状态',
			sortable : true,
			width : 100
		}, {
			name : 'sign',
			display : '是否签约',
			sortable : true,
			width : 70
		}, {
			name : 'orderstate',
			display : '纸质合同状态',
			sortable : true,
			width : 100
		}, {
			name : 'parentOrder',
			display : '父合同名称',
			sortable : true,
			hide : true
		}],
		buttonsEx : [{
			name : 'export',
			text : "合同导出",
			icon : 'excel',
			action : function(row) {
				var type = $("#tablename").val();
				var state = $("#state").val();
				var ExaStatus = $("#ExaStatus").val();
				var i = 1;
				var colId = "";
				var colName = "";
				$("#myOrderInfoGrid_hTable").children("thead").children("tr")
						.children("th").each(function() {
							if ($(this).css("display") != "none"
									&& $(this).attr("colId") != undefined) {
								colName += $(this).children("div").html() + ",";
								colId += $(this).attr("colId") + ",";
								i++;
							}
						})
				window
						.open("?model=projectmanagent_order_order&action=myExportExcel&colId="
								+ colId
								+ "&colName="
								+ colName
								+ "&type="
								+ type
								+ "&state="
								+ state
								+ "&ExaStatus="
								+ ExaStatus
								+ "&1width=200,height=200,top=200,left=200,resizable=yes")
			}
		}],

		comboEx : [{
			text : '合同类型',
			key : 'tablename',
			data : [{
				text : '销售合同',
				value : 'oa_sale_order'
			}, {
				text : '租赁合同',
				value : 'oa_sale_lease'
			}, {
				text : '服务合同',
				value : 'oa_sale_service'
			}, {
				text : '研发合同',
				value : 'oa_sale_rdproject'
			}]
		}, {
			text : '合同状态',
			key : 'state',
			data : [{
				text : '未提交',
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
				text : '打回',
				value : '打回'
			}, {
				text : '完成',
				value : '完成'
			}]
		}],
		menusEx : [{
			text : '查看',
			icon : 'view',
			action : function(row) {
				if (row.tablename == 'oa_sale_order') {
					showOpenWin('?model=projectmanagent_order_order&action=toViewTab&id='
							+ row.orgid + "&skey=" + row['skey_']);
				} else if (row.tablename == 'oa_sale_service') {
					showOpenWin("?model=engineering_serviceContract_serviceContract&action=toViewTab&id="
							+ row.orgid + "&skey=" + row['skey_'])
				} else if (row.tablename == 'oa_sale_lease') {
					showOpenWin("?model=contract_rental_rentalcontract&action=toViewTab&id="
							+ row.orgid + "&skey=" + row['skey_'])
				} else if (row.tablename == 'oa_sale_rdproject') {
					showOpenWin("?model=rdproject_yxrdproject_rdproject&action=toViewTab&id="
							+ row.orgid + "&skey=" + row['skey_'])
				}

			}
		}
				//		   ,{
				//				text : '审批情况',
				//				icon : 'view',
				//	            showMenuFn : function (row) {
				//	               if (row.ExaStatus=='保存'){
				//	                   return true;
				//	               }
				//	                   return true;
				//	            },
				//				action : function(row) {
				//                     if(row.tablename == 'oa_sale_order'){
				//				         showThickboxWin('controller/projectmanagent/order/readview.php?itemtype=oa_sale_order&pid='
				//							+ row.orgid
				//							+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=600');
				//					  } else if (row.tablename == 'oa_sale_service'){
				//					     showThickboxWin('controller/engineering/serviceContract/readview.php?itemtype=oa_sale_service&pid='
				//							+ row.orgid
				//							+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=600');
				//	                  } else if (row.tablename == 'oa_sale_lease'){
				//	                     showThickboxWin('controller/contract/rental/readview.php?itemtype=oa_sale_lease&pid='
				//							+ row.orgid
				//							+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=600');
				//	                  } else if (row.tablename == 'oa_sale_rdproject') {
				//	                     showThickboxWin('controller/rdproject/yxrdproject/readview.php?itemtype=oa_sale_rdproject&pid='
				//							+ row.orgid
				//							+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=600');
				//	                  }
				//
				//				}
				//			}
		, {
			text : '导出',
			icon : 'add',
//			showMenuFn : function (row){
//				   if(row.exportOrder == 1){
//				       return true;
//				   }
//				       return false;
//				},
			action : function(row) {
				window
						.open('?model=contract_common_allcontract&action=importCont&id='
								+ row.orgid
								+ '&type='
								+ row.tablename
								+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=600');
			}
		}, {
			text : '附件上传',
			icon : 'add',
			action : function(row) {
				showThickboxWin('?model=projectmanagent_order_order&action=toUploadFile&id='
						+ row.orgid
						+ '&type='
						+ row.tablename
						+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=200&width=600');
			}
		}, {
			text : '物料修改',
			icon : 'edit',
			action : function(row) {
				if (row.tablename == 'oa_sale_order') {
					showOpenWin('?model=projectmanagent_order_order&action=productedit&id='
							+ row.orgid + "&skey=" + row['skey_']);
				} else if (row.tablename == 'oa_sale_service') {
					showOpenWin("?model=engineering_serviceContract_serviceContract&action=productedit&id="
							+ row.orgid + "&skey=" + row['skey_'])
				} else if (row.tablename == 'oa_sale_lease') {
					showOpenWin("?model=contract_rental_rentalcontract&action=productedit&id="
							+ row.orgid + "&skey=" + row['skey_'])
				} else if (row.tablename == 'oa_sale_rdproject') {
					showOpenWin("?model=rdproject_yxrdproject_rdproject&action=productedit&id="
							+ row.orgid + "&skey=" + row['skey_'])
				}

			}
		}],
		/**
		 * 快速搜索
		 */
		searchitems : [{
			display : '合同名称',
			name : 'orderName'
		}, {
			display : '合同编号',
			name : 'orderCodeOrTempSearch'
		}],
		//		sortname : "createTime",
		//设置新增页面宽度
		toAddConfig : {
			formHeight : 500,
			formWidth : 900
		},
		//设置编辑页面宽度
		toEditConfig : {
			formHeight : 500,
			formWidth : 900
		},
		//设置查看页面宽度
		toViewConfig : {
			formHeight : 500,
			formWidth : 900
		}

	});
});