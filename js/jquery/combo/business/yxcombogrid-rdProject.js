/**
 * �����з���Ŀ������
 */
(function($) {
	$.woo.yxcombogrid.subclass('woo.yxcombogrid_rdProject', {
		options : {
			hiddenId : 'id',
			nameCol : 'projectName',
			gridOptions : {
				showcheckbox : true,
				model : 'rdproject_project_rdproject',
				action : 'pageJson2',
				// ����Ϣ
				colModel : [{
						display : '��Ŀ���',
						name : 'projectCode',
						width : 130
					}, {
						display : '��Ŀ����',
						name : 'projectName',
						width : 150
					}, {
						display : '��Ŀ���',
						name : 'projectType',
						datacode : 'YFXMGL'
					}, {
						display : '��Ŀ����',
						name : 'managerName'
					}
				],
				// ��������
				searchitems : [{
						display : '��Ŀ����',
						name : 'seachProjectName'
					},{
						display : '��Ŀ���',
						name : 'seachProjectCode'
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