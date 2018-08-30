var show_page = function(page) {
	$("#attendanceGrid").yxgrid("reload");
};
$(function() {

	buttonsArr = [];

	// 表头按钮数组
	excelOutArr = {
		name : 'exportIn',
		text : "导入",
		icon : 'excel',
		action : function(row) {
			showThickboxWin("?model=hr_personnel_attendance&action=toImport"
					+ "&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=600")
		}
	};

	$.ajax({
		type : 'POST',
		url : '?model=hr_personnel_attendance&action=getLimits',
		data : {
			'limitName' : '导入权限'
		},
		async : false,
		success : function(data) {
			if (data == 1) {
				buttonsArr.push(excelOutArr);
			}
		}
	});
	$("#attendanceGrid").yxgrid({
		model : 'hr_personnel_attendance',
		title : '考勤信息',
		// 列信息
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'userNo',
			display : '员工编号',
			sortable : true
		}, {
			// name : 'userAccount',
			// display : '员工账号',
			// sortable : true
			// }, {
			name : 'userName',
			display : '员工姓名',
			sortable : true,
			process : function(v, row) {
				return "<a href='#' onclick='showThickboxWin(\"?model=hr_personnel_attendance&action=toView&id="
						+ row.id
						+ '&skey='
						+ row.skey_
						+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=800\")'>"
						+ v + "</a>";
			}
		}, {
			// name : 'companyType',
			// display : '公司类型',
			// sortable : true
			// }, {
			name : 'companyName',
			display : '公司名称',
			sortable : true
		}, {
			name : 'deptNameS',
			display : '二级部门',
			sortable : true
		}, {
			name : 'deptNameT',
			display : '三级部门',
			sortable : true
		}, {
			name : 'beginDate',
			display : '开始时间',
			sortable : true
		}, {
			name : 'days',
			display : '天数',
			sortable : true
		}, {
			// name : 'typeCode',
			// display : '请假类型编码',
			// sortable : true
			// }, {
			name : 'typeName',
			display : '请假类型',
			sortable : true
		}, {
			// name : 'docStatus',
			// display : '状态编码',
			// sortable : true
			// }, {
			name : 'docStatusName',
			display : '状态',
			sortable : true
		}, {
			name : 'inputName',
			display : '制单人名称',
			sortable : true
		}],
		buttonsEx : buttonsArr,
		menusEx : [{
			name : 'view',
			text : '查看',
			icon : 'view',
			action : function(row, rows, grid) {
				if (row) {
					showThickboxWin("?model=hr_personnel_attendance&action=toView&id="
							+ row.id
							+ "&perm=view&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=900");
				} else {
					alert("请选中一条数据");
				}
			}
		}],

		isAddAction : false,
		isEditAction : false,
		isViewAction : false,
		isDelAction : false,
		// toEditConfig : {
		// action : 'toEdit'
		// },
		// toViewConfig : {
		// action : 'toView'
		// },
		searchitems : [{
			display : "员工编号",
			name : 'userNoM'
		}, {
			display : "员工姓名",
			name : 'userNameM'
		}, {
			display : "部门",
			name : 'deptName'
		}]
	});
});