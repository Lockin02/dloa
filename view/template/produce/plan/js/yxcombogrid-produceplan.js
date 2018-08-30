/**
 * �����ƻ�������Ϣ����������
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

				// ����Ϣ
				colModel: [{
					display: '���ݱ��',
					name: 'docCode',
					width: 120
				}, {
					display: '��ͬ���',
					name: 'relDocCode',
					width: 120
				}, {
					display: '���ϱ���',
					name: 'productCode',
					width: 80
				}, {
					display: '��������',
					name: 'productName',
					width: 180
				}, {
					display: '��������',
					name: 'planNum',
					width: 60
				}, {
					display: '����ͺ�',
					name: 'pattern',
					width: 80
				}, {
					display: '��λ',
					name: 'unitName'
				}],

				// ��������
				searchitems: [{
					display: '���ݱ��',
					name: 'docCode'
				}, {
					display: '��ͬ���',
					name: 'relDocCode'
				}, {
					display: '���ϱ���',
					name: 'productCode'
				}, {
					display: '��������',
					name: 'productName'
				}],

				// Ĭ�������ֶ���
				sortname: "id",
				// Ĭ������˳��
				sortorder: "DESC"
			}
		}
	});
})(jQuery);