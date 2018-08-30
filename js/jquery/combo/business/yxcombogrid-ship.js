/**
 * ���������ƻ����
 */
(function($) {
	$.woo.yxcombogrid.subclass('woo.yxcombogrid_ship', {
		options : {
			hiddenId : 'id',
			nameCol : 'shipCode',
			gridOptions : {
				showcheckbox : false,
				model : 'stock_outplan_ship',
				// param : {
				// docType : 'independent'
				// },
				pageSize : 10,
				// ����Ϣ
				colModel : [{
						display : 'id',
						name : 'id',
						hide : true
					}, {
						display : '���������',
						width : 120,
						name : 'shipCode'
					}, {
						display : "Դ������",
						width : 120,
						name : 'docType',
						process : function(v) {
							switch(v){
								case 'oa_sale_order' : return '���۷���';break;
								case 'oa_sale_lease' : return '���޷���"';break;
								case 'oa_sale_service' : return '���񷢻�';break;
								case 'oa_sale_rdproject' : return '�з�����';break;
								case 'oa_borrow_borrow' : return '���÷���';break;
								case 'oa_service_accessorder' : return '���������';break;
								case 'oa_service_repair_apply' : return 'ά�����뵥';break;
								default : return '��������';
							}
						}
					}, {
						display : 'Դ��Id',
						name : 'docId',
						hide : true
					}, {
						display : 'Դ�����',
						width : 180,
						name : 'docCode'
					}, {
						display : 'Դ������',
						name : 'docName',
						width : 120,
						hide : true
					}, {
						display : '������ʽ',
						name : 'shipType',
						width : 80,
						process : function(v) {
							if (v == 'order') {
								return "����";
							} else if (v == 'borrow') {
								return "����";
							} else if (v == 'lease') {
								return "����";
							} else if (v == 'trial') {
								return "����";
							} else if (v == 'change') {
								return "����";
							}
						}
					}, {
						display : '�ͻ�����',
						name : 'customerName',
						width : 120
					}, {
						display : '�ͻ�id',
						name : 'customerId',
						hide : true
					}],
				// ��������
				searchitems : [{
					display : '���������',
					name : 'shipCode'
				}],
				// Ĭ������˳��
				sortorder : "DESC"
			}
		}
	});
})(jQuery);