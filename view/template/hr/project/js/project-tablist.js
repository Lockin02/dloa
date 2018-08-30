var show_page = function(page) {
	$("#tablistGrid").yxgrid("reload");
};
$(function() {
	$("#tablistGrid").yxgrid({
		model : 'hr_project_project',
		title : '项目经历',
		param : {"userNo" : $("#userNo").val()},
		showcheckbox:false,
		isAddAction : false,
		isEditAction : false,
		isDelAction:false,
		isOpButton : false,
		bodyAlign:'center',
		//列信息
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'userNo',
			display : '员工编号',
			sortable : true,
			width:80,
			process : function(v,row){
				return "<a href='#' onclick='showThickboxWin(\"?model=hr_project_project&action=toView&id=" + row.id + '&skey=' + row.skey_ + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=800\")'>" + v + "</a>";
			}
		}, {
			name : 'userName',
			display : '员工姓名',
			width:80,
			sortable : true
		}, {
			name : 'deptName',
			display : '部门名称',
			sortable : true
		}, {
			name : 'jobName',
			display : '职位',
			sortable : true
		},{
			name : 'projectName',
			display : '项目名称',
			sortable : true,
			width:150
		}, {
			name : 'projectManager',
			display : '项目经理',
			sortable : true
		},  {
			name : 'beginDate',
			display : '参加项目开始时间',
			sortable : true
		}, {
			name : 'closeDate',
			display : '参加项目结束时间',
			sortable : true
		}],
		toViewConfig : {
			action : 'toView'
		},
		/**
		 * 快速搜索
		 */
		searchitems : [{
						display : "部门",
						name : 'deptNameSearch'
					},{
						display : "职位",
						name : 'jobNameSearch'
					},{
						display : "项目名称",
						name : 'projectNameSearch'
					},{
						display : "项目经理",
						name : 'projectManagerSearch'
					}]
	});
});