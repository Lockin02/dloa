var show_page = function(page) {
	$("#workflowGrid").yxgrid("reload");
};

// 获取工作流类型数组
formTypeArr = [];
$.ajax({
			type : "POST",
			url : "?model=common_workflow_workflow&action=getFormType",
			data : "",
			async : false,
			success : function(data) {
				formTypeArr = eval("(" + data + ")");
			}
		});

/**
 * 进入审批页面
 */
function toAudit(task, id, code, Pid, name, code, isTemp) {
	if (parent.parent.openTab) {
		var openTab = parent.parent.openTab;
		var url = "controller/common/workflow/ewf_index.php?actTo=ewfExam"
				+ "&taskId=" + task + "&spid=" + id + "&examCode=" + code
				+ "&billId=" + Pid + "&formName=" + name + "&code=" + code
				+ "&isTemp=" + isTemp;
		openTab(url, "我的代办任务");
	}

}

/**
 * 进入审批页面
 */
function toView(task, id, code, Pid, name, code, isTemp) {
	showModalWin('?model=common_workflow_workflow&action=toViweObjInfo'
			+ "&taskId=" + task + "&spid=" + id + "&examCode=" + code
			+ "&billId=" + Pid + "&formName=" + name + "&code=" + code
			+ "&isTemp=" + isTemp);
}

$(function() {
	$("#workflowGrid").yxgrid({
		model : 'common_workflow_workflow',
		action : 'auditingPageJson',
		title : '工作流',
		// showcheckbox : false,
		isAddAction : false,
		isEditAction : false,
		isViewAction : false,
		isDelAction : false,
		showTitle : false,
		height : 180,
		isOpButton : false,
		// 列信息
		colModel : [{
					display : 'id',
					name : 'id',
					sortable : true,
					hide : true
				}, {
					name : 'task',
					display : '审批单号',
					sortable : true,
					width : 50
				}, {
					name : 'name',
					display : '审批类型',
					sortable : true,
					width : 120,
					process : function(v) {
						switch (v) {
							case '销售订单审批' :
								return '销售合同审批';
								break;
							case '借用出库申请' :
								return '借试用申请审批';
								break;
							default :
								return v;
						}
					}
				}, {
					name : 'creatorName',
					display : '提交人',
					sortable : true
				}, {
					name : 'start',
					display : '提交时间',
					sortable : true,
					width : 150
				}, {
					name : 'Pid',
					display : '源单id',
					sortable : true,
					hide : true
				}, {
					display : '操 作',
					align : 'center',
					process : function(v, row) {
						return "<a href='javascript:void(0)' onclick='toView(\""
								+ row.task
								+ "\",\""
								+ row.id
								+ "\",\""
								+ row.code
								+ "\",\""
								+ row.Pid
								+ "\",\""
								+ row.name
								+ "\",\""
								+ row.code
								+ "\",\""
								+ row.isTemp
								+ "\")'>查看单据</a> |"
								+ " <a href='javascript:void(0)' onclick='toAudit(\""
								+ row.task
								+ "\",\""
								+ row.id
								+ "\",\""
								+ row.code
								+ "\",\""
								+ row.Pid
								+ "\",\""
								+ row.name
								+ "\",\""
								+ row.code
								+ "\",\""
								+ row.isTemp + "\")'>审批</a>";
					}
				}],
		menusEx : [{
			text : '查看单据',
			icon : 'view',
			action : function(row, rows, grid) {
				showModalWin('?model=common_workflow_workflow&action=toViweObjInfo'
						+ '&taskId='
						+ row.task
						+ "&spid="
						+ row.id
						+ "&examCode="
						+ row.code
						+ "&billId="
						+ row.Pid
						+ "&formName="
						+ row.name
						+ "&code="
						+ row.code
						+ "&isTemp=" + row.isTemp);
			}
		}, {
			text : '审批',
			icon : 'edit',
			action : function(row) {
				toAudit(row.task, row.id, row.code, row.Pid, row.name,
						row.code, row.isTemp)
			}
		}],
		searchitems : [{
					display : '审批单号',
					name : 'taskSearch'
				}, {
					display : '提交人',
					name : 'creatorNameSearch'
				}, {
					display : '提交时间',
					name : 'startSearch'
				}]
	});

});