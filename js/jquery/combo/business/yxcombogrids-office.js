/**
 * �����ͻ�������
 */
(function($) {
	$.woo.yxcombogrid.subclass('woo.yxcombogrid_office', {
				options : {
					hiddenId : 'officeId',
					nameCol : 'officeName',
					gridOptions : {
						showcheckbox : true,
						model : 'engineering_officeinfo_officeinfo',
						// ����Ϣ
						colModel : [{
									display : '���´�',
									name : 'officeName'
								}, {
									display : '���´�������',
									name : 'managerName',
									width : '150px'
								}, {
									display : '���η�Χ',
									name : 'rangeName',
									width : '200px'
								}, {
									display : '��ע',
									name : 'TypeOne'
								}],
						// ��������
						searchitems : [{
									display : '���´�����',
									name : 'officeName'
								}],
						// Ĭ�������ֶ���
						sortname : "id",
						// Ĭ������˳��
						sortorder : "ASC"
					}
				}
			});
})(jQuery);