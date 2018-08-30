var show_page = function(page) {
	$("#coachplanGrid").yxgrid("reload");
};
$(function() {
	$("#coachplanGrid").yxgrid({
		model : 'hr_tutor_coachplan',
		title : '员工辅导计划',
		action : 'pageJsonForCoachplan',
		isAddAction : false,
		isEditAction : false,
		isDelAction : false,
		showcheckbox : false,
		isOpButton : false,
		bodyAlign:'center',
		customCode : 'hr_coachplan_list',
		//列信息
		colModel : [{
				display : 'id',
				name : 'id',
				sortable : true,
				hide : true
			}, {
				name : 'studentNo',
				display : '学员员工编号',
				sortable : true,
				width:'80',
				hide : true
			}, {
				name : 'studentName',
				display : '学员姓名',
				width:'60',
				sortable : true
			}, {
				name : 'studentJob',
				display : '学员职位',
				width:'80',
				sortable : true
			}, {
				name : 'studentDeptName',
				display : '学员部门',
				width:'80',
				sortable : true
			}, {
				name : 'userNo',
				display : '导师员工编号',
				width:'80',
				sortable : true,
				hide : true
			}, {
				name : 'userName',
				display : '导师姓名',
				width:'60',
				sortable : true
			},{
				name : 'jobName',
				display : '导师职位',
				width:'80',
				sortable : true
			},{
				name : 'deptName',
				display : '导师部门',
				width:'80',
				sortable : true
			}, {
				name : 'studentSuperior',
				display : '学员直接上级',
				width:'80',
				sortable : true
			},{
				name : 'createTime',
				display : '提交时间',
				width:'80',
				sortable : true
			},{
				name : 'reachinfoStu',
				display : '学员是否已经确认辅导计划达成情况',
				sortable : true,
				type:'statictext',
				width:'210',
				process:function(v){
					if(v==1&&v!=null){
						return "是";
					}else{
						return "否";
					}
				}
			}, {
				name : 'reachinfoTut',
				display : '导师是否已经确认辅导计划达成情况',
				sortable : true,
				width:'210',
				process:function(v){
					if(v==1&&v!=null){
						return "是";
					}else{
						return "否";
					}
				}
			}],
		lockCol:['studentNo','studentName','studentJob'],//锁定的列名
		toViewConfig : {
			toViewFn : function(p,g) {
				var rowObj = g.getSelectedRow();
				var rowData = rowObj.data('data');
				showModalWin("?model=hr_tutor_coachplan&action=toView&id=" + rowData[p.keyField] );
			}
		},
		// 扩展右键菜单
		menusEx : [{
			name : 'aduit',
			text : '审批情况',
			icon : 'view',
			showMenuFn : function(row) {
				if (row.ExaStatus == '打回' || row.ExaStatus == '完成' || row.ExaStatus == '部门审批') {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				if (row) {
					showThickboxWin("controller/common/readview.php?itemtype=oa_hr_tutor_coachplan&pid="
							+ row.id
							+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=800");
				}
			}
		}],
		searchitems : [{
			display : "学员姓名",
			name : 'studentNameSearch'
		},{
			display : "导师姓名",
			name : 'userNameSearch'
		},{
			display : "学员职位",
			name : 'studentJobSearch'
		},{
			display : "导师职位",
			name : 'jobNameSearch'
		},{
			display : "学员部门",
			name : 'studentDeptNameSearch'
		},{
			display : "导师部门",
			name : 'deptNameSearch'
		}]
	});
});