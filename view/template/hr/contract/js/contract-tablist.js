var show_page = function(page) {
	$("#tablistGrid").yxgrid("reload");
};
$(function() {
	$("#tablistGrid").yxgrid({
		model : 'hr_contract_contract',
		title : '��ͬ��Ϣ',
		param : {"userNoSelect" : $("#userNo").val()},
		showcheckbox:false,
		isAddAction : false,
		isEditAction : false,
		isDelAction:false,
		isOpButton:false,
		bodyAlign:'center',
		// ����Ϣ
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'userName',
			display : 'Ա������',
			width:80,
			sortable : true
		}, {
			name : 'userNo',
			display : 'Ա�����',
			width:80,
			sortable : true
		}, {
			name : 'conNo',
			display : '��ͬ���',
			sortable : true,
			process : function(v,row){
				return "<a href='#' onclick='showThickboxWin(\"?model=hr_contract_contract&action=toView&id=" + row.id + '&skey=' + row.skey_ + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=800\")'>" + v + "</a>";
			}
		}, {
			name : 'conName',
			display : '��ͬ����',
			sortable : true
		}, {
			name : 'conTypeName',
			display : '��ͬ����',
			sortable : true
		},  {
			name : 'conStateName',
			display : '��ͬ״̬',
			sortable : true
		}, {
			name : 'beginDate',
			display : '��ʼʱ��',
			sortable : true
		}, {
			name : 'closeDate',
			display : '����ʱ��',
			sortable : true
		}, {
			name : 'conNumName',
			display : '��ͬ����',
			sortable : true
		}],
		toViewConfig : {
			action : 'toView'
		},
		/**
		 * ��������
		 */
		searchitems : [ {
					display : '��ͬ���',
					name : 'conNo'
				}, {
					display : '��ͬ����',
					name : 'conName'
				}, {
					display : '��ͬ����',
					name : 'conTypeName'
				}, {
					display : '��ͬ״̬',
					name : 'conStateName'
				}]
	});
});