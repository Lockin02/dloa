(function($) {
	$.woo.yxgrid.subclass('woo.yxgrid_selectorder', {
		options : {
			model : 'projectmanagent_order_order',
            action : 'presentInfoJson',
            title : '��ͬ�б�',
			/**
			 * �Ƿ���ʾ�鿴��ť/�˵�
			 */
			isViewAction : false,
			/**
			 * �Ƿ���ʾ�޸İ�ť/�˵�
			 */
			isEditAction : false,
			// ����Ϣ
			colModel :
			[
				{
					display : 'id',
					name : 'id',
					sortable : true,
					hide : true
				}, {
					name : 'tablename',
					display : '��ͬ����',
					sortable : true,
					width : 80,
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
//					hide : true,
//					process : function(v,row) {
//						if(v == ""){
//							return "<span style='color:blue'>" + row.orderTempCode + "</span>";
//						}else{
//							return v;
//						}
//					}
				}, {
					name : 'orderTempCode',
					display : '��ʱ��ͬ��',
					sortable : true,
					width : 180,
					hide : false
				}, {
					name : 'customerName',
					display : '�ͻ�����',
					sortable : true,
					width : 180
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
					width : 150,
					hide : true
				}, {
					name : 'sign',
					display : '�Ƿ�ǩԼ',
					sortable : true,
					width : 70,
					hide : true
				}, {
					name : 'prinvipalName',
					display : '��ͬ������',
					sortable : true,
					width : 80
				}, {
					name : 'areaName',
					display : '��������',
					sortable : true,
					width : 80
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
					width : 80
				}, {
					name : 'ExaStatus',
					display : '����״̬',
					sortable : true,
					width : 80
				}
			],
			// ��������
			searchitems : [
				{
					display : '��ͬ���',
					name : 'orderCodeOrTempSearch'
				}, {
					display : '��ͬ����',
					name : 'orderName'
				}, {
					display : '�ͻ�����',
					name : 'customerName'
				}
			],

			// Ĭ������˳��
			sortorder : "DESC"
		}
	});
})(jQuery);