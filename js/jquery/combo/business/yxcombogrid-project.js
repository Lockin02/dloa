/**
 * �����ͻ�������
 */
(function($) {
	$.woo.yxcombogrid.subclass('woo.yxcombogrid_project', {
				options : {
					hiddenId : 'projectId',
					nameCol : 'projectName',
					gridOptions : {
						showcheckbox : true,
						model : 'engineering_project_esmproject',
						action : 'pageJsonMyPj',
						// ����Ϣ
						colModel : [{
									display : '��Ŀ����',
									name : 'projectName',
									width : 180
								}, {
									display : '��Ŀ���',
									name : 'projectCode',
									width : 130
								}, {
									display : '�������´�',
									name : 'officeName'
								}, {
									display : '��Ŀ����',
									name : 'managerName'
								}, {
									display : '(Ԥ�ƽ�������)',
									name : 'planDateClose',
									hide: true
								}],
						// ��������
						searchitems : [{
									display : '��Ŀ����',
									name : 'projectName'
								}],
						// Ĭ�������ֶ���
						sortname : "id",
						// Ĭ������˳��
						sortorder : "ASC"
					}
				}
			});
})(jQuery);