/**
 * ���ϻ�����Ϣ����������
 */
(function($) {
	$.woo.yxcombogrid.subclass('woo.yxcombogrid_contequ', {
		setValue : function(rowData) {
			if (rowData) {
				var t = this, p = t.options, el = t.el;
				p.rowData = rowData;
				if (p.gridOptions.showcheckbox) {
					if (p.hiddenId) {
						p.idStr = rowData.idArr;
						$("#" + p.hiddenId).val(p.idStr);
						p.nameStr = rowData.text;
						$(el).val(p.nameStr);
						$(el).attr('title', p.nameStr);
					}
				} else if (!p.gridOptions.showcheckbox) {
					if (p.hiddenId) {
						p.idStr = rowData[p.valueCol];
						$("#" + p.hiddenId).val(p.idStr);
						p.nameStr = rowData[p.nameCol];
						$(el).val(p.nameStr);
						$(el).attr('title', p.nameStr);
					}
				}
			}
		},
		options : {
			hiddenId : 'productId',
			nameCol : 'productNo',
			gridOptions : {
				showcheckbox : false,
				model : 'stock_outplan_outplan',
				action : 'shipEquJson',
				param : {
	// "ext1" : "WLSTATUSKF"
				},
				pageSize : 10,
				// ����Ϣ
				colModel : [{
							display : '��Ʒ��',
							name : 'productLine',
							hide : true,
							width : 130
						}, {
							display : 'ִ�в���',
							name : 'productLineName',
							hide : true,
							width : 130
						}, {
							display : '����Id',
							name : 'productId',
							hide : true,
							width : 130
						}, {
							display : '���ϱ���',
							name : 'productNo',
							width : 130
						}, {
							display : '��������',
							name : 'productName',
							width : 180
						}, {
							display : '�Ƿ�����',
							name : 'isSell',
							process : function (v){
								if( v=='on' ){
									return '��';
								}else{
									return '��';
								}
							}
						}, {
							display : '��λ',
							name : 'unitName'
						}, {
							display : 'Դ���豸Id',
							name : 'id',
							hide : true
						}, {
							display : 'Դ��Id',
							name : 'orderOrgid',
							hide : true
						}, {
							display : '����+Դ��Id',
							name : 'orderId',
							hide : true
						}, {
							display : 'Դ������',
							name : 'orderCode',
							hide : true
						}, {
							display : 'Դ������',
							name : 'orderName',
							hide : true
						}, {
							display : '����',
							name : 'number',
							hide : true
						}, {
							display : '���´﷢������',
							name : 'issuedShipNum',
							hide : true
						}, {
							display : '���´�ɹ�����',
							name : 'issuedPurNum',
							hide : true
						}, {
							display : '���´���������',
							name : 'issuedProNum',
							hide : true
						}, {
							display : '��ִ������',
							name : 'executedNum',
							hide : true
						}],
				// ��������
				searchitems : [{
							display : '���ϱ���',
							name : 'productNo'
						}, {
							display : '��������',
							name : 'productName'
						}],
				// Ĭ�������ֶ���
				sortname : "id",
				// Ĭ������˳��
				sortorder : "ASC"
			}
		}
	});
})(jQuery);