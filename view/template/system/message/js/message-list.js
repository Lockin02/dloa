$(function() {
	$("#tree").yxtree({
				data : [{
							id : "mymessage",
							name : "我的消息",
							isParent : true,
							nodes : [{
										id : "noview",
										name : "未读消息"
									}, {
										id : "view",
										name : "已读消息"
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
		// 表单
		colModel : [{
					display : 'id',
					name : 'id',
					sortable : true,
					hide : true
				}, {
					display : '消息标题',
					name : 'title',
					width : 150
				}, {
					display : '消息内容',
					name : 'content',
					width : 400
				}, {
					display : '发送人',
					name : 'sendName'
				}, {
					display : '发送时间',
					name : 'sendTime',
					width : 120
				}, {
					display : '状态',
					name : 'isView',
					process : function(v) {
						return v == 0 ? "未读" : "已读";
					}
				}],
		/**
		 * 快速搜索
		 */
		searchitems : [{
					display : '标题',
					name : 'title'
				}, {
					display : '内容',
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
		title : '我的消息'
	});
});