/**
 * �����ɹ���ͬ������
 */
(function($) {
	$.woo.yxcombogrid.subclass('woo.yxcombogrid_purchcontract', {
				options : {
					hiddenId : 'id',
					nameCol : 'hwapplyNumb',
					gridOptions : {
						model : 'purchase_contract_purchasecontract',
						// ����Ϣ
						colModel : [{
									display : 'id',
									name : 'id',
									hide : true,
									width:130
								},{
									display : '�������',
									name : 'hwapplyNumb',
									width:100
								},{
									display : '�ɹ�Ա',
									name : 'sendName',
									width:150
								},{
									display : '��Ӧ������',
									name : 'suppName',
									width:150
								},{
									display : '��Ӧ��Id',
									name : 'suppId',
									width:150,
									hide : true
								}],
						// ��������
						searchitems : [{
									display : '�������',
									name : 'hwapplyNumb'
								}],
						// Ĭ�������ֶ���
						sortname : "hwapplyNumb",
						// Ĭ������˳��
						sortorder : "DESC"
					}
				}
			});
})(jQuery);