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
		rootText : '��Ŀ�ĵ�',
		myRightMenus : [{
			text : '����',
			handler : function() {
				var selectNode = tree.selectedNode;
				selectNode.expand();
				var newNode = new Ext.tree.TreeNode({
							text : '�½ڵ�'
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
			text : '�޸�',
			handler : function() {
				var selectNode = tree.selectedNode;
				treeEditor.editNode = selectNode;
				treeEditor.startEdit(selectNode.ui.textNode);
			}
		}, {
			text : 'ɾ��',
			handler : function() {
				var selectNode = tree.selectedNode;
				if (selectNode.id == -1) {
					alert('�޷�ɾ�����ڵ㣡');
					return;
				}
				Ext.MessageBox.confirm('ȷ��ɾ��', 'ȷ��Ҫɾ���÷�����?��', function(btn) {
					if (btn == 'yes') {
						Ext.Ajax.request({
							waitMsg : '����ɾ�����ݣ����Ժ򡣡���������',
							url : "?model=rdproject_uploadfile_template&action=ajaxdeletes",
							success : function(result, request) {
								alert('ɾ������ɹ���');
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
