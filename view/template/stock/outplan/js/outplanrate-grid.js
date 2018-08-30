var show_page = function(page) {
	$("#outplanrateGrid").yxgrid("reload");
};
$(function() {
	$("#outplanrateGrid").yxgrid({
		model : 'stock_outplan_outplanrate',
		title : '�����ƻ����ȱ�ע',
		param : { planId : $('#planId').val() },
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
			name : 'planId',
			display : '�����ƻ�Id',
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
			name : 'createId',
			display : '������Id',
			sortable : true,
			hide : true
		}, {
			name : 'createTime',
			display : '����ʱ��',
			width : 120,
			sortable : true
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
				showThickboxWin('?model=stock_outplan_outplanrate&action=toAdd&planId='
				+ $('#planId').val()
				+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800');
			}
		}],
		menusEx : [{
			name : 'view',
			// hide : true,
			text : "�鿴",
			icon : 'view',

			action : function(row) {
				showThickboxWin('?model=stock_outplan_outplanrate&action=init&id='
				+ row.id
				+ '&perm=view&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800');
			}
		}]
	});
});