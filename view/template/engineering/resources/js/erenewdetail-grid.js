var show_page = function(page) {
	$("#erenewdetailGrid").yxgrid("reload");
};
$(function() {
	$("#erenewdetailGrid").yxgrid({
		model : 'engineering_resources_erenewdetail',
		title : '����������ϸ',
		// ����Ϣ
		colModel : [ {
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'mainId',
			display : 'mainId',
			sortable : true
		}, {
			name : 'resourceId',
			display : '�豸id',
			sortable : true
		}, {
			name : 'resourceName',
			display : '�豸����',
			sortable : true
		}, {
			name : 'resourceTypeId',
			display : '�豸����id',
			sortable : true
		}, {
			name : 'resourceTypeName',
			display : '�豸��������',
			sortable : true
		}, {
			name : 'coding',
			display : '������',
			sortable : true
		}, {
			name : 'dpcoding',
			display : '������',
			sortable : true
		}, {
			name : 'number',
			display : '����',
			sortable : true
		}, {
			name : 'unit',
			display : '��λ',
			sortable : true
		}, {
			name : 'endDate',
			display : 'Ԥ�ƹ黹����',
			sortable : true
		} ]
	});
});