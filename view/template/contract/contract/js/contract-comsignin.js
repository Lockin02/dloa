var show_page = function(page) {
	$("#comsigninGrid").yxgrid("reload");
};

$(function() {
	$("#comsigninGrid").yxgrid({
		model : 'contract_contract_contract',
//		action : 'conPageJson',
		param : {
			'states' : '1,2,3,4,5,6,7',
			'isTemp' : '0',
			'ExaStatus' : '完成',
			'signStatusArr' : '1'
		},

		title : '已签收合同',
		isViewAction : false,
		isEditAction : false,
		isDelAction : false,
		showcheckbox : false,
		isAddAction : false,
		customCode : 'contractSigninCom',
		buttonsEx : [{
			name : 'export',
			text : "导出",
			icon : 'excel',
			action : function(row) {
				var searchConditionKey = "";
				var searchConditionVal = "";
				for (var t in $("#comsigninGrid").data('yxgrid').options.searchParam) {
					if (t != "") {
						searchConditionKey += t;
						searchConditionVal += $("#comsigninGrid")
								.data('yxgrid').options.searchParam[t];
					}
				}
				var state = $("#state").val();
				var ExaStatus = $("#ExaStatus").val();
				var contractType = $("#contractType").val();
				var beginDate = $("#comsigninGrid").data('yxgrid').options.extParam.beginDate;//开始时间
				var endDate = $("#comsigninGrid").data('yxgrid').options.extParam.endDate;//截止时间
				var ExaDT = $("#comsigninGrid").data('yxgrid').options.extParam.ExaDT;//建立时间
				var areaNameArr = $("#comsigninGrid").data('yxgrid').options.extParam.areaNameArr;//归属区域
				var orderCodeOrTempSearch = $("#comsigninGrid").data('yxgrid').options.extParam.orderCodeOrTempSearch;//合同编号
				var prinvipalName = $("#comsigninGrid").data('yxgrid').options.extParam.prinvipalName;//合同负责人
				var customerName = $("#comsigninGrid").data('yxgrid').options.extParam.customerName;//客户名称
				var customerType = $("#comsigninGrid").data('yxgrid').options.extParam.customerType;//客户类型
				var orderNatureArr = $("#comsigninGrid").data('yxgrid').options.extParam.orderNatureArr;//合同属性
				var DeliveryStatusArr = $("#comsigninGrid").data('yxgrid').options.extParam.DeliveryStatusArr;//是否有发货记录
				var i = 1;
				var colId = "";
				var colName = "";
				$("#comsigninGrid_hTable").children("thead").children("tr")
						.children("th").each(function() {
							if ($(this).css("display") != "none"
									&& $(this).attr("colId") != undefined) {
								colName += $(this).children("div").html() + ",";
								colId += $(this).attr("colId") + ",";
								i++;
							}
						})

			window.open("?model=contract_contract_contract&action=singInExportExcel&colId="
								+ colId + "&colName=" + colName+ "&state=" + state + "&ExaStatus=" + ExaStatus + "&contractType=" + contractType
								+ "&beginDate=" + beginDate + "&endDate=" + endDate + "&ExaDT=" + ExaDT
								+ "&areaNameArr=" + areaNameArr + "&orderCodeOrTempSearch=" + orderCodeOrTempSearch
								+ "&prinvipalName=" + prinvipalName + "&customerName=" + customerName
								+ "&customerType=" + customerType
								+ "&orderNatureArr=" + orderNatureArr
								+ "&DeliveryStatusArr=" + DeliveryStatusArr
								+ "&searchConditionKey="
								+ searchConditionKey
								+ "&searchConditionVal="
								+ searchConditionVal
								+ "&signStatusArr=1"
//								+ "&ExaStatus=完成,变更审批中"
//								+ "&states=2,3,4"
								+ "&1width=200,height=200,top=200,left=200,resizable=yes")
			}
		},{
			name : 'advancedsearch',
			text : "高级搜索",
			icon : 'search',
			action : function(row) {
				showThickboxWin("?model=contract_contract_contract&action=search&gridName=comsigninGrid"
						+ "&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=700")
			}
		}],
		// 扩展右键菜单

		menusEx : [{
			text : '查看',
			icon : 'view',
			action : function(row) {
				showModalWin('?model=contract_contract_contract&action=init&perm=view&id='
							+ row.id + "&skey=" + row['skey_']);
			}
		},{
			text : '签收合同',
			icon : 'add',
			showMenuFn : function(row) {
				if (row.isAcquiring == '1') {
					return true;
				}
				return false;
			},
			action : function(row) {
				showModalWin('?model=contract_contract_contract&action=signEditView&id='
							+ row.id + "&skey=" + row['skey_']);
			}
		},{
			text : '合同收单',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.isAcquiring == '1') {
					return false;
				}
				return true;
			},
			action : function(row) {
			    if (window.confirm(("确定已收单吗?"))) {
					$.ajax({
						type : "POST",
						url : "?model=contract_contract_contract&action=ajaxAcquiring",
						data : {
							id : row.id
						},
						success : function(msg) {
							if (msg == 1) {
								alert('收单成功！');
								$("#comsigninGrid").yxgrid("reload");
							}
						}
					});
				}
			}
		}, {

			text : '更新付款条件',
			icon : 'add',
			action : function(row) {

				showThickboxWin('?model=contract_contract_receiptplan&action=updatePlan&contractId='
						+ row.id
						+ '&isfinance=1'
						+ "&skey="
						+ row['skey_']
						+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=1000');

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
			width : 80,
			hide : true
		}, {
			name : 'contractType',
			display : '合同类型',
			sortable : true,
			datacode : 'HTLX',
			width : 60
		}, {
			name : 'contractNatureName',
			display : '合同属性',
			sortable : true,
			width : 60
		}, {
			name : 'contractCode',
			display : '合同编号',
			sortable : true,
			width : 180,
			process : function(v, row) {
					return  '<a href="javascript:void(0)" onclick="javascript:showOpenWin(\'?model=contract_contract_contract&action=init&perm=view&id='
						+ row.id
						+'&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800\')">'+"<font color = '#4169E1'>" + v +"</font>"+'</a>';
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
			process:function(v){
			    if(v=='0'){
			        return "未签收";
			    }else if(v=='1'){
			        return "已签收";
			    }else if(v=='2'){
			        return "变更未签收";
			    }
			}
		}, {
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
				return moneyFormat2(v);
			}
		}, {
			name : 'surplusInvoiceMoney',
			display : '剩余开票金额',
			sortable : true,
			process : function(v, row) {
				return "<font color = 'blue'>" + v + "</font>"
			}
		}, {
			name : 'incomeMoney',
			display : '已收金额',
			width : 60,
			sortable : true,
			process : function(v, row) {
				return moneyFormat2(v);
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
			width : 70,
			process : function(v){
			    if(v == 0){
			       return "未签约";
			    }else{
			       return "已签约";

			    }
			}
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
					name : 'signContractType',
					display : "签收合同类型",
					sortable : true,
					datacode : 'HTLX',
					width : 60
		}, {
			name : 'isAcquiring',
			display : '收单状态',
			sortable : true,
			width : 80,
			process:function(v,row){
			    if(v=='0'){
			    	if(row.isAcquiringDate != '')
			           return "<span style='color:red'>未收单</span>";
			        else
			           return "未收单"
			    }else if(v=='1'){
			        return "已收单";
			    }
			}
		}, {
			name : 'ExaDTOne',
			display : '建立时间',
			sortable : true,
			width : 80
		}, {
			name : 'isAcquiringDate',
			display : '收单日期',
			sortable : true,
			width : 80
		}, {
			name : 'signinDate',
			display : '签收日期',
			sortable : true,
			width : 80
		}],
		comboEx : [{
			text : '类型',
			key : 'contractType',
			data : [{
				text : '销售合同',
				value : 'HTLX-XSHT'
			}, {
				text : '租赁合同',
				value : 'HTLX-FWHT'
			}, {
				text : '服务合同',
				value : 'HTLX-ZLHT'
			}, {
				text : '研发合同',
				value : 'HTLX-YFHT'
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
});
});