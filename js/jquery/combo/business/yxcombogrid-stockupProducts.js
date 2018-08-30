/**
 * �����ͻ�������
 */
(function($) {
	$.woo.yxcombogrid.subclass('woo.yxcombogrid_stockupProducts', {
		isDown : false,
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
			nameCol : 'typeName',
			openPageOptions : {
				url : '?model=stockup_basic_products&action=pageSelect',
				width : '750'
			},
			gridOptions : {
				showcheckbox : false,
				model : 'stockup_basic_products',
				action : 'jsonSelect',
				//����Ϣ
						colModel : [{
         								name : 'id',
										display : '��ƷID',
										hide : true
							        },{
											name : 'productName',
											display : '��Ʒ����',
											width:100,
											sortable : true
										},{
										name : 'productCode',
										display : '��Ʒ���',
										width:100,
										sortable : true
		                              },{
											name : 'remark',
											display : '��ע',
											width:200,
											sortable : true
									  }],
						// ��������
						searchitems : [{
								display : '��Ʒ����',
								name : 'productName'
							}],
						// Ĭ�������ֶ���
						sortname : "id",
						// Ĭ������˳��
						sortorder : "DESC"
					}
				}
			});
})(jQuery);