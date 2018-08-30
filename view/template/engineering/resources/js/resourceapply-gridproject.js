var show_page = function(page) {
	$("#resourceapplyGrid").yxgrid("reload");
};
$(function() {
	$("#resourceapplyGrid").yxgrid({
		model : 'engineering_resources_resourceapply',
		param : {"projectId" : $("#projectId").val()},
		title : '��Ŀ�豸�����',
		isAddAction : false,
		isDelAction : false,
		isEditAction : false,
		isOpButton : false,
		showcheckbox : false,
		//����Ϣ
		colModel : [{
				display : 'id',
				name : 'id',
				sortable : true,
				hide : true
			}, {
				name : 'formNo',
				display : '���뵥���',
				sortable : true,
				width : 120,
				process : function(v, row) {
					return "<a href='javascript:void(0)' onclick='showOpenWin(\"?model=engineering_resources_resourceapply&action=toView&id="
							+ row.id + '&skey=' + row.skey_ + "\",1,700,1100,"+row.id+")'>" + v + "</a>";
				}
			}, {
				name : 'applyUser',
				display : '������',
				sortable : true
			}, {
				name : 'applyUserId',
				display : '������id',
				sortable : true,
				hide : true
			}, {
				name : 'applyDate',
				display : '��������',
				sortable : true,
				width : 80
			}, {
				name : 'applyTypeName',
				display : '��������',
				sortable : true,
				width : 80
			}, {
				name : 'getTypeName',
				display : '���÷�ʽ',
				sortable : true,
				width : 80
			}, {
				name : 'place',
				display : '�豸ʹ�õ�',
				sortable : true
			}, {
				name : 'deptName',
				display : '��������',
				sortable : true,
				hide : true
			}, {
				name : 'projectCode',
				display : '��Ŀ���',
				sortable : true,
				width : 120,
				hide : true
			}, {
				name : 'projectName',
				display : '��Ŀ����',
				sortable : true,
				width : 120,
				hide : true
			}, {
				name : 'managerName',
				display : '��Ŀ����',
				sortable : true,
				width : 80,
				hide : true
			}, {
				name : 'managerId',
				display : '��Ŀ����id',
				sortable : true,
				hide : true
			}, {
				name : 'remark',
				display : '��ע��Ϣ',
				sortable : true,
				width : 130,
				hide : true
			}, {
				name : 'status',
				display : '����״̬',
				sortable : true,
				width : 80,
				process : function(v){
					switch(v){
						case '0' : return 'δ����';
						case '1' : return '������';
						case '2' : return '�Ѵ���';
						case '3' : return '�����';
						default : return v;
					}
				}
			}, {
				name : 'ExaStatus',
				display : '����״̬',
				sortable : true,
				width : 80
			}, {
				name : 'ExaDT',
				display : '��������',
				sortable : true,
				width : 80
			}, {
				name : 'createName',
				display : '������',
				sortable : true,
				hide : true
			}, {
				name : 'createTime',
				display : '����ʱ��',
				sortable : true,
				hide : true
			}, {
				name : 'updateName',
				display : '�޸���',
				sortable : true,
				hide : true
			}, {
				name : 'updateTime',
				display : '�޸�ʱ��',
				sortable : true,
				hide : true
			}],
		toViewConfig : {
			toViewFn : function(p, g) {
				var rowObj = g.getSelectedRow();
				var row = rowObj.data('data');
				showOpenWin("?model=engineering_resources_resourceapply&action=toView&id="
						+ row[p.keyField],1,700,1100,row.id);
			}
		},
		comboEx : [{
			text : '����״̬',
			key : 'status',
			data : [{
				text : 'δ����',
				value : '0'
			}, {
				text : '������',
				value : '1'
			}, {
				text : '�Ѵ���',
				value : '2'
			}, {
				text : '�����',
				value : '3'
			}]
		},{
		     text:'���״̬',
		     key:'ExaStatus',
		     type : 'workFlow'
		}],
		searchitems : [{
			display : "���뵥��",
			name : 'formNoSch'
		},{
			display : "��Ŀ���",
			name : 'projectCodeSch'
		},{
			display : "��Ŀ����",
			name : 'projectNameSch'
		}]
	});
});