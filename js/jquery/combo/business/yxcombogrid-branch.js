/**
 * ������˾��Ϣ
 */
(function($) {
	$.woo.yxcombogrid.subclass('woo.yxcombogrid_branch', {
		options : {
			hiddenId : 'NamePT',
			nameCol : 'NameCN',
			valueCol : 'NamePT',
			gridOptions : {
				title : '��˾��Ϣ',
				showcheckbox : false,
				model : 'deptuser_branch_branch',
				// ����Ϣ
				colModel : [{
						display : 'id',
						name : 'id',
						hide : true
					}, {
						display : '��˾����',
						name : 'NameCN'
					}, {
						display : '��˾����',
						name : 'NamePT'
					}
				],
				// ��������
//				searchitems : [{
//						display : '��������',
//						name : 'agencyName'
//					}
//				],
				// Ĭ�������ֶ���
				sortname : "id",
				// Ĭ������˳��
				sortorder : "ASC"
			}
		}
	});
})(jQuery);