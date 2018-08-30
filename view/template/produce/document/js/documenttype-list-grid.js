/** ����������Ϣ�б�* */

var show_page = function(page) {
	$("#documentTypeTree").yxtree("reload");
	$("#documentTypeGrid").yxgrid("reload");

};

$(function() {
	$("#documentTypeTree").yxtree({
		url : '?model=produce_document_documenttype&action=getTreeDataByParentId',
		event : {
			"node_click" : function(event, treeId, treeNode) {
				var documentTypeGrid = $("#documentTypeGrid").data('yxgrid');
				documentTypeGrid.options.param['parentId'] = treeNode.id;
				$("#parentName").val(treeNode.name);
				$("#parentId").val(treeNode.id);
				documentTypeGrid.reload();
			}
		}
	});

	$("#documentTypeGrid").yxgrid({
		model : 'produce_document_documenttype',
		title : '�ĵ�������Ϣ',
		isToolBar : true,
		showcheckbox : true,
		colModel : [{
					display : 'id',
					name : 'id',
					sortable : true,
					hide : true
				}, {
					display : '�ĵ�����',
					name : 'type',
					width : 200,
					sortable : true
				}, {
					display : '��������',
					name : 'parentName',
					width : 150,
					sortable : true
				}, {
					display : '����',
					name : 'orderNum',
					sortable : true
				}
//				, {
//					display : '�Ƿ�����',
//					name : 'isUse',
//					width : 80,
//					process : function(v) {
//						if (v == "1") {
//							return "��";
//						} else {
//							return "��";
//						}
//					},
//					sortable : true
//				}
				],

		searchitems : [{
					display : '�ĵ�����',
					name : 'type'
				}, {
					display : '��������',
					name : 'parentName'
				}],
		toAddConfig : {
			toAddFn : function(p) {
				showThickboxWin("?model=produce_document_documenttype&action=toAdd&parentName="
						+ $("#parentName").val()
						+ "&parentId="
						+ $("#parentId").val()
						+ "&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=850");
			}
		},
		toEditConfig : {
			action : 'toEdit',
			formHeight : 400,
			formWidth : 850
		},
		toViewConfig : {
			action : 'toView',
			formHeight : 400,
			formWidth : 850
		},
		sortname : 'id',
		sortorder : 'DESC'
	});
});