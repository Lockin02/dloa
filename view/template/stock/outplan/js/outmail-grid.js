var show_page = function(page) {
	$("#outmailGrid").yxgrid("reload");
};
$(function() {
	$("#outmailGrid").yxgrid({
		model : 'stock_outplan_outmail',
		title : '�����ƻ��ʼĽ�����',
		isViewAction : false,
		isEditAction : false,
		//����Ϣ
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'mailmanId',
			display : '�ʼ�������Id',
			hide : true,
			sortable : true
		}, {
			name : 'mailmanName',
			display : '�ʼ�������',
			sortable : true
		}]
	});
});