var show_page = function(page) {
	$("#provincelist").yxgrid("reload");
}

$(function() {
	$("#provincelist").yxgrid({
		model : 'system_procity_province',
		isAddAction : false,
		isDelAction : false,
		isViewAction : false,
		showcheckbox : false,
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			display : '����',
			name : 'countryName',
			sortable : true,
			hide : true
		}, {
			display : 'ʡ������',
			name : 'provinceName',
			sortable : true
		}, {
			display : 'ʡ�ݱ��',
			name : 'provinceCode',
			sortable : true,
			hide : true
		}, {
			display : '���̷�����',
			name : 'esmManager',
			width : 150,
			sortable : true
		}, {
			display : '��ע��Ϣ',
			name : 'remark',
			width : 200,
			sortable : true
		}, {
			display : '���',
			name : 'sequence',
			sortable : true,
			hide : true
		}],
		toEditConfig : {
			action : 'toEditEsm'
		},
		/**
		 * ��������
		 */
		searchitems : [{
			display : 'ʡ������',
			name : 'provinceName'
		}],
		sortorder : "ASC",
		title : 'ʡ������'
	});
});