var show_page = function(page) {
	$("#provincelist").yxgrid("reload");
	$("#tree").yxtree("reload");
}

$(function() {

	$("#tree").yxtree({
				url : '?model=system_procity_country&action=getChildren&parentId=',
				event : {
					"node_click" : function(event, treeId, treeNode) {
						var provincelist = $("#provincelist").data('yxgrid');
						provincelist.options.param['countryId'] = treeNode.id;
						provincelist.reload();
					}
				}
			});
	$("#provincelist").yxgrid({
				model : 'system_procity_province',
				isViewAction:false,
				formHeight : "300px",
				colModel : [{
							display : 'id',
							name : 'id',
							sortable : true,
							hide : true
						}, {
							display : '国家',
							name : 'countryName',
							sortable : true,
							width : 200
						}, {
							display : '省份名称	',
							name : 'provinceName',
							sortable : true,
							width : 200
						}, {
							display : '省份编号	',
							name : 'provinceCode',
							sortable : true,
							width : 200
						}, {
							display : '序号',
							name : 'sequence',
							width : 200,
							sortable : true
						}],

				/**
				 * 快速搜索
				 */
				searchitems : [{
							display : '省份名称',
							name : 'provinceName'
						}],
				sortorder : "ASC",
				title : '省份名称'
			});
});