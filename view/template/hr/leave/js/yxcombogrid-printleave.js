/**
 * ������ӡ��ְ֤����Ϣ
 */
(function($) {
	$.woo.yxcombogrid.subclass('woo.yxcombogrid_printleave', {
		options : {
			hiddenId : 'id',
			nameCol : 'userName',
			gridOptions : {
				title : '�ɴ�ӡ��ְ֤����Ա��Ϣ',
				showcheckbox : false,
				model : 'hr_leave_leave',
				// ����Ϣ
				colModel : [{
								display : 'id',
								name : 'id',
								sortable : true,
								hide : true
							},
							{
								name : 'leaveCode',
								display : '���ݱ��',
								sortable : true
							},{
								name : 'userNo',
								display : 'Ա�����',
								width:80,
								sortable : true
							}, {
								name : 'userName',
								display : 'Ա������',
								width:60,
								sortable : true
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
				sortorder : "DESC"
			}
		}
	});
})(jQuery);