/**
 * ��ɫ����������
 */
(function($) {
	$.woo.yxcombogrid.subclass('woo.yxcombogrid_jobs', {
				options : {
					hiddenId : 'jobId',
					nameCol : 'name',
					gridOptions : {
						showcheckbox : false,
						model : 'deptuser_jobs_jobs',
						action : 'pageJson',
						pageSize : 10,
						// ����Ϣ
						colModel : [{
									display : 'id',
									name : 'id',
									sortable : true,
									hide : true
								}, {
									name : 'name',
									width:200,
									display : '��ɫ����',
									sortable : true

								}],
						// Ĭ�������ֶ���
						sortname : "id",
						// Ĭ������˳��
						sortorder : "ASC"
					}
				}
			});
})(jQuery);