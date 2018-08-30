// 用于新增/修改后回调刷新表格

var show_page = function(page) {
	$("#allocationProGrid").yxsubgrid('reload');
};

$(function() {
	$("#allocationProGrid").yxsubgrid({
		model : 'asset_daily_allocation',
		title : '固定资产调拨信息',
		action : "pageByProJson",
		param : {projectId:$("#projectId").val()},
		showcheckbox : false,
		// 列信息
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			display : '调拨单编号',
			name : 'billNo',
			sortable : true,
			width : 120
		}, {
			display : '调拨日期',
			name : 'moveDate',
			sortable : true

		}, {
			display : '调出部门Id',
			name : 'outDeptId',
			sortable : true,
			hide : true

		}, {
			display : '调出部门',
			name : 'outDeptName',
			sortable : true
		}, {
			display : '调入部门id',
			name : 'inDeptId',
			sortable : true,
			hide : true
		}, {
			display : '调入部门',
			name : 'inDeptName',
			sortable : true

		}, {
			display : '调拨申请人',
			name : 'proposer',
			sortable : true
		}, {
			display : '调入确认人',
			name : 'recipient',
			sortable : true
		}, {
			display : '备注',
			name : 'remark',
			sortable : true,
			hide : true
		}, {
			name : 'ExaStatus',
			display : '审批状态',
			sortable : true
		}, {
			name : 'ExaDT',
			display : '审批时间',
			sortable : true
		}, {
			name : 'isSign',
			display : '是否签收',
			sortable : true
		}, {
			name : 'createName',
			display : '创建人',
			sortable : true,
			hide : true
		}, {
			name : 'createId',
			display : '创建人id',
			sortable : true,
			hide : true
		}, {
			name : 'createTime',
			display : '创建日期',
			sortable : true,
			hide : true
		}, {
			name : 'updateName',
			display : '录入人',
			sortable : true,
			hide : true
		}, {
			name : 'updateId',
			display : '修改人id',
			sortable : true,
			hide : true
		}, {
			name : 'updateTime',
			display : '修改日期',
			sortable : true,
			hide : true
		}],
		// 列表页加上显示从表
		subGridOptions : {
			url : '?model=asset_daily_allocationitem&action=pageJson',
			param : [{
				paramId : 'allocateID',
				colId : 'id'
			}],
			colModel : [{
				display : '卡片编号',
				name : 'assetCode',
				width : 250
			}, {
				display : '资产名称',
				name : 'assetName'
			}, {
				display : '英文名称',
				name : 'englishName',
				readonly : true
			}, {
				display : '购置日期',
				name : 'buyDate',
				// type : 'date',
				readonly : true
			}, {
				display : '规格型号',
				name : 'spec',
				readonly : true
			}, {
				display : '附属设备',
				name : 'equip',
				type : 'statictext',
				process : function(e, data) {
					return '<a href="#" onclick="javascript:window.open(\'?model=asset_assetcard_equip&action=toPage&showBtn=0&assetCode='
							+ data.assetCode + '\')">详细</a>'
				}
			}, {
				display : '备注',
				name : 'remark'
			}]
		},
		isDelAction : false,
		isViewAction : false,
		isEditAction : false,
		toAddConfig : {
			formWidth : 1100,
			/**
			 * 新增表单默认高度
			 */
			formHeight : 400
		},

		menusEx : [{
			name : 'view',
			text : '查看',
			icon : 'view',
			action : function(row, rows, grid) {
				if (row) {
					showThickboxWin("?model=asset_daily_allocation&action=init&id="
							+ row.id
							+ "&skey="
							+ row['skey_']
							+ "&perm=view&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=1100");
				} else {
					alert("请选中一条数据");
				}
			}

		}, {
			name : 'aduit',
			text : '审批情况',
			icon : 'view',
			showMenuFn : function(row) {
				if (row.ExaStatus == '打回' || row.ExaStatus == '完成'
						|| row.ExaStatus == '部门审批') {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				if (row) {
					showThickboxWin("controller/common/readview.php?itemtype=oa_asset_allocation&pid="
							+ row.id
							+ "&skey="
							+ row['skey_']
							+ "&perm=view&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=700&width=900");
				}
			}
		}, {
			text : '签收',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.ExaStatus == "完成" && row.isSign == "未签收" && row.inProId==$("#projectId").val()) {
					return true;
				} else {
					return false;
				}

			},
			action : function(row) {
				if (window.confirm(("确定签收吗？"))) {

					$.ajax({
						type : "POST",
						url : "?model=asset_daily_allocation&action=sign&id="
								+ row.id,
						success : function(msg) {
							if (msg == 1) {
								alert('签收成功！');
								$("#allocationGrid").yxsubgrid("reload");
							} else {
								alert('签收失败！');
							}
						}
					});
				}
			}

		}],

		// 快速搜索
		searchitems : [{
			display : '调拨单编号',
			name : 'billNo'
		}, {
			display : '调拨日期',
			name : 'moveDate'
		}, {
			display : '调出部门',
			name : 'outDeptName'
		}, {
			display : '调入部门',
			name : 'inDeptName'
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
		// 默认搜索顺序

		sortorder : "DESC"

	});

});