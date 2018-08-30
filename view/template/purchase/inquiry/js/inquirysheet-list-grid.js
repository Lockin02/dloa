
(function($) {
	$.woo.yxgrid.subclass('woo.yxgrid_inquirysheet', {
		options : {
			model : 'purchase_inquiry_inquirysheet',
			action : 'myPageJson',
			isTitle : false,
			isToolBar : false,

			// ����Ϣ
			colModel : [{
				display : 'id',
				name : 'id',
				sortable : true,
				hide : true
			}, {
				display : 'ѯ�۵����',
				name : 'inquiryCode',
				sortable : true,
				width : 160
			}, {
				display : '�ɹ�Ա',
				name : 'purcherName',
				sortable : true
			}, {
				display : 'ѯ������',
				name : 'inquiryBgDate',
				sortable : true
			}, {
				display : '���۽�ֹ����',
				name : 'inquiryEndDate',
				sortable : true
			}, {
				display : '��Ч����',
				name : 'effectiveDate',
				sortable : true
			}, {
				display : 'ʧЧ����',
				name : 'expiryDate',
				sortable : true
			}, {
				display : '״̬',
				name : 'stateName',
//				sortable : true,
				width:60
			}, {
				display : 'ָ����Ӧ��',
				name : 'suppName',
				sortable : true
			}, {
				display : 'ָ��������',
				name : 'amaldarName',
				sortable : true
			}, {
				display : 'ָ����ע',
				name : 'amaldarRemark',
				sortable : true,
				hide:true
			}],
			searchitems : [{
				display : 'ѯ�۵����',
				name : 'inquiryCode'
			}],
		// Ĭ������˳��
		sortorder : "DESC",
		sortname:"updateTime"

		}
	});
})(jQuery);