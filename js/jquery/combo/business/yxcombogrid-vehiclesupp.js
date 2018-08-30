/**
 * ����������Ӧ�������������ϵ�ˡ��绰����ַ��
 */
(function($) {
	$.woo.yxcombogrid.subclass('woo.yxcombogrid_vehiclesupp', {
		options: {
			hiddenId: 'id',
			nameCol: 'suppName',
			width: 600,
			isFocusoutCheck: false,
			gridOptions: {
				showcheckbox: false,
				model: 'outsourcing_outsourcessupp_vehiclesupp',
				param : {
					"suppLevelNeq" : '0'
				},
				//����Ϣ
				colModel: [{
					display: 'id',
					name: 'id',
					sortable: true,
					hide: true
				},{
					name: 'suppName',
					display: 'ǩԼ��˾',
					width: 130,
					sortable: true
				},{
					name: 'province',
					display: '��˾ʡ��',
					width: 70,
					sortable: true
				},{
					name: 'city',
					display: '��˾����',
					width: 70,
					sortable: true
				},{
					name: 'linkmanName',
					display: '��ϵ��',
					width: 80,
					sortable: true
				},{
					name: 'linkmanPhone',
					display: '��ϵ�绰',
					width: 80,
					sortable: true
				},{
					name: 'address',
					display: '��ַ',
					width: 150,
					sortable: true
				}],

				// ��������
				searchitems: [{
					display: 'ǩԼ��˾',
					name: 'suppName'
				},{
					display: '��˾ʡ��',
					name: 'provinceSea'
				},{
					display: '��˾����',
					name: 'citySea'
				},{
					display: '��ϵ��',
					name: 'linkmanName'
				}],

				// Ĭ�������ֶ���
				sortname: "id",
				// Ĭ������˳��
				sortorder: "DESC"
			}
		}
	});
})(jQuery);