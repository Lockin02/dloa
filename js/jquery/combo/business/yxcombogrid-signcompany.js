/**
 * �����ͻ�������
 */
(function($) {
	$.woo.yxcombogrid.subclass('woo.yxcombogrid_signcompany', {
		options : {
			hiddenId : 'signCompanyId',
			nameCol : 'signCompanyName',
			searchName : 'signCompanyNameSearch',
			gridOptions : {
				showcheckbox : false,
				model : 'contract_signcompany_signcompany',
				// ����Ϣ
				colModel : [{
							display : 'ǩԼ��˾',
							name : 'signCompanyName',
							width : 120
						}, {
							display : '��˾ʡ��',
							name : 'proName',
							width : 80
						}, {
							display : '��ϵ��',
							name : 'linkman',
							width : 80
						}, {
							display : '��ϵ�绰',
							name : 'phone',
							width : 80
						}, {
							display : '��ַ',
							name : 'address',
							sortable : true,
							width : 150
						}],
				// ��������
				searchitems : [{
					display : 'ǩԼ��˾',
					name : 'signCompanyNameSearch'
				}, {
					display : '��ϵ��',
					name : 'linkmanSearch'
				}],
				// Ĭ�������ֶ���
				sortname : "id",
				// Ĭ������˳��
				sortorder : "ASC"
			}
		}
	});
})(jQuery);