/**
 * �������ⵥ������
 */
(function($) {
	$.woo.yxcombogrid.subclass('woo.yxcombogrid_stockout', {
				options : {
					hiddenId : 'id',
					nameCol : 'docCode',
					gridOptions : {
						model : 'stock_outstock_stockout',
						// ����Ϣ
						colModel : [
									{
										display : 'id',
										name : 'id',
										sortable : true,
										hide : true
									},
									{
										name : 'isRed',
										display : '����ɫ',
										sortable : true,
										align : 'center',
										width : '35',
										process : function(v, row) {
											if (row.isRed == '0') {
												return "<img src='images/icon/hblue.gif' />";
											} else {
												return "<img src='images/icon/hred.gif' />";
											}
										}
									}, {
										name : 'docCode',
										display : '���ݱ��',
										sortable : true

									}, {
										name : 'docType',
										display : '���ⵥ����',
										sortable : true,
										hide : true
									}, {
										name : 'relDocId',
										display : 'Դ��id',
										sortable : true,
										hide : true
									}, {
										name : 'relDocType',
										display : 'Դ������',
										sortable : true,
										datacode : 'RKDYDLX2'
									}, {
										name : 'relDocCode',
										display : 'Դ�����',
										sortable : true
									}, {
										name : 'relDocName',
										display : 'Դ������',
										sortable : true,
										hide : true

									}, {
										name : 'stockId',
										display : '���ϲֿ�id',
										sortable : true,
										hide : true
									}, {
										name : 'stockCode',
										display : '���ϲֿ����',
										sortable : true
									}, {
										name : 'stockName',
										display : '���ϲֿ�����',
										sortable : true
									}, {
										name : 'docStatus',
										display : '����״̬',
										sortable : true,
										process : function(v, row) {
											if (row.docStatus == 'WSH') {
												return "δ���";
											} else {
												return "�����";
											}
										}
									}],
						// ��������
						searchitems : [{
									display : '���ݱ��',
									name : 'docCode'
								}],
						// Ĭ�������ֶ���
						sortname : "docCode",
						// Ĭ������˳��
						sortorder : "ASC"
					}
				}
			});
})(jQuery);