/**
 * 下拉研发项目表格组件
 */
(function($) {
	$.woo.yxcombogrid.subclass('woo.yxcombogrid_rdProject', {
		options : {
			hiddenId : 'id',
			nameCol : 'projectName',
			gridOptions : {
				showcheckbox : true,
				model : 'rdproject_project_rdproject',
				action : 'pageJson2',
				// 列信息
				colModel : [{
						display : '项目编号',
						name : 'projectCode',
						width : 130
					}, {
						display : '项目名称',
						name : 'projectName',
						width : 150
					}, {
						display : '项目类别',
						name : 'projectType',
						datacode : 'YFXMGL'
					}, {
						display : '项目经理',
						name : 'managerName'
					}
				],
				// 快速搜索
				searchitems : [{
						display : '项目名称',
						name : 'seachProjectName'
					},{
						display : '项目编号',
						name : 'seachProjectCode'
					}
				],
				// 默认搜索字段名
				sortname : "id",
				// 默认搜索顺序
				sortorder : "ASC"
			}
		}
	});
})(jQuery);