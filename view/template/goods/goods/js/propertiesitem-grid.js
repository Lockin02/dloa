var show_page = function(page) {
	$("#propertiesitemGrid").yxgrid("reload");
};
$(function() {
			$("#propertiesitemGrid").yxgrid({
						model : 'goods_goods_propertiesitem',
						title : '配置项内容',
						// 列信息
						colModel : [{
									display : 'id',
									name : 'id',
									sortable : true,
									hide : true
								}, {
									name : 'itemContent',
									display : '值内容',
									sortable : true
								}, {
									name : 'isNeed',
									display : '是否必选',
									sortable : true
								}, {
									name : 'isDefault',
									display : '是否默认',
									sortable : true
								}, {
									name : 'productCode',
									display : '对应物料编号',
									sortable : true
								}, {
									name : 'productName',
									display : '对应物料名称',
									sortable : true
								}, {
									name : 'pattern',
									display : '对应物料型号',
									sortable : true
								}, {
									name : 'proNum',
									display : '对应物料数量',
									sortable : true
								}, {
									name : 'status',
									display : '状态',
									sortable : true
								}, {
									name : 'remakr',
									display : '具体描述',
									sortable : true
								}],

						toEditConfig : {
							action : 'toEdit'
						},
						toViewConfig : {
							action : 'toView'
						},
						searchitems : [{
									display : "搜索字段",
									name : 'XXX'
								}]
					});
		});