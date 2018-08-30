var show_page = function(page) {
	$("#contractGrid").yxsubgrid("reload");
};

$(function() {
	var shipCondition = $('#shipCondition').val();
	$("#contractGrid").yxsubgrid({
		model : 'contract_contract_contract',
		action : 'shipmentsJson',
		height:270,
		title : '发货需求',
		param : {
			'states' : '2,4',
			'shipCondition' : shipCondition,
			'DeliveryStatusArr' : 'WFH,BFFH',
			'makeStatusArr' : 'WXD,BFXD',
			'ExaStatusArr' : "完成,变更审批中"
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
		}, {
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
				if ((row.dealStatus == 0) && row.lExaStatus == ''
						&& row.ExaStatus == '完成') {
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
				if (row.dealStatus == 0
						&& (row.lExaStatus == '未审批' || row.lExaStatus == '打回')
						&& row.ExaStatus == '完成') {
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
				if ((row.dealStatus == 1 || row.dealStatus == 2)
						&& row.lExaStatus == '完成' && row.ExaStatus == '完成') {
					return true;
				}
				return false;
			},
			action : function(row) {
				showOpenWin('?model=contract_contract_equ&action=toEquChange&id='
						+ row.id + "&skey=" + row['skey_']);
			}
		}, {
			text : '锁定库存',
			icon : 'lock',
			showMenuFn : function(row) {
				if (row.ExaStatus == '完成' && row.DeliveryStatus != 'YFH'
						&& row.DeliveryStatus != 'TZFH') {
					return true;
				}
				return false;
			},
			action : function(row) {
				var objCode = row.objCode;
				showOpenWin('?model=stock_lock_lock&action=toLokStock&id='
						+ row.id + "&objCode=" + objCode
						+ "&objType=oa_contract_contract&skey=" + row['skey_']);
			}
		}, {
			text : '审批情况',
			icon : 'view',
			showMenuFn : function(row) {
				if (row.lExaStatus != '') {
					return true;
				}
				return false;
			},
			action : function(row) {

				showThickboxWin('controller/contract/contract/readview.php?itemtype=oa_contract_equ_link&pid='
						+ row.lid
						+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=600');
			}
		}, {
			text : '下达发货计划',
			icon : 'add',
			showMenuFn : function(row) {
				if (row.dealStatus == 1
						&& row.ExaStatus == '完成'
						&& row.lExaStatus == '完成'
						&& row.makeStatus != 'YXD'
						&& (row.DeliveryStatus == 'WFH' || row.DeliveryStatus == 'BFFH')) {
					return true;
				}
				return false;
			},
			action : function(row, rows, rowIds, g) {
				var idArr = g.getSubSelectRowCheckIds(rows);
				showOpenWin("?model=stock_outplan_outplan&action=toAdd&id="
						+ row.id + "&equIds=" + idArr
						+ "&docType=oa_contract_contract" + "&skey="
						+ row['skey_']);
			}
		}, {
			text : '下达采购申请',
			icon : 'add',
			showMenuFn : function(row) {
				if ((row.dealStatus == 1 && row.ExaStatus == '完成' && (row.DeliveryStatus == 'WFH' || row.DeliveryStatus == 'BFFH'))) {
					return true;
				}
				return false;
			},
			action : function(row, rows, rowIds, g) {
				var idArr = g.getSubSelectRowCheckIds(rows);
				showOpenWin("?model=purchase_external_external&action=toAddByContract&contractId="
						+ row.id
						+ "&purchType="
						+ row.contractType
						+ "&contractName="
						+ row.contractName
						+ "&contractCode="
						+ row.contractCode
						+ "&objCode="
						+ row.objCode
						+ "&equIds="
						+ idArr
						+ "&skey="
						+ row['skey_']);
			}
		}],

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
					display : '建立时间',
					name : 'ExaDTOne',
					sortable : true,
					width : 70
				}, {
					name : 'contractRate',
					display : '进度',
					sortable : false,
					process : function(v, row) {
						return '<a href="javascript:void(0)" onclick="javascript:showThickboxWin(\'?model=stock_outplan_contractrate&action=updateRate&docId='
								+ row.id
								+ "&docType=oa_contract_contract"
								+ "&objCode="
								+ row.objCode
								+ "&skey="
								+ row['skey_']
								+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800\')">备注：'
								+ "<font color='gray'>"
								+ v
								+ "</font>"
								+ '</a>';
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
									+ "<font color = '#0000FF'>" + v
									+ "</font>" + '</a>';
						} else if (row.isBecome == 1) {
							return "<font color = '#FF0000'>"
									+ '<a href="javascript:void(0)" onclick="javascript:showOpenWin(\'?model=contract_contract_contract&action=init&perm=view&id='
									+ row.id
									+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800\')">'
									+ "<font color = '#FF0000'>" + v
									+ "</font>" + '</a>';
						} else {
							return '<a href="javascript:void(0)" onclick="javascript:showOpenWin(\'?model=contract_contract_contract&action=init&perm=view&id='
									+ row.id
									+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800\')">'
									+ "<font color = '#4169E1'>"
									+ v
									+ "</font>" + '</a>';
						}
					}
				}, {
					name : 'customerName',
					display : '客户名称',
					sortable : true,
					width : 150
				}, {
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
					name : 'objCode',
					display : '业务编号',
					sortable : true,
					width : 120,
					hide : true
				}],
		// 主从表格设置
		subGridOptions : {
			subgridcheck : true,
			url : '?model=common_contract_allsource&action=equJson',// 获取从表数据url
			// 传递到后台的参数设置数组
			param : [{
						'docType' : 'oa_contract_contract'
					}, {
						paramId : 'contractId',// 传递给后台的参数名称
						colId : 'id'// 获取主表行数据的列名称
						// }, {
						// paramId : 'ifDeal',// 传递给后台的参数名称
						// colId : '1'// 获取主表行数据的列名称
					}],
			// 显示的列
			colModel : [{
						name : 'productName',
						width : 200,
						display : '物料名称',
						process : function(v, row) {
							if (row.changeTips == 1) {
								return "<font color = 'red'>" + v + "</font>"
							} else
								return v;
						}
					}, {
						name : 'number',
						display : '数量',
						width : 40
					}, {
						name : 'lockNum',
						display : '锁定数量',
						width : 50,
						process : function(v) {
							if (v == '') {
								return 0;
							} else
								return v;
						}
					}, {
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