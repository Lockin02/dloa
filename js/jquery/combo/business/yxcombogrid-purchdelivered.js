/**
 * �������ϵ�������
 */
(function($) {
	$.woo.yxcombogrid.subclass('woo.yxcombogrid_purchdelivered', {
				options : {
					hiddenId : 'id',
					nameCol : 'returnCode',
					gridOptions : {
						model : 'purchase_delivered_delivered',
						// ����Ϣ
						colModel : [{
									display : 'id',
									name : 'id',
									hide : true,
									width:130
								},{
									display : '���ϵ���',
									name : 'returnCode',
									width:180
								},{
									display : '��������',
									name : 'returnType',
									width:150
								},{
									display : '��Ӧ������',
									name : 'supplierName',
									width:150
								},{
									display : '��Ӧ��Id',
									name : 'supplierId',
									width:150,
									hide : true
								}],
						// ��������
						searchitems : [{
									display : '���ϵ���',
									name : 'returnCode'
								}],
						// Ĭ�������ֶ���
						sortname : "returnCode",
						// Ĭ������˳��
						sortorder : "DESC"
					}
				}
			});
})(jQuery);