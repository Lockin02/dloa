var show_page = function(page) {
	$("#assitemGrid").yxgrid("reload");
};
$(function() {
			$("#assitemGrid").yxgrid({
						model : 'goods_goods_assitem',
						title : '数据项级联关系',
						// 列信息
						colModel : [{
									display : 'id',
									name : 'id',
									sortable : true,
									hide : true
								}, {
									name : 'itemName',
									display : '关联项名称',
									sortable : true
								}, {
									name : 'propertiesName',
									display : '属性名称',
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