$(function() {
	$("#tree").yxtree({
				data : [{
							id : "mymessage",
							name : "�ҵ���Ϣ",
							isParent : true,
							nodes : [{
										id : "noview",
										name : "δ����Ϣ"
									}, {
										id : "view",
										name : "�Ѷ���Ϣ"
									}]
						}],
				event : {
					node_click : function(event, treeId, treeNode) {
						var portlet = $("#messageGrid").data('yxgrid');
						if (treeNode.id == "noview") {
							portlet.options.extParam['isView'] = 0;
						} else if (treeNode.id == "view") {
							portlet.options.extParam['isView'] = 1;
						} else {
							portlet.options.extParam = {};
						}
						portlet.reload();
					}
				}
			});
	$("#tree").yxtree("expandAll");

	$("#messageGrid").yxgrid({
		isAddAction : false,
		isEditAction : false,
		isDelAction : xxxx,
		model : 'system_message_message',
		action : 'myMessage',
		// ��
		colModel : [{
					display : 'id',
					name : 'id',
					sortable : true,
					hide : true
				}, {
					display : '��Ϣ����',
					name : 'title',
					width : 150
				}, {
					display : '��Ϣ����',
					name : 'content',
					width : 400
				}, {
					display : '������',
					name : 'sendName'
				}, {
					display : '����ʱ��',
					name : 'sendTime',
					width : 120
				}, {
					display : '״̬',
					name : 'isView',
					process : function(v) {
						return v == 0 ? "δ��" : "�Ѷ�";
					}
				}],
		/**
		 * ��������
		 */
		searchitems : [{
					display : '����',
					name : 'title'
				}, {
					display : '����',
					name : 'content'
				}],
		toViewConfig : {
			// action : 'view',
			toViewFn : function(p, g) {
				var rowObj = g.getSelectedRow();
				var rowData = rowObj.data('data');
				var url = "index1.php?model=system_message_message&action=view&id="
						+ rowData.id;
				showModalDialog(url, self,
						"dialogWidth:700px;dialogHeight:520px;");
			}
		},
		sortorder : "DESC",
		sortname : "id",
		title : '�ҵ���Ϣ'
	});
});