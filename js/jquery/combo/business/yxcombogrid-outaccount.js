/**
 * ����������������
 */
(function($) {
	$.woo.yxcombogrid.subclass('woo.yxcombogrid_outaccount', {
				options : {
					hiddenId : 'id',
					nameCol : 'formCode',
					gridOptions : {
						model : 'outsourcing_account_basic',
						// ����Ϣ
						colModel : [{
									display : 'id',
									name : 'id',
									hide : true,
									width:130
								},{
									display : '���ݱ��',
									name : 'formCode',
									width:150
								},{
									display : '�����Ӧ��',
									name : 'suppName',
									width:150
								},{
									display : '���۸�����',
									name : 'saleManangerName',
									width:150
								}],
						// ��������
						searchitems : [{
									display : '���ݱ��',
									name : 'formCode'
								}],
						// Ĭ�������ֶ���
						sortname : "formCode",
						// Ĭ������˳��
						sortorder : "DESC"
					}
				}
			});
})(jQuery);