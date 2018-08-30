var show_page = function(page) {
	$("#inventoryGrid").yxgrid("reload");
};

$(function() {

	buttonsArr = [{
		name : 'view',
		text : "查询",
		icon : 'view',
		action : function() {
			showThickboxWin("?model=hr_invent_inventory&action=toSearch"
				+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=450&width=800');
		}
	},{
		name : 'exportIn',
		text : "导入",
		icon : 'excel',
		action : function(row) {
			showThickboxWin("?model=hr_invent_inventory&action=toImport"
				+ "&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=600");
		}
	},{
		name : 'exportOut',
		text : "导出",
		icon : 'excel',
		action : function(row) {
			showThickboxWin("?model=hr_invent_inventory&action=toExcelOut"
				+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=450&width=800');
		}
	}];

	$("#inventoryGrid").yxgrid({
		model : 'hr_invent_inventory',
		title : '人员盘点信息',
		bodyAlign:'center',
		isAddAction : false,
		isEditAction : false,
		isViewAction : false,
		isDelAction : false,

		//列信息
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		},{
			name : 'userNo',
			display : '员工编号',
			width:70,
			sortable : true
		},{
			name : 'userName',
			display : '员工姓名',
			sortable : true,
			width:60,
			process : function(v,row){
				return "<a href='#' onclick='showThickboxWin(\"?model=hr_invent_inventory&action=toView&id=" + row.id + '&skey=' + row.skey_ + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=900\")'>" + v + "</a>";
			}
		},{
			name : 'deptNameS',
			display : '部门',
			sortable : true
		},{
			name : 'position',
			display : '职位',
			sortable : true
		},{
			name : 'entryDate',
			display : '入职日期',
			width:80,
			sortable : true
		},{
			name : 'inventoryDate',
			display : '盘点日期',
			width:80,
			sortable : true
		},{
			name : 'alternative',
			display : '此职位的市场可替代性',
			sortable : true
		},{
			name : 'matching',
			display : '现工作能力与现在职位的匹配度',
			sortable : true
		},{
			name : 'isCritical',
			display : '是否关键员工',
			sortable : true
		},{
			name : 'critical',
			display : '员工关键性',
			sortable : true
		},{
			name : 'isCore',
			display : '核心保留人才',
			sortable : true
		},{
			name : 'recruitment',
			display : '市场招聘难度',
			sortable : true
		},{
			name : 'recruitment',
			display : '对绩效达成情况的评价',
			sortable : true
		},{
			name : 'examine',
			display : '上一季度考核是否排后5%',
			sortable : true
		},{
			name : 'preEliminated',
			display : '是否为预淘汰人员',
			sortable : true
		},{
			name : 'remark',
			display : '是否可能流失',
			sortable : true
		},{
			name : 'adjust',
			display : '对此员工的后续调整方向',
			sortable : true
		},{
			name : 'workQuality',
			display : '工作质量',
			sortable : true
		},{
			name : 'workEfficiency',
			display : '工作效率',
			sortable : true
		},{
			name : 'workZeal',
			display : '工作激情',
			sortable : true
		}],

		lockCol:['userNo','userName','deptNameS'],//锁定的列名

		buttonsEx : buttonsArr,

		menusEx : [{
			name : 'view',
			text : '查看',
			icon : 'view',
			action : function(row, rows, grid) {
				if (row) {
					showThickboxWin("?model=hr_invent_inventory&action=toView&id="
						+ row.id
						+ "&perm=view&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=900");
				} else {
					alert("请选中一条数据");
				}
			}
		}],

		/**
		 * 快速搜索
		 */
		searchitems : [{
			display : '员工编号',
			name : 'userNoM'
		},{
			display : '员工名称',
			name : 'userNameM'
		},{
			display : '部门',
			name : 'deptName'
		}],

		sortorder : "DESC",
		sortname : "id",
		title : '员工盘点信息'
	});
});