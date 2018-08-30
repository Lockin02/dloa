/**
 * 下拉检验申请单产品表格组件
 */
(function($) {
	$.woo.yxcombogrid.subclass('woo.yxcombogrid_produceapplyitem', {
			options : {
				hiddenId : 'materialId',
				nameCol : 'materialName',
				gridOptions : {
				showcheckbox : false,
				model : 'quality_apply_produceapplyitem',
				pageSize : 10,
				// 列信息
				colModel : [{
							name : 'id',
							header : 'id',
							hide : true
						},{
							display : '产品id',
							name : 'materialId',
							width : 180,
							hide : true
						},{
							display : '产品名称',
							name : 'materialName',
							width : 140
						}, {
							display : '产品编号',
							name : 'materialCode',
							width : 100
						}, {
							display : '规格型号',
							name : 'pattern'
						}, {
							display : '批次',
							name : 'batchNum',
							hide : true
						}, {
							display : '报检数量',
							name : 'storageNum'
						}, {
							display : '累计检验数量',
							name : 'subCheckNum'
						}],
				// 快速搜索
				searchitems : [{
							display : '产品名称',
							name : 'materialName'
						}],
				// 默认搜索字段名
				sortname : "id",
				// 默认搜索顺序
				sortorder : "ASC"
			}
		}
	});
})(jQuery);