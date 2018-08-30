Ext.onReady(function() {
	Ext.BLANK_IMAGE_URL = 'js/ext/resources/images/default/s.gif';
	Ext.QuickTips.init();
	var projectType = $("#projectType").val();
	$("#projectType").bind('change', function() {
				projectType = $(this).val();
				tree.loader.baseParams['projectType'] = projectType;
				tree.root.reload();
			});
	var tree = new Ext.ux.tree.MyTree({
		renderTo : 'tree',
		isRightMenu : true,
		rootVisible : true,
		rootText : '项目文档',
		myRightMenus : [{
			text : '新增',
			handler : function() {
				var selectNode = tree.selectedNode;
				selectNode.expand();
				var newNode = new Ext.tree.TreeNode({
							text : '新节点'
						});
				selectNode.appendChild(newNode);
				Ext.Ajax.request({
					url : "?model=rdproject_uploadfile_template&action=saveType",
					success : function(result, request) {
						var id = result.responseText;
						newNode.id = id;
					},
					params : {
						'type[parentId]' : selectNode.id,
						'type[name]' : newNode.text,
						'type[projectType]' : projectType
					}
				});
				// treeEditor.editNode = newNode;
				// treeEditor.startEdit(newNode.ui.textNode);
			}
		}, {
			text : '修改',
			handler : function() {
				var selectNode = tree.selectedNode;
				treeEditor.editNode = selectNode;
				treeEditor.startEdit(selectNode.ui.textNode);
			}
		}, {
			text : '删除',
			handler : function() {
				var selectNode = tree.selectedNode;
				if (selectNode.id == -1) {
					alert('无法删除根节点！');
					return;
				}
				Ext.MessageBox.confirm('确认删除', '确认要删除该分类吗?！', function(btn) {
					if (btn == 'yes') {
						Ext.Ajax.request({
							waitMsg : '正在删除数据，请稍候。。。。。。',
							url : "?model=rdproject_uploadfile_template&action=ajaxdeletes",
							success : function(result, request) {
								alert('删除分类成功！');
								selectNode.remove();
							},
							params : {
								id : selectNode.id
							}
						});
					}
				});
			}
		}],
		url : '?model=rdproject_uploadfile_template&action=tree&parentId=',
		param : {
			projectType : projectType
		}

	});
	var treeEditor = new Ext.tree.TreeEditor(tree, {
				allowBlank : false
			});
	treeEditor.on('beforestartedit', function(editor, e, v) {
				var selectNode = editor.editNode;
				if (selectNode.id == -1) {
					return false;
				}
			});
	treeEditor.on('complete', function(editor, newValue, oldValue) {
		var selectNode = editor.editNode;
		Ext.Ajax.request({
					url : "?model=rdproject_uploadfile_template&action=saveType",
					success : function(result, request) {
						var id = result.responseText;
						selectNode.id = id;
					},
					params : {
						'type[id]' : selectNode.id,
						'type[parentId]' : selectNode.parentNode.id,
						'type[name]' : newValue,
						'type[projectType]' : projectType
					}
				});
	});

});
