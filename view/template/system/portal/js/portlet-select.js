var show_page = function(page) {
	$("#portletGrid").yxgrid_portlet("reload");
};
$(function() {
			$("#tree").yxtree({
						url : '?model=system_portal_portlettype&action=getChildren',
						height : 350,
						event : {
							"node_click" : function(event, treeId, treeNode) {
								var portlet = $("#portletGrid")
										.data('yxgrid');
								portlet.options.extParam['typeId'] = treeNode.id;
								portlet.reload();
								$("#parentId").val(treeNode.id);
								$("#parentName").val(treeNode.typeName);

							}
						}
					});
			var idArr = [];
			var nameArr = [];
			$("#portletGrid").yxgrid({
						url : '?model=system_portal_portlet&action=getPermPortlets',
						colModel : [{
									display : 'portlet����',
									name : 'portletName',
									width:150
								}, {
									display : '����',
									name : 'typeName'
								}, {
									display : '˵��',
									name : 'remark',
									width:200
								}],
						isAddAction : false,
						isDelAction : false,
						isEditAction : false,
						height : 350,
						searchitems : [{
									display : 'portlet����',
									name : 'portletName'
								}],
						event : {
							row_check : function(e, checkbox, row, rowData) {
								if (checkbox.attr('checked')) {
									if (idArr.indexOf(rowData['id']) == -1) {
										idArr.push(rowData['id']);
										nameArr.push(rowData['portletName']);
									}
								} else {
									// ���ֵ���ڣ�ɾ��������
									var index = idArr.indexOf(rowData['id']);
									if (index != -1) {
										idArr.splice(index, 1);
										nameArr.splice(index, 1);
									}
								}

							}
						}
					});
			// ȷ��ѡ��
			$("#confirmButton").click(function() {
						window.returnValue = {
							text : nameArr.toString(),
							val : idArr.toString()
						};
						window.close();
					});
		});