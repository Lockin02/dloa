var show_page = function(page) {
	$("#reduceitemGrid").yxgrid("reload");
};
$(function() {
	$("#reduceitemGrid").yxgrid({
		model : 'service_reduce_reduceitem',
		title : '维修费用减免清单',
		// 列信息
		colModel : [{
					display : 'id',
					name : 'id',
					sortable : true,
					hide : true
				}, {
					name : 'productType',
					display : '物料分类',
					hide : true,
					sortable : true
				}, {
					name : 'productCode',
					display : '物料编号',
					sortable : true
				}, {
					name : 'productName',
					display : '物料名称',
					sortable : true,
					width : 250
				}, {
					name : 'pattern',
					display : '规格型号',
					sortable : true
				}, {
					name : 'unitName',
					display : '单位',
					sortable : true
				}, {
					name : 'serilnoName',
					display : '序列号',
					sortable : true
				}, {
					name : 'fittings',
					display : '配件信息',
					sortable : true
				}, {
					name : 'cost',
					display : '收取费用',
					sortable : true
				}, {
					name : 'reduceCost',
					display : '减免费用',
					sortable : true
				}, {
					name : 'remark',
					display : '备注',
					sortable : true
				}],

		toEditConfig : {
			toEditFn : function(p) {
				action : showThickboxWin("?model=service_reduce_reduceitem&action=toEdit&id="
						+ row.id + "&skey=" + row['skey_'])
			}
		},
		toViewConfig : {
			toViewFn : function(p) {
				action : showThickboxWin("?model=service_reduce_reduceitem&action=toView&id="
						+ row.id + "&skey=" + row['skey_'])
			}
		}
	});
});