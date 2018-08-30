/**
 * 物料基本信息下拉表格组件
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
				// 列信息
				colModel : [{
							display : 'id',
							name : 'id',
							hide : true,
							width : 130
						}, {
							display : '产品描述',
							name : 'description',
							width : 180
						}, {
							display : '金额',
							name : 'expectAmount',
							width : 50
						}, {
							display : '数量',
							name : 'number',
							width : 50
						}, {
							display : '已执行数量',
							name : 'executedNum'
						}, {
							display : '预计交货日期',
							name : 'dateHope',
							width : 70
						}, {
							display : '配置',
							name : 'deploy',
							width : 180
						}, {
							display : '备注',
							name : 'remark',
							width : 180
						}],
				// 快速搜索
				searchitems : [],
				// 默认搜索字段名
				sortname : "id",
				// 默认搜索顺序
				sortorder : "ASC"
			}
		}
	});
})(jQuery);