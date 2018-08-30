var show_page = function (page) {
	$("#viewListGrid").yxgrid("reload");
};
$(function () {
	//表头按钮数组
	buttonsArr = [
		//        {
		//			name : 'view',
		//			text : "高级查询",
		//			icon : 'view',
		//			action : function() {
		//				alert('功能暂未开发完成');
		//				showThickboxWin("?model=finance_payablesapply_payablesapply&action=toSearch&"
		//					+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=450&width=800');
		//			}
		//        }
	];

	//表头按钮数组
	excelOutArr = {};
	$("#viewListGrid").yxgrid({
		model : 'hr_recruitment_interview',
		param : {'resumeId' : $("#resumeId").val()},
		title : '面试记录',
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
			}, {
				name : 'formCode',
				display : '单据编号',
				sortable : true,
				width:120,
				process : function (v, row) {
					return "<a href='#' onclick='showOpenWin(\"?model=hr_recruitment_interview&action=toView&id=" + row.id + '&skey=' + row.skey_ + "\",1)'>" + v + "</a>";
				}
			}, {
				name : 'formDate',
				display : '单据日期',
				width:70,
				sortable : true
			}, {
				name : 'userName',
				display : '姓名',
				width:70,
				sortable : true
			}, {
				name : 'sexy',
				display : '性别',
				sortable : true,
				width : 60
			}, {
				name : 'positionsName',
				display : '应聘岗位',
				sortable : true
			}, {
				name : 'deptState',
				display : '部门确认状态',
				width:70,
				sortable : true,
				process : function (v) {
					if (v == 1)
						return "已确认";
					else
						return "未确认";
				}
			}, {
				name : 'hrState',
				display : '人力资源确认状态',
				sortable : true,
				process : function (v) {
					if (v == 1)
						return "已确认";
					else
						return "未确认";
				}
			}, {
				name : 'stateC',
				display : '状态',
				width:70
			}, {
				name : 'ExaStatus',
				display : '审核状态',
				width:70,
				sortable : true
			}, {
				name : 'deptName',
				display : '用人部门',
				sortable : true
			},/* {
				name : 'hrRequire',
				display : '招聘需求',
				sortable : true
			},*/ {
				name : 'useInterviewResult',
				display : '面试结果',
				width:70,
				sortable : true,
				process : function (v) {
					if (v == 0)
						return "储备人才";
					else
						return "立即录用";
				}
			}, {
				name : 'hrSourceType1Name',
				display : '简历来源大类',
				sortable : true
			}, {
				name : 'hrSourceType2Name',
				display : '简历来源小类',
				sortable : true
			}
		],
		lockCol:['formCode','formDate','userName'],//锁定的列名
		toEditConfig : {
			toEditFn : function (p, g) {
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
					showOpenWin("?model=hr_recruitment_interview&action=lastedit&id=" +  + rowData[p.keyField]
						 + keyUrl,'1');
				} else {
					alert('请选择一行记录！');
				}
			}
		},
		toViewConfig : {
			toViewFn : function (p, g) {
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
		buttonsEx : buttonsArr,
		searchitems : [{
				display : '姓名',
				name : 'userNameSearch'
			}, {
				display : '用人部门',
				name : 'deptNamSearche'
			}
		]
	});
});