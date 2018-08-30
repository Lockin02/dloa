/**
 * ��ͬ������Ϣ����������
 */
(function($) {
	$.woo.yxcombogrid.subclass('woo.yxcombogrid_contractEqu', {
		isDown : true,
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
			hiddenId : 'id',
			openPageOptions : {
				url : '?model=contract_contract_equ&action=selectEqu',
				width : '750'
			},
			gridOptions : {
				isTitle: true,
				showcheckbox : false,
				model : 'contract_contract_equ',
				action : 'pageJson',
				param : {
					'isDel' : '0',
					'maxNum' : 0
				},
				pageSize : 10,
				// ����Ϣ
				colModel : [{
							display : 'id',
							name : 'id',
							hide : true
						},{
							display : '����id',
							name : 'productId',
							hide : true
						},{
							display : '���ϱ���',
							name : 'productCode',
							width : 100
						}, {
							display : '��������',
							name : 'productName',
							width : 200
						}, {
							display : '��ͬid',
							name : 'contractId',
							hide : true
						}, {
							display : '�ͺ�/�汾',
							name : 'productModel',
							width : 300
						}, {
							display : '��ִ������',
							name : 'executedNum',
							hide : true
						}, {
							display : '���˻�����',
							name : 'backNum',
							hide : true
						}, {
							display : '��ȷ�ϻ�������',
							name : 'applyExchangeNum',
							hide : true
						}],
				// ��������
				searchitems : [{
							display : '���ϱ���',
							name : 'productCodeSearch'
						}, {
							display : '��������',
							name : 'productNameSearch'
						}]
			}
		}
	});
})(jQuery);