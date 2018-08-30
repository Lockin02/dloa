/**
 * �������ϵ�������
 */
(function($) {
	$.woo.yxcombogrid.subclass('woo.yxcombogrid_arrival', {
				options : {
					hiddenId : 'id',
					nameCol : 'arrivalCode',
					gridOptions : {
						model : 'purchase_arrival_arrival',
						// ����Ϣ
						colModel : [{
									display : 'id',
									name : 'id',
									hide : true,
									width : 130
								}, {
									display : '���ϵ���',
									name : 'arrivalCode',
									width : 180
								}, {
									display : '�ɹ��������',
									name : 'purchaseCode',
									width : 180
								}, {
									display : '�ɹ�����id',
									name : 'purchaseId',
									hide : true,
									width : 180
								}, {
									display : '���ϵ�����',
									name : 'arrivalType',
									datacode : 'ARRIVALTYPE',
									width : 150
								}, {
									display : '��Ӧ������',
									name : 'supplierName',
									width : 150
								}, {
									display : '��Ӧ��Id',
									name : 'supplierId',
									width : 150,
									hide : true
								}, {
									display : '���ϲֿ�',
									name : 'stockName',
									width : 150
								}, {
									display : '���ϲֿ�id',
									name : 'stockId',
									width : 150,
									hide : true
								}, {
									display : '�ɹ���ʽ',
									name : 'purchMode',
									datacode : 'cgfs',
									width : 150,
									hide : true
								}],
						// ��������
						searchitems : [{
					display : '���ϵ���',
					name : 'arrivalCode'
				}, {
					display : '�ɹ�Ա',
					name : 'purchManName'
				},{
					display : '��Ӧ��',
					name : 'supplierName'
				}],
						// Ĭ�������ֶ���
						sortname : "arrivalCode",
						// Ĭ������˳��
						sortorder : "DESC"
					}
				}
			});
})(jQuery);