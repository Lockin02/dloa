/**
 * �������������������
 */
(function($) {
	$.woo.yxcombogrid.subclass('woo.yxcombogrid_batchnumb', {
				options : {
					hiddenId : 'batchNumb',
					nameCol : 'batchNumb',
					gridOptions : {
						showcheckbox : false,
						model : 'purchase_plan_basic',
						action : 'batchNumbPageJson',
						pageSize : 10,
						// ����Ϣ
						colModel : [{
									name : 'batchNumb',
									display : '���κ�',
									sortable : true,
									width:300
								}],
						// ��������
						searchitems : [{
									display : '���κ�',
									name : 'batchNumb'
								}],
						// Ĭ�������ֶ���
						sortname : "id",
						// Ĭ������˳��
						sortorder : "DESC"
					}
				}
			});
})(jQuery);