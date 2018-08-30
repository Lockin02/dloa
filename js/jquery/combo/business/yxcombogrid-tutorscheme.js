/**
 * 联系人表格组件
 */

(function($) {
	$.woo.yxcombogrid.subclass('woo.yxcombogrid_tutorscheme', {
				options : {
					hiddenId : 'id',
					nameCol : 'schemeName',
					title : '导师考核方案',
					gridOptions : {
						model : 'hr_tutor_tutorScheme&action=page',

						// 表单
						colModel : [{
									display : 'id',
									name : 'id',
									sortable : true,
									hide : true
								}, {
									name : 'schemeName',
									display : '方案名称',
									sortable : true
								}, {
									name : 'createName',
									display : '创建人',
									sortable : true,
									width : 100
								}, {
									name : 'remark',
									display : '备注',
									sortable : true,
									width : 200
								}],

						/**
						 * 快速搜索
						 */
						searchitems : [{
									display : "方案名称",
									name : 'schemeName'
								}],
						sortname : "id",
						// 默认搜索顺序
						sortorder : "ASC"
					}
				}
			});
})(jQuery);