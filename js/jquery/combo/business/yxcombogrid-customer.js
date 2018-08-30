/**
 * 下拉客户表格组件
 */
(function ($) {
	$.woo.yxcombogrid.subclass('woo.yxcombogrid_customer', {
		_create: function () {
			if (this.options.isShowButton) {
				var t = this, p = t.options, el = t.el;
				$button = $("<span class='add-trigger'  title='添加客户'>&nbsp;</span>");
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
					// 更改宽度
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
				title: '客户信息',
				model: 'customer_customer_customer',
				param: {
					isUsing: 1
				},
				openPageOptions: {
					// url: '?model=engineering_project_esmproject&action=pageSelect',
					// width: '750'
				},
				// 列信息
				colModel: [{
					display: '客户名称',
					name: 'Name'
				}, {
					display: '销售工程师',
					name: 'SellMan',
					sortable: true
				}, {
					display: '销售工程师Id',
					name: 'SellManId',
					hide: true
				}, {
					display: '客户类型',
					name: 'TypeOneName',
					// datacode : 'KHLX',// 数据字典编码
					sortable: true
				}, {
					display: '地址',
					name: 'Address',
					hide: true,
					sortable: true,
					width: 90
				}, {
					display: '省份',
					name: 'Prov',
					sortable: true,
					width: 90
				}],
				// 快速搜索
				searchitems: [{
					display: '客户类型',
					name: 'customerType'
				}, {
					display: '客户名称',
					name: 'Name',
					isdefault: true
				}],
				// 默认搜索字段名
				sortname: "Name",
				// 默认搜索顺序
				sortorder: "ASC"
			}
		}
	});
})(jQuery);