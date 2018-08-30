/**
 * 基本信息下拉表格组件
 */
(function($) {
	$.woo.yxcombogrid.subclass('woo.yxcombogrid_ticket', {
		options : {
			hiddenId : 'id',
			nameCol : 'institutionName',//要默认现实
			height : 270,
			gridOptions : {
				title : '订票机构',
				isTitle : true,
				showcheckbox : false,
				model : 'flights_ticketagencies_ticket',
				action : 'pageJson',
				pageSize : 10,
				// 列信息
				colModel : [{
					display : 'id',
					name : 'id',
					sortable : true,
					hide : true
				}, {
					name : 'institutionId',
					display : '机构编码',
					sortable : true,
					width:100
				}, {
					name : 'institutionName',
					display : '机构名称',
					sortable : true,
					width:100
				}, {
					name : 'bookingBusiness',
					display : '订票业务',
					sortable : true,
					width:150
				}, {
					name : 'institutionBusiness',
					display : '机构描述',
					sortable : true,
					width:100
				}],
				// 快速搜索
				searchitems : [{
					display : '机构编码',
					name : 'institutionIdSearch'
				},{
					display : '机构名称',
					name : 'institutionNameSearch'
				}],
				// 默认搜索字段名
				sortname : "id",
				// 默认搜索顺序
				sortorder : "desc"
			}
		}
	});
})(jQuery);