/**
 * 物料基本信息下拉表格组件
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
			closeCheck: false,// 关闭状态,不可选择
			closeAndStockCheck: false,//关闭且校验库存
			height: 250,
			gridOptions: {
				isTitle: true,
				title: '物料信息',
				showcheckbox: false,
				model: 'stock_productinfo_productinfo',
				action: 'pageJson',
				pageSize: 10,
				// 列信息
				colModel: [{
					display: 'id',
					name: 'id',
					sortable: true,
					hide: true
				}, {
					display: '状态',
					name: 'ext1',
					process: function (v) {
						if (v == "WLSTATUSKF") {
							return '<img src="images/icon/cicle_green.png" title="开放"/>';
						} else {
							return '<img src="images/icon/cicle_grey.png" title="关闭"/>';
						}
					},
					align: 'center',
					width: 20
				}, {
					display: '物料编码',
					name: 'productCode',
					sortable: true,
					process: function (v, row) {
						return "<a title='" + row.remark + "' href='#' onclick='viewProDetail(" + row.id + ")' >" + v + "</a>";
					},
					width: 80
				}, {
					display: 'k3编码',
					name: 'ext2',
					sortable: true,
					width: 70
				}, {
					display: '物料名称',
					name: 'productName',
					sortable: true,
					width: 180,
					process: function (v, row) {
						return "<div title='" + row.remark + "'>" + v + "</div>"
					}
				}, {
					display: '所属分类',
					name: 'proType',
					width: 80,
					sortable: true
				}, {
					name: 'pattern',
					display: '规格型号',
					sortable: true,
					width: 80
				}, {
					name: 'priCost',
					display: '单价',
					sortable: true,
					hide: true
				}, {
					name: 'unitName',
					display: '单位',
					hide: true,
					sortable: true,
					width: 50
				}, {
					name: 'aidUnit',
					display: '辅助单位',
					sortable: true,
					hide: true
				}, {
					name: 'warranty',
					display: '保修期(月)',
					hide: true,
					sortable: true
				}, {
					name: 'arrivalPeriod',
					display: '到货周期(月)',
					hide: true,
					sortable: true
				}, {
					name: 'accountingCode',
					display: '会计科目代码',
					sortable: true,
					datacode: 'KJKM',
					hide: true
				}, {
					name: 'remark',
					display: '备注',
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
					display: '归属公司',
					sortable: true,
					width: 60
				}],
				// 快速搜索
				searchitems: [{
					display: '物料编码',
					name: 'productCode'
				}, {
					display: '物料名称',
					name: 'productName'
				}, {
					display: '归属类型',
					name: 'ext3Search'
				}, {
					display: '品牌',
					name: 'brand'
				}, {
					display: '规格型号',
					name: 'pattern'
				}, {
					name: 'ext2Search',
					display: 'K3编码'
				}],
				// 默认搜索字段名
				sortname: "ext1 desc,id",
				// 默认搜索顺序
				sortorder: "asc"
			}
		}
	});
})(jQuery);