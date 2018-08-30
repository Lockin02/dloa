/**
 * 下拉客户表格组件
 */
(function($) {
	$.woo.yxcombogrid.subclass('woo.yxcombogrid_office', {
				options : {
					hiddenId : 'officeId',
					nameCol : 'officeName',
					gridOptions : {
						showcheckbox : true,
						model : 'engineering_officeinfo_officeinfo',
						// 列信息
						colModel : [{
									display : '办事处',
									name : 'officeName'
								}, {
									display : '办事处负责人',
									name : 'managerName',
									width : '150px'
								}, {
									display : '责任范围',
									name : 'rangeName',
									width : '200px'
								}, {
									display : '备注',
									name : 'TypeOne'
								}],
						// 快速搜索
						searchitems : [{
									display : '办事处名称',
									name : 'officeName'
								}],
						// 默认搜索字段名
						sortname : "id",
						// 默认搜索顺序
						sortorder : "ASC"
					}
				}
			});
})(jQuery);