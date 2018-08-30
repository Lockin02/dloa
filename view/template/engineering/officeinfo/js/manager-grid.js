var show_page = function(page) {
	$("#managerGrid").yxgrid("reload");
};
$(function() {
	$("#managerGrid").yxgrid({
		model : 'engineering_officeinfo_manager',
		title : '������',
		//����Ϣ
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'productLine',
			display : 'ִ������',
			sortable : true,
			datacode : 'GCSCX'
		}, {
			name : 'province',
			display : 'ʡ��',
			sortable : true
		}, {
			name : 'managerId',
			display : '������ID',
			sortable : true,
			hide : true
		}, {
			name : 'managerName',
			display : '������',
			sortable : true,
			width : 150
		}, {
			name : 'formBelong',
			display : '�����������',
			sortable : true,
			hide : true
		}, {
			name : 'formBelongName',
			display : '�����������',
			sortable : true,
			hide : true
		}, {
			name : 'businessBelong',
			display : '������˾����',
			sortable : true,
			hide : true
		}, {
			name : 'businessBelongName',
			display : '������˾',
			sortable : true
		}, {
			name : 'remark',
			display : '��ע��Ϣ',
			sortable : true,
			width : 300
		}],
		toEditConfig : {
			action : 'toEdit'
		},
		toViewConfig : {
			action : 'toView'
		},
		searchitems : [{
			display : "ʡ��",
			name : 'provinceSch'
		},{
			display : "������",
			name : 'managerNameSch'
		},{
			display : "������˾",
			name : 'businessBelongNameSch'
		},{
			display : "ִ������",
			name : 'productLineNameSch'
		}]
	});
});