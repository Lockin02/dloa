var show_page = function(page) {
	$("#assignrateGrid").yxgrid("reload");
};
$(function() {
	$("#assignrateGrid").yxgrid({
		model : 'stock_outplan_assignrate',
		param : { relDocId : $('#docId').val(),relDocType : $('#docType').val() },
		title : '����ȷ�Ͻ��ȱ�ע',
		//����Ϣ
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'relDocType',
			display : 'Դ������',
			sortable : true
		}, {
			name : 'relDocId',
			display : 'Դ��id',
			sortable : true
		}, {
			name : 'rObjCode',
			display : 'Դ��ҵ�����',
			sortable : true
		}, {
			name : 'keyword',
			display : '�ؼ���',
			sortable : true
		}, {
			name : 'remark',
			display : '��������',
			sortable : true
		}, {
			name : 'createTime',
			display : '����ʱ��',
			sortable : true
		}, {
			name : 'createName',
			display : '����������',
			sortable : true
		}, {
			name : 'createId',
			display : '������ID',
			sortable : true
		}, {
			name : 'updateId',
			display : '�޸���Id',
			sortable : true
		}, {
			name : 'updateName',
			display : '�޸�������',
			sortable : true
		}, {
			name : 'updateTime',
			display : '�޸�ʱ��',
			sortable : true
		}],

		toEditConfig : {
			action : 'toEdit'
		},
		toViewConfig : {
			action : 'toView'
		},
		searchitems : {
			display : "�����ֶ�",
			name : 'XXX'
		}
	});
});