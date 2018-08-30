var show_page = function(page) {
	$("#contractGrid").yxgrid("reload");
};
$(function() {

	$("#contractGrid").yxgrid({
		model : 'hr_contract_contract',
		title : '��ͬ��Ϣ',
		isAddAction : false,
		isEditAction : false,
		isDelAction : false,
		// ����Ϣ
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'userName',
			display : 'Ա������',
			sortable : true
		}, {
			name : 'userNo',
			display : 'Ա�����',
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
			display : '��ͬ��ʼʱ��',
			sortable : true
		}, {
			name : 'closeDate',
			display : '��ͬ����ʱ��',
			sortable : true
		}, {
			name : 'trialBeginDate',
			display : '���ÿ�ʼʱ��',
			sortable : true
		}, {
			name : 'trialEndDate',
			display : '���ý���ʱ��',
			sortable : true
		}],
		/**
		 * ��������
		 */
		searchitems : [{
					display : 'Ա������',
					name : 'userName'
				}, {
					display : 'Ա�����',
					name : 'userNo'
				}, {
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