/**
 * �����ͻ�������
 */
(function($) {
	$.woo.yxcombogrid.subclass('woo.yxcombogrid_agency', {
		options : {
			hiddenId : 'agencyCode',
			nameCol : 'agencyName',
			valueCol : 'agencyCode',
			gridOptions : {
				showcheckbox : false,
				model : 'asset_basic_agency',
				// ����Ϣ
				colModel : [{
						display : '��������',
						name : 'agencyName',
						width : '80'
					}, {
						display : '�������',
						name : 'agencyCode',
						width : '70'
					}, {
						display : '��������',
						name : 'chargeName',
						width : '80'
					}, {
						display : '��ע',
						name : 'remark'
					}
				],
				// ��������
				searchitems : [{
						display : '��������',
						name : 'agencyName'
					}
				],
				// Ĭ�������ֶ���
				sortname : "id",
				// Ĭ������˳��
				sortorder : "ASC"
			}
		}
	});
})(jQuery);