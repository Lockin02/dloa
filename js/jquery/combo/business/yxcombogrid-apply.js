/**
 * �����̶��ʲ��ɹ�����
 */
(function($) {
	$.woo.yxcombogrid.subclass('woo.yxcombogrid_apply', {
		options : {
			hiddenId : 'id',
			nameCol : 'formCode',
			width : 550,
			gridOptions : {
				showcheckbox : false,
				model : 'asset_purchase_apply_apply',
				param : {
					"ExaStatus" : '���'
				},
				// ����Ϣ
				colModel : [{
					name : 'id',
					display : '�ɹ�����id',
					sortable : true,
					hide : true
				}, {
					name : 'formCode',
					display : '���ݱ��',
					sortable : true
				}, {
					name : 'applyDetName',
					display : '���벿��',
					sortable : true
				}, {
					name : 'applyTime',
					display : '��������',
					sortable : true
				}, {
					name : 'applicantName',
					display : '����������',
					sortable : true
				}, {
					name : 'useDetName',
					display : 'ʹ�ò���',
					sortable : true
				}, {
					name : 'userName',
					display : 'ʹ��������',
					sortable : true
				}, {
					name : 'purchCategory',
					display : '�ɹ�����',
					sortable : true,
					datacode : 'CGZL'
				}]
			}
		}
	});
})(jQuery);