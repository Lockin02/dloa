var show_page = function(page) {
	$("#esmentryGrid").yxgrid("reload");
};
$(function() {
	$("#esmentryGrid").yxgrid({
		model : 'engineering_member_esmentry',
		title : '��Ա�����',
		isViewAction : false,
		isAddAction : false,
		isDelAction : false,
		isOpButton : false,
		param : {
			projectId : $("#projectId").val()
		},
		//����Ϣ
		colModel : [ {
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			display : '��Ŀid',
			name : 'projectId',
			sortable : true,
			hide : true
		}, {
			name : 'memberName',
			display : '��Ա����',
			sortable : true
		}, {
			name : 'memberId',
			display : '��ԱId',
			sortable : true,
			hide : true
		}, {
			name : 'personLevel',
			display : '��Ա�ȼ�',
			sortable : true,
			process : function(v,row){
				if(row.memberId != 'SYSTEM') return v;
			}
		}, {
			name : 'beginDate',
			display : '������Ŀ',
			sortable : true
		}, {
			name : 'endDate',
			display : '�뿪��Ŀ',
			sortable : true
		}, {
			name : 'remark',
			display : '��ע',
			sortable : true,
			width : '200'
		} ],

		toEditConfig : {
			action : 'toEdit'
		},
		toAddConfig : {
			action : 'toAdd',
			plusUrl : '&projectId='+$("#projectId").val()
		},
		searchitems : [ {
			display : "��Ա����",
			name : 'memberName'
		}  ]
	});
});