/**
 * ��ɫ����������
 */
(function($) {
	$.woo.yxcombogrid.subclass('woo.yxcombogrid_socialplace', {
				options : {
					hiddenId : 'id',
					nameCol : 'socialCity',
       				title : '�籣�����',
					gridOptions : {
						showcheckbox : false,
						model : 'hr_basicinfo_socialplace',
						action : 'pageJson',
						pageSize : 10,
						// ����Ϣ
						colModel : [{
									display : 'id',
									name : 'id',
									sortable : true,
									hide : true
								}, {
									name : 'socialCity',
									width:200,
									display : '�籣����',
									sortable : true

								}],
						// Ĭ�������ֶ���
						sortname : "id",
						// Ĭ������˳��
						sortorder : "ASC",
					searchitems : [{
								display : "����",
								name : 'socialCity'
							}]
					}
				}
			});
})(jQuery);