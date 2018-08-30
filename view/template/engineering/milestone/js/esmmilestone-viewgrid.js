var show_page = function(page) {
	$("#esmmilestoneGrid").yxgrid("reload");
};

$(function() {
	var projectId = $("#projectId").val();
	$("#esmmilestoneGrid").yxgrid({
		model : 'engineering_milestone_esmmilestone',
		title : '��Ŀ��̱�',
		param : {
			"projectId" : $("#projectId").val()
		},
		isDelAction : false,
		isAddAction : false,
		isEditAction : false,
		//����Ϣ
		colModel : [{
				display : 'id',
				name : 'id',
				sortable : true,
				hide : true
			}, {
				name : 'milestoneName',
				display : '��̱�����',
				sortable : true
			}, {
				name : 'planBeginDate',
				display : '�ƻ���ʼ����',
				sortable : true,
				width : 80
			}, {
				name : 'planEndDate',
				display : '�ƻ��������',
				sortable : true,
				width : 80
			}, {
				name : 'actBeginDate',
				display : 'ʵ�ʿ�ʼ����',
				sortable : true,
				process : function(v, row) {
					if (v == "0000-00-00") {
						return "";
					} else {
						return v;
					}
				},
				width : 80
			}, {
				name : 'actEndDate',
				display : 'ʵ�ʽ�������',
				sortable : true,
				process : function(v, row) {
					if (v == "0000-00-00") {
						return "";
					} else {
						return v;
					}
				},
				width : 80
			}, {
				name : 'projectId',
				display : '��ĿId',
				sortable : true,
				hide : true
			}, {
				name : 'projectCode',
				display : '��Ŀ���',
				sortable : true,
				hide : true
			}, {
				name : 'projectName',
				display : '��Ŀ����',
				sortable : true,
				hide : true
			}, {
				name : 'versionNo',
				display : '�汾��',
				sortable : true,
				width : 80
			}, {
				name : 'status',
				display : '״̬',
				sortable : true,
				datacode : 'LCBZT',
				width : 80
			}, {
				name : 'preMilestoneId',
				display : 'ǰ����̱�id',
				sortable : true,
				hide : true
			}, {
				name : 'preMilestoneName',
				display : 'ǰ����̱�',
				sortable : true
			}, {
				name : 'remark',
				display : '��ע˵��',
				sortable : true,
				width : 250
			}
		],
		toAddConfig : {
			plusUrl : "?model=engineering_milestone_esmmilestone&action=toAdd&id="
					+ projectId
		},
		sortname : 'c.planBeginDate',
		sortorder : 'ASC'
	});
});