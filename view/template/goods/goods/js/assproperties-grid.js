var show_page = function(page) {
	$("#asspropertiesGrid").yxgrid("reload");
};
$(function() {
			$("#asspropertiesGrid").yxgrid({
						model : 'goods_goods_assproperties',
						title : '属性不可见性关系',
						// 列信息
						colModel : [{
									display : 'id',
									name : 'id',
									sortable : true,
									hide : true
								}, {
									name : 'proTypeName',
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