var show_page = function(page) {
	$("#TGrid").yxgrid("reload");
};
$(function() {
	buttonsArr = [{
			text: "重置",
			icon: 'delete',
			action: function (row) {
				history.go(0);
			}
		},{
            name: 'export',
            text: "报表导出",
            icon: 'excel',
            action: function (row) {
                var searchConditionKey = "";
                var searchConditionVal = "";
                for (var t in $("#TGrid").data('yxgrid').options.searchParam) {
                    if (t != "") {
                        searchConditionKey += t;
                        searchConditionVal += $("#TGrid").data('yxgrid').options.searchParam[t];
                    }
                }

                var states = $("#states").val();
                var isIncome = $("#isIncome").val();
				var isReplan = $("#isReplan").val();


                var i = 1;
                var colId = "";
                var colName = "";
                $("#TGrid_hTable").children("thead").children("tr")
                    .children("th").each(function () {
                        if ($(this).css("display") != "none"
                            && $(this).attr("colId") != undefined) {
                            colName += $(this).children("div").html() + ",";
                            colId += $(this).attr("colId") + ",";
                            i++;
                        }
                    })
                var searchSql = $("#TGrid").data('yxgrid').getAdvSql();

				var msg = $.ajax({
					url: '?model=contract_contract_contract&action=setColInfoToSession',
					data: 'ColId=' + colId + '&ColName='+colName + '&sType=exportContractTdate',
					dataType: 'html',
					type: 'post',
					async: false
				}).responseText;

				if(msg == 1){
                window
                    .open("?model=contract_contract_contract&action=TdataExportExcel"
                        + "&states="
                        + states
                        + "&isIncome="
                        + isIncome
						+ "&isReplan="
						+ isReplan
                        + "&searchSql="
                        + searchSql
                        + "&searchConditionKey="
                        + searchConditionKey
                        + "&searchConditionVal=" + searchConditionVal)
				}
            }
		}],
	$("#TGrid").yxgrid({
		model : 'contract_contract_contract',
		action : 'tdatDataJson',
		title : '合同T日信息表',
		isViewAction : false,
		isEditAction : false,
		isDelAction : false,
		isAddAction : false,
		leftLayout: true,
		customCode: 'TdataGrid',
		// 扩展右键菜单
		menusEx : [],
		//		lockCol : ['conflag', 'exeStatus'],// 锁定的列名
		// 列信息
		colModel : [{
			display : 'id',
			name : 'id',
			able : true,
			hide : true
		}, {
			name : 'year',
			display : "年份",
			able : true,
			width : 40
		}, {
			name : 'ExaDTOne',
			display : "建立时间",
			able : true,
			width : 70
		}, {
			name : 'signSubjectName',
			display : "签约主体",
			able : true,
			width : 60
		}, {
			name : 'contractTypeName',
			display : "合同类型",
			able : true,
			width : 60
		}, {
			name : 'contractNatureName',
			display : '合同属性',
			able : true,
			width : 70,
			process : function(v, row) {
				if (v == 'NULL') {
					return "-";
				} else {
					return v;
				}
			}
		}, {
			name : 'contractCode',
			display : '合同编号',
			able : true,
			width : 70
		}, {
			name : 'customerName',
			display : '客户名称',
			able : true,
			width : 70
		}, {
			name : 'customerTypeName',
			display : '客户类型',
			able : true,
			width : 70
		}, {
			name : 'contractName',
			display : '合同名称',
			able : true,
			width : 70
		}, {
			name : 'payInfo',
			display : '收款条款',
			able : true,
			width : 80
		}, {
			name : 'clauseInfo',
			display : '验收条款',
			able : true,
			width : 80
		}, {
			name : 'editInfo',
			display : '修改记录',
			width : 80
		}, {
			name : 'incomeDate_1',
			display : '回款1期T日',
			width : 60,
			process : function(v, row) {
				if (v == '') {
					return "-";
				} else {
					return v;
				}
			}
		}, {
			name : 'incomePtn_1',
			display : '回款1期比例',
			width : 60,
			process : function(v, row) {
				if (v == '') {
					return "-";
				} else {
					return v + "%";
				}
			}
		}, {
			name : 'incomeMoney_1',
			display : '回款1期金额',
			width : 60,
			process : function(v, row) {
				if (v == '') {
					return "-";
				} else {
					return moneyFormat2(v);
				}
			}
		}, {
			name : 'invoiceMoney_1',
			display : '回款1期开票金额',
			width : 60,
			process : function(v, row) {
				if (v == '') {
					return "-";
				} else {
					return moneyFormat2(v);
				}
			}
		}, {
			name : 'incomeDate_2',
			display : '回款2期T日',
			width : 60,
			process : function(v, row) {
				if (v == '') {
					return "-";
				} else {
					return v;
				}
			}
		}, {
			name : 'incomePtn_2',
			display : '回款2期比例',
			width : 60,
			process : function(v, row) {
				if (v == '') {
					return "-";
				} else {
					return v + "%";
				}
			}
		}, {
			name : 'incomeMoney_2',
			display : '回款2期金额',
			width : 60,
			process : function(v, row) {
				if (v == '') {
					return "-";
				} else {
					return moneyFormat2(v);
				}
			}
		}, {
			name : 'invoiceMoney_2',
			display : '回款2期开票金额',
			width : 60,
			process : function(v, row) {
				if (v == '') {
					return "-";
				} else {
					return moneyFormat2(v);
				}
			}
		}, {
			name : 'incomeDate_3',
			display : '回款3期T日',
			width : 60,
			process : function(v, row) {
				if (v == '') {
					return "-";
				} else {
					return v;
				}
			}
		}, {
			name : 'incomePtn_3',
			display : '回款3期比例',
			width : 60,
			process : function(v, row) {
				if (v == '') {
					return "-";
				} else {
					return v+ "%";
				}
			}
		}, {
			name : 'incomeMoney_3',
			display : '回款3期金额',
			width : 60,
			process : function(v, row) {
				if (v == '') {
					return "-";
				} else {
					return moneyFormat2(v);
				}
			}
		}, {
			name : 'invoiceMoney_3',
			display : '回款3期开票金额',
			width : 60,
			process : function(v, row) {
				if (v == '') {
					return "-";
				} else {
					return moneyFormat2(v);
				}
			}
		}, {
			name : 'incomeDate_4',
			display : '回款4期T日',
			width : 60,
			process : function(v, row) {
				if (v == '') {
					return "-";
				} else {
					return v;
				}
			}
		}, {
			name : 'incomePtn_4',
			display : '回款4期比例',
			width : 60,
			process : function(v, row) {
				if (v == '') {
					return "-";
				} else {
					return v + "%";
				}
			}
		}, {
			name : 'incomeMoney_4',
			display : '回款4期金额',
			width : 60,
			process : function(v, row) {
				if (v == '') {
					return "-";
				} else {
					return moneyFormat2(v);
				}
			}
		}, {
			name : 'invoiceMoney_4',
			display : '回款4期开票金额',
			width : 60,
			process : function(v, row) {
				if (v == '') {
					return "-";
				} else {
					return moneyFormat2(v);
				}
			}
		}, {
			name : 'incomeDate_5',
			display : '回款5期T日',
			width : 60,
			process : function(v, row) {
				if (v == '') {
					return "-";
				} else {
					return v;
				}
			}
		}, {
			name : 'incomePtn_5',
			display : '回款5期比例',
			width : 60,
			process : function(v, row) {
				if (v == '') {
					return "-";
				} else {
					return v + "%";
				}
			}
		}, {
			name : 'incomeMoney_5',
			display : '回款5期金额',
			width : 60,
			process : function(v, row) {
				if (v == '') {
					return "-";
				} else {
					return moneyFormat2(v);
				}
			}
		}, {
			name : 'invoiceMoney_5',
			display : '回款5期开票金额',
			width : 60,
			process : function(v, row) {
				if (v == '') {
					return "-";
				} else {
					return moneyFormat2(v);
				}
			}
		}, {
			name : 'incomeDate_6',
			display : '回款6期T日',
			width : 60,
			process : function(v, row) {
				if (v == '') {
					return "-";
				} else {
					return v;
				}
			}
		}, {
			name : 'incomePtn_6',
			display : '回款6期比例',
			width : 60,
			process : function(v, row) {
				if (v == '') {
					return "-";
				} else {
					return v + "%";
				}
			}
		}, {
			name : 'incomeMoney_6',
			display : '回款6期金额',
			width : 60,
			process : function(v, row) {
				if (v == '') {
					return "-";
				} else {
					return moneyFormat2(v);
				}
			}
		}, {
			name : 'invoiceMoney_6',
			display : '回款6期开票金额',
			width : 60,
			process : function(v, row) {
				if (v == '') {
					return "-";
				} else {
					return moneyFormat2(v);
				}
			}
		}, {
			name : 'incomeDate_7',
			display : '回款7期T日',
			width : 60,
			process : function(v, row) {
				if (v == '') {
					return "-";
				} else {
					return v;
				}
			}
		}, {
			name : 'incomePtn_7',
			display : '回款7期比例',
			width : 60,
			process : function(v, row) {
				if (v == '') {
					return "-";
				} else {
					return v + "%";
				}
			}
		}, {
			name : 'incomeMoney_7',
			display : '回款7期金额',
			width : 60,
			process : function(v, row) {
				if (v == '') {
					return "-";
				} else {
					return moneyFormat2(v);
				}
			}
		}, {
			name : 'invoiceMoney_7',
			display : '回款7期开票金额',
			width : 60,
			process : function(v, row) {
				if (v == '') {
					return "-";
				} else {
					return moneyFormat2(v);
				}
			}
		}, {
			name : 'incomeDate_8',
			display : '回款8期T日',
			width : 60,
			process : function(v, row) {
				if (v == '') {
					return "-";
				} else {
					return v;
				}
			}
		}, {
			name : 'incomePtn_8',
			display : '回款8期比例',
			width : 60,
			process : function(v, row) {
				if (v == '') {
					return "-";
				} else {
					return v + "%";
				}
			}
		}, {
			name : 'incomeMoney_8',
			display : '回款8期金额',
			width : 60,
			process : function(v, row) {
				if (v == '') {
					return "-";
				} else {
					return moneyFormat2(v);
				}
			}
		}, {
			name : 'invoiceMoney_8',
			display : '回款8期开票金额',
			width : 60,
			process : function(v, row) {
				if (v == '') {
					return "-";
				} else {
					return moneyFormat2(v);
				}
			}
		}, {
			name : 'incomeDate_9',
			display : '回款9期T日',
			width : 60,
			process : function(v, row) {
				if (v == '') {
					return "-";
				} else {
					return v;
				}
			}
		}, {
			name : 'incomePtn_9',
			display : '回款9期比例',
			width : 60,
			process : function(v, row) {
				if (v == '') {
					return "-";
				} else {
					return v + "%";
				}
			}
		}, {
			name : 'incomeMoney_9',
			display : '回款9期金额',
			width : 60,
			process : function(v, row) {
				if (v == '') {
					return "-";
				} else {
					return moneyFormat2(v);
				}
			}
		}, {
			name : 'invoiceMoney_9',
			display : '回款9期开票金额',
			width : 60,
			process : function(v, row) {
				if (v == '') {
					return "-";
				} else {
					return moneyFormat2(v);
				}
			}
		}, {
			name : 'incomeDate_10',
			display : '回款10期T日',
			width : 60,
			process : function(v, row) {
				if (v == '') {
					return "-";
				} else {
					return v;
				}
			}
		}, {
			name : 'incomePtn_10',
			display : '回款10期比例',
			width : 60,
			process : function(v, row) {
				if (v == '') {
					return "-";
				} else {
					return v + "%";
				}
			}
		}, {
			name : 'incomeMoney_10',
			display : '回款10期金额',
			width : 60,
			process : function(v, row) {
				if (v == '') {
					return "-";
				} else {
					return moneyFormat2(v);
				}
			}
		}, {
			name : 'invoiceMoney_10',
			display : '回款10期开票金额',
			width : 60,
			process : function(v, row) {
				if (v == '') {
					return "-";
				} else {
					return moneyFormat2(v);
				}
			}
		}, {
			name : 'incomeDate_11',
			display : '回款11期T日',
			width : 60,
			process : function(v, row) {
				if (v == '') {
					return "-";
				} else {
					return v;
				}
			}
		}, {
			name : 'incomePtn_11',
			display : '回款11期比例',
			width : 60,
			process : function(v, row) {
				if (v == '') {
					return "-";
				} else {
					return v + "%";
				}
			}
		}, {
			name : 'incomeMoney_11',
			display : '回款11期金额',
			width : 60,
			process : function(v, row) {
				if (v == '') {
					return "-";
				} else {
					return moneyFormat2(v);
				}
			}
		}, {
			name : 'invoiceMoney_11',
			display : '回款11期开票金额',
			width : 60,
			process : function(v, row) {
				if (v == '') {
					return "-";
				} else {
					return moneyFormat2(v);
				}
			}
		}, {
			name : 'incomeDate_12',
			display : '回款12期T日',
			width : 60,
			process : function(v, row) {
				if (v == '') {
					return "-";
				} else {
					return v;
				}
			}
		}, {
			name : 'incomePtn_12',
			display : '回款12期比例',
			width : 60,
			process : function(v, row) {
				if (v == '') {
					return "-";
				} else {
					return v + "%";
				}
			}
		}, {
			name : 'incomeMoney_12',
			display : '回款12期金额',
			width : 60,
			process : function(v, row) {
				if (v == '') {
					return "-";
				} else {
					return moneyFormat2(v);
				}
			}
		}, {
			name : 'invoiceMoney_12',
			display : '回款12期开票金额',
			width : 60,
			process : function(v, row) {
				if (v == '') {
					return "-";
				} else {
					return moneyFormat2(v);
				}
			}
		}, {
			name : 'incomeDate_13',
			display : '回款13期T日',
			width : 60,
			process : function(v, row) {
				if (v == '') {
					return "-";
				} else {
					return v;
				}
			}
		}, {
			name : 'incomePtn_13',
			display : '回款13期比例',
			width : 60,
			process : function(v, row) {
				if (v == '') {
					return "-";
				} else {
					return v + "%";
				}
			}
		}, {
			name : 'incomeMoney_13',
			display : '回款13期金额',
			width : 60,
			process : function(v, row) {
				if (v == '') {
					return "-";
				} else {
					return moneyFormat2(v);
				}
			}
		}, {
			name : 'invoiceMoney_13',
			display : '回款13期开票金额',
			width : 60,
			process : function(v, row) {
				if (v == '') {
					return "-";
				} else {
					return moneyFormat2(v);
				}
			}
		}, {
			name : 'incomeDate_14',
			display : '回款14期T日',
			width : 60,
			process : function(v, row) {
				if (v == '') {
					return "-";
				} else {
					return v;
				}
			}
		}, {
			name : 'incomePtn_14',
			display : '回款14期比例',
			width : 60,
			process : function(v, row) {
				if (v == '') {
					return "-";
				} else {
					return v + "%";
				}
			}
		}, {
			name : 'incomeMoney_14',
			display : '回款14期金额',
			width : 60,
			process : function(v, row) {
				if (v == '') {
					return "-";
				} else {
					return moneyFormat2(v);
				}
			}
		}, {
			name : 'invoiceMoney_14',
			display : '回款14期开票金额',
			width : 60,
			process : function(v, row) {
				if (v == '') {
					return "-";
				} else {
					return moneyFormat2(v);
				}
			}
		}, {
			name : 'incomeDate_15',
			display : '回款15期T日',
			width : 60,
			process : function(v, row) {
				if (v == '') {
					return "-";
				} else {
					return v;
				}
			}
		}, {
			name : 'incomePtn_15',
			display : '回款15期比例',
			width : 60,
			process : function(v, row) {
				if (v == '') {
					return "-";
				} else {
					return v + "%";
				}
			}
		}, {
			name : 'incomeMoney_15',
			display : '回款15期金额',
			width : 60,
			process : function(v, row) {
				if (v == '') {
					return "-";
				} else {
					return moneyFormat2(v);
				}
			}
		}, {
			name : 'invoiceMoney_15',
			display : '回款15期开票金额',
			width : 60,
			process : function(v, row) {
				if (v == '') {
					return "-";
				} else {
					return moneyFormat2(v);
				}
			}
		}, {
			name : 'signContractMoney',
			display : '签约合同有效合同额',
			able : true,
			width : 80,
			process : function(v, row) {
				if (v == '') {
					return "0.00";
				} else {
					return moneyFormat2(v);
				}
			}
		}, {
			name : 'unIncomeMoney',
			display : '签约合同应收帐款余额',
			able : true,
			width : 80,
			process : function(v, row) {
				if (v == '') {
					return "0.00";
				} else {
					return moneyFormat2(v);
				}
			}
		}, {
			name : 'Tmoney',
			display : '已确认T日金额',
			able : true,
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
			display : '剩余开票金额',
			able : true,
			width : 80,
			process : function(v, row) {
				if (v == '') {
					return "0.00";
				} else {
					return moneyFormat2(v);
				}
			}
		}, {
			name : 'planInvoiceDate',
			display : '计划开票日期',
			width : 40
		}, {
			name : 'contractProvince',
			display : '省份',
			able : true,
			width : 60
		}, {
			name : 'contractCity',
			display : '城市',
			able : true,
			width : 60
		}, {
			name : 'areaName',
			display : '归属区域',
			width : 60
		}, {
			name : 'prinvipalName',
			display : '合同负责人',
			width : 60
		}, {
			name : 'outstockDate',
			display : '完成交付时间',
			width : 60
		}, {
			name : 'relDate',
			display : '租赁期间',
			width : 60
		}, {
			name : 'projectState',
			display : '项目状态',
			width : 60
		}, {
			name : 'projectEndDate',
			display : '项目预计结束时间',
			width : 60
		}, {
			name : 'payRemark',
			display : '合同项目备注',
			width : 60
		}, {
			name : 'unTdayMoney',
			display : '未确认T日金额',
			width : 60,
			process : function(v, row) {
				return moneyFormat2(v);
			}
		}],
		//		comboEx : [{
		//			text : '类型',
		//			key : 'contractType',
		//			data : [{
		//				text : '销售合同',
		//				value : 'HTLX-XSHT'
		//			}, {
		//				text : '服务合同',
		//				value : 'HTLX-FWHT'
		//			}, {
		//				text : '租赁合同',
		//				value : 'HTLX-ZLHT'
		//			}, {
		//				text : '研发合同',
		//				value : 'HTLX-YFHT'
		//			}]
		//		}, {
		//			text : '合同状态',
		//			key : 'states',
		//			value : '0,1,2,3,4,5,6',
		//			data : [{
		//				text : '所有(不含异常合同)',
		//				value : '0,1,2,3,4,5,6'
		//			}, {
		//				text : '审批中',
		//				value : '1'
		//			}, {
		//				text : '执行中',
		//				value : '2'
		//			}, {
		//				text : '已完成',
		//				value : '4'
		//			}, {
		//				text : '已关闭',
		//				value : '3'
		//			}
		//					// , {
		//					// text : '已合并',
		//					// value : '5'
		//					// }, {
		//					// text : '已拆分',
		//					// value : '6'
		//					// }
		//					, {
		//						text : '异常关闭',
		//						value : '7'
		//					}, {
		//						text : '有效合同(执行，完成，关闭)',
		//						value : '2,3,4'
		//					}]
		//		}, {
		//			text : '审批状态',
		//			key : 'ExaStatusArr',
		//			data : [{
		//				text : '未审批',
		//				value : '未审批'
		//			}, {
		//				text : '部门审批',
		//				value : '部门审批'
		//			}, {
		//				text : '变更审批中',
		//				value : '变更审批中'
		//			}, {
		//				text : '打回',
		//				value : '打回'
		//			}, {
		//				text : '完成',
		//				value : '完成'
		//			}, {
		//				text : '完成和变更审批中',
		//				value : '完成,变更审批中'
		//			}]
		//		}, {
		//			text : '签约主体',
		//			key : 'businessBelong',
		//			datacode : 'QYZT'
		//		}],
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
			display : '产品名称',
			name : 'conProductName'
		}, {
			display : '试用项目',
			name : 'trialprojectCode'
		}],
		name : "createTime",
		buttonsEx: buttonsArr,
		//分类搜索
        comboEx: [
            {
                text: '合同状态',
                key: 'states',
                value: '2,3,4',
                data: [
                    {
                        text: '所有(不含异常合同)',
                        value: '0,1,2,3,4,5,6'
                    },
                    {
                        text: '审批中',
                        value: '1'
                    },
                    {
                        text: '执行中',
                        value: '2'
                    },
                    {
                        text: '已完成',
                        value: '4'
                    },
                    {
                        text: '已关闭',
                        value: '3'
                    },
                    {
                        text: '异常关闭',
                        value: '7'
                    },
                    {
                        text: '有效合同(执行，完成，关闭)',
                        value: '2,3,4'
                    }
                ]
            },
            {
                text: '回款状态',
                key: 'isIncome',
                value: '0',
                data: [
                    {
                        text: '未完成回款',
                        value: '0'
                    },
                    {
                        text: '已完成回款',
                        value: '1'
                    }
                ]
            },
			{
				text: '回款条款',
				key: 'isReplan',
				data: [
					{
						text: '有回款条款',
						value: '0'
					},
					{
						text: '有条款/T日',
						value: '1'
					}
				]
			}
        ],
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
                }
                ,
                {
                    name: '客户类型',
                    value: 'c.customerType',
                    type: 'select',
                    datacode: 'KHLX'
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
                    name: '签约主体',
                    value: 'c.businessBelong',
                    type: 'select',
                    datacode: 'QYZT'
                }
            ]
        }
	});

});
