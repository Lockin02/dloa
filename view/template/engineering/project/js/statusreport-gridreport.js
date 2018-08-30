var show_page = function(page) {
	$("#statusreportGrid").yxgrid("reload");
};

$(function() {
	$("#statusreportGrid").yxgrid({
		model : 'engineering_project_statusreport',
		action : 'pageJsonReport',
		title : '项目状态报告',
		isDelAction : false,
		isAddAction : false,
		//列信息
		colModel : [{
				display : 'id',
				name : 'id',
				sortable : true,
				hide : true
			}, {
				name : 'projectId',
				display : '项目id',
				sortable : true,
				hide : true
			}, {
				name : 'projectName',
				display : '项目名称',
				sortable : true,
				width : 140
			}, {
				name : 'projectCode',
				display : '项目编号',
				sortable : true
			}, {
				name : 'officeName',
				display : '办事处',
				sortable : true
			}, {
				name : 'createName',
				display : '提交人',
				sortable : true
			}, {
				name : 'handupDate',
				display : '提交日期',
				sortable : true,
				width : 80
			}, {
				name : 'budgetAll',
				display : '总预算',
				sortable : true,
				process : function(v){
					return moneyFormat2(v);
				},
				width : 80
			}, {
				name : 'budgetField',
				display : '现场费用预算',
				sortable : true,
				process : function(v){
					return moneyFormat2(v);
				},
				width : 80
			}, {
				name : 'feeField',
				display : '现场费用',
				sortable : true,
				process : function(v){
					return moneyFormat2(v);
				},
				width : 80
			}, {
				name : 'feeAll',
				display : '总费用',
				sortable : true,
				process : function(v){
					return moneyFormat2(v);
				},
				width : 80
			}, {
				name : 'feeAllProcess',
				display : '费用进度',
				sortable : true,
				process : function(v){
					return v + ' %';
				},
				width : 80
			}, {
				name : 'projectProcess',
				display : '工程进度',
				sortable : true,
				process : function(v){
					return v + ' %';
				},
				width : 80
			}, {
				name : 'feeFieldProcess',
				display : '现场费用进度',
				sortable : true,
				process : function(v){
					return v + ' %';
				},
				width : 80
			}, {
				name : 'planEndDate',
				display : '预计结束日期',
				sortable : true,
				process : function(v,row){
					if(v=="0000-00-00"){
						return "";
					}else{
						return  v;
					}
				},
				width : 80
			}, {
				name : 'actEndDate',
				display : '实际结束日期',
				sortable : true,
				process : function(v,row){
					if(v=="0000-00-00"){
						return "";
					}else{
						return  v;
					}
				},
				width : 80
			}, {
				name : 'changeTimes',
				display : '变更次数',
				sortable : true,
				hide : true,
				width : 80
			}, {
				name : 'status',
				display : '报告状态',
				sortable : true,
				datacode : 'XMZTBG',
				width : 80
			}, {
				name : 'confirmName',
				display : '确认人',
				sortable : true
			}, {
				name : 'confirmDate',
				display : '确认日期',
				sortable : true,
				width : 80
			}, {
				name : 'milestoneName',
				display : '完成里程碑',
				sortable : true,
				width : 80
			}, {
				name : 'createTime',
				display : '创建时间',
				sortable : true,
				hide : true
			}
		],
		toViewConfig : {
			formWidth : 1000,
			formHeight : 500
		},
		toEditConfig : {
			showMenuFn : function(row) {
				if (row.status == "XMZTBG01") {
					return true;
				}
				return false;
			},
			formWidth : 1000,
			formHeight : 500
		},
		// 扩展右键菜单
		menusEx : [
			{
				text : '确认',
				icon : 'edit',
				showMenuFn : function(row) {
					if (row.status == "XMZTBG02") {
						return true;
					}
					return false;
				},
				action : function(row, rows, grid) {
					showThickboxWin("?model=engineering_project_statusreport&action=toConfirmReport&id="
								+ row.id
								+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=1000");
				}
			},{
				text : '删除',
				icon : 'delete',
				showMenuFn:function(row){
					if (row.status == "XMZTBG01" || row.status == "") {
						return true;
					}
					return false;
				},
				action : function(rowData, rows, rowIds, g) {
					g.options.toDelConfig.toDelFn(g.options,g);
				}
			}
		],
		searchitems : [{
			display : '项目编号',
			name : 'projectCode'
		},{
			display : '项目名称',
			name : 'projectName'
		},{
			display : '提交人',
			name : 'createName'
		},{
			display : '提交日期',
			name : 'handupDateSearch'
		}],
		// 审批状态数据过滤
		comboEx : [{
			text : '报告状态',
			key: 'status',
			datacode : 'XMZTBG',
			value : 'XMZTBG02'
		}]
	});
});