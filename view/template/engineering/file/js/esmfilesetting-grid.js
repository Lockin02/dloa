var show_page = function(page) {
	$("#esmfilesettingGrid").yxgrid("reload");
};
$(function() {
	$("#esmfilesettingGrid").yxgrid({
		model : 'engineering_file_esmfilesetting',
		title : '��Ŀ�ĵ�����',
		//����Ϣ
		colModel : [ {
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'fileName',
			display : '�ĵ�����',
			sortable : true,
			width : 120
		}, {
			name : 'fileType',
			display : '�ĵ�����',
			sortable : true,
			width : 120
		}, {
			name : 'isNeedUpload',
			display : '�Ƿ�����ϴ�',
			sortable : true,
			process :��function (v){
				if(v == 1){
					return '��';
				}
				else {
					return '��';
				}
			},
			width : 80
		}, {
			name : 'description',
			display : '����',
			sortable : true,
			width : 200
		} ],
		toEditConfig : {
			action : 'toEdit'
		},
		toViewConfig : {
			action : 'toView'
		},
		searchitems : [ {
			display : "�ĵ�����",
			name : 'fileNameSch'
		},{
			display : "�ĵ�����",
			name : 'fileTypeSch'
		} ]
	});
});