var show_page = function(page) {
	$("#personnelGrid").yxgrid("reload");
};

$(function(){
	//部门
	$("#deptName").yxselect_dept({
		hiddenId : 'deptId',
		mode : 'no'
	});
//	initGrid();
});

//初始化列表 - 查询明细
function initGrid(){
	var objGrid = $("#personnelGrid");
	//工作日志
	objGrid.yxeditgrid("remove").yxeditgrid({
		url : '?model=hr_personnel_personnel&action=listJsonEngineering',
		param : {"searchDate":$("#searchDate").val(),"employeesState":$("#employeesState").val()},
		type : 'view',
		//列信息
		colModel : [{
			name : 'userNo',
			display : '员工编号',
			sortable : true,
			process : function(v, row) {
				return "<a href='#' title='点击查看员工信息' onclick='viewPersonnel(\""
						+ row.id
						+ "\",\""
						+ row.userNo
						+ "\",\""
						+ row.userAccount + "\")' >" + v + "</a>";
			},
			hide : true,
			width : 80
		}, {
			name : 'userName',
			display : '姓名',
			sortable : true,
			width : 80
		}, {
			name : 'entryDate',
			display : '入职日期',
			sortable : true,
			width : 80
		}, {
			name : 'personLevel',
			display : '技术等级',
			sortable : true,
			width : 60
		}, {
			name : 'belongDeptName',
			display : '所属部门',
			sortable : true,
			width : 80,
			hide:true
		}, {
			name : 'workGroup',
			display : '工作组',
			sortable : true,
			width : 80,
			hide:true
		}, {
			name : 'officeName',
			display : '所属区域',
			sortable : true,
			width : 80,
			hide:true
		}, {
			name : 'eprovinceCity',
			display : '无补助城市',
			sortable : true,
			width : 70
		}, {
			name : 'belongProject',
			display : '归属项目',
			sortable : true,
			width : 120
		}, {
			name : 'projectCode',
			display : '项目编号',
			sortable : true,
			hide:true
		}, {
			name : 'projectName',
			display : '参与项目',
			sortable : true,
			width : 120
		}, {
			name : 'workStatus',
			display : '工作状态',
			sortable : true,
			width : 80
		}, {
			name : 'projectEndDate',
			display : '项目预计结束',
			sortable : true,
			width : 80
		}, {
			name : 'activityName',
			display : '参与任务',
			sortable : true
		}, {
			name : 'activityEndDate',
			display : '任务预计结束',
			sortable : true,
			width : 80
		}, {
			name : 'technology',
			display : '技术领域',
			sortable : true,
			width : 80
		}, {
			name : 'net',
			display : '网络',
			sortable : true,
			width : 80
		}, {
			name : 'equLevel',
			display : '设备厂家及级别',
			sortable : true,
			width : 80
		}, {
			name : 'updateTime',
			display : '最近更新',
			sortable : true,
			width : 120,
			hide:true
		}]
	});
}

//设置值
function setVal(obj,thisKey,thisVal){
//	parent.$("#" + thisKey).val(thisVal);
	return obj.options.param[thisKey] = thisVal;
}

//缓存表格对象
var gridObj;


//初始化列表
function initPageGrid(){
	var searchDate = $("#searchDate").val();
	var employeesState = $("#employeesState").val();
	var deptId = $("#deptId").val();
	if(gridObj){
		//主列表对象获取
		var gridData = $("#personnelGrid").data('yxgrid');
		setVal(gridData,'searchDate',searchDate);
		setVal(gridData,'employeesState',employeesState);
		setVal(gridData,'belongDeptId',deptId);
		gridData.reload();
		return false;
	}
	gridObj = $("#personnelGrid");
	var thisHeight = document.documentElement.clientHeight - 120;

	//表头按钮数组
	var buttonsArr = [{
		name : 'view',
		text : "查询",
		icon : 'view',
		action : function() {
			showThickboxWin("?model=hr_personnel_personnel&action=toSearch&"
					+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=450&width=800');
		}
	}, {
		name : 'export',
		text : "导出EXCEL",
		icon : 'excel',
		action : function(row) {
			window.open("?model=hr_personnel_personnel&action=outExcelEngineering&belongDeptId="+$("#deptId").val()
				+"&searchDate="+$("#searchDate").val()
				+"&employeesState="+$("#employeesState").val(),
				"", "width=200,height=200,top=200,left=200");
		}
	}];

	gridObj.yxgrid({
		model : 'hr_personnel_personnel',
		action : 'pageJsonEngineering',
		title : '人员状态表',
		height : thisHeight,
		param : { "searchDate" : searchDate,"employeesState" : employeesState,"belongDeptId" : deptId },
		showcheckbox : false,
		isAddAction : false,
		isEditAction : false,
		isDelAction : false,
		isViewAction : false,
		customCode : 'personnelGrid',
		//列信息
		colModel : [{
			name : 'userNo',
			display : '员工编号',
			sortable : true,
//			process : function(v, row) {
//				return "<a href='#' title='点击查看员工信息' onclick='viewPersonnel(\""
//						+ row.id
//						+ "\",\""
//						+ row.userNo
//						+ "\",\""
//						+ row.userAccount + "\")' >" + v + "</a>";
//			},
			hide : true,
			width : 80
		}, {
			name : 'userName',
			display : '姓名',
			sortable : true,
			width : 80
		}, {
			name : 'entryDate',
			display : '入职日期',
			sortable : true,
			width : 80
		}, {
			name : 'personLevel',
			display : '技术等级',
			sortable : true,
			width : 60
		}, {
			name : 'belongDeptName',
			display : '所属部门',
			sortable : true,
			width : 80
		}, {
			name : 'workGroup',
			display : '工作组',
			sortable : true,
			width : 80,
			hide : true
		}, {
			name : 'officeName',
			display : '所属区域',
			sortable : true,
			width : 80,
			hide : true
		}, {
			name : 'eprovinceCity',
			display : '无补助城市',
			sortable : true,
			width : 70
		}, {
			name : 'belongProject',
			display : '归属项目',
			sortable : true,
			width : 120
		}, {
			name : 'projectCode',
			display : '项目编号',
			sortable : true,
			hide : true
		}, {
			name : 'projectName',
			display : '参与项目',
			sortable : true,
			width : 120
		}, {
			name : 'workStatus',
			display : '工作状态',
			sortable : true,
			width : 80
		}, {
			name : 'projectEndDate',
			display : '项目预计结束',
			sortable : true,
			width : 80
		}, {
			name : 'activityName',
			display : '参与任务',
			sortable : true
		}, {
			name : 'activityEndDate',
			display : '任务预计结束',
			sortable : true,
			width : 80
		}, {
			name : 'technology',
			display : '技术领域',
			sortable : true,
			width : 80
		}, {
			name : 'net',
			display : '网络',
			sortable : true,
			width : 80
		}, {
			name : 'equLevel',
			display : '设备厂家及级别',
			sortable : true,
			width : 80
		}, {
			name : 'updateTime',
			display : '最近更新',
			sortable : true,
			hide : true,
			width : 120,
			type : 'hidden'
		}],
		buttonsEx : buttonsArr,

		toEditConfig : {
			action : 'toEdit'
		},
		toViewConfig : {
			action : 'toTabView'
		},
		//扩展右键
//		menusEx : [{
//			text : '查看',
//			icon : 'view',
//			action : function(row, rows, grid) {
//				if (row) {
//					showModalWin(
//						"?model=hr_personnel_personnel&action=toTabView&id="
//								+ row.id + "&skey=" + row['skey_']
//								+ "&userNo=" + row.userNo + "&userAccount="
//								+ row.userAccount, 'newwindow1',
//						'resizable=yes,scrollbars=yes');
//				}
//			}
//		}],
		searchitems : [{
			display : "员工姓名",
			name : 'userNameSearch'
		},{
			display : "入职日期",
			name : 'entryDateSearch'
		}]
	});
}

//查看员工档案
function viewPersonnel(id, userNo, userAccount) {
	var skey = "";
	$.ajax({
		type : "POST",
		url : "?model=hr_personnel_personnel&action=md5RowAjax",
		data : {
			"id" : id
		},
		async : false,
		success : function(data) {
			skey = data;
		}
	});
	showModalWin("?model=hr_personnel_personnel&action=toTabView&id=" + id
		+ "&userNo=" + userNo + "&userAccount=" + userAccount
		+ "&skey=" + skey, 'newwindow1',
	'resizable=yes,scrollbars=yes');
}