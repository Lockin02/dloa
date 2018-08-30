/**
 * ������ͬ �б�
 */
(function($) {
	$.woo.yxcombogrid.subclass('woo.yxcombogrid_allorder', {
		options : {
			isDown : true,
			hiddenId : 'orgid',
//			nameCol : 'orderTempCode',
			focusoutCheckAction:'getCountByNameForView',
            openPageOptions : {
				url : '?model=projectmanagent_order_order&action=selectOrder'
			},
			gridOptions : {
				model : 'projectmanagent_order_order',
				action : 'presentInfoJson',

				// ����Ϣ
				colModel : [{
							display : 'id',
							name : 'id',
							sortable : true,
							hide : true
						}, {
							name : 'tablename',
							display : '��ͬ����',
							sortable : true,
							width : 60,
							process : function(v) {
								if (v == 'oa_sale_order') {
									return "���ۺ�ͬ";
								} else if (v == 'oa_sale_service') {
									return "�����ͬ";
								} else if (v == 'oa_sale_lease') {
									return "���޺�ͬ";
								} else if (v == 'oa_sale_rdproject') {
									return "�з���ͬ";
								}
							}
						}, {
							name : 'orderCode',
							display : '������ͬ��',
							sortable : true,
							width : 180
						}, {
							name : 'orderTempCode',
							display : '��ʱ��ͬ��',
							sortable : true,
							width : 180
						}, {
							name : 'customerName',
							display : '�ͻ�����',
							sortable : true,
							width : 100
						}, {
							name : 'customerType',
							display : '�ͻ�����',
							sortable : true,
							datacode : 'KHLX',
							width : 70,
							hide : true
						}, {
							name : 'orderName',
							display : '��ͬ����',
							sortable : true,
							width : 150
						}, {
							name : 'ExaStatus',
							display : '����״̬',
							sortable : true,
							width : 60
						}, {
							name : 'sign',
							display : '�Ƿ�ǩԼ',
							sortable : true,
							width : 70
						}, {
							name : 'areaName',
							display : '��������',
							sortable : true,
							width : 60,
							hide : true
						}, {
							name : 'prinvipalName',
							display : '��ͬ������',
							sortable : true,
							width : 80,
							hide : true
						}, {
							name : 'state',
							display : '��ͬ״̬',
							sortable : true,
							process : function(v) {
								if (v == '0') {
									return "δ�ύ";
								} else if (v == '1') {
									return "������";
								} else if (v == '2') {
									return "ִ����";
								} else if (v == '3') {
									return "�ѹر�";
								} else if (v == '4') {
									return "�����";
								} else if (v == '5') {
									return "�Ѻϲ�";
								} else if (v == '5') {
									return "�Ѳ��";
								}
							},
							width : 60
						}],
				// ��������
				searchitems : [{
							display : '��ͬ���',
							name : 'orderCodeOrTempSearch'
						}, {
							display : '��ͬ����',
							name : 'orderName',
							isdefault : true
						}],

				// Ĭ������˳��
				sortorder : "DESC"
			}
		}
	});
})(jQuery);