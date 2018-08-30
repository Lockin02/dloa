/**
 * ���ϻ�����Ϣ����������
 */
(function ($) {
	$.woo.yxcombogrid.subclass('woo.yxcombogrid_product', {
		isDown: true,
		setValue: function (rowData) {
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
		options: {
			hiddenId: 'id',
			nameCol: 'productCode',
			openPageOptions: {
				// url: '?model=stock_productinfo_productinfo&action=selectProduct',
				// width: '820'
			},
			closeCheck: false,// �ر�״̬,����ѡ��
			closeAndStockCheck: false,//�ر���У����
			height: 250,
			gridOptions: {
				isTitle: true,
				title: '������Ϣ',
				showcheckbox: false,
				model: 'stock_productinfo_productinfo',
				action: 'pageJson',
				pageSize: 10,
				// ����Ϣ
				colModel: [{
					display: 'id',
					name: 'id',
					sortable: true,
					hide: true
				}, {
					display: '״̬',
					name: 'ext1',
					process: function (v) {
						if (v == "WLSTATUSKF") {
							return '<img src="images/icon/cicle_green.png" title="����"/>';
						} else {
							return '<img src="images/icon/cicle_grey.png" title="�ر�"/>';
						}
					},
					align: 'center',
					width: 20
				}, {
					display: '���ϱ���',
					name: 'productCode',
					sortable: true,
					process: function (v, row) {
						return "<a title='" + row.remark + "' href='#' onclick='viewProDetail(" + row.id + ")' >" + v + "</a>";
					},
					width: 80
				}, {
					display: 'k3����',
					name: 'ext2',
					sortable: true,
					width: 70
				}, {
					display: '��������',
					name: 'productName',
					sortable: true,
					width: 180,
					process: function (v, row) {
						return "<div title='" + row.remark + "'>" + v + "</div>"
					}
				}, {
					display: '��������',
					name: 'proType',
					width: 80,
					sortable: true
				}, {
					name: 'pattern',
					display: '����ͺ�',
					sortable: true,
					width: 80
				}, {
					name: 'priCost',
					display: '����',
					sortable: true,
					hide: true
				}, {
					name: 'unitName',
					display: '��λ',
					hide: true,
					sortable: true,
					width: 50
				}, {
					name: 'aidUnit',
					display: '������λ',
					sortable: true,
					hide: true
				}, {
					name: 'warranty',
					display: '������(��)',
					hide: true,
					sortable: true
				}, {
					name: 'arrivalPeriod',
					display: '��������(��)',
					hide: true,
					sortable: true
				}, {
					name: 'accountingCode',
					display: '��ƿ�Ŀ����',
					sortable: true,
					datacode: 'KJKM',
					hide: true
				}, {
					name: 'remark',
					display: '��ע',
					process: function (v) {
						if (v.length > 10) {
							return "<div title='" + v + "'>"
								+ v.substr(0, 40)
								+ "....</div>";
						}
						return v
					}
				}, {
					name: 'businessBelongName',
					display: '������˾',
					sortable: true,
					width: 60
				}],
				// ��������
				searchitems: [{
					display: '���ϱ���',
					name: 'productCode'
				}, {
					display: '��������',
					name: 'productName'
				}, {
					display: '��������',
					name: 'ext3Search'
				}, {
					display: 'Ʒ��',
					name: 'brand'
				}, {
					display: '����ͺ�',
					name: 'pattern'
				}, {
					name: 'ext2Search',
					display: 'K3����'
				}],
				// Ĭ�������ֶ���
				sortname: "ext1 desc,id",
				// Ĭ������˳��
				sortorder: "asc"
			}
		}
	});
})(jQuery);