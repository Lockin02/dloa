var show_page = function(page) {
	$("#weeklyGrid").yxgrid("reload");
};
$(function() {
	var role = $("#role").val();
	buttonsArr = [], addButton = {
		name : 'Add',
		// hide : true,
		text : "新增",
		icon : 'add',

		action : function(row) {
			showThickboxWin("?model=hr_tutor_weekly&action=toAdd&tutorId="
					+ $("#tutorId").val()
					+ "&placeValuesBefore&TB_iframe=true&modal=false&height=800&width=1000")
		}
	}
	if (role == "学员") {
		buttonsArr.push(addButton);
	}else{
		var paramState='1';
	}

	$("#weeklyGrid").yxgrid({
		model : 'hr_tutor_weekly',
		title : '员工辅导周报',
		action:'pageForRead',
		isViewAction : false,
		isAddAction : false,
		isEditAction : false,
		isDelAction : false,
		isOpButton : false,
		showcheckbox : false,
		bodyAlign:'center',
		buttonsEx : buttonsArr,
		customCode : 'hr_weekly_list',
		param : {
			"tutorId" : $("#tutorId").val(),
			"role" : $("#role").val(),
			"state":paramState
		},
		event : {
			'row_dblclick' : function(e, row, data) {
				showThickboxWin("?model=hr_tutor_weekly&action=toView&id="+data.id
					+ "&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=800")
			}
		},
		// 扩展右键菜单
		menusEx : [{
			text : '查看',
			icon : 'view',
			action : function(row) {
				showThickboxWin("?model=hr_tutor_weekly&action=toView&id="+row.id
					+ "&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=1000")
			}
		},{
			text : '编辑',
			icon : 'edit',
			 showMenuFn : function(row) {

			 if (role == '学员' && row.state == '0') {
			    return true;
			 }
			    return false;
			 },
			action : function(row) {
				showThickboxWin("?model=hr_tutor_weekly&action=toEditWeekly&id="+row.id
					+ "&placeValuesBefore&TB_iframe=true&modal=false&height=800&width=1000")
			}
		},{
			text : '批复周报',
			icon : 'edit',
			 showMenuFn : function(row) {

			 if (role == '导师' && row.isSign == '0') {
			    return true;
			 }
			    return false;
			 },
			action : function(row) {
				showThickboxWin("?model=hr_tutor_weekly&action=toEdit&id="+row.id
					+ "&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=1000")
			}
		}],
		comboEx : [{
			text : '批复状态',
			key : 'isSign',
			data : [{
				text : '未批复',
				value : '0'
			}, {
				text : '已批复',
				value : '1'
			}]
		}],
		// 列信息
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'studentName',
			display : '学员姓名',
			width:'80',
			sortable : true
		}, {
			name : 'studentDeptName',
			display : '学员部门',
			width:'80',
			sortable : true
		}, {
			name : 'studentJob',
			display : '学员职位',
			width:'80',
			sortable : true
		}, {
			name : 'userName',
			display : '导师姓名',
			width:'80',
			sortable : true
		},{
			name : 'state',
			display : '提交状态',
			sortable : true,
			width:'80',
			process : function(v, row) {
				if (v == '0') {
					return "<span style='color:red'>未提交</span>";
				} else if (v == '1') {
					return "已提交";
				}
			}
		}, {
			name : 'isSign',
			display : '是否批复',
			sortable : true,
			width:'80',
			process : function(v, row) {
				if (v == '0') {
					return "<span style='color:red'>未批复</span>";
				} else if (v == '1') {
					return "已批复";
				}
			}
		}, {
			name : 'signDate',
			display : '导师批复日期',
			width:'80',
			sortable : true
		}, {
			name : 'submitDate',
			display : '提交日期',
			width:'80',
			sortable : true
		},{
			name : 'isOnTime',
			display : '是否按期批复',
			width:'100',
			sortable : true,
			process:function(v){
				if(v!=null&&v!=''){
					if(v==0){
					  return '否';
					}else{
					  return '是';
					}
				}
			}
		}],

		toEditConfig : {
			action : 'toEdit'
		},
		toViewConfig : {
			action : 'toView'
		},
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
			display : "学员部门",
			name : 'studentDeptNameSearch'
		}]
	});
});