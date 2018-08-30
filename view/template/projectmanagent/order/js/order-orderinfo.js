var show_page = function(page) {
	$("#orderInfoGrid").yxgrid("reload");
};
$(function() {
	//表头按钮数组
	buttonsArr = [{
			name : 'advancedsearch',
			text : "高级搜索",
			icon : 'search',
			action : function(row) {
				showThickboxWin("?model=projectmanagent_order_order&action=search&gridName=orderInfoGrid"
						+ "&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=700")
			}
		},{
		name : 'export',
		text : "合同导出",
		icon : 'excel',
		action : function(row) {
				var searchConditionKey = "";
				var searchConditionVal = "";
				for (var t in $("#orderInfoGrid").data('yxgrid').options.searchParam) {
					if (t != "") {
						searchConditionKey += t;
						searchConditionVal += $("#orderInfoGrid")
								.data('yxgrid').options.searchParam[t];
					}
				}
				var type = $("#signinType").val();
				var state = $("#state").val();
				var ExaStatus = $("#ExaStatus").val();
				var beginDate = $("#orderInfoGrid").data('yxgrid').options.extParam.beginDate;// 开始时间
				var endDate = $("#orderInfoGrid").data('yxgrid').options.extParam.endDate;// 截止时间
				var ExaDT = $("#orderInfoGrid").data('yxgrid').options.extParam.ExaDT;// 建立时间
				var areaNameArr = $("#orderInfoGrid").data('yxgrid').options.extParam.areaNameArr;// 归属区域
				var orderCodeOrTempSearch = $("#orderInfoGrid").data('yxgrid').options.extParam.orderCodeOrTempSearch;// 合同编号
				var prinvipalName = $("#orderInfoGrid").data('yxgrid').options.extParam.prinvipalName;// 合同负责人
				var customerName = $("#orderInfoGrid").data('yxgrid').options.extParam.customerName;// 客户名称
				var orderProvince = $("#orderInfoGrid").data('yxgrid').options.extParam.orderProvince;// 所属省份
				var customerType = $("#orderInfoGrid").data('yxgrid').options.extParam.customerType;// 客户类型
				var orderNatureArr = $("#orderInfoGrid").data('yxgrid').options.extParam.orderNatureArr;// 合同属性
				var isShip = $("#orderInfoGrid").data('yxgrid').options.extParam.DeliveryStatusArr;// 是否有发货记录
				var i = 1;
				var colId = "";
				var colName = "";
				$("#orderInfoGrid_hTable").children("thead").children("tr")
						.children("th").each(function() {
							if ($(this).css("display") != "none"
									&& $(this).attr("colId") != undefined) {
								colName += $(this).children("div").html() + ",";
								colId += $(this).attr("colId") + ",";
								i++;
							}
						})
				window
						.open("?model=projectmanagent_order_order&action=exportExcel&colId="
								+ colId
								+ "&colName="
								+ colName
								+ "&type="
								+ type
								+ "&state="
								+ state
								+ "&ExaStatus="
								+ ExaStatus
								+ "&beginDate="
								+ beginDate
								+ "&endDate="
								+ endDate
								+ "&ExaDT="
								+ ExaDT
								+ "&areaNameArr="
								+ areaNameArr
								+ "&orderCodeOrTempSearch="
								+ orderCodeOrTempSearch
								+ "&prinvipalName="
								+ prinvipalName
								+ "&customerName="
								+ customerName
								+ "&orderProvince="
								+ orderProvince
								+ "&customerType="
								+ customerType
								+ "&orderNatureArr="
								+ orderNatureArr
								+ "&isShip="
								+ isShip
								+ "&searchConditionKey="
								+ searchConditionKey
								+ "&searchConditionVal="
								+ searchConditionVal
								+ "&1width=200,height=200,top=200,left=200,resizable=yes")
			}
	}];

	//表头按钮数组
	updateProA = {
		name : 'update',
		text : "更新工作量进度",
		icon : 'add',
		action : function(row) {
				$.ajax({
					type : 'get',
					url : "?model=projectmanagent_order_order&action=updateProjectProcess",
					success : function(data) {
						if (data == 1) {
							alert("更新成功.");
							show_page();
						} else {
							alert("更新失败.失败原因:"+data)
						}
					}

				});
			}
	};
    updateProB = {
		name : 'update',
		text : "更新业务编号",
		icon : 'add',
		action : function(row) {
				showThickboxWin("?model=common_contract_allsource&action=toUpdateObjCode"
						+ "&placeValuesBefore&TB_iframe=true&modal=false&height=150&width=600")
			}
	};
	updateProC = {
		name : 'update',
		text : "更新合同状态",
		icon : 'add',
		action : function(row) {
				if(confirm("更新数据可能会较慢，点击确定后请稍作等待,不要关闭浏览器。确定更新吗？")){
				   $.ajax({
						type : 'get',
						url : "?model=projectmanagent_order_order&action=updateContractState",
						success : function(data) {
							if (data == 1) {
								alert("更新成功.");
								show_page();
							} else {
								alert("更新失败.失败原因:"+data)
							}
						}
					});
				}
			}
	};
	HTDR = {
		name : 'update',
		text : "合同导入",
		icon : 'excel',
		action : function(row) {
				showThickboxWin("?model=projectmanagent_order_order&action=toExcel"
						+ "&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=600")
			}
	};
//	HTDC = {
//		name : 'export',
//		text : "合同导出",
//		icon : 'excel',
//		action : function(row) {
//				var searchConditionKey = "";
//				var searchConditionVal = "";
//				for (var t in $("#orderInfoGrid").data('yxgrid').options.searchParam) {
//					if (t != "") {
//						searchConditionKey += t;
//						searchConditionVal += $("#orderInfoGrid")
//								.data('yxgrid').options.searchParam[t];
//					}
//				}
//				var type = $("#tablename").val();
//				var state = $("#state").val();
//				var ExaStatus = $("#ExaStatus").val();
//				var beginDate = $("#orderInfoGrid").data('yxgrid').options.extParam.beginDate;// 开始时间
//				var endDate = $("#orderInfoGrid").data('yxgrid').options.extParam.endDate;// 截止时间
//				var ExaDT = $("#orderInfoGrid").data('yxgrid').options.extParam.ExaDT;// 建立时间
//				var areaNameArr = $("#orderInfoGrid").data('yxgrid').options.extParam.areaNameArr;// 归属区域
//				var orderCodeOrTempSearch = $("#orderInfoGrid").data('yxgrid').options.extParam.orderCodeOrTempSearch;// 合同编号
//				var prinvipalName = $("#orderInfoGrid").data('yxgrid').options.extParam.prinvipalName;// 合同负责人
//				var customerName = $("#orderInfoGrid").data('yxgrid').options.extParam.customerName;// 客户名称
//				var orderProvince = $("#orderInfoGrid").data('yxgrid').options.extParam.orderProvince;// 所属省份
//				var customerType = $("#orderInfoGrid").data('yxgrid').options.extParam.customerType;// 客户类型
//				var orderNatureArr = $("#orderInfoGrid").data('yxgrid').options.extParam.orderNatureArr;// 合同属性
//				var isShip = $("#orderInfoGrid").data('yxgrid').options.extParam.DeliveryStatusArr;// 是否有发货记录
//				var i = 1;
//				var colId = "";
//				var colName = "";
//				$("#orderInfoGrid_hTable").children("thead").children("tr")
//						.children("th").each(function() {
//							if ($(this).css("display") != "none"
//									&& $(this).attr("colId") != undefined) {
//								colName += $(this).children("div").html() + ",";
//								colId += $(this).attr("colId") + ",";
//								i++;
//							}
//						})
//				window
//						.open("?model=projectmanagent_order_order&action=exportExcel&colId="
//								+ colId
//								+ "&colName="
//								+ colName
//								+ "&type="
//								+ type
//								+ "&state="
//								+ state
//								+ "&ExaStatus="
//								+ ExaStatus
//								+ "&beginDate="
//								+ beginDate
//								+ "&endDate="
//								+ endDate
//								+ "&ExaDT="
//								+ ExaDT
//								+ "&areaNameArr="
//								+ areaNameArr
//								+ "&orderCodeOrTempSearch="
//								+ orderCodeOrTempSearch
//								+ "&prinvipalName="
//								+ prinvipalName
//								+ "&customerName="
//								+ customerName
//								+ "&orderProvince="
//								+ orderProvince
//								+ "&customerType="
//								+ customerType
//								+ "&orderNatureArr="
//								+ orderNatureArr
//								+ "&isShip="
//								+ isShip
//								+ "&searchConditionKey="
//								+ searchConditionKey
//								+ "&searchConditionVal="
//								+ searchConditionVal
//								+ "&1width=200,height=200,top=200,left=200,resizable=yes")
//			}
//	};
	HTWLDR = {
		name : 'importOrderPro',
		text : "合同物料导入",
		icon : 'excel',
		action : function(row) {
				showThickboxWin("?model=projectmanagent_order_order&action=toImportOrderPro"
						+ "&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=600")
			}
	};
	CWJEDR = {
		name : 'import',
		text : "财务金额导入",
		icon : 'excel',
		action : function(row) {
				showThickboxWin("?model=projectmanagent_order_order&action=FinancialImportexcel"
						+ "&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=600")
			}
	};
	$.ajax({
		type : 'POST',
		url : '?model=projectmanagent_order_order&action=getLimits',
		data : {
			'limitName' : '更新权限'
		},
		async : false,
		success : function(data) {
			if (data == 1) {
				buttonsArr.push(updateProA);
				buttonsArr.push(updateProB);
				buttonsArr.push(updateProC);

			}
		}
	});
	$.ajax({
		type : 'POST',
		url : '?model=projectmanagent_order_order&action=getLimits',
		data : {
			'limitName' : '合同导入'
		},
		async : false,
		success : function(data) {
			if (data == 1) {
				buttonsArr.push(HTDR);
			}
		}
	});
//	$.ajax({
//		type : 'POST',
//		url : '?model=projectmanagent_order_order&action=getLimits',
//		data : {
//			'limitName' : '合同导出'
//		},
//		async : false,
//		success : function(data) {
//			if (data == 1) {
//				buttonsArr.push(HTDC);
//			}
//		}
//	});
	$.ajax({
		type : 'POST',
		url : '?model=projectmanagent_order_order&action=getLimits',
		data : {
			'limitName' : '合同物料导入'
		},
		async : false,
		success : function(data) {
			if (data == 1) {
				buttonsArr.push(HTWLDR);
			}
		}
	});
	$.ajax({
		type : 'POST',
		url : '?model=projectmanagent_order_order&action=getLimits',
		data : {
			'limitName' : '财务金额导入'
		},
		async : false,
		success : function(data) {
			if (data == 1) {
				buttonsArr.push(CWJEDR);
			}
		}
	});
	$("#orderInfoGrid").yxgrid({
		model : 'projectmanagent_order_order',
		action : 'OrderInfoJson',
		param : {
			'states' : '1,2,3,4,5,6'
		},

		title : '合同信息',

		/**
		 * 是否显示查看按钮/菜单
		 *
		 * @type Boolean
		 */
		isViewAction : false,
		/**
		 * 是否显示修改按钮/菜单
		 *
		 * @type Boolean
		 */
		isEditAction : false,
		/**
		 * 是否显示删除按钮/菜单
		 *
		 * @type Boolean
		 */
		isDelAction : false,
		showcheckbox : false,
		isAddAction : false,
		customCode : 'orderinfo',
		// 扩展右键菜单

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
		}, {
			text : '导出',
			icon : 'add',
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
		}, {
			text : '完成合同',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.com == 1 && row.state == 2) {
					return true;
				}
				return false;
			},
			action : function(row) {
				if (window.confirm(("确定要把合同状态改为 “完成” 状态吗？"))) {
					$.ajax({
						type : "POST",
						url : "?model=projectmanagent_order_order&action=completeOrder&id="
								+ row.orgid + "&type=" + row.tablename,
						success : function(msg) {
							$("#orderInfoGrid").yxgrid("reload");
						}
					});
				}
			}
		}, {
			text : '执行合同',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.com == 1 && row.state == 4) {
					return true;
				}
				return false;
			},
			action : function(row) {
				if (window.confirm(("确定要把合同状态改为 “执行中” 状态吗？"))) {
					$.ajax({
						type : "POST",
						url : "?model=projectmanagent_order_order&action=exeOrder&id="
								+ row.orgid + "&type=" + row.tablename,
						success : function(msg) {
							if (msg == '0') {
								alert("合同发货已完成，请选择变更流程");
								$("#orderInfoGrid").yxgrid("reload");
							} else {
								$("#orderInfoGrid").yxgrid("reload");
							}

						}
					});
				}
			}
		}],

		// 列信息
		colModel : [{
					display : 'id',
					name : 'id',
					sortable : true,
					hide : true
				}, {
					name : 'createTime',
					display : '建立时间',
					sortable : true,
					width : 80
				}, {
					name : 'tablename',
					display : '合同类型',
					sortable : true,
					width : 60,
					process : function(v) {
						if (v == 'oa_sale_order') {
							return "销售合同";
						} else if (v == 'oa_sale_service') {
							return "服务合同";
						} else if (v == 'oa_sale_lease') {
							return "租赁合同";
						} else if (v == 'oa_sale_rdproject') {
							return "研发合同";
						} else if (v == '') {
							return "金额合计";
						}
					}
				}, {
					name : 'signinType',
					display : '签收类型',
					sortable : true,
					width : 60,
					process : function(v) {
						if (v == 'order') {
							return "销售合同";
						} else if (v == 'service') {
							return "服务合同";
						} else if (v == 'lease') {
							return "租赁合同";
						} else if (v == 'rdproject') {
							return "研发合同";
						}
					}
				}, {
					name : 'orderNatureName',
					display : '合同属性',
					sortable : true,
					width : 60
				}, {
					name : 'orderCode',
					display : '鼎利合同号',
					sortable : true,
					width : 180,
					process : function(v, row) {
						if (row.isR == 1) {
							return "<font color = '#0000FF'>" + v;
						} else if (row.isBecome == 1) {
							return "<font color = '#FF0000'>" + v;
						} else {
							return v;
						}
					}
				}, {
					name : 'orderTempCode',
					display : '临时合同号',
					sortable : true,
					width : 180,
					process : function(v, row) {
						if (row.isR == 1) {
							return "<font color = '#0000FF'>" + v;
						} else if (row.isBecome == 1) {
							return "<font color = '#FF0000'>" + v;
						} else {
							return v;
						}
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
					name : 'orderName',
					display : '合同名称',
					sortable : true,
					width : 150
				}, {
					name : 'signIn',
					display : '签收状态',
					sortable : true,
					process : function(v) {
						if (v == '0') {
							return "未签收";
						} else if (v == '1') {
							return "已签收";
						}
					},
					width : 80
				}, {
					name : 'orderTempMoney',
					display : '预计合同金额',
					sortable : true,
					width : 80,
					process : function(v, row) {
						if (row.orderMoney == '' || row.orderMoney == 0.00
								|| row.id == 'allMoney') {
							return moneyFormat2(v);
						} else {
							return "<font color = '#B2AB9B'>" + moneyFormat2(v)
									+ "</font>";
						}

					}
				}, {
					name : 'orderMoney',
					display : '签约合同金额',
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
						if (row.id == "allMoney" || row.id == undefined) {
							return moneyFormat2(v);
						}
						if (row.orderMoney == '******'
								|| row.orderTempMoney == '******') {
							return "******";
						} else if (v == '') {
							return "0.00";
						} else {
							return moneyFormat2(v);
						}
					}
				}, {
					name : 'surplusInvoiceMoney',
					display : '剩余开票金额',
					sortable : true,
					process : function(v, row) {
						if (row.orderMoney == '******'
								|| row.orderTempMoney == '******') {
							return "******";
						} else {
							return "<font color = 'blue'>" + moneyFormat2(v);
							+"</font>"
						}
					}
				}, {
					name : 'incomeMoney',
					display : '已收金额',
					width : 60,
					sortable : true,
					process : function(v, row) {
						if (row.id == "allMoney" || row.id == undefined) {
							return moneyFormat2(v);
						}
						if (row.orderMoney == '******'
								|| row.orderTempMoney == '******') {
							return "******";
						} else if (v == '') {
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
						if (row.id == "allMoney" || row.id == undefined) {
							return moneyFormat2(v);
						}
						if (row.orderMoney == '******'
								|| row.orderTempMoney == '******') {
							return "******";
						} else if (v != '') {
							return "<font color = 'blue'>" + moneyFormat2(v);
							+"</font>"
						}
					}
				}, {
					name : 'surincomeMoney',
					display : '财务应收账款余额',
					sortable : true,
					process : function(v, row) {
						if (row.id == "allMoney" || row.id == undefined) {
							return moneyFormat2(v);
						}
						if (row.orderMoney == '******'
								|| row.orderTempMoney == '******') {
							return "******";
						} else if (v != '') {
							return "<font color = 'blue'>" + moneyFormat2(v);
							+"</font>"
						} else {
							return "<font color = 'blue'>"
									+ moneyFormat2(accSub(row.invoiceMoney,
											row.incomeMoney, 2)) + "</font>"
						}
					}
				}, {
					name : 'ExaStatus',
					display : '审批状态',
					sortable : true,
					width : 60
				}, {
					name : 'sign',
					display : '是否签约',
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
					name : 'orderProvince',
					display : '省份',
					sortable : true,
					width : 60
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
						}
					},
					width : 60
				}, {
					name : 'softMoney',
					display : '软件金额',
					width : 80,
					sortable : true,
					process : function(v, row) {
						if (row.id == "allMoney" || row.id == undefined) {
							return moneyFormat2(v);
						}
						if (row.orderMoney == '******'
								|| row.orderTempMoney == '******') {
							return "******";
						} else if (v == '') {
							return "0.00";
						} else {
							return moneyFormat2(v);
						}
					}
				}, {
					name : 'hardMoney',
					display : '硬件金额',
					width : 80,
					sortable : true,
					process : function(v, row) {
						if (row.id == "allMoney" || row.id == undefined) {
							return moneyFormat2(v);
						}
						if (row.orderMoney == '******'
								|| row.orderTempMoney == '******') {
							return "******";
						} else if (v == '') {
							return "0.00";
						} else {
							return moneyFormat2(v);
						}
					}
				}, {
					name : 'serviceMoney',
					display : '服务金额',
					width : 80,
					sortable : true,
					process : function(v, row) {
						if (row.id == "allMoney" || row.id == undefined) {
							return moneyFormat2(v);
						}
						if (row.orderMoney == '******'
								|| row.orderTempMoney == '******') {
							return "******";
						} else if (v == '') {
							return "0.00";
						} else {
							return moneyFormat2(v);
						}
					}
				}, {
					name : 'repairMoney',
					display : '维修金额',
					width : 80,
					sortable : true,
					process : function(v, row) {
						if (row.id == "allMoney" || row.id == undefined) {
							return moneyFormat2(v);
						}
						if (row.orderMoney == '******'
								|| row.orderTempMoney == '******') {
							return "******";
						} else if (v == '') {
							return "0.00";
						} else {
							return moneyFormat2(v);
						}
					}
				}
				// , {
				// name : 'productConfirmMoney',
				// display : '产品确认收入',
				// sortable : true,
				// width : 80,
				// process : function(v,row) {
				// if(row.id == "allMoney"){
				// return "";
				// }
				// if(row.FinanceCon == '1'){
				// return moneyFormat2(accAdd(row.softMoney,row.hardMoney));
				// }else{
				// return "******";
				// }
				// }
				// }
				, {
					name : 'serviceconfirmMoneyAll',
					display : '财务确认总收入',
					sortable : true,
					width : 80,
					process : function(v, row) {
						if (row.id == "allMoney" || row.id == undefined) {
							if (row.FinanceCon == '1') {
								return moneyFormat2(v);
							} else {
								return "******";
							}
						}
						if (row.FinanceCon == '1') {
							return '<a href="javascript:void(0)" onclick="javascript:showThickboxWin(\'?model=projectmanagent_order_order&action=financialDetailTab&id='
									+ row.orgid
									+ '&tablename='
									+ row.tablename
									+ '&moneyType=serviceconfirmMoney'
									+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=1000\')">'
									+ "<font color = '#4169E1'>"
									+ moneyFormat2(v) + "</font>" + '</a>';
						} else {
							return "******";
						}

					}
				}

				// , {
				// name : 'financeconfirmAmout',
				// display : '财务确认总成本',
				// sortable : true,
				// width : 80,
				// process : function(v,row) {
				// if(row.id == "allMoney"){
				// return "";
				// }
				// if(row.FinanceCon == '1'){
				// return
				// moneyFormat2(accAdd(row.serviceconfirmMoney,accAdd(row.softMoney,row.hardMoney)));
				// }else{
				// return "******";
				// }
				// }
				// }
				, {
					name : 'financeconfirmPlan',
					display : '财务确认进度',
					sortable : false,
					width : 80,
					process : function(v, row) {
						if (row.id == "allMoney" || row.id == undefined) {
							return "";
						}
						if (row.FinanceCon == '1') {
							if (row.orderMoney != '0.00') {
								var contractMoney = row.orderMoney;
							} else {
								var contractMoney = row.orderTempMoney;
							}
							var financePlan = (row.serviceconfirmMoney)
									/ (accSub(contractMoney, row.deductMoney));
							if (isNaN(financePlan)) {
								return "0.00%";
							} else {
								financePlan = parseFloat(financePlan).toFixed(2);
								return financePlan * 100 + "%";
							}
						} else {
							return "******";
						}
					}
				}, {
					name : 'financeconfirmMoneyAll',
					display : '财务确认总成本',
					sortable : true,
					width : 80,
					process : function(v, row) {
						if (row.id == "allMoney" || row.id == undefined) {
							if (row.FinanceCon == '1') {
								return moneyFormat2(v);
							} else {
								return "******";
							}
						} else {
							if (row.FinanceCon == '1') {
								return '<a href="javascript:void(0)" onclick="javascript:showThickboxWin(\'?model=projectmanagent_order_order&action=financialDetailTab&id='
										+ row.orgid
										+ '&tablename='
										+ row.tablename
										+ '&moneyType=financeconfirmMoney'
										+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=1000\')">'
										+ "<font color = '#4169E1'>"
										+ moneyFormat2(v) + "</font>" + '</a>';
							} else {
								return "******";
							}
						}
					}
				}, {
					name : 'gross1',
					display : '毛利',
					sortable : true,
					width : 80,
					process : function(v, row) {
						if (row.id == "allMoney" || row.id == undefined) {
							return "";
						}
						if (row.FinanceCon == '1') {
							if (isNaN(v) || !v) {
								return v;
							} else {
								return moneyFormat2(v);
							}

						} else {
							return "******";
						}
					}
				}, {
					name : 'rateOfGross1',
					display : '毛利率',
					sortable : true,
					width : 80,
					process : function(v, row) {
						if (row.id == "allMoney" || row.id == undefined) {
							return "";
						}
						if (row.FinanceCon == '1') {
							if (isNaN(v) || !v) {
								return v;
							} else {
								return v + "%";
							}
						} else {
							return "******";
						}
					}
				},
				// {
				// name : 'gross',
				// display : '毛利',
				// sortable : true,
				// width : 80,
				// process : function(v, row) {
				// if (row.id == "allMoney" || row.id == undefined) {
				// return "";
				// }
				// if (row.FinanceCon == '1') {
				// return moneyFormat2(v);
				// } else {
				// return "******";
				// }
				// }
				// }, {
				// name : 'rateOfGross',
				// display : '毛利率',
				// sortable : true,
				// width : 80,
				// process : function(v, row) {
				// if (row.id == "allMoney" || row.id == undefined) {
				// return "";
				// }
				// if (row.FinanceCon == '1') {
				// if (v == '') {
				// return "0.00%";
				// }
				// return v + "%";
				// } else {
				// return "******";
				// }
				// }
				// },
				{
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
							if (row.FinanceCon == '1') {
								return moneyFormat2(v);
							} else {
								return "******";
							}
						}
						if (row.FinanceCon == '1') {
                            if (v == "") {
							  return "0.00";
						    }
							  return moneyFormat2(v);
						} else {
							return "******";
						}
					}
				}, {
					name : 'projectProcess',
					display : '工作量进度',
					sortable : true,
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
					sortable : true,
					width : 80,

					process : function(v, row) {
						if (row.id == "allMoney" || row.id == undefined) {
							 moneyFormat2(v);
						}
						if (v == "") {
							return "0.00";
						}
						return moneyFormat2(v);
					}
				}, {
					name : 'deductMoney',
					display : '扣款金额',
					sortable : true,
					width : 80,
					process : function(v, row) {
						if (row.id == "allMoney" || row.id == undefined) {
							return moneyFormat2(v);
						}
						if (v == '') {
							return "0.00";
						} else {
							return '<a href="javascript:void(0)" onclick="javascript:showThickboxWin(\'?model=projectmanagent_order_order&action=financialDetailTab&id='
									+ row.orgid
									+ '&tablename='
									+ row.tablename
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
					name : 'objCode',
					display : '业务编号',
					sortable : true,
					width : 120
				}, {
					name : 'completeDate',
//					name : "if(c.signinType='service' or c.state != 4,'-',c.completeDate)",
					display : '合同完成日期',
					sortable : true,
					width : 80,
					process : function(v,row){
					   if(v == ''){
						    return "-";
						  }
						    return v ;
					}
				}, {
					name : 'exeDate',
					display : '合同执行时间',
					sortable : true,
					width : 80,
					process : function(v, row) {
						if (row.id == "allMoney" || row.id == undefined) {
							return "";
						}
						if(row.state != '4' && row.state != '3'){
							if(v == '' || v == '-'){
						      return "-";
						    }
						    return "<span class='red'>" + v + "天</span>";
						}else{
						  if(v == '' || v == '-'){
						    return "-";
						  }
						    return v + "天";
						}
					}
				}, {
					name : "invoiceDifferenceTemp",
//					name : "if(c.signinType='service',(c.processMoney-i.invoiceMoney),'-')",
//					name : 'invoiceDifference',
					display : '开票与执行差异',
					sortable : true,
					width : 80,
					process : function(v, row) {
						if (row.id == "allMoney" || row.id == undefined) {
							return "";
						}
							if(v=='0.00'){
							   return "-";
							}else{
							  if (isNaN(v) || !v) {
									return v;
								} else {
									var m = v * 100;
									return moneyFormat2(m) + "%";
								}
							}

					}
				}, {
					name : 'AffirmincomeDifference',
					display : '确认收入与执行差异',
					sortable : true,
					width : 100,
					process : function(v, row) {
						if (row.id == "allMoney" || row.id == undefined) {
							return "";
						}
						if (row.FinanceCon == '1') {
							if(v=='0.00'){
							  return "-";
							}else{
							  if (isNaN(v) || !v) {
									return v;
								} else {
									return v + "%";
								}
							}
						} else {
							return "******";
						}
					}
				}],
		buttonsEx : buttonsArr,
		comboEx : [{
					text : '类型',
					key : 'signinType',
					data : [{
								text : '销售合同',
								value : 'order'
							}, {
								text : '租赁合同',
								value : 'lease'
							}, {
								text : '服务合同',
								value : 'service'
							}, {
								text : '研发合同',
								value : 'rdproject'
							}]
				}, {
					text : '合同状态',
					key : 'state',
					data : [{
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
							}]
				}, {
					text : '审批状态',
					key : 'ExaStatus',
					value : '完成',
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
					name : 'orderCodeOrTempSearch'
				}, {
					display : '合同名称',
					name : 'orderName'
				}, {
					display : '客户名称',
					name : 'customerName'
				}, {
					display : '业务编号',
					name : 'objCode'
				}],
		sortname : "isBecome desc,ExaDT"
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
			// }
			// sortorder : "DESC"

	});
		// $("#orderInfoGrid").yxgrid("createAdvSearchWin");
});