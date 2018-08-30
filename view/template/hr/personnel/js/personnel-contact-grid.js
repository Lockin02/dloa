var show_page = function(page) {
	$("#contactGrid").yxgrid("reload");
};
$(function() {
	//表头按钮数组
	buttonsArr = [];

	//表头按钮数组
	excelOutArr = {
		name : 'exportIn',
		text : "导入",
		icon : 'excel',
		action : function(row) {
			showThickboxWin("?model=hr_personnel_personnel&action=toContactExcelIn"
				+ "&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=600");
		}
	};

	excelOutArr2 = {
		name : 'exportOut',
		text : "导出",
		icon : 'excel',
		action : function(row) {
			showThickboxWin("?model=hr_personnel_personnel&action=toContactExcelOut"
				+ "&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=800");
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
				buttonsArr.push(excelOutArr);
				buttonsArr.push(excelOutArr2);
			}
		}
	});
	$("#contactGrid").yxgrid({
		model : 'hr_personnel_personnel',
		action:"contractPageJson",
		title : '人员联系信息',
		showcheckbox : false,
		isAddAction : false,
		isEditAction : false,
		isDelAction : false,
		isOpButton:false,
		bodyAlign:'center',
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
			width:60,
			process : function(v, row) {
				return "<a href='#' onclick='showThickboxWin(\"?model=hr_personnel_personnel&action=toContactView&id="
					+ row.id
					+ '&skey='
					+ row.skey_
					+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=800\")'>"
					+ v + "</a>";
			}
		},{
			name : 'staffName',
			display : '姓名',
			width:60,
			sortable : true
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
			name : 'telephone',
			display : '固定电话',
			sortable : true
		},{
			name : 'mobile',
			display : '移动电话',
			sortable : true
		},{
			name : 'personEmail',
			display : '个人电子邮箱',
			sortable : true
		},{
			name : 'findCompanyEmail',
			display : '公司邮箱',
			sortable : true
		},{
			name : 'homePhone',
			display : '家庭电话',
			sortable : true
		},{
			name : 'emergencyName',
			display : '紧急联系人',
			sortable : true
		},{
			name : 'emergencyRelation',
			display : '紧急联系人关系',
			sortable : true
		},{
			name : 'emergencyTel',
			display : '紧急联系人电话',
			sortable : true
		},{
			name : 'unitPhone',
			display : '单位电话',
			sortable : true
		},{
			name : 'extensionNum',
			display : '分机号',
			sortable : true
		},{
			name : 'unitFax',
			display : '单位传真',
			sortable : true
		},{
			name : 'shortNum',
			display : '短号',
			sortable : true
		},{
			name : 'otherPhone',
			display : '其他手机',
			sortable : true
		},{
			name : 'otherPhoneNum',
			display : '其他号码',
			sortable : true
		}],

		lockCol:['userNo','staffName'],//锁定的列名

		menusEx:[{
			text:'修改',
			icon:'edit',
			action:function(row) {
				if(row) {
					showModalWin("?model=hr_personnel_personnel&action=toMyContactEdit&id=" + row.id  + "&placeValuesBefore&TB_iframe=true&modal=false&height=600&width=400");
				}
			}
		}],

		buttonsEx : buttonsArr,

		toEditConfig : {
			action : 'toEdit'
		},
		toViewConfig : {
			action : 'toContactView'
		},

		searchitems : [{
			display : "员工编号",
			name : 'userNoSearch'
		},{
			display : "员工姓名",
			name : 'staffNameSearch'
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
			display : "职位",
			name : 'jobNameSearch'
		},{
			display : "公司",
			name : 'companyNameSearch'
		},{
			display : "个人邮箱",
			name : 'personEmail'
		},{
			display : "公司邮箱",
			name : 'compEmailA'
		}]
	});
});