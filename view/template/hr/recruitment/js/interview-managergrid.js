var show_page = function(page) {
	$("#interviewGrid").yxgrid("reload");
};

$(function() {
	//表头按钮数组
	buttonsArr = [];

	$("#interviewGrid").yxgrid({
		model : 'hr_recruitment_interview',
		action:'pageForManager',
		title : '面试评估',
		isDelAction : false,
		isEditAction : false,
		isAddAction : false,
		showcheckbox : false,
		isOpButton:false,
		bodyAlign:'center',

		//列信息
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		},{
			name : 'formCode',
			display : '单据编号',
			sortable : true,
			width:120,
			process : function (v, row) {
				return "<a href='#' onclick='showOpenWin(\"?model=hr_recruitment_interview&action=toView&id=" + row.id + '&skey=' + row.skey_ + "\",1)'>" + v + "</a>";
			}
		},{
			name : 'formDate',
			display : '单据日期',
			width:70,
			sortable : true
		},{
			name : 'userName',
			display : '姓名',
			width:60,
			sortable : true
		},{
			name : 'sexy',
			display : '性别',
			width:50,
			sortable : true
		},{
			name : 'positionsName',
			display : '应聘岗位',
			sortable : true
		},{
			name : 'deptState',
			display : '部门确认状态',
			sortable : true,
			width:70,
			process : function (v) {
				if (v == 1) {
					return "已确认";
				} else {
					return "未确认";
				}
			}
		},{
			name : 'hrState',
			display : '人力资源确认状态',
			sortable : true,
			width:95,
			process : function (v) {
				if (v == 1) {
					return "已确认";
				} else {
					return "未确认";
				}
			}
		},{
			name : 'stateC',
			display : '状态',
			width:60
		},{
			name : 'ExaStatus',
			display : '审核状态',
			width:60,
			sortable : true
		},{
			name : 'entryState',
			display : '入职状态',
			width : 60,
			sortable : true,
			process : function (v) {
				switch (v) {
					case '1' : return '待入职';
					case '2' : return '已入职';
					case '3' : return '放弃入职';
					default : return '';
				}
			}
		},{
			name : 'deptName',
			display : '用人部门',
			sortable : true
		},{
			name : 'useInterviewResult',
			display : '面试结果',
			width:70,
			sortable : true,
			process : function (v) {
				if (v == 0) {
					return "储备人才";
				} else {
					return "立即录用";
				}
			}
		},{
			name : 'hrSourceType1Name',
			display : '简历来源大类',
			sortable : true
		},{
			name : 'hrSourceType2Name',
			display : '简历来源小类',
			sortable : true
		}],

		lockCol:['formCode','formDate','userName'],//锁定的列名

		toAddConfig : {
			toAddFn : function() {
				showOpenWin("?model=hr_recruitment_interview&action=toAdd" );
			}
		},
		toEditConfig : {
			toEditFn : function(p, g) {
				var c = p.toViewConfig;
				var w = c.formWidth ? c.formWidth : p.formWidth;
				var h = c.formHeight ? c.formHeight : p.formHeight;
				var rowObj = g.getSelectedRow();
				if (rowObj) {
					var rowData = rowObj.data('data');
					var keyUrl = "";
					if (rowData['skey_']) {
						keyUrl = "&skey=" + rowData['skey_'];
					}
					showOpenWin("?model=hr_recruitment_interview&action=toEdit&id=" + rowData[p.keyField]
						+ keyUrl);
				} else {
					alert('请选择一行记录！');
				}
			}
		},
		toViewConfig : {
			toViewFn : function(p, g) {
				var c = p.toViewConfig;
				var w = c.formWidth ? c.formWidth : p.formWidth;
				var h = c.formHeight ? c.formHeight : p.formHeight;
				var rowObj = g.getSelectedRow();
				if (rowObj) {
					var rowData = rowObj.data('data');
					var keyUrl = "";
					if (rowData['skey_']) {
						keyUrl = "&skey=" + rowData['skey_'];
					}
					showOpenWin("?model=hr_recruitment_interview&action=toView&id=" + rowData[p.keyField]
						+ keyUrl,'1');
				} else {
					alert('请选择一行记录！');
				}
			}
		},

		menusEx:[{
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
					showThickboxWin("controller/common/readview.php?itemtype=oa_hr_recruitment_interview&pid="
						+ row.id
						+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=350&width=800");
				}
			}
		},{
			name : 'resume',
			text : '查看关联简历',
			icon : 'view',
			showMenuFn : function(row) {
				if (row.resumeId >0) {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				if (row) {
					showModalWin('?model=hr_recruitment_resume&action=toView&id=' + row.resumeId + '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800','1');
				}
			}
		},{
			name : 'resume',
			text : '查看关联职位申请',
			icon : 'view',
			showMenuFn : function(row) {
				if (row.applyId >0) {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				if (row) {
					showModalWin('?model=hr_recruitment_employment&action=toView&id=' + row.applyId + '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800','1');
				}
			}
		},{
			text : '填写评估意见',
			icon : 'add',
			action : function(row) {
				showOpenWin("?model=hr_recruitment_interview&action=todeptedit&id="+row.id,'1');
			},
			showMenuFn: function(row) {
				if(row.deptState == 0) {
					return true;
				} else {
					return false;
				}
			}
		},{
			text : '提交审批',
			icon : 'add',
			action : function (row) {},
			showMenuFn : function (row) {
				if (row.hrState == 0 && row.deptState == 1 && row.ExaStatus == "未提交" && row.parentDeptId == '35')
					return true;
				else
					return false;
			},
			action : function (row, rows, grid) {
				if (row) {
					if (row.hrIsManageJob == "是" && row.useInterviewResult == 1) {
						location = 'controller/hr/recruitment/ewf_interview_fwmanage_index.php?actTo=ewfSelect&billId='
						+ row.id + '&billDept=' + row.deptId
						+ '&examCode=oa_hr_recruitment_interview&formName=面试评估审批';
					} else {
						if (row.isLocal == "是") {
							location = 'controller/hr/recruitment/ewf_interview_local_index.php?actTo=ewfSelect&billId='
							+ row.id + '&billDept=' + row.deptId
							+ '&examCode=oa_hr_recruitment_interview&formName=面试评估审批';
						} else {
							location = 'controller/hr/recruitment/ewf_interview_notLocal_index.php?actTo=ewfSelect&billId='
							+ row.id + '&billDept=' + row.deptId
							+ '&examCode=oa_hr_recruitment_interview&formName=面试评估审批';
						}
					}
				} else {
					alert("请选中一条数据");
				}
			}
		}],

		buttonsEx : buttonsArr,

		searchitems : [{
			display : '单据编号',
			name : 'formCode'
		},{
			display : '单据日期',
			name : 'formDate'
		},{
			display : '姓名',
			name : 'userNameSearch'
		},{
			display : '性别',
			name : 'sexy'
		},{
			display : '应聘岗位',
			name : 'positionsNameSearch'
		},{
			display : '用人部门',
			name : 'deptNamSearche'
		},{
			display : '简历来源大类',
			name : 'hrSourceType1Name'
		},{
			display : '简历来源小类',
			name : 'hrSourceType2Name'
		}]
	});
});