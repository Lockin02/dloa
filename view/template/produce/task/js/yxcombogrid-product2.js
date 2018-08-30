/**
 * �����������ϻ�����Ϣ����������
 */
(function($) {
	$.woo.yxcombogrid.subclass('woo.yxcombogrid_product2', {
		options : {
			hiddenId : 'productId',
			nameCol : 'productCode',
			gridOptions : {
				showcheckbox : false,
				model : 'produce_apply_produceapplyitem',
				action : 'productJson',
				param : {
					canOrder : true,
					state : 0
				},

				// ����Ϣ
				colModel : [{
					display : '���ϱ���',
					name : 'productCode',
					width : 80
				},{
					display : '��������',
					name : 'productName',
					width : 180
				},{
					display : '��������',
					name : 'produceNum',
					width : 60
				},{
					display : '���´�����',
					name : 'exeNum',
					width : 60
				},{
					display : '����ͺ�',
					name : 'pattern',
					width : 80
				},{
					display : '��λ',
					name : 'unitName'
				}],

				// ��������
				searchitems : [{
					display : '���ϱ���',
					name : 'productCode'
				},{
					display : '��������',
					name : 'productName'
				},{
					display : '����ͺ�',
					name : 'pattern'
				}],

				// Ĭ�������ֶ���
				sortname : "id",
				// Ĭ������˳��
				sortorder : "DESC"
			}
		}
	});
})(jQuery);