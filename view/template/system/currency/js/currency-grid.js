var show_page = function(page) {
	$("#currencyGrid").yxgrid("reload");
};
$(function() {
	$("#currencyGrid").yxgrid({
		model : 'system_currency_currency',
		title : '���һ���',
		//����Ϣ
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'Currency',
			display : '�ұ�',
			sortable : true
		}, {
			name : 'currencyCode',
			display : '�ұ����',
			sortable : true
		}, {
			name : 'rate',
			display : '����',
			sortable : true
		}, {
			name : 'standard',
			display : '��λ��',
			sortable : true
		}]
	});
});