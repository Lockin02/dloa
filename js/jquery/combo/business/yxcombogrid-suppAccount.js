/**
 * ������������
 */
(function($) {
	$.woo.yxcombogrid.subclass('woo.yxcombogrid_suppAccount', {
				options : {
					//hiddenId : 'suppId',
					nameCol : 'accountNum',
					gridOptions : {
						model : 'supplierManage_formal_bankinfo',
						// ����Ϣ
						colModel : [{
									display : 'id',
									name : 'id',
									hide : true
								}
								,{
									display : '��Ӧ������',
									name : 'suppName',
									width:150
								}
								,{
									display : '�����ʺ�',
									name : 'accountNum',
									width:150
								}
								,{
									display : '��������',
									name : 'bankName',
									width:150
								}
								,{
									display : '��Ӧ��ID',
									name : 'suppId',
									hide : true,
									width:100
								}
								],
						// ��������
						searchitems : [{
									display : '��Ӧ������',
									name : 'suppName'
								},
								{
									display : '��������',
									name : 'depositbank'
								},
								{
									display : '�����ʺ�',
									name : 'accountNum'
								}
								],
						// Ĭ�������ֶ���
						sortname : "depositbank",
						// Ĭ������˳��
						sortorder : "ASC"
					}
				}
			});
})(jQuery);