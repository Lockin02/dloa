var show_page = function(page) {
	$("#shipconditionGrid").yxsubgrid("reload");
};
$(function() {
	$("#shipconditionGrid").yxsubgrid({
		model : 'contract_contract_contract',
		title : '合同主表',
		param : {
			'ExaStatus' : '完成',
			'prinvipalId' : $("#userId").val(),
			'shipCondition' : '1',
			'isTemp' : '0'
		},

		title : '延迟发货合同列表',
		isViewAction : false,
		isEditAction : false,
		isDelAction : false,
		showcheckbox : false,
		isAddAction : false,
		customCode : 'mycontract',
		// 扩展右键菜单
         menusEx : [
          	{
			text : '查看',
			icon : 'view',
			action: function(row){
                showModalWin('?model=contract_contract_contract&action=init&id='
						+ row.id
                        + '&perm=view'
                        + "&skey="+row['skey_']
                        + '&placeValuesBefore&TB_iframe=true&modal=false&height=800&width=900');
			   }
          	},{
			text : '合同发货通知',
			icon : 'add',
			action: function(row){
			       showThickboxWin('?model=contract_contract_contract&action=informShipments&id='
						+ row.id
                        + '&placeValuesBefore&TB_iframe=true&modal=false&height=200&width=500');
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
		},{
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
					return "<font color = 'blue'>" + v + "</font>"
				}
			}
		}, {
			name : 'incomeMoney',
			display : '已收金额',
			width : 60,
			sortable : true,
			process : function(v, row) {
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
				if (row.orderMoney == '******'
						|| row.orderTempMoney == '******') {
					return "******";
				} else if (v != '') {
					return "<font color = 'blue'>" + moneyFormat2(v);
					+"</font>"
				} else {
					return "<font color = 'blue'>"
							+ moneyFormat2(accSub(row.orderMoney,
									row.incomeMoney, 2)) + "</font>"
				}

			}
		}, {
			name : 'surincomeMoney',
			display : '财务应收账款余额',
			sortable : true,
			process : function(v, row) {
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
			name : 'dealStatus',
			display : '处理状态',
			sortable : true,
			width : 60,
			process : function(v){
				if (v == '0') {
					return "未处理";
				} else if (v == '1') {
					return "已处理";
				} else if (v == '2') {
					return "变更未处理";
				} else if (v == '3') {
					return "已关闭";
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
			name : 'prinvipalId',
			display : '合同负责人Id',
			sortable : true,
			hide : true,
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
			name : 'objCode',
			display : '业务编号',
			sortable : true,
			width : 120
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

		// 主从表格设置
		subGridOptions : {
			url : '?model=contract_contract_product&action=pageJson',// 获取从表数据url
			// 传递到后台的参数设置数组
			param : [{
				paramId : 'contractId',// 传递给后台的参数名称
				colId : 'id'// 获取主表行数据的列名称

			}],
			// 显示的列
			colModel : [{
						name : 'conProductName',
						width : 200,
						display : '产品名称',
						process : function(v, row) {
							 	return  '<a href="javascript:void(0)" onclick="javascript:showThickboxWin(\'?model=goods_goods_goodsbaseinfo&action=toView&id='
									+ row.conProductId
									+'&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800\')">'+"<font color = '#4169E1'>" + v +"</font>"+'</a>';
			            }
					}, {
					    name : 'conProductDes',
					    display : '产品描述',
						width : 80
					}, {
					    name : 'number',
					    display : '数量',
						width : 80
					}, {
					    name : 'price',
					    display : '单价',
						width : 80
					}, {
					    name : 'money',
					    display : '金额',
						width : 80
					},{
						name : 'licenseButton',
						display : '加密配置',
						process : function(v,row){
							if(row.license != ""){
								return "<a href='#' onclick='showLicense(\""+ row.license + "\")'>查看</a>";
							}else{
							    return "";
							}
						}
					},{
						name : 'deployButton',
						display : '产品配置',
						process : function(v,row){
							if(row.deploy != ""){
								return "<a href='#' onclick='showGoods(\""+ row.deploy + "\",\""+ row.conProductName + "\")'>查看</a>";
							}else{
							    return "";
							}
						}
		            }]
		},
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
			//		// 高级搜索
			//		advSearchOptions : {
			//			modelName : 'orderInfo',
			//			// 选择字段后进行重置值操作
			//			selectFn : function($valInput) {
			//				$valInput.yxcombogrid_area("remove");
			//			},
			//			searchConfig : [{
			//						name : '创建日期',
			//						value : 'c.createTime',
			//						changeFn : function($t, $valInput) {
			//							$valInput.click(function() {
			//										WdatePicker({
			//													dateFmt : 'yyyy-MM-dd'
			//												});
			//									});
			//						}
			//					}, {
			//						name : '归属区域',
			//						value : 'c.areaPrincipal',
			//						changeFn : function($t, $valInput, rowNum) {
			//							if (!$("#areaPrincipalId" + rowNum)[0]) {
			//								$hiddenCmp = $("<input type='hidden' id='areaPrincipalId"
			//										+ rowNum + "' value=''>");
			//								$valInput.after($hiddenCmp);
			//							}
			//							$valInput.yxcombogrid_area({
			//										hiddenId : 'areaPrincipalId' + rowNum,
			//										height : 200,
			//										width : 550,
			//										gridOptions : {
			//											showcheckbox : true
			//										}
			//									});
			//						}
			//					}]
			//		}
	});
});