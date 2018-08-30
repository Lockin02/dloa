$(function() {
	var objGrid = $("#esmworklogGrid");
	//工作日志
	objGrid.yxgrid({
		model : 'engineering_worklog_esmworklog',
		action : 'suppUserLogJson',
		title : '工作日志',
		showcheckbox : false,
		isAddAction : false,
		isViewAction : false,
		isEditAction : false,
		isDelAction : false,
		colModel : [{
				display : 'id',
				name : 'id',
				hide : true
			},{
				display : '填写人',
				name : 'createName',
				width : 80
			},{
				display : '日期',
				name : 'executionDate',
				width : 70
			},{
				display : '项目编号',
				name : 'projectCode',
				width : 140,
				align : 'left'
			},{
				display : '任务',
				name : 'activityName',
				width : 120,
				align : 'left'
			}, {
				display : '工作量',
				name : 'workloadDay',
				width : 60
			}, {
				display : '单位',
				name : 'workloadUnitName',
				width : 40
			}, {
				display : '任务进展',
				name : 'thisActivityProcess',
				width : 60,
				process : function(v){
					return v + " %";
				}
			}, {
				display : '项目进展',
				name : 'thisProjectProcess',
				width : 60,
				process : function(v){
					return v + " %";
				}
			}, {
				display : '进展系数',
				name : 'processCoefficient',
				width : 60
			}, {
				display : '人工投入占比',
				name : 'inWorkRate',
				width : 70,
				process : function(v){
					return v + " %";
				}
			}, {
				display : '工作系数',
				name : 'workCoefficient',
				width : 60
			}, {
				display : '费用',
				name : 'costMoney',
				width : 60,
				process : function(v,row){
					if(v * 1 == 0 || v == ''){
						return v;
					}else{
						return "<span class='blue'>" + moneyFormat2(v) + "</span>";
					}
				}
			}, {
				display : '情况描述',
				name : 'description',
				align : 'left'
			}, {
				display : '考核结果',
				name : 'assessResultName',
				width : 60
			}, {
				display : '回复',
				name : 'feedBack',
				align : 'left',
				process : function(v,row){
					return v;
				}
			},{
				display : 'assessResult',
				name : 'assessResult',
				hide : true
			}
		],
		buttonsEx : [{
				name : 'workVerify',
				text : '发起工作量确认',
				icon : 'add',
				action :function(row) {
					showModalWin("?model=outsourcing_workverify_suppVerify&action=toAdd"
							+ "&placeValuesBefore&TB_iframe=true&modal=false&height=650&width=950")

				}
			}
        ],
		searchitems : [{
				display : "填写人",
				name : 'createName'
			},{
				display : "部门",
				name : 'deptNameSearch'
			},{
				display : "日期",
				name : 'executionDate'
			}, {
				display : "项目编号",
				name : 'projectCode'
			}
		],
		menusEx : [{
			text : '查看',
			icon : 'view',
			action : function(row, rows, grid) {
				showOpenWin("?model=engineering_worklog_esmworklog&action=toView&id="
						+ row.id
						+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=600&width=1100");
			}
		}]
	});
});