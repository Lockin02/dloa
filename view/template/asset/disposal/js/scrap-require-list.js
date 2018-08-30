/**
 * 确认报废申请列表
 */
var show_page = function(page) {
	$("#requireList").yxsubgrid("reload");
};
$(function() {
	$("#requireList").yxsubgrid({
		model : 'asset_disposal_scrap',
		action : 'requirePageJson',
		title : '确认报废申请',
		showcheckbox : false,
		isViewAction : false,
		isEditAction : false,
		isAddAction : false,
		isDelAction : false,
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
		}, {
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
				display : '备注',
				name : 'remark',
				width : 150
			}]
		},
		// 扩展右键菜单
		menusEx : [{
			text : '核对',
			icon : 'add',
			showMenuFn : function(row) {
				if (row.financeStatus == "财务确认" || row.ExaStatus == "打回") {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				if (row) {
					showThickboxWin('?model=asset_disposal_scrap&action=toCheckRequire&id='
							+ row.id
							+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=900");
				} else {
					alert("请选中一条数据");
				}
			}

		},{
			text : '提交审批',
			icon : 'add',
			showMenuFn : function(row) {
				if (row.financeStatus == "已确认" && row.ExaStatus != '部门审批') {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				if (row) {
					showThickboxWin('controller/asset/disposal/ewf_index.php?actTo=ewfSelect&billId='
							+ row.id
							+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=750");
				} else {
					alert("请选中一条数据");
				}
			}

		}, {
			name : 'cancel',
			text : '撤消审批',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.ExaStatus == '部门审批') {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				if (row) {
					$.ajax({
						type : "POST",
						url : "?model=common_workflow_workflow&action=isAudited",
						data : {
							billId : row.id,
							examCode : 'oa_asset_scrap'
						},
						success : function(msg) {
							if (msg == '1') {
								alert('单据已经存在审批信息，不能撤销审批！');
						    	show_page();
								return false;
							}else{
								if(confirm('确定要撤消审批吗？')){
									$.ajax({
									    type: "GET",
									    url: "controller/asset/disposal/ewf_index.php?actTo=delWork&billId=",
									    data: {"billId" : row.id },
									    async: false,
									    success: function(data){
									    	alert(data)
									    	show_page();
										}
									});
								}
							}
						}
					});
				} else {
					alert("请选中一条数据");
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
		// 默认搜索字段名
		sortname : "id",
		// 默认搜索顺序 降序DESC 升序ASC
		sortorder : "DESC"

	});
});
