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
			'states' : '2,4',
			'shipCondition' : shipCondition,
			'ExaStatus' : '完成'
		},

		title : '发货需求',
		isViewAction : false,
		isEditAction : false,
		isDelAction : false,
		showcheckbox : false,
		isAddAction : false,
		customCode : 'contractShipInfo',
		// 扩展右键菜单

		menusEx : [{
			text : '查看合同',
			icon : 'view',
			action : function(row) {
				showModalWin('?model=contract_contract_contract&action=showViewTab&id='
						+ row.id + "&skey=" + row['skey_']);
			}
		},{
			text : '查看发货物料',
			icon : 'view',
			showMenuFn : function(row) {
				if (row.linkId) {
					return true;
				}
				return false;
			},
			action : function(row) {
				showModalWin('?model=contract_contract_equ&action=toViewTab&id='
						+ row.linkId + "&skey=" + row['skey_']);
			}
		}, {
			text : '确认发货物料',
			icon : 'view',
			showMenuFn : function(row) {
				if (row.dealStatus == 0 && row.lExaStatus == '') {
					return true;
				}
				return false;
			},
			action : function(row) {
				showOpenWin('?model=contract_contract_equ&action=toEquAdd&id='
						+ row.id + "&skey=" + row['skey_']);
			}
		}, {
			text : '编辑发货物料',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.dealStatus == 0 && row.lExaStatus == '未审批') {
					return true;
				}
				return false;
			},
			action : function(row) {
				showOpenWin('?model=contract_contract_equ&action=toEquEdit&id='
						+ row.id + "&skey=" + row['skey_']);
			}
		}, {
			text : '发货物料变更',
			icon : 'delete',
			showMenuFn : function(row) {
				if (row.dealStatus == 1 && row.lExaStatus == '完成') {
					return true;
				}
				return false;
			},
			action : function(row) {
				showOpenWin('?model=contract_contract_equ&action=toEquChange&id='
						+ row.id + "&skey=" + row['skey_']);
			}
		}, {
			text : '审批情况',
			icon : 'view',
			showMenuFn : function(row) {
				if (row.ExaStatus == '部门审批') {
					return true;
				}
				return false;
			},
			action : function(row) {

				showThickboxWin('controller/contract/contract/readview.php?itemtype=oa_contract_equ_link&pid='
						+ row.lid
						+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=600');
			}
		}],

		// 列信息
		colModel : [{
			display : '建立时间',
			name : 'ExaDTOne',
			sortable : true,
			width : 70
		}, {
			name : 'contractRate',
			display : '进度',
			sortable : false,
			process : function(v,row){
				return '<a href="javascript:void(0)" onclick="javascript:showThickboxWin(\'?model=stock_outplan_contractrate&action=updateRate&docId='
						+ row.id
						+ "&docType=contract"
						+ "&objCode="
						+ row.objCode
						+ "&skey="
						+ row['skey_']
						+'&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800\')">备注：'+"<font color='gray'>"+v+"</font>"+'</a>';
			}
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
				if (row.isR == 1) {
					return "<font color = '#0000FF'>"
							+ '<a href="javascript:void(0)" onclick="javascript:showOpenWin(\'?model=contract_contract_contract&action=init&perm=view&id='
							+ row.id
							+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800\')">'
							+ "<font color = '#0000FF'>" + v + "</font>"
							+ '</a>';
				} else if (row.isBecome == 1) {
					return "<font color = '#FF0000'>"
							+ '<a href="javascript:void(0)" onclick="javascript:showOpenWin(\'?model=contract_contract_contract&action=init&perm=view&id='
							+ row.id
							+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800\')">'
							+ "<font color = '#FF0000'>" + v + "</font>"
							+ '</a>';
				} else {
					return '<a href="javascript:void(0)" onclick="javascript:showOpenWin(\'?model=contract_contract_contract&action=init&perm=view&id='
							+ row.id
							+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800\')">'
							+ "<font color = '#4169E1'>"
							+ v
							+ "</font>"
							+ '</a>';
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
//			name : 'state',
//			display : '合同状态',
//			sortable : true,
//			process : function(v) {
//				if (v == '0') {
//					return "未提交";
//				} else if (v == '1') {
//					return "审批中";
//				} else if (v == '2') {
//					return "执行中";
//				} else if (v == '3') {
//					return "已关闭";
//				} else if (v == '4') {
//					return "已完成";
//				} else if (v == '5') {
//					return "已合并";
//				} else if (v == '6') {
//					return "已拆分";
//				}
//			},
//			width : 60
//		}, {
			name : 'dealStatus',
			display : '处理状态',
			sortable : true,
			process : function(v) {
				if (v == '0') {
					return "未处理";
				} else if (v == '1') {
					return "已处理";
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
				}
			},
			width : 50
		}, {
			name : 'objCode',
			display : '业务编号',
			sortable : true,
			width : 120,
			hide : true
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
				text : '未发货',
				value : 'WFH'
			}, {
				text : '部分发货',
				value : 'BFFH'
			}, {
				text : '已发货',
				value : 'YFH'
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
					return '<a href="javascript:void(0)" onclick="javascript:showThickboxWin(\'?model=goods_goods_goodsbaseinfo&action=toView&id='
							+ row.conProductId
							+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800\')">'
							+ "<font color = '#4169E1'>"
							+ v
							+ "</font>"
							+ '</a>';
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
			}, {
				name : 'licenseButton',
				display : '加密配置',
				process : function(v, row) {
					if (row.license != "") {
						return "<a href='#' onclick='showLicense(\""
								+ row.license + "\")'>查看</a>";
					} else {
						return "";
					}
				}
			}, {
				name : 'deployButton',
				display : '产品配置',
				process : function(v, row) {
					if (row.deploy != "") {
						return "<a href='#' onclick='showGoods(\"" + row.deploy
								+ "\",\"" + row.conProductName + "\")'>查看</a>";
					} else {
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
		sortname : "id"
	});
});