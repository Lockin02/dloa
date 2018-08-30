/**
 * ���ϻ�����Ϣ����������
 */
(function($) {
	$.woo.yxcombogrid.subclass('woo.yxcombogrid_assetrequireitem', {
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
			nameCol : 'description',
			gridOptions : {
				showcheckbox : false,
				model : 'asset_require_requireitem',
				action : 'pageByRequireJson',
//				param : {
//	 				"mainId" : "WLSTATUSKF"
//				},
				pageSize : 10,
				// ����Ϣ
				colModel : [{
							display : 'id',
							name : 'id',
							hide : true,
							width : 130
						}, {
							display : '��Ʒ����',
							name : 'description',
							width : 180
						}, {
							display : '���',
							name : 'expectAmount',
							width : 50
						}, {
							display : '����',
							name : 'number',
							width : 50
						}, {
							display : '��ִ������',
							name : 'executedNum'
						}, {
							display : 'Ԥ�ƽ�������',
							name : 'dateHope',
							width : 70
						}, {
							display : '����',
							name : 'deploy',
							width : 180
						}, {
							display : '��ע',
							name : 'remark',
							width : 180
						}],
				// ��������
				searchitems : [],
				// Ĭ�������ֶ���
				sortname : "id",
				// Ĭ������˳��
				sortorder : "ASC"
			}
		}
	});
})(jQuery);