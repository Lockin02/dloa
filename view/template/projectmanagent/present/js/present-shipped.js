var show_page = function(page) {
	$("#shippedGrid").yxsubgrid("reload");
};
$(function() {
	$("#shippedGrid").yxsubgrid({
		model : 'projectmanagent_present_present',
		action : 'shipmentsPageJson',
		customCode : 'presentShippedGrid',
		param : {
			"ExaStatus" : "完成",
			"DeliveryStatus2" : "YFH,TZFH"
		},
		title : '赠送申请',
		// 按钮
		isViewAction : false,
		isAddAction : false,
		isEditAction : false,
		isDelAction : false,
		showcheckbox : false,
		// 列信息
		colModel : [{
			name : 'rate',
			display : '进度',
			sortable : false,
			process : function(v, row) {
				return '<a href="javascript:void(0)" onclick="javascript:showThickboxWin(\'?model=stock_outplan_contractrate&action=updateRate&docId='
						+ row.id
						+ "&docType=oa_present_present"
						+ "&objCode="
						+ row.objCode
						+ "&skey="
						+ row['skey_']
						+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800\')">备注 : '
						+ "<font color='gray'>" + v + "</font>" + '</a>';
			}
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
			name : 'customerName',
			display : '客户名称',
			width : 150,
			sortable : true
		}, {
			name : 'orderCode',
			display : '源单编号',
			width : 170,
			sortable : true
		}, {
			name : 'orderName',
			display : '源单名称',
			width : 170,
			hide : true,
			sortable : true
		}, {
			name : 'Code',
			display : '编号',
			width : 120,
			sortable : true,
			process : function(v, row) {
				if( v=='' ){
					v='无'
				}
				if (row.changeTips == 1) {
					return "<font color = '#FF0000'>"
							+ '<a href="javascript:void(0)" onclick="javascript:showOpenWin(\'?model=stock_outplan_outplan&action=viewByPresent&id='
							+ row.id
							+ "&objType=oa_present_present"
							+ "&linkId="
							+ row.linkId
							+ "&skey="
							+ row['skey_']
							+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800\')">'
							+ "<font color = '#FF0000'>" + v + "</font>"
							+ '</a>';
				} else {
					return '<a href="javascript:void(0)" onclick="javascript:showOpenWin(\'?model=stock_outplan_outplan&action=viewByPresent&id='
							+ row.id
							+ "&objType=oa_present_present"
							+ "&linkId="
							+ row.linkId
							+ "&skey="
							+ row['skey_']
							+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800\')">'
							+ "<font color = '#4169E1'>"
							+ v
							+ "</font>"
							+ '</a>';
				}
			}
		}, {
//			name : 'limits',
//			display : '源单类型',
//			width : 60,
//			sortable : true,
//			process : function(v) {
//				if (v == 'oa_sale_order') {
//					return "销售发货";
//				} else if (v == 'oa_sale_lease') {
//					return "租赁发货";
//				} else if (v == 'oa_sale_service') {
//					return "服务发货";
//				} else if (v == 'oa_sale_rdproject') {
//					return "研发发货";
//				} else if (v == 'oa_borrow_borrow') {
//					return "借用发货";
//				}
//			}
//		}, {
			name : 'DeliveryStatus',
			display : '发货状态',
			sortable : true,
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
			width : 60,
			sortable : true
		}, {
			name : 'issuedStatus',
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
			name : 'salesName',
			display : '申请人',
			width : 80,
			sortable : true
		}, {
			name : 'reason',
			display : '申请理由',
			hide : true,
			sortable : true
		}, {
			name : 'remark',
			display : '备注',
			hide : true,
			sortable : true
		}, {
			name : 'ExaStatus',
			display : '审批状态',
			width : 60,
			sortable : true
		}, {
			name : 'objCode',
			display : '业务编号',
			width : 120
		}, {
			name : 'rObjCode',
			display : '源单业务编号',
			width : 120
		}],
		// 主从表格设置
		subGridOptions : {
			subgridcheck : true,
			url : '?model=common_contract_allsource&action=equJson',// 获取从表数据url
			// 传递到后台的参数设置数组
			param : [ {
				'docType' : 'oa_present_present'
			}, {
				paramId : 'presentId',// 传递给后台的参数名称
				colId : 'id'// 获取主表行数据的列名称
			} ],
			// 显示的列
			colModel : [{
				name : 'productNo',
				display : '物料编号',
				process : function( v,data,rowData,$row ){
					if( data.changeTips==1 ){
						return '<img title="变更编辑的产品" src="images/changeedit.gif" />' + v;
					}else if( data.changeTips==2 ){
						return '<img title="变更新增的产品" src="images/new.gif" />' + v;
					}else{
						return v;
					}
				},
				width : 95
			}, {
				name : 'productName',
				width : 200,
				display : '物料名称',
				process : function(v,row){
			    	if(row.changeTips == 1 || row.changeTips == 2){
			    		return "<font color = 'red'>"+ v + "</font>"
			    	}else
			    		return v;
				}
			}, {
				name : 'productModel',
				display : '规格型号'
//						,width : 40
			}, {
				name : 'number',
				display : '数量',
				width : 40
			}, {
//								name : 'lockNum',
//								display : '锁定数量',
//								width : 50,
//								process : function(v) {
//									if (v == '') {
//										return 0;
//									} else
//										return v;
//								}
//							}, {
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
			}, {
				name : 'arrivalPeriod',
				display : '标准交货期',
				width : 80,
				process : function(v) {
					if (v == null) {
						return '0';
					} else {
						return v;
					}
				}
			} ]
		},
		menusEx : [{
			text : '查看详细',
			icon : 'view',
			action : function(row) {
				showOpenWin('?model=stock_outplan_outplan&action=viewByPresent&id='
						+ row.id
						+　"&linkId="
						+ row.linkId
						+ "&objType=oa_present_present"
						+ "&skey=" + row['skey_']);
			}
		}, {
//			text : '进度备注',
//			icon : 'edit',
//			action : function(row) {
//				showThickboxWin('?model=stock_outplan_contractrate&action=page&docId='
//						+ row.id
//						+ "&docType=oa_present_present"
//						+ "&objCode="
//						+ row.objCode
//						+ "&skey="
//						+ row['skey_']
//						+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800');
//			}
//		}, {
			text : "关闭",
			icon : 'delete',
			showMenuFn : function(row) {
				if (row.DeliveryStatus != 'TZFH') {
					return true;
				}
				return false;
			},
			action : function(row) {
				$.ajax({
					type : 'POST',
					url : '?model=contract_common_allcontract&action=closeCont&skey='
							+ row['skey_'],
					data : {
						id : row.id,
						type : 'oa_present_present'
					},
					// async: false,
					success : function(data) {
						alert("关闭成功");
						show_page();
						return false;
					}
				});
			}
		}, {
			text : "恢复发货",
			icon : 'add',
			showMenuFn : function(row) {
				if (row.DeliveryStatus == 'TZFH') {
					return true;
				}
				return false;
			},
			action : function(row) {
				if (confirm('确定要恢复发货？')) {
					$.ajax({
						type : 'POST',
						url : '?model=contract_common_allcontract&action=recoverCont&skey='
								+ row['skey_'],
						data : {
							id : row.id,
							type : 'oa_present_present'
						},
						// async: false,
						success : function(data) {
							alert("恢复成功");
							show_page();
							return false;
						}
					});
				}
			}
		}],
		/**
		 * 快速搜索
		 */
		searchitems : [{
			display : '编号',
			name : 'Code'
		}, {
			display : '源单号',
			name : 'orderCode'
		}, {
			display : '业务编号',
			name : 'objCode'
		}, {
			display : '源单业务编号',
			name : 'rObjCode'
		},{
			display : '申请人',
			name : 'createName'
		}],
		sortname : 'ExaDT',
		sortorder : 'DESC'
	});
});