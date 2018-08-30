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
							display : '����',
							name : 'countryName',
							sortable : true,
							width : 200
						}, {
							display : 'ʡ������	',
							name : 'provinceName',
							sortable : true,
							width : 200
						}, {
							display : 'ʡ�ݱ��	',
							name : 'provinceCode',
							sortable : true,
							width : 200
						}, {
							display : '���',
							name : 'sequence',
							width : 200,
							sortable : true
						}],

				/**
				 * ��������
				 */
				searchitems : [{
							display : 'ʡ������',
							name : 'provinceName'
						}],
				sortorder : "ASC",
				title : 'ʡ������'
			});
});