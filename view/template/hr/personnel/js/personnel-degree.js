var show_page = function(page) {
	$("#personnelGrid").yxgrid("reload");
};

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
	showModalWin(
		"?model=hr_personnel_personnel&action=toDegreeTabView&id="
		+ id + "&userNo=" + userNo + "&userAccount=" + userAccount
		+ "&skey=" + skey, 'newwindow1',
		'resizable=yes,scrollbars=yes');
}

$(function() {
	// 表头按钮数组
	var buttonsArr = [{
		name : 'view',
		text : "高级查询",
		icon : 'view',
		action : function() {
			showThickboxWin("?model=hr_personnel_personnel&action=toSearch&"
				+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=450&width=900');
		}
	}];

	// 表头按钮数组
	excelOutArr2 = {
		name : 'excelOutAllArr',
		text : "导出档案信息",
		icon : 'excel',
		action : function() {
			if($("#totalSize").val()<1){
				alert("没有可导出的记录");
			} else {
				document.getElementById("form2").submit();
			}
		}
	};

	$.ajax({
		type : 'POST',
		url : '?model=hr_personnel_personnel&action=getLimits',
		data : {
			'limitName' : '导入权限'
		},
		async : false,
		success : function(data) {
			if (data = 1) {
				buttonsArr.push(excelOutArr2);
			}
		}
	});

	var levelArr = [];
	$.ajax({
		url : '?model=engineering_baseinfo_eperson&action=listJson',
		async : false,
		success : function(data) {
			data = eval("(" + data + ")");
			for (var i = 0; i < data.length; i++) {
				var opt = {
					text : data[i].personLevel,
					value : data[i].personLevel
				};
				levelArr.push(opt);
			}
		}
	});

	$("#personnelGrid").yxgrid({
		model : 'hr_personnel_personnel',
		title : '人员档案信息',
		action : 'pageJsonForRead',
		showcheckbox : false,
		isAddAction : false,
		isEditAction : false,
		isDelAction : false,
		isViewAction : false,
		isOpButton : false,
		bodyAlign : 'center',
		event : {
			afterload : function(data ,g) {
				$("#listSql").val(g.listSql);
				$("#totalSize").val(g.totalSize);
			}
		},
		//列信息
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		},{
			name : 'userNo',
			display : '员工编号',
			width:60,
			sortable : true,
			process : function(v, row) {
				return "<a href='#' title='点击查看员工信息' onclick='viewPersonnel(\""
					+ row.id
					+"\",\""
					+ row.userNo
					+"\",\""
					+row.userAccount
					+ "\")' >"
					+ v
					+ "</a>";
			}
		},{
			name : 'staffName',
			display : '姓名',
			width:60,
			sortable : true
		},{
			name : 'sex',
			display : '性别',
			sortable : true,
			width:50
		},{
			name : 'companyName',
			display : '公司',
			width:60,
			sortable : true
		},{
			name : 'belongDeptName',
			display : '所属部门',
			width:80,
			hide : true
		},{
			name : 'deptName',
			display : '直属部门',
			width:80,
			sortable : true
		},{
			name : 'deptNameS',
			display : '二级部门',
			width:80,
			sortable : true
		},{
			name : 'deptNameT',
			display : '三级部门',
			width:80,
			sortable : true
		},{
			name : 'deptNameF',
			display : '四级部门',
			width:80,
			sortable : true
		},{
			name : 'personLevel',
			display : '技术等级',
			sortable : true,
			width:60
		},{
			name : 'officeName',
			display : '归属区域',
			width:60,
			sortable : true
		},{
			name : 'eprovinceCity',
			display : '无补助城市',
			sortable : true
		},{
			name : 'technologyName',
			display : '技术领域',
			sortable : true
		},{
			name : 'networkName',
			display : '网络',
			width:60,
			sortable : true
		},{
			name : 'deviceName',
			display : '设备厂家及级别',
			sortable : true
		},{
			name : 'jobName',
			display : '职位',
			width:80,
			sortable : true
		},{
			name : 'employeesStateName',
			display : '员工状态',
			sortable : true,
			width:60
		},{
			name : 'isNeedTutor',
			display : '导师状态',
			sortable : true,
			width : 90,
			process : function(v, row) {
				if (v == 1) {
					return "不需要指定导师";
				} else {
					if (row.isTut == 1) {
						return "已指定导师";
					} else {
						return "未指定导师";
					}
				}
			}
		},{
			name : 'personnelTypeName',
			display : '员工类型',
			width:60,
			sortable : true
		},{
			name : 'entryDate',
			display : '入职日期',
			width:70,
			sortable : true
		},{
			name : 'becomeDate',
			display : '转正日期',
			width:70,
			sortable : true
		},{
			name : 'mobile',
			display : '移动电话',
			width:100,
			sortable : true
		},{
			name : 'compEmail',
			display : '公司邮箱',
			width:180,
			sortable : true
		},{
			name : 'highEducationName',
			display : '最高学历',
			width:60,
			sortable : true
		},{
			name : 'highSchool',
			display : '毕业学校',
			width:120,
			sortable : true
		},{
			name : 'professionalName',
			display : '专业',
			width:80,
			sortable : true
		},{
			name : 'outsourcingSupp',
			display : '外包公司',
			width:80,
			sortable : true
		},{
			name : 'outsourcingName',
			display : '外包性质',
			width:80,
			sortable : true
		}],
		lockCol:['userNo','staffName'],//锁定的列名
		buttonsEx : buttonsArr,

		toEditConfig : {
			action : 'toEdit'
		},
		toViewConfig : {
			action : 'toTabView'
		},

		comboEx:[{
			text:'导师状态',
			key:'tutorState',
			data:[{
				text:'不需要指定导师',
				value:'1'
			},{
				text:'未指定导师',
				value:'2'
			},{
				text:'已指定导师',
				value:'3'
			}]
		}],

		// 扩展右键
		menusEx : [{
			text : '查看',
			icon : 'view',
			action : function(row, rows, grid) {
				if (row) {
					showModalWin("?model=hr_personnel_personnel&action=toDegreeTabView&id="
						+ row.id+"&userNo=" +row.userNo + "&userAccount=" + row.userAccount
						+ "&skey=" + row['skey_']
						+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=1000");
				}
			}
		},{
			text : '修改',
			icon : 'edit',
			action : function(row, rows, grid) {
				if (row) {
					showThickboxWin("?model=hr_personnel_personnel&action=toDegreeEdit&id="
						+ row.id
						+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=1000");
				} else {
					alert("请选择一条记录信息");
				}
			}
		},{
			name : 'view',
			text : "操作日志",
			icon : 'view',
			action : function(row, rows, grid) {
				showThickboxWin("?model=syslog_operation_logoperation&action=businessView&pkValue="
					+ row.id
					+ "&tableName=oa_hr_personnel"
					+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=850");
			}
		},{
			text : '指定导师',
			icon : 'add',
			showMenuFn : function(row) {
				if (row.isNeedTutor != 1 && row.isTut == '0') {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				if (row) {
					showThickboxWin("?model=hr_tutor_tutorrecords&action=toSetTutor&id="
						+ row.id
						+ "&skey="
						+ row['skey_']
						+ "&userNo="
						+ row.userNo
						+ "&userAccount="
						+ row.userAccount
						+ "&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=800");
				}
			}
		},{
			text : '不指定导师',
			icon : 'add',
			showMenuFn : function(row) {
				if (row.isNeedTutor != 1 && row.isTut == '0') {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				if (row) {
					showThickboxWin("?model=hr_tutor_tutorrecords&action=toUnsetTutor&id="
						+ row.id
						+ "&skey="
						+ row['skey_']
						+ "&userNo="
						+ row.userNo
						+ "&userAccount="
						+ row.userAccount
						+ "&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=800");
				}
			}
		}],
		searchitems : [{
			display : "员工编号",
			name : 'userNoSearch'
		},{
			display : "姓  名",
			name : 'staffNameSearch'
		},{
			display : "性  别",
			name : 'sex'
		},{
			display : "公  司",
			name : 'companyNameSearch'
		},{
			display : "直属部门",
			name : 'deptNameSearch'
		},{
			display : "二级部门",
			name : 'deptNameSSearch'
		},{
			display : "三级部门",
			name : 'deptNameTSearch'
		},{
			display : "四级部门",
			name : 'deptNameFSearch'
		},{
			display : "职  位",
			name : 'jobNameSearch'
		},{
			display : "员工状态",
			name : 'employeesStateNameSearch'
		},{
			display : "员工类型",
			name : 'personnelTypeNameSearch'
		},{
			display : "技术等级",
			name : 'personLevel'
		},{
			display : "归属区域",
			name : 'officeName'
		},{
			display : "无补助城市",
			name : 'eprovinceCity'
		},{
			display : "技术领域",
			name : 'technologyName'
		},{
			display : "网络",
			name : 'networkName'
		},{
			display : "设备厂家及级别",
			name : 'deviceName'
		},{
			display : "入职日期",
			name : 'entryDateSearch'
		},{
			display : "转正日期",
			name : 'becomeDateSearch'
		},{
			display : "移动电话",
			name : 'mobileSearch'
		},{
			display : "公司邮箱",
			name : 'compEmail'
		},{
			display : "最高学历",
			name : 'highEducationName'
		},{
			display : "毕业学校",
			name : 'highSchool'
		},{
			display : "专业",
			name : 'professionalName'
		},{
			display : "外包公司",
			name : 'outsourcingSupp'
		},{
			display : "外包性质",
			name : 'outsourcingName'
		}]
	});
});
