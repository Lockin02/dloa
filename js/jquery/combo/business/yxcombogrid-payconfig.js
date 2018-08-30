/**
 * ��������
 */

(function($) {
	$.woo.yxcombogrid.subclass('woo.yxcombogrid_payconfig', {
		options: {
			hiddenId: 'id',
			nameCol: 'configName',
			gridOptions: {
				model: 'contract_config_payconfig',

				// ��
				colModel: [{
					display: 'id',
					name: 'id',
					sortable: true,
					hide: true
				},
				{
					name: 'configName',
					display: '��������',
					sortable: true
				},
				{
					name: 'dateName',
					display: '��������',
					sortable: true
				},
				{
					name: 'days',
					display: '��ֹ����',
					sortable: true
				},
				{
					name: 'description',
					display: '˵��',
					sortable: true,
					width: 200
				}],

				/**
				 * ��������
				 */
				searchitems: [{
					display: '��������',
					name: 'configName'
				}],
				sortorder: "ASC",
				title: ''
			}
		}
	});
})(jQuery);