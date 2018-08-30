/**
 * 下拉客户表格组件
 */
(function($) {
	$.woo.yxcombogrid.subclass('woo.yxcombogrid_behamoduledetail', {
		options : {
			hiddenId : 'id',
			nameCol : 'detailName',
			gridOptions : {
				showcheckbox : false,
				model : 'hr_baseinfo_behamoduledetail',
				// 列信息
				colModel : [{
						display : '行为模块id',
						name : 'moduleId',
						hide : true
					}, {
						display : '行为模块',
						name : 'moduleName',
						width : 120
					}, {
						display : 'id',
						name : 'id',
						hide : true
					}, {
						display : '行为要项',
						name : 'detailName',
						width : 120
					}, {
						display : '备注',
						name : 'remark',
						width : 200
					}
				],
				// 快速搜索
				searchitems : [{
						display : '行为要项',
						name : 'detailNameSearch'
					},{
						display : '行为模块',
						name : 'moduleNameSearch'
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