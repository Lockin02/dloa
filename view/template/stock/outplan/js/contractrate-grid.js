var show_page = function(page) {
	$("#contractrateGrid").yxgrid("reload");
};
$(function() {
	$("#contractrateGrid").yxgrid({
		model : 'stock_outplan_contractrate',
		title : '����������ȱ�ע',
		param : { relDocId : $('#docId').val(),relDocType : $('#docType').val() },
		showcheckbox : false,
		isAddAction : false,
		isEditAction : false,
		isViewAction : false,
		isDelAction : false,
		height : 400,
		//����Ϣ
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'relDocType',
			display : '��ͬ����',
			sortable : true,
			hide : true
		}, {
			name : 'relDocId',
			display : '��ͬid',
			sortable : true,
			hide : true
		}, {
			name : 'rObjCode',
			display : '��ͬҵ�����',
			sortable : true,
			hide : true
		}, {
			name : 'keyword',
			display : '�ؼ���',
			width : 150,
			sortable : true
		}, {
			name : 'createName',
			display : '������',
			width : 80,
			sortable : true
		}, {
			name : 'createTime',
			display : '����ʱ��',
			width : 120,
			sortable : true
		}, {
			name : 'createId',
			display : '������ID',
			sortable : true,
			hide : true
		}, {
			name : 'remark',
			display : '��������',
			width : 400,
			sortable : true
		}],
		buttonsEx : [{
			name : 'Add',
			// hide : true,
			text : "����",
			icon : 'add',

			action : function(row) {
				showThickboxWin('?model=stock_outplan_contractrate&action=toAdd&relDocId='
				+ $('#docId').val()
				+ '&relDocType='
				+ $('#docType').val()
				+ '&rObjCode='
				+ $('#objCode').val()
				+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800');
			}
		}],
		menusEx : [{
			name : 'view',
			// hide : true,
			text : "�鿴",
			icon : 'view',

			action : function(row) {
				showThickboxWin('?model=stock_outplan_contractrate&action=init&id='
				+ row.id
				+ '&perm=view&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800');
			}
		}]
	});
});