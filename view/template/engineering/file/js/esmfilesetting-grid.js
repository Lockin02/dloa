var show_page = function(page) {
	$("#esmfilesettingGrid").yxgrid("reload");
};
$(function() {
	$("#esmfilesettingGrid").yxgrid({
		model : 'engineering_file_esmfilesetting',
		title : '项目文档设置',
		//列信息
		colModel : [ {
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'fileName',
			display : '文档名称',
			sortable : true,
			width : 120
		}, {
			name : 'fileType',
			display : '文档类型',
			sortable : true,
			width : 120
		}, {
			name : 'isNeedUpload',
			display : '是否必须上传',
			sortable : true,
			process :　function (v){
				if(v == 1){
					return '是';
				}
				else {
					return '否';
				}
			},
			width : 80
		}, {
			name : 'description',
			display : '描述',
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
			display : "文档名称",
			name : 'fileNameSch'
		},{
			display : "文档类型",
			name : 'fileTypeSch'
		} ]
	});
});