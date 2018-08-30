/**
 * �ֿ������Ϣ����������
 */
(function($) {
	$.woo.yxcombogrid.subclass('woo.yxcombogrid_stockinfo', {
				options : {
					hiddenId : 'id',
					nameCol : 'stockCode',
					gridOptions : {
						showcheckbox : false,
						model : 'stock_stockinfo_stockinfo',
						action : 'pageJson',
						pageSize : 10,
						// ����Ϣ
						colModel : [{
									display : 'id',
									name : 'id',
									sortable : true,
									hide : true
								}, {
									name : 'stockCode',
									display : '�ֿ����',
									sortable : true
								}, {
									name : 'stockName',
									display : '�ֿ�����',
									width:150,
									sortable : true
								}, {
									name : 'chargeUserName',
									display : '�ֿ����Ա',
									sortable : true
								}, {
									name : 'stockUseCode',
									display : '�ֿ���;',
									datacode:'CKYT',
									width:70,
									sortable : true
								}, {
									name : 'stockType',
									display : '�ֿ�����',
									datacode:'CKLX',
									width:50,
									sortable : true
								}],
						// ��������
						searchitems : [{
									display : '�ֿ����',
									name : 'stockCode'
								}, {
									display : '�ֿ�����',
									name : 'stockName'
								}],
						// Ĭ�������ֶ���
						sortname : "id",
						// Ĭ������˳��
						sortorder : "desc"
					}
				}
			});
})(jQuery);