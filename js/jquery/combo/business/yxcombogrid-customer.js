/**
 * �����ͻ�������
 */
(function ($) {
	$.woo.yxcombogrid.subclass('woo.yxcombogrid_customer', {
		_create: function () {
			if (this.options.isShowButton) {
				var t = this, p = t.options, el = t.el;
				$button = $("<span class='add-trigger'  title='��ӿͻ�'>&nbsp;</span>");
				$button.click(function () {
					el.click();
					var returnValue = showModalDialog(
						"?model=customer_customer_customer&action=toAdd&showDialog=1",
						'', "dialogWidth:900px;dialogHeight:500px;");
					if (returnValue) {
						var objRv = $.json2obj(returnValue);
						t.setValue(objRv);
						var $row = $(t.grid.addOneRow(1, objRv));
						t.grid.el.trigger('row_dblclick', [$row,
							t.grid.transRow(objRv)]);
						t.kill();
					}
				});
				// $(el).after("&nbsp;");
				$(el).next().after($button);
				if ($(el).attr("wchangeTag2") != 'true') {
					// ���Ŀ��
					var w = $(el).width();
					if (w > $button.width()) {
						$(el).width(w - $button.width());
						$(el).attr("wchangeTag2", true);
					}
				}
			}
		},
		options: {
			hiddenId: 'customerId',
			nameCol: 'Name',
			gridOptions: {
				isTitle: true,
				title: '�ͻ���Ϣ',
				model: 'customer_customer_customer',
				param: {
					isUsing: 1
				},
				openPageOptions: {
					// url: '?model=engineering_project_esmproject&action=pageSelect',
					// width: '750'
				},
				// ����Ϣ
				colModel: [{
					display: '�ͻ�����',
					name: 'Name'
				}, {
					display: '���۹���ʦ',
					name: 'SellMan',
					sortable: true
				}, {
					display: '���۹���ʦId',
					name: 'SellManId',
					hide: true
				}, {
					display: '�ͻ�����',
					name: 'TypeOneName',
					// datacode : 'KHLX',// �����ֵ����
					sortable: true
				}, {
					display: '��ַ',
					name: 'Address',
					hide: true,
					sortable: true,
					width: 90
				}, {
					display: 'ʡ��',
					name: 'Prov',
					sortable: true,
					width: 90
				}],
				// ��������
				searchitems: [{
					display: '�ͻ�����',
					name: 'customerType'
				}, {
					display: '�ͻ�����',
					name: 'Name',
					isdefault: true
				}],
				// Ĭ�������ֶ���
				sortname: "Name",
				// Ĭ������˳��
				sortorder: "ASC"
			}
		}
	});
})(jQuery);