/**
 * ������������
 */
(function($) {
	$.woo.yxcombogrid.subclass('woo.yxcombogrid_fillup', {
				options : {
					hiddenId : 'id',
					nameCol : 'fillupCode',
					gridOptions : {
						model : 'stock_fillup_fillup',
						// ����Ϣ
						colModel : [{
								display : 'id',
								name : 'id',
								sortable : true,
								hide : true
							}, {
								name : 'fillupCode',
								display : '����ƻ�����',
								sortable : true
							}, {
								name : 'stockId',
								display : '�ֿ�id',
								sortable : true,
								hide : true
							}, {
								name : 'stockName',
								display : '�ֿ�����',
								sortable : true
							}, {
								name : 'stockCode',
								display : '�ֿ����',
								sortable : true
							}, {
								name : 'remark',
								display : '����ԭ���뱸ע',
								sortable : true
							}, {
								name : 'auditStatus',
								display : '�ύ״̬',
								sortable : true,
								hide : true
							}, {
								name : 'ExaStatus',
								display : '����ƻ�����״̬',
								sortable : true,
								hide : true
							}, {
								name : 'ExaDT',
								display : '����ʱ��',
								sortable : true,
								hide : true
							}, {
								name : 'updateId',
								display : '�޸���id',
								sortable : true,
								hide : true
							}, {
								name : 'updateName',
								display : '�޸���',
								sortable : true,
								hide : true
							}, {
								name : 'createTime',
								display : '��������',
								sortable : true,
								hide : true
							}, {
								name : 'createName',
								display : '������',
								sortable : true,
								hide : true
							}, {
								name : 'createId',
								display : '������id',
								sortable : true,
								hide : true
							}, {
								name : 'updateTime',
								display : '�޸�����',
								sortable : true,
								hide : true
							}],
						// ��������
						searchitems : [{
									display : '�ֿ�����',
									name : 'stockName'
								}],
						// Ĭ�������ֶ���
						sortname : "updateTime",
						// Ĭ������˳��
						sortorder : "DESC"
					}
				}
			});
})(jQuery);