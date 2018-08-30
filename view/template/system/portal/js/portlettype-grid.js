var show_page = function(page) {
	$("#portletTypeGrid").yxgrid_portlettype("reload");
	$("#tree").yxtree("reload");
};
$(function() {
	$("#tree").yxtree({
		url : '?model=system_portal_portlettype&action=getChildren',
		event : {
			"node_click" : function(event, treeId, treeNode) {
				var portletType = $("#portletTypeGrid")
						.data('yxgrid_portlettype');
				// alert(portletType);
				portletType.options.param['parentId'] = treeNode.id;

				portletType.reload();
				$("#parentId").val(treeNode.id);
				$("#parentName").val(treeNode.typeName);

			}
		}
	});
	$("#portletTypeGrid").yxgrid_portlettype({
		isAddAction : false,
		buttonsEx : [{
			name : 'add',
			text : "ÐÂÔö",
			icon : 'add',
			action : function(row) {
				showThickboxWin("?model=system_portal_portlettype&action=toAdd"
						+ "&typeId="
						+ $('#parentId').val()
						+ "&typeName="
						+ $('#parentName').val()
						+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=700');
			}
		}]
	});
});