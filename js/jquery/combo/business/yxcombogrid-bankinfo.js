/**
 * ������������
 */
(function($) {
	$.woo.yxcombogrid.subclass('woo.yxcombogrid_bankinfo', {
				options : {
					//hiddenId : 'suppId',
					nameCol : 'depositbank_name',
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
									display : '��������',
									name : 'depositbank',
									datacode : 'KHBANK',
									width:150
								}
								,{
									display : '��Ӧ��ID',
									name : 'suppId',
									hide : true,
									width:100
								}
								,{
									display : '��ע',
									name : 'remark'
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
						sortname : "suppId",
						// Ĭ������˳��
						sortorder : "ASC"
					}
				}
			});
})(jQuery);