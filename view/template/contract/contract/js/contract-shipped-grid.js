var show_page = function(page) {
	$("#contractGrid").yxsubgrid("reload");
};

$(function() {
	var shipCondition = $('#shipCondition').val();
	$("#contractGrid").yxsubgrid({
		model : 'contract_contract_contract',
		action : 'shipmentsJson',
		title : '发货需求',
		param : {
			'states' : '2,4,3,7',
			'DeliveryStatusArr' : 'YFH,TZFH',
			'ExaStatus' : '完成'
		},

		title : '发货需求',
		isViewAction : false,
		isEditAction : false,
		isDelAction : false,
		showcheckbox : false,
		isAddAction : false,
		autoload : false,
		customCode : 'contractShipInfo',
		// 扩展右键菜单

		menusEx : [{
			text : '查看合同',
			icon : 'view',
			action : function(row) {
				showModalWin('?model=contract_contract_contract&action=toViewShipInfoTab&id='
						+ row.id + "&skey=" + row['skey_'] + "&linkId=" + row.lid);
			}
		}, {
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
					url : '?model=common_contract_allsource&action=closeCont&skey='+row['skey_'],
					data : {
						id : row.id,
						type : 'oa_contract_contract'
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
			text : "恢复",
			icon : 'add',
			showMenuFn : function(row) {
				if (row.DeliveryStatus == 'TZFH') {
					return true;
				}
				return false;
			},
			action : function(row) {
				$.ajax({
					type : 'POST',
					url : '?model=common_contract_allsource&action=recoverCont&skey='+row['skey_'],
					data : {
						id : row.id,
						type : 'oa_contract_contract'
					},
					// async: false,
					success : function(data) {
						alert("恢复成功");
						show_page();
						return false;
					}
				});
			}
		}],

		// 列信息
		colModel : [{
			display : '建立时间',
			name : 'ExaDTOne',
			sortable : true,
			width : 70
		}, {
			display : '合同物料审批状态',
			name : 'lExaStatus',
			sortable : true
		}, {
			display : '合同物料审批表Id',
			name : 'lid',
			sortable : true,
			hide : true
		}, {
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
//			name : 'createTime',
//			display : '建立时间',
//			sortable : true,
//			width : 80
//		}, {
			name : 'contractType',
			display : '合同类型',
			sortable : true,
			datacode : 'HTLX',
			width : 60
		}, {
			name : 'contractCode',
			display : '合同编号',
			sortable : true,
			width : 180,
			process : function(v, row) {
				if (row.isBecome != '0') {
					return "<font color = '#FF0000'>"
							+ '<a href="javascript:void(0)" onclick="javascript:showOpenWin(\'?model=contract_contract_contract&action=toViewShipInfoTab&id='
							+ row.id
							+ '&linkId='
							+ row.linkId
							+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800\')">'
							+ "<font color = '#FF0000'>" + v
							+ "</font>" + '</a>';
				} else {
					return '<a href="javascript:void(0)" onclick="javascript:showOpenWin(\'?model=contract_contract_contract&action=toViewShipInfoTab&id='
							+ row.id
							+ '&linkId='
							+ row.linkId
							+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800\')">'
							+ "<font color = '#4169E1'>"
							+ v
							+ "</font>" + '</a>';
				}
			}
		}, {
			name : 'contractName',
			display : '合同名称',
			sortable : true,
			width : 150
		}, {
			name : 'customerName',
			display : '客户名称',
			sortable : true,
			width : 150
		}, {
			name : 'customerId',
			display : '客户Id',
			sortable : true,
			width : 100,
			hide : true
		}, {
			name : 'dealStatus',
			display : '处理状态',
			sortable : true,
			process : function(v) {
				if (v == '0') {
					return "未处理";
				} else if (v == '1') {
					return "已处理";
				} else if (v == '2') {
					return "变更未处理";
				} else if (v == '3') {
					return "已关闭";
				}
			},
			width : 50
		}, {
			name : 'ExaStatus',
			display : '审批状态',
			sortable : true,
			width : 60
		}, {
			name : 'makeStatus',
			display : '下达状态',
			sortable : true,
			process : function(v) {
				if (v == 'BFXD') {
					return "部分下达";
				} else if (v == 'YXD') {
					return "已下达";
				} else {
					return "未下达"
				}
			},
			width : 50
		}, {
			name : 'DeliveryStatus',
			display : '发货状态',
			sortable : true,
			process : function(v) {
				if (v == 'BFFH') {
					return "部分发货";
				} else if (v == 'YFH') {
					return "已发货";
				} else if ( v == 'WFH' ){
					return "未发货"
				} else if ( v == 'TZFH' ){
					return "停止发货"
				}
			},
			width : 50
		}, {
			name : 'objCode',
			display : '业务编号',
			sortable : true,
			width : 120,
			hide : true
		}, {
            name : 'outstockDate',
            display : '交付完成时间',
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
				value : 'HTLX-ZLHT'
			}, {
				text : '服务合同',
				value : 'HTLX-FWHT'
			}, {
				text : '研发合同',
				value : 'HTLX-YFHT'
			}]
		}, {
			text : '下达状态',
			key : 'makeStatus',
			data : [{
				text : '未下达',
				value : 'WXD'
			}, {
				text : '部分下达',
				value : 'BFXD'
			}, {
				text : '已下达',
				value : 'YXD'
			}]
		}, {
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
		// 主从表格设置
		subGridOptions : {
			subgridcheck:true,
			url : '?model=common_contract_allsource&action=equJson',// 获取从表数据url
			// 传递到后台的参数设置数组
			param : [{
						'docType' : 'oa_contract_contract'
					}, {
						paramId : 'contractId',// 传递给后台的参数名称
						colId : 'id'// 获取主表行数据的列名称
					}],
			// 显示的列
			colModel : [{
						name : 'productCode',
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
						process : function(v, data, rowData, $row) {
							if (data.changeTips == 1) {
								if (data.isBorrowToorder == 1) {
									$row.attr("title", "该物料为借试用转销售的物料");
									return "<img src='images/icon/icon147.gif'  title='借试用转销售物料'/>"
											+ "<font color=red>"
											+ v
											+ "</font>";
								} else {
									return "<font color=red>" + v + "</font>";
								}
							} else {
								if (data.isBorrowToorder == 1) {
									$row.attr("title", "该物料为借试用转销售的物料");
									return "<img src='images/icon/icon147.gif'  title='借试用转销售物料'/>"
											+ v;
								} else {
									return v;
								}
							}
							if (row.changeTips != 0) {
								return "<font color = 'red'>" + v + "</font>"
							} else
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
//						name : 'lockNum',
//						display : '锁定数量',
//						width : 50,
//						process : function(v) {
//							if (v == '') {
//								return 0;
//							} else
//								return v;
//						}
//					}, {
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
						width : 60,
						process : function(v) {
							if (v == '') {
								return 0;
							} else
								return v;
						}
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
		},{
			display : '申请人',
			name : 'createName'
		}],
		sortname : "id"
	});
});