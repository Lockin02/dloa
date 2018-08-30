/**
 * 生产计划基本信息下拉表格组件
 */
(function ($) {
	$.woo.yxcombogrid.subclass('woo.yxcombogrid_produceplan', {
		options: {
			hiddenId: 'planId',
			nameCol: 'planCode',
			width: 650,
			gridOptions: {
				showcheckbox: false,
				model: 'produce_plan_produceplan',
				action: 'pageJson',
				param: {
					docStatusIn: '0,1'
				},

				// 列信息
				colModel: [{
					display: '单据编号',
					name: 'docCode',
					width: 120
				}, {
					display: '合同编号',
					name: 'relDocCode',
					width: 120
				}, {
					display: '物料编码',
					name: 'productCode',
					width: 80
				}, {
					display: '物料名称',
					name: 'productName',
					width: 180
				}, {
					display: '申请数量',
					name: 'planNum',
					width: 60
				}, {
					display: '规格型号',
					name: 'pattern',
					width: 80
				}, {
					display: '单位',
					name: 'unitName'
				}],

				// 快速搜索
				searchitems: [{
					display: '单据编号',
					name: 'docCode'
				}, {
					display: '合同编号',
					name: 'relDocCode'
				}, {
					display: '物料编码',
					name: 'productCode'
				}, {
					display: '物料名称',
					name: 'productName'
				}],

				// 默认搜索字段名
				sortname: "id",
				// 默认搜索顺序
				sortorder: "DESC"
			}
		}
	});
})(jQuery);