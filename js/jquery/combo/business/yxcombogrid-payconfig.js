/**
 * 付款条件
 */

(function($) {
	$.woo.yxcombogrid.subclass('woo.yxcombogrid_payconfig', {
		options: {
			hiddenId: 'id',
			nameCol: 'configName',
			gridOptions: {
				model: 'contract_config_payconfig',

				// 表单
				colModel: [{
					display: 'id',
					name: 'id',
					sortable: true,
					hide: true
				},
				{
					name: 'configName',
					display: '付款名称',
					sortable: true
				},
				{
					name: 'dateName',
					display: '付款属性',
					sortable: true
				},
				{
					name: 'days',
					display: '截止天数',
					sortable: true
				},
				{
					name: 'description',
					display: '说明',
					sortable: true,
					width: 200
				}],

				/**
				 * 快速搜索
				 */
				searchitems: [{
					display: '付款名称',
					name: 'configName'
				}],
				sortorder: "ASC",
				title: ''
			}
		}
	});
})(jQuery);