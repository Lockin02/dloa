var show_page = function(page) {
	$("#portletGrid").yxgrid_portlet("reload");
};
$(function() {
	$("#tree").yxtree({
				url : '?model=system_portal_portlettype&action=getChildren',
				event : {
					"node_click" : function(event, treeId, treeNode) {
						var portlet = $("#portletGrid").data('yxgrid_portlet');
						portlet.options.extParam['typeId'] = treeNode.id;

						portlet.reload();
						$("#parentId").val(treeNode.id);
						$("#parentName").val(treeNode.typeName);

					}
				}
			});
	$("#portletGrid").yxgrid_portlet({
		isAddAction : false,
		buttonsEx : [ {
					name : 'add',
					text : "ÐÂÔö",
					icon : 'add',
					action : function(row) {
						showThickboxWin("?model=system_portal_portlet&action=toAdd"
								+ "&typeId="
								+ $('#parentId').val()
								+ "&typeName="
								+ $('#parentName').val()
								+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=700');
					}
				}]
	});
});