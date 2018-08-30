var show_page = function(page) {
	$("#shippedGrid").yxsubgrid("reload");
};
$(function() {
	$("#shipmentsGrid").yxsubgrid({
		model : 'projectmanagent_exchange_exchange',
		action : 'shipmentsPageJson',
		customCode : 'exchangeShipmentsGrid',
		param : {
			'ExaStatusArr':"完成,变更审批中",
//			'lExaStatusArr':"完成,变更审批中",
			"DeliveryStatus2" : "YFH,TZFH"
		},
		title : '换货申请',
		// 按钮
		isViewAction : false,
		isAddAction : false,
		isEditAction : false,
		isDelAction : false,
		showcheckbox : false,
//
//		buttonsEx : [{
//			name : 'export',
//			text : "发货数据导出",
//			icon : 'excel',
//			action : function(row) {
//				window.open("?model=contract_common_allcontract&action=preExportExcel"
//								+ "&1width=200,height=200,top=200,left=200,resizable=yes")
//			}
//		}],
		// 列信息
		colModel : [{
			name : 'status2',
			display : '下达状态',
			sortable : false,
			width : '20',
			align : 'center',
			// hide : aaa,
			process : function(v, row) {
				if (row.makeStatus == 'YXD') {
					return "<img src='images/icon/icon073.gif' />";
				} else {
					return "<img src='images/icon/green.gif' />";
				}
			}
				}, {
					name : 'rate',
					display : '进度',
					sortable : false,
					process : function(v,row){
						return '<a href="javascript:void(0)" onclick="javascript:showThickboxWin(\'?model=stock_outplan_contractrate&action=updateRate&docId='
								+ row.id
								+ "&docType=oa_contract_exchangeapply"
								+ "&objCode="
								+ row.objCode
								+ "&skey="
								+ row['skey_']
								+'&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800\')">备注 : '+"<font color='gray'>"+v+"</font>"+'</a>';
					}
				}, {
					display : '物料审批状态',
					name : 'lExaStatus',
					sortable : true
				}, {
					display : '物料审批表Id',
					name : 'lid',
					sortable : true,
					hide : true
				}, {
					display : 'id',
					name : 'id',
					sortable : true,
					hide : true
				}, {
					name : 'ExaDT',
					display : '建立时间',
					width : 70,
					sortable : true
				}, {
					name : 'deliveryDate',
					display : '交货日期',
					width : 80,
					sortable : true
				}, {
					name : 'exchangeCode',
					display : '换货单编号',
					sortable : true
				}, {
					name : 'contractCode',
					display : '源单号',
					sortable : true
				}, {
					name : 'customerName',
					display : '客户名称',
					sortable : true
				}, {
					name : 'saleUserName',
					display : '销售负责人',
					sortable : true
				}, {
					name : 'DeliveryStatus',
					display : '发货状态',
					process : function(v) {
						if (v == 'WFH') {
							return "未发货";
						} else if (v == 'YFH') {
							return "已发货";
						} else if (v == 'BFFH') {
							return "部分发货";
						} else if (v == 'TZFH') {
							return "停止发货";
						}
					},
					width : '60',
					sortable : true
				}, {
					name : 'makeStatus',
					display : '下达状态',
					sortable : true,
					process : function(v) {
						if (v == 'WXD') {
							return "未下达";
						} else if (v == 'BFXD') {
							return "部分下达";
						} else if (v == 'YXD') {
							return "已下达";
						} else if (v == 'WXFH') {
							return "无需发货";
						}
					},
					width : 60,
					sortable : true
				}, {
					name : 'ExaStatus',
					display : '审批状态',
					width : 60,
					sortable : true
				}
//				, {
//					name : 'objCode',
//					display : '业务编号',
//					width : 120
//				}
//				, {
//					name : 'rObjCode',
//					display : '源单业务编号',
//					width : 120
//				}
				],
		// 主从表格设置
		subGridOptions : {
			url : '?model=common_contract_allsource&action=equJson',// 获取从表数据url
			// 传递到后台的参数设置数组
			param : [{
				'docType' : 'oa_contract_exchangeapply'
			}, {
				paramId : 'exchangeId',// 传递给后台的参数名称
				colId : 'id'// 获取主表行数据的列名称
			}],
			// 显示的列
			colModel : [{
						name : 'productName',
						width : 200,
						display : '物料名称'
					}, {
						name : 'number',
						display : '数量',
						width : 40
					}, {
					    name : 'lockNum',
					    display : '锁定数量',
						width : 50,
					    process : function(v){
					    	if(v==''){
					    		return 0;
					    	}else
					    		return v;
					    }
					},{
						name : 'exeNum',
						display : '库存数量',
						width : 50,
						process : function(v) {
							if (v == '') {
								return 0;
							} else
								return v;
						}
					}, {
						name : 'issuedShipNum',
						display : '已下达发货数量',
						width : 90,
						process : function(v) {
							if (v == '') {
								return 0;
							} else
								return v;
						}
					}, {
						name : 'executedNum',
						display : '已发货数量',
						width : 60
					}, {
						name : 'issuedPurNum',
						display : '已下达采购数量',
						width : 90,
						process : function(v) {
							if (v == '') {
								return 0;
							} else
								return v;
						}
					}, {
						name : 'issuedProNum',
						display : '已下达生产数量',
						width : 90,
						process : function(v) {
							if (v == '') {
								return 0;
							} else
								return v;
						}
					}, {
					    name : 'backNum',
					    display : '退库数量',
						width : 60
					},{
						name : 'projArraDate',
						display : '计划交货日期',
						width : 80,
						process : function(v) {
							if (v == null) {
								return '无';
							} else {
								return v;
							}
						}
					}]
		},
		comboEx : [{
					text : '发货状态',
					key : 'DeliveryStatus',
					data : [{
								text : '已发货',
								value : 'YFH'
							}, {
								text : '停止发货',
								value : 'TZFH'
							}]
				}],

		menusEx : [{
			text : '查看发货物料',
			icon : 'view',
			showMenuFn : function(row) {
				if (row.linkId) {
					return true;
				}
				return false;
			},
			action : function(row) {
				showModalWin('?model=projectmanagent_exchange_exchangeequ&action=toEquView&linkId='
						+ row.linkId + "&skey=" + row['skey_']);
			}
//		}, {
//			text : '确认发货物料',
//			icon : 'view',
//			showMenuFn : function(row) {
//				if ( row.dealStatus == 0 && row.lExaStatus == '') {
//					return true;
//				}
//				return false;
//			},
//			action : function(row) {
//				showOpenWin('?model=projectmanagent_exchange_exchangeequ&action=toEquAdd&id='
//						+ row.id + "&skey=" + row['skey_']);
//			}
//		}, {
//			text : '编辑发货物料',
//			icon : 'edit',
//			showMenuFn : function(row) {
//				if (row.dealStatus == 0 && (row.lExaStatus == '未审批'||row.lExaStatus == '打回')) {
//					return true;
//				}
//				return false;
//			},
//			action : function(row) {
//				showOpenWin('?model=projectmanagent_exchange_exchangeequ&action=toEquEdit&id='
//						+ row.id + "&skey=" + row['skey_']);
//			}
//		}, {
//			text : '发货物料变更',
//			icon : 'delete',
//			showMenuFn : function(row) {
//				if ( (row.dealStatus == 1||row.dealStatus == 2) && row.lExaStatus == '完成') {
//					return true;
//				}
//				return false;
//			},
//			action : function(row) {
//				showOpenWin('?model=projectmanagent_exchange_exchangeequ&action=toEquChange&id='
//						+ row.id + "&skey=" + row['skey_']);
//			}
//		}, {
//			text : '锁定库存',
//			icon : 'lock',
//			showMenuFn : function(row) {
//				if (row.ExaStatus != '变更审批中' && row.DeliveryStatus != 'YFH'
//						&& row.DeliveryStatus != 'TZFH') {
//					return true;
//				}
//				return false;
//			},
//			action : function(row) {
//				var objCode = row.objCode;
//				showOpenWin('?model=stock_lock_lock&action=toLokStock&id='
//						+ row.id + "&objCode=" + objCode
//						+ "&objType=oa_contract_exchangeapply&skey=" + row['skey_']);
//			}
//		}, {
//			text : '审批情况',
//			icon : 'view',
//			showMenuFn : function(row) {
//				if (row.lExaStatus != '') {
//					return true;
//				}
//				return false;
//			},
//			action : function(row) {
//
//				showThickboxWin('controller/contract/contract/readview.php?itemtype=oa_exchange_equ_link&pid='
//						+ row.lid
//						+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=600');
//			}
//		},{
//			text : '下达发货计划',
//			icon : 'add',
//			showMenuFn : function(row) {
//				if (row.makeStatus != 'YXD'
//						&& (row.DeliveryStatus == 'WFH' || row.DeliveryStatus == 'BFFH')) {
//					return true;
//				}
//				return false;
//			},
//			action : function(row) {
//				showModalWin("?model=stock_outplan_outplan&action=toAdd&id="
//						+ row.id
//						+ "&skey="
//						+ row['skey_']
//						+ "&docType=oa_contract_exchangeapply"
//						+ "&placeValuesBefore&TB_iframe=true&modal=false&height=800&width=1100");
//			}
//		}, {
//			text : '下达采购申请',
//			icon : 'edit',
//			showMenuFn : function(row) {
//				if (false&&(row.DeliveryStatus == 'WFH' || row.DeliveryStatus == 'BFFH')) {
//					return true;
//				}
//				return false;
//			},
//			action : function(row) {
//				if (row.orderCode == '')
//					var codeValue = row.orderTempCode;
//				else
//					var codeValue = row.orderCode;
//				showModalWin('?model=purchase_external_external&action=purchasePlan&orderId='
//						+ row.id
//						+ "&orderCode="
//						+ row.Code
//						+ "&orderName="
//						+ "&purchType=oa_contract_exchangeapply"
//						+ "&skey="
//						+ row['skey_']
//						+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=1150');
//			}
//		}, {
//			text : '下达生产任务',
//			icon : 'add',
//			showMenuFn : function(row) {
//				if (row.DeliveryStatus == 7 || row.DeliveryStatus == 10) {
//					return true;
//				}
//				return false;
//			},
//			action : function(row) {
//				showModalWin("?model=produce_protask_protask&action=toAdd&id="
//						+ row.id
//						+ "&skey="
//						+ row['skey_']
//						+ "&docType=oa_contract_exchangeapply"
//						+ "&placeValuesBefore&TB_iframe=true&modal=false&height=800&width=1100");
//			}
//		}, {
//			text : "关闭",
//			icon : 'delete',
//			showMenuFn : function(row) {
//				if (row.DeliveryStatus != 'TZFH') {
//					return true;
//				}
//				return false;
//			},
//			action : function(row) {
//				$.ajax({
//					type : 'POST',
//					url : '?model=contract_common_allcontract&action=closeCont&skey='
//							+ row['skey_'],
//					data : {
//						id : row.id,
//						type : 'oa_contract_exchangeapply'
//					},
//					// async: false,
//					success : function(data) {
//						alert("关闭成功");
//						show_page();
//						return false;
//					}
//				});
//			}
		}],
		/**
		 * 快速搜索
		 */
		searchitems : [{
					display : '编号',
					name : 'Code'
				}
//				, {
//					display : '业务编号',
//					name : 'objCode'
//				}
//				, {
//					display : '源单业务编号',
//					name : 'rObjCode'
//				}
				,{
				    display : '换货单编号',
				    name : 'exchangeCode'
				}],
		sortname : 'ExaDT',
		sortorder : 'DESC'
	});
});