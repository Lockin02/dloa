/**
 * 物料基本信息下拉表格组件
 */
(function($) {
	$.woo.yxcombogrid.subclass('woo.yxcombogrid_product', {
		isDown:false,
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
			nameCol : 'productCode',
			openPageOptions : {
				url : '?model=stock_productinfo_productinfo&action=selectProduct',
				width : '750'
			},

			gridOptions : {
				showcheckbox : false,
				model : 'stock_productinfo_productinfo',
				action : 'pageJson',
                param : {"ext1" : "WLSTATUSKF"},
				pageSize : 10,

				// 列信息
				colModel : [{
							display : '物料编码',
							name : 'productCode',
							width : 130
						}, {
							display : '物料名称',
							name : 'productName',
							width : 180
						}, {
							display : '所属分类',
							name : 'proType'
						}, {
							display : '规格型号',
							name : 'pattern'
						}, {
							name : 'warranty',
							display : '保修期'
						}, {
							display : '单位',
							name : 'unitName',
							hide : true
						}, {
							display : '辅助单位',
							name : 'aidUnit',
							hide : true
						}, {
							display : '换算率',
							name : 'converRate',
							hide : true
						}],
			    // 主从表格设置
				subGridOptions : {
					url : '?model=stock_productinfo_configuration&action=conPageJson',// 获取从表数据url
					// 传递到后台的参数设置数组
					param : [{
								paramId : 'productId',// 传递给后台的参数名称
								colId : 'id'// 获取主表行数据的列名称

							}],
					// 显示的列
					colModel : [{
								name : 'configName',
								width : 80,
								display : '配置名称'
							}, {
								name : 'configPattern',
								display : '配置型号',
								width : 80
							},{
								name : 'configNum',
								display : '配置数量',
								width : 80
							},{
								name : 'explains',
								display : '说明',
								width : 80
							}]
				},
				// 快速搜索
				searchitems : [{
							display : '物料编码',
							name : 'productCode'
						}, {
							display : '物料名称',
							name : 'productName'
						}],
				// 默认搜索字段名
				sortname : "id",
				// 默认搜索顺序
				sortorder : "ASC"
			}
		}
	});
})(jQuery);