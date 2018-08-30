var show_page = function(page) {
	$("#handoverGrid").yxgrid("reload");
};

$(function() {
	$("#handoverGrid").yxgrid({
		model : 'hr_leave_handover',
		title : '离职交接清单',
		action : 'handoverAffirmJson&affirmUserId=' + $("#userId").val(),
		showcheckbox : false,
		isDelAction : false,
		isEditAction : false,
		isAddAction : false,
		isViewAction : false,
		isOpButton : false,
		pageSize : 20, // 每页默认的结果数
		bodyAlign:'center',

		// 扩展右键菜单
		menusEx : [{
			text : '确认离职清单',
			icon : 'add',
			showMenuFn : function(row) {
				if (row.isDone == '1' ) {
					return true;
				}
				return false;
			},
			action : function(row) {
				showModalWin('?model=hr_leave_handover&action=affirmList&handoverId=' + row.id);
			}
		},{
			text : '查看',
			icon : 'view',
			action : function(row) {
				showModalWin('?model=hr_leave_handover&action=toViewAffirmList&handoverId=' + row.id);
			}
		}],

		//列信息
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		},{
			name : 'userNo',
			display : '员工编号',
			sortable : true,
			process : function(v ,row) {
				if(row.isDone == "1") {
					return "<font color=blue>" + v + "</font>";
				} else {
					return  v ;
				}
			},
			width:70
		},{
			name : 'userName',
			display : '员工姓名',
			sortable : true,
			width:70
		},{
			name : 'companyName',
			display : '公司',
			sortable : true,
			width:80
		},{
			name : 'regionName',
			display : '区域',
			sortable : true,
			width:60
		},{
			name : 'deptName',
			display : '部门',
			sortable : true
		},{
			name : 'jobName',
			display : '职位',
			sortable : true
		},{
			name : 'entryDate',
			display : '入职日期',
			sortable : true
		},{
			name : 'quitDate',
			display : '离职日期',
			sortable : true
		},{
			name : 'salaryEndDate',
			display : '工资结算截止日期',
			width : 100,
			sortable : true
		},{
			name : 'isDone',
			display : '状态',
			process : function(v,row){
				if(v == "0" ) {
					return "完成"
				} else {
					return  "未完成" ;
				}
			},
			width:70
		}],

		toEditConfig : {
			action : 'toEdit'
		},
		toViewConfig : {
			action : 'toView'
		},

		comboEx : [{
			text : '状态',
			key : 'isDone',
			data : [{
				text : '完成',
				value : '0'
			},{
				text : '未完成',
				value : '1'
			}]
		}],

		searchitems : [{
			display : "员工编号",
			name : 'userNoSearch'
		},{
			display : "员工姓名",
			name : 'userName'
		},{
			display : "部门",
			name : 'deptName'
		},{
			display : "职位",
			name : 'jobName'
		}],

		sortorder : "DESC",
		sortname : "id"
	});
});