/**
 * 联系人表格组件
 */

(function($) {
	$.woo.yxcombogrid.subclass('woo.yxcombogrid_templatevals', {
				options : {
					hiddenId : 'id',
					// param : { 'customerId' :$('customerId').val() },
					nameCol : 'templateName',
					title : '盘点模板',
					gridOptions : {
						model : 'hr_inventory_template&action=page',

						// 表单
						colModel : [{
									display : 'id',
									name : 'id',
									sortable : true,
									hide : true
								}, {
									name : 'templateName',
									display : '模板名称',
									sortable : true,
									width:250
								},{
									name : 'remark',
									display : '备注',
									sortable : true,
									width:250
								}],

						/**
						 * 快速搜索
						 */
						searchitems : [{
									display : "属性名称",
									name : 'attrName'
								}],
						sortname : "id",
						// 默认搜索顺序
						sortorder : "ASC"
					}
				}
			});
})(jQuery);