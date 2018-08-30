var show_page = function(page) {
	$("#personnelGrid").yxgrid("reload");
};

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

$(function() {
	//表头按钮数组
	buttonsArr = [{
		name : 'view',
		text : "查询",
		icon : 'view',
		action : function() {
			showThickboxWin("?model=hr_personnel_personnel&action=toSearch&"
				+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=450&width=800');
		}
	}];

	$("#personnelGrid").yxgrid({
		model : 'hr_personnel_personnel',
		action : 'pageJsonWaitEntry',
		title : '待转正员工列表',
		showcheckbox : false,
		isAddAction : false,
		isEditAction : false,
		isDelAction : false,
		isViewAction : false,
		customCode : 'personnelWaitGrid',

		//列信息
		colModel : [{
			name : 'becomeMonth',
			display : '预计转正月份',
			width : 80
		},{
			name : 'userNo',
			display : '员工编号',
			sortable : true,
			process : function(v, row) {
				return "<a href='#' title='点击查看员工信息' onclick='viewPersonnel(\""
					+ row.id + "\",\"" + row.userNo + "\",\""
					+ row.userAccount + "\")' >" + v + "</a>";
			},
			width : 70
		},{
			name : 'staffName',
			display : '姓名',
			sortable : true,
			process : function(v, row) {
				return "<a href='#' title='点击查看员工信息' onclick='viewPersonnel(\""
					+ row.id + "\",\"" + row.userNo + "\",\""
					+ row.userAccount + "\")' >" + v + "</a>";
			},
			width : 60
		},{
			name : 'personLevel',
			display : '技术等级',
			sortable : true,
			width : 50
		},{
			name : 'deptName',
			display : '部门',
			sortable : true,
			width : 80
		},{
			name : 'deptNameS',
			display : '二级部门',
			sortable : true,
			width : 80,
			hide : true
		},{
			name : 'deptNameT',
			display : '三级部门',
			sortable : true,
			width : 80,
			hide : true
		},{
            name : 'deptNameF',
            display : '四级部门',
            sortable : true,
            width : 80,
            hide : true
        },{
			name : 'officeName',
			display : '所属区域',
			sortable : true,
			width : 80
		},{
			name : 'entryDate',
			display : '入职日期',
			sortable : true,
			width : 80
		},{
			name : 'becomeDate',
			display : '转正日期',
			sortable : true,
			width : 80
		},{
			name : 'lastBecomeDate',
			display : '转正倒数',
			sortable : true,
			width : 60
		},{
			name : 'sex',
			display : '性别',
			sortable : true,
			width : 50,
			hide : true
		},{
			name : 'jobLevel',
			display : '职级',
			sortable : true,
			hide : true
		},{
			name : 'nativePlace',
			display : '籍贯',
			sortable : true,
			width : 80,
			hide : true
		},{
			name : 'nativePlacePro',
			display : '籍贯省份',
			sortable : true,
			width : 80,
			hide : true
		},{
			name : 'nativePlaceCity',
			display : '籍贯城市',
			sortable : true,
			width : 80,
			hide : true
		},{
			name : 'companyType',
			display : '公司类型',
			sortable : true,
			hide : true
		},{
			name : 'companyName',
			display : '公司',
			sortable : true,
			hide : true
		},{
			name : 'jobName',
			display : '职位',
			sortable : true,
			hide : true
		},{
			name : 'employeesStateName',
			display : '员工状态',
			sortable : true,
			width : 60,
			hide : true
		},{
			name : 'personnelTypeName',
			display : '员工类型',
			sortable : true,
			width : 80,
			hide : true
		},{
			name : 'positionName',
			display : '岗位分类',
			sortable : true,
			hide : true
		},{
			name : 'personnelClassName',
			display : '人员分类',
			sortable : true,
			hide : true
		},{
			name : 'baseScore',
			display : '达标积分',
			sortable : true,
			hide : true,
			width : 60
		},{
			name : 'allScore',
			display : '累计积分',
			sortable : true,
			hide : true,
			width : 60
		},{
			name : 'trialPlanProcess',
			display : '转正进展',
			sortable : true,
			width : 60,
			process : function(v){
				return v + " %";
			}
		},{
			name : 'trialPlanProcessC',
			display : '预计进度',
			sortable : true,
			width : 60,
			process : function(v){
				return v + " %";
			},
			hide : true
		},{
			name : 'diff',
			display : '差距',
			sortable : true,
			width : 60,
			process : function(v) {
				if( v * 1 < 0){
					return "<span class='red'>" + v + " %</span>";
				} else {
					return v + " %";
				}
			}
		},{
			name : 'trialPlan',
			display : '培训计划',
			sortable : true,
			width : 130,
			process : function(v ,row) {
				if(row.trialPlanId == "0") {
					return "<span class='blue'>" + v + "</span>" ;
				} else {
					return v;
				}
			}
		},{
			name : 'trialTask',
			display : '培训任务',
			sortable : true,
			width : 80,
			hide : true
		},{
			name : 'deptSuggest',
			display : '部门建议',
			sortable : true,
			datacode : 'HRBMJY',
			width : 80
		},{
			name : 'suggestion',
			display : '建议描述',
			sortable : true,
			width : 120,
			hide : true
		},  {
			name : 'ExaStatus',
			display : '审批状态',
			sortable : true,
			width : 70
		},{
			name : 'ExaDT',
			display : '审批日期',
			sortable : true,
			width : 70
		}],

		lockCol:['userNo','staffName','deptName'],//锁定的列名

		buttonsEx : buttonsArr,

		toEditConfig : {
			action : 'toEdit'
		},
		toViewConfig : {
			action : 'toTabView'
		},

		//扩展右键
		menusEx : [{
			text : '查看',
			icon : 'view',
			action : function(row, rows, grid) {
				if (row) {
					showModalWin(
						"?model=hr_personnel_personnel&action=toTabView&id="
						+ row.id + "&skey=" + row['skey_']
						+ "&userNo=" + row.userNo + "&userAccount="
						+ row.userAccount, 'newwindow1',
						'resizable=yes,scrollbars=yes');
				}
			}
		},{
			text : '安排培训计划',
			icon : 'add',
			showMenuFn : function(row) {
				if (row.trialPlanId != "" && row.trialPlanId != "0") {
					return false;
				}
				return true;
			},
			action : function(row, rows, grid) {
				if (row) {
					showModalWin(
						"?model=hr_trialplan_trialplan&action=toSelectModel&id="
						+ row.id + "&skey=" + row['skey_']
						+ "&userNo=" + row.userNo + "&userAccount=" + row.userAccount + "&userName="
						+ row.userName, 'newwindow1',
						'resizable=yes,scrollbars=yes');
				}
			}
		},{
			text : '查看计划进展',
			icon : 'view',
			action : function(row, rows, grid) {
				if (row) {
					showModalWin("?model=hr_trialplan_trialplandetail&action=viewList&userAccount="
						+ row.userAccount
						+ "&userName="
						+ row.userName
						+ "&planId="
						+ row.trialPlanId ,1);
				}
			}
		},{
			text : '录入部门建议',
			icon : 'edit',
			showMenuFn : function(row) {
				if(row.suggestId == '' || row.deptSuggest == 'HRBMJY-00') {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				if (row) {
					showThickboxWin("?model=hr_personnel_personnel&action=toDeptSuggest&id="
						+ row.id
						+ "&skey=" + row['skey_']
						+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800");
				}
			}
		},{
			text : '查看部门建议',
			icon : 'view',
			showMenuFn : function(row) {
				if(row.suggestId == '' || row.deptSuggest == 'HRBMJY-00'){
					return false;
				}
				return true;
			},
			action : function(row, rows, grid) {
				if (row) {
					showThickboxWin("?model=hr_trialplan_trialdeptsuggest&action=toView&id="
						+ row.suggestId
						+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=900");
				}
			}
//		},{
//			text : '修改部门建议',
//			icon : 'edit',
//			showMenuFn : function(row) {
//				if(row.ExaStatus == '待提交'){
//					return true;
//				}
//				return false;
//			},
//			action : function(row, rows, grid) {
//				if (row) {
//					showThickboxWin("?model=hr_trialplan_trialdeptsuggest&action=toReEdit&id="
//						+ row.suggestId
//						+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=900");
//				}
//			}
		}],

		//过滤数据
		comboEx:[{
			text:'部门建议',
			key:'deptSuggest',
			datacode : 'HRBMJY',
			value : 'HRBMJY-00'
		},{
			text: "审批状态",
			key: 'tExaStatus',
			type : 'workFlow'
		}],

		searchitems : [{
			display : "员工编号",
			name : 'userNoSearch'
		},{
			display : "姓名",
			name : 'staffNameSearch'
		},{
			display : "部门",
			name : 'deptNameSearch'
		},{
			display : "预计转正月份",
			name : 'becomeMonth'
		},{
			display : "技术等级",
			name : 'personLevelSearch'
		},{
			display : "所属区域",
			name : 'officeName'
		},{
			display : "入职日期",
			name : 'entryDateSearch'
		},{
			display : "转正日期",
			name : 'becomeDateSearch'
		},{
			display : "性别",
			name : 'sex'
		},{
			display : "培训计划",
			name : 'trialPlan'
		},{
			display : "部门建议",
			name : 'deptSuggest'
		}]
	});
});