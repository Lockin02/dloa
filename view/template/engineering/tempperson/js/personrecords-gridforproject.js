var show_page = function(page) {
	$("#personrecordsGrid").yxgrid("reload");
};
$(function() {
	$("#personrecordsGrid").yxgrid({
		model : 'engineering_tempperson_personrecords',
		title : '��Ƹ��Ա��¼',
		param : { "projectId" : $("#projectId").val() },
		isAddAction : false,
		isDelAction : false,
		isEditAction : false,
		showcheckbox : false,
		//����Ϣ
		colModel : [{
				display : 'id',
				name : 'id',
				sortable : true,
				hide : true
			}, {
				name : 'thisDate',
				display : '����',
				sortable : true,
				width : 80,
				process : function(v,row){
					if(row.id == 'noId') return v;
					return "<a href='javascript:void(0)' onclick='showThickboxWin(\"?model=engineering_tempperson_personrecords&action=toView&id=" + row.id + '&skey=' + row.skey_ + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=800\")'>" + v + "</a>";
				}
			}, {
				name : 'personName',
				display : '��Ƹ��Ա',
				sortable : true,
				width : 80,
				process : function(v,row){
					if(row.id == 'noId') return v;
					return "<a href='javascript:void(0)' onclick='showThickboxWin(\"?model=engineering_tempperson_tempperson&action=toView&id=" + row.personId + '&skey=' + row.skey_ + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=450&width=800\")'>" + v + "</a>";
				}
			}, {
				name : 'money',
				display : '���ʼ�����',
				process : function(v){
					return moneyFormat2(v);
				},
				sortable : true
			}, {
				name : 'workContent',
				display : '��������',
				sortable : true,
				width : 150
			}, {
				name : 'projectId',
				display : '��Ŀid',
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
				name : 'activityName',
				display : '�����',
				sortable : true,
				hide : true
			}, {
				name : 'createName',
				display : '������',
				sortable : true
			}, {
				name : 'createTime',
				display : '����ʱ��',
				sortable : true,
				width : 140
			}, {
				name : 'updateName',
				display : '�޸�������',
				sortable : true,
				hide : true
			}, {
				name : 'updateTime',
				display : '�޸�ʱ��',
				sortable : true,
				hide : true
			}],
		toEditConfig : {
			action : 'toEdit'
		},
		toViewConfig : {
			action : 'toView'
		},
		searchitems : [{
			display : "��Ƹ��Ա",
			name : 'personNameSearch'
		}]
	});
});