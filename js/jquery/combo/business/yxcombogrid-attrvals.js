/**
 * 联系人表格组件
 */

(function($) {
	$.woo.yxcombogrid.subclass('woo.yxcombogrid_attrvals', {
				options : {
					hiddenId : 'id',
					// param : { 'customerId' :$('customerId').val() },
					nameCol : 'attrName',
					title : '盘点表属性',
					// openPageOptions : {
					// url :
					// '?model=customer_linkman_linkman&action=selectLinkman'
					// },
					gridOptions : {
						model : 'hr_inventory_attr&action=page',

						// 表单
						colModel : [{
									display : 'id',
									name : 'id',
									sortable : true,
									hide : true
								}, {
									name : 'attrName',
									display : '属性名称',
									sortable : true,
									width:200
								}, {
									name : 'attrType',
									display : '属性类型',
									process : function(v,row) {
										return (v == 0 ? "文本框" : "下拉框");
									},
									sortable : true
								}, {
									name : 'remark',
									display : '备注',
									sortable : true
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