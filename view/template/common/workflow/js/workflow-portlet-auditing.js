var show_page = function(page) {
	$("#workflowGrid").yxgrid("reload");
};

// ��ȡ��������������
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
 * ��������ҳ��
 */
function toAudit(task, id, code, Pid, name, code, isTemp) {
	if (parent.parent.openTab) {
		var openTab = parent.parent.openTab;
		var url = "controller/common/workflow/ewf_index.php?actTo=ewfExam"
				+ "&taskId=" + task + "&spid=" + id + "&examCode=" + code
				+ "&billId=" + Pid + "&formName=" + name + "&code=" + code
				+ "&isTemp=" + isTemp;
		openTab(url, "�ҵĴ�������");
	}

}

/**
 * ��������ҳ��
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
		title : '������',
		// showcheckbox : false,
		isAddAction : false,
		isEditAction : false,
		isViewAction : false,
		isDelAction : false,
		showTitle : false,
		height : 180,
		isOpButton : false,
		// ����Ϣ
		colModel : [{
					display : 'id',
					name : 'id',
					sortable : true,
					hide : true
				}, {
					name : 'task',
					display : '��������',
					sortable : true,
					width : 50
				}, {
					name : 'name',
					display : '��������',
					sortable : true,
					width : 120,
					process : function(v) {
						switch (v) {
							case '���۶�������' :
								return '���ۺ�ͬ����';
								break;
							case '���ó�������' :
								return '��������������';
								break;
							default :
								return v;
						}
					}
				}, {
					name : 'creatorName',
					display : '�ύ��',
					sortable : true
				}, {
					name : 'start',
					display : '�ύʱ��',
					sortable : true,
					width : 150
				}, {
					name : 'Pid',
					display : 'Դ��id',
					sortable : true,
					hide : true
				}, {
					display : '�� ��',
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
								+ "\")'>�鿴����</a> |"
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
								+ row.isTemp + "\")'>����</a>";
					}
				}],
		menusEx : [{
			text : '�鿴����',
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
			text : '����',
			icon : 'edit',
			action : function(row) {
				toAudit(row.task, row.id, row.code, row.Pid, row.name,
						row.code, row.isTemp)
			}
		}],
		searchitems : [{
					display : '��������',
					name : 'taskSearch'
				}, {
					display : '�ύ��',
					name : 'creatorNameSearch'
				}, {
					display : '�ύʱ��',
					name : 'startSearch'
				}]
	});

});