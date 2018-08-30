/**
 * 下拉研发项目表格组件
 */
(function($) {
	$.woo.yxcombogrid.subclass('woo.yxcombogrid_rdprojectfordl', {
		isDown : false,
		setValue : function(rowData) {
			if (rowData) {
				var t = this, p = t.options, el = t.el;
				p.rowData = rowData;
				if (p.gridOptions.showcheckbox) {
					if (p.hiddenId) {
						p.idStr = rowData.idArr;
						$("#" + p.hiddenId).val(p.idStr);
						p.nameStr = rowData.text;
						$(el).val(p.nameStr);
						$(el).attr('title', p.nameStr);
					}
				} else if (!p.gridOptions.showcheckbox) {
					if (p.hiddenId) {
						p.idStr = rowData[p.valueCol];
						$("#" + p.hiddenId).val(p.idStr);
						p.nameStr = rowData[p.nameCol];
						$(el).val(p.nameStr);
						$(el).attr('title', p.nameStr);
					}
				}
			}
		},
		options : {
			hiddenId : 'projectCode',
			nameCol : 'projectName',
			searchName : 'searhDProjectCode',
			openPageOptions : {
				url : '?model=rdproject_project_rdproject&action=pageForDL',
				width : '750'
			},
			closeCheck : false,// 关闭状态,不可选择
			gridOptions : {
				showcheckbox : false,
				model : 'rdproject_project_rdproject',
				action : 'pageJsonForDL',
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
						display : '项目经理',
						name : 'managerName',
						width : 80
					}, {
						display : '项目描述',
						name : 'description',
						width : 300
					}
				],
				// 快速搜索
				searchitems : [{
						display : '项目名称',
						name : 'searhDProjectName'
					},{
						display : '项目编号',
						name : 'searhDProjectCode'
					}
				],
				// 默认搜索字段名
				sortname : "number",
				// 默认搜索顺序
				sortorder : "DESC"
			}
		}
	});
})(jQuery);