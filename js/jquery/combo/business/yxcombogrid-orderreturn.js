/**
 * ���ϻ�����Ϣ����������
 */
(function($) {
	$.woo.yxcombogrid.subclass('woo.yxcombogrid_orderreturn', {
				options : {
					hiddenId : 'id',
					nameCol : 'renturnCode',
					gridOptions : {
						showcheckbox : false,
						model : 'projectmanagent_return_return',
						action : 'pageJson',
						pageSize : 10,
						// ����Ϣ
						colModel : [{
									display : 'id',
									name : 'id',
									sortable : true,
									hide : true
								}, {
									name : 'renturnCode',
									display : '���ݱ��',
									sortable : true
								}, {
									name : 'orderCode',
									display : '��ͬ��',
									sortable : true
								}, {
									name : 'orderName',
									display : '��ͬ����',
									hide : true
								}, {
									name : 'orderId',
									display : '��ͬId',
									hide : true
								}, {
									name : 'prinvipalName',
									display : '��ͬ������',
									sortable : true
								}, {
									name : 'returnCause',
									display : '����ԭ��',
									sortable : true
								}, {
									name : 'saleWay',
									display : '���۷�ʽ',
									sortable : true
								}, {
									name : 'storage',
									display : '�ջ��ֿ�',
									sortable : true
								}, {
									name : 'createName',
									display : '������',
									sortable : true
								}, {
									name : 'ExaStatus',
									display : '����״̬',
									sortable : true
								}, {
									name : 'ExaDT',
									display : '��������',
									sortable : true
								}],
						/**
						 * ��������
						 */
						searchitems : [{
									display : '�˻������',
									name : 'renturnCode'
								}],
						// Ĭ�������ֶ���
						sortname : "id",
						// Ĭ������˳��
						sortorder : "ASC"
					}
				}
			});
})(jQuery);