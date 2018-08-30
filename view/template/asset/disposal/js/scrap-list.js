/**
 * 资产报废信息列表
 *
 * @linzx
 */
var show_page = function(page) {
	$("#datadictList").yxsubgrid("reload");
};
$(function() {
	$("#datadictList").yxsubgrid({
		model : 'asset_disposal_scrap',
		title : '资产报废',
		isToolBar : true,
		showcheckbox : false,
		// isViewAction : false,
		// isEditAction : false,
		// isAddAction : false,
		isDelAction : false,
		isViewAction : false,
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			display : '报废编号',
			name : 'billNo',
			sortable : true,
			width : 110
		}, {
			display : '报废申请日期',
			name : 'scrapDate',
			sortable : true,
			width : 80
		}, {
			display : '报废申请部门Id',
			name : 'deptId',
			sortable : true,
			hide : true
		}, {
			display : '报废申请部门',
			name : 'deptName',
			sortable : true,
			width : 90
		}, {
			display : '报废申请人',
			name : 'proposer',
			sortable : true
		}, {
			display : '报废总数',
			name : 'scrapNum',
			sortable : true,
			width : 60
		}, {
			display : '报废原因',
			name : 'reason',
			sortable : true,
			width : 70
		},
		// {
		// display : '报废总金额',
		// name : 'amount',
		// sortable : true
		// },
		{
			display : '报废总残值',
			name : 'salvage',
			sortable : true,
			// 列表格式化千分位
			process : function(v) {
				return moneyFormat2(v);
			},
			width : 80
		}, {
			display : '报废总净值',
			name : 'netValue',
			sortable : true,
			// 列表格式化千分位
			process : function(v) {
				return moneyFormat2(v);
			},
			width : 80
		}, {
			display : '财务确认状态',
			name : 'financeStatus',
			sortable : true,
			width : 70
		}, {
			display : '审批状态',
			name : 'ExaStatus',
			sortable : true,
			width : 70
		}, {
			display : '审批时间',
			name : 'ExaDT',
			sortable : true,
			// 只有审批状态为完成或打回时才显示
			process : function(v,row) {
				if(row.ExaStatus == '完成' || row.ExaStatus == '打回'){
					return v;
				}
				return '';
			},
			width : 70
		}, {
			display : '备注',
			name : 'remark',
			sortable : true,
			width : 150
		}],
		// 列表页加上显示从表
		subGridOptions : {
			url : '?model=asset_disposal_scrapitem&action=pageJson',
			param : [{
				paramId : 'allocateID',
				colId : 'id'
			}],
			colModel : [{
				display : '卡片编号',
				name : 'assetCode',
				width : 160
			}, {
				display : '资产名称',
				name : 'assetName',
				width : 150
			}, {
				display : '规格型号',
				name : 'spec'
			}, {
				display : '购置日期',
				name : 'buyDate',
				width : 80
			}, {
				display : '资产原值',
				name : 'origina',
				// 列表格式化千分位
				process : function(v) {
					return moneyFormat2(v);
				}
			}, {
				display : '残值',
				name : 'salvage',
				// 列表格式化千分位
				process : function(v) {
					return moneyFormat2(v);
				}
			}, {
				display : '净值',
				name : 'netValue',
				// 列表格式化千分位
				process : function(v) {
					return moneyFormat2(v);
				}
			}, {
//				display : '已提折旧',
//				name : 'depreciation',
//				// 列表格式化千分位
//				process : function(v) {
//					return moneyFormat2(v);
//				}
//			}, {
				display : '备注',
				name : 'remark',
				width : 150
			}]
		},
		toAddConfig : {
			formWidth : 1000,
			formHeight : 600
		},
		toEditConfig : {
			formWidth : 1000,
			formHeight : 450,
			showMenuFn : function(row) {
				if ((row.ExaStatus == "待提交" || row.ExaStatus == "打回") && (row.financeStatus == '待提交' || row.financeStatus == '打回')) {
					return true;
				}
				return false;
			}
		},
		// 扩展按钮
		// buttonEx : [{
		// name : 'Add',
		// // hide : true,
		// text : "新增",
		// icon : 'add',
		// /**
		// * row 最后一条选中的行 rows 选中的行（多选） rowIds
		// * 选中的行id数组 grid 当前表格实例
		// */
		// action : function(row, rows, grid) {
		// showWin('replacement-add.htm','900','600','新增资产置换');
		// }
		//
		//
		// }],

		// 扩展右键菜单
		menusEx : [{
			text : '查看',
			icon : 'view',
			action : function(row) {
				if (row) {
					showOpenWin("?model=asset_disposal_scrap&action=init&perm=view&id="
							+ row.id
							+ "&skey=" + row['skey_'],1,700,1900);

				}
			}
		}, {
//			name : 'aduit',
//			text : '审批情况',
//			icon : 'view',
//			showMenuFn : function(row) {
//				if ((row.ExaStatus == "完成" || row.ExaStatus == "打回" || row.ExaStatus == "部门审批")) {
//					return true;
//				}
//				return false;
//			},
//			action : function(row, rows, grid) {
//				if (row) {
//					showThickboxWin("controller/common/readview.php?itemtype=oa_asset_scrap&pid="
//							+ row.id
//							+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=300&width=800");
//				}
//			}
//		}, {
//			name : 'clear',
//			text : '清理卡片',
//			icon : 'add',
//			showMenuFn : function(row) {
//				if ((row.ExaStatus == "完成")) {
//					return true;
//				}
//				return false;
//			},
//			action : function(row, rows, grid) {
//				if (row) {
//					location = "?model=asset_assetcard_clean&action=toCleanScrap&billNo="
//							+ row.billNo + "&allocateID=" + row.id;
//				} else {
//					alert("请选中一条数据");
//				}
//			}
//		}, {
			text : '删除',
			icon : 'delete',
			showMenuFn : function(row) {
				if ((row.ExaStatus == '待提交' || row.ExaStatus == '打回') && (row.financeStatus == '待提交' || row.financeStatus == '打回')) {
					return true;
				}
				return false;
			},
			action : function(row) {
				if (window.confirm(("确定删除吗？"))) {
					$.ajax({
						type : "POST",
						url : "?model=asset_disposal_scrap&action=deletes&id="
								+ row.id,
						success : function(msg) {
							// alert(msg);
							$("#datadictList").yxsubgrid("reload");
						}
					});
				}
			}
		},{
			text : '撤回',
			icon : 'delete',
			showMenuFn : function(row) {
				if (row.financeStatus == "财务确认") {
					return true;
				}
				return false;
			},
			action : function(row) {
				if (window.confirm(("确定要撤回并发送邮件通知相关人员?"))) {
					$.ajax({
						type : "POST",
						url : "?model=asset_disposal_scrap&action=recall",
						data : {
							id : row.id
						},
						async : false,
						success : function(msg) {
							if (msg == 1) {
								alert("撤回成功!");
								show_page(1);
							} else {
								alert("撤回失败!");
							}
						}
					});
				}
			}
		}],

		searchitems : [{
			display : '报废单编号',
			name : 'billNo'
		}, {
			display : '报废申请人',
			name : 'proposer'
		}, {
			display : '报废申请部门',
			name : 'deptName'
		}],
		comboEx : [{
			text : '审批状态',
			key : 'ExaStatus',
			data : [{
				text : '部门审批',
				value : '部门审批'
			}, {
				text : '待提交',
				value : '待提交'
			}, {
				text : '完成',
				value : '完成'
			}, {
				text : '打回',
				value : '打回'
			}]
		}],
		// 默认搜索字段名
		sortname : "id",
		// 默认搜索顺序 降序DESC 升序ASC
		sortorder : "DESC"

	});
});
