Ext.onReady(function() {
	Ext.BLANK_IMAGE_URL = 'js/ext/resources/images/default/s.gif';
	Ext.QuickTips.init();
	var myRightMenus = [];
	var isRightMenu = false;
	if (serviceType == 'oa_rd_project'&&$("#swfupload")[0]) {
		myRightMenus = [{
			text : '新增',
			handler : function() {
				var selectNode = tree.selectedNode;
				selectNode.expand();
				var newNode = new Ext.tree.TreeNode({
							text : '新节点'
						});
				selectNode.appendChild(newNode);
				Ext.Ajax.request({
					url : "?model=rdproject_uploadfile_uploadfiletype&action=saveType",
					success : function(result, request) {
						var id = result.responseText;
						newNode.id = id;
					},
					params : {
						'type[parentId]' : selectNode.id,
						'type[name]' : newNode.text,
						'type[projectId]' : projectId
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
				Ext.MessageBox.confirm('确认删除', '确认要删除该分类吗?将删除该分类下的子分类及附件！',
						function(btn) {
							if (btn == 'yes') {

								Ext.Ajax.request({
									waitMsg : '正在删除数据，请稍候。。。。。。',
									url : "?model=rdproject_uploadfile_uploadfiletype&action=ajaxdeletes",
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
		}];
		isRightMenu = true;
	}

	var projectType = getData("YFXMGL");
	var data = [];
	for (var i = 0; i < projectType.length; i++) {
		var d = [projectType[i].dataCode, projectType[i].dataName];
		data.push(d);
	}

	var treeParam = {
		renderTo : 'tree',
		isRightMenu : isRightMenu,
		rootVisible : true,
		rootText : '项目文档',
		height : document.documentElement.clientHeight - 30,
		myRightMenus : myRightMenus,
		url : '?model=rdproject_uploadfile_uploadfiletype&action=tree&projectId='
				+ projectId + '&parentId='

	};
	if (serviceType == 'oa_rd_project'&&$("#swfupload")[0]) {
		treeParam.myTbar = [new Ext.form.ComboBox({
			emptyText : '复制类型',
			width : 70,
			store : new Ext.data.SimpleStore({
						fields : ['id', 'name'],
						data : data
					}),
			mode : 'local',
			displayField : 'name',
			valueField : 'id',
			triggerAction : 'all',
			listeners : {
				select : function(t) {
					if (confirm("确认复制选中的项目类型到当前节点下吗？")) {
						var type = t.getValue();
						$.ajax({
							url : '?model=rdproject_uploadfile_uploadfiletype&action=copyTemplateToType',
							type : 'POST',
							data : {
								projectType : type,
								projectId : projectId
							},
							success : function(data) {
								if (data == true) {
									alert("复制成功");
									tree.root.reload();
								}
							}
						});
					}
				}
			}
		})]
	}

	var tree = new Ext.ux.tree.MyTree(treeParam);
	tree.on('click', function(node) {
				var grid = $("#table").data('yxgrid_uploadfile');
				grid.options.param['typeId'] = node.id;
				// grid.options.param['objId'] = projectId;
				// grid.options.param['objTable'] = 'oa_rd_project';
				grid.reload();
			});
	if (serviceType == 'oa_rd_project'&&$("#swfupload")[0]) {
		var treeEditor = new Ext.tree.TreeEditor(tree, {
					allowBlank : false,
					listeners : {
						complete : function(t, v) {
							// alert(1)
						},
						canceledit : function() {
							// alert(2)
						},
						startedit : function(e, v) {
							alert(e.id)
						}

					}
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
				url : "?model=rdproject_uploadfile_uploadfiletype&action=saveType",
				success : function(result, request) {
					var id = result.responseText;
					selectNode.id = id;
					$("#table").yxgrid_uploadfile('reload');
				},
				params : {
					'type[id]' : selectNode.id,
					'type[parentId]' : selectNode.parentNode.id,
					'type[name]' : newValue,
					'type[projectId]' : projectId
				}
			});
		});
	}
	var h = 120;
	if ($("#swfupload")[0]) {
		h = h + 30;
	}
	var param = {};
	if (serviceType && serviceType != "") {
		param.objTable = serviceType;
	}
	$("#table").yxgrid_uploadfile({
		height : document.documentElement.clientHeight - h,
		url : '?model=file_uploadfile_management&action=pageJsonProject'
				+ '&objId=' + serviceId,
		param : param
	});
	if ($("#swfupload")[0]) {
		var uploadfile = createSWFUpload({
					"serviceType" : serviceType,
					"serviceId" : serviceId
				}, {
					file_dialog_start_handler : function() {
						var selectNode = tree.selectedNode;
						if (selectNode) {
							uploadfile
									.addPostParam("typeName", selectNode.text);
							uploadfile.addPostParam("typeId", selectNode.id);
						}
					},
					upload_complete_handler : function() {
						try {
							if (this.getStats().files_queued === 0) {
								document
										.getElementById(this.customSettings.cancelButtonId).disabled = true;
							} else {
								this.startUpload();
							}
							$("#table").yxgrid_uploadfile('reload');
						} catch (ex) {
							this.debug(ex);
						}
					}
				});
	}

});
